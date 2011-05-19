<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2011, snowflake productions GmbH, Switzerland
* All rights reserved.
*
* This script is part of the todoyu project.
* The todoyu project is free software; you can redistribute it and/or modify
* it under the terms of the BSD License.
*
* This script is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the BSD License
* for more details.
*
* This copyright notice MUST APPEAR in all copies of the script.
*****************************************************************************/

/**
 * Client for repository access
 *
 * @package		Todoyu
 * @subpackage	Sysmanager
 */
class TodoyuSysmanagerRepository {

	/**
	 * Response data
	 *
	 * @var	Array
	 */
	private $response = array();


	/**
	 * Initialize
	 */
	public function __construct() {

	}



	/**
	 * Get full response (header + content)
	 *
	 * @return	Array
	 */
	public function getResponse() {
		return $this->response;
	}



	/**
	 * Get response headers
	 *
	 * @return	Array
	 */
	public function getResponseHeaders() {
		return $this->response['headers'];
	}



	/**
	 * Get response content
	 *
	 * @return	Array
	 */
	public function getResponseContent() {
		return $this->response['content'];
	}



	/**
	 * Search for extensions on the update server
	 *
	 * @throws	TodoyuSysmanagerRepositoryConnectionException
	 * @param	String		$query
	 * @return	Array		Search results
	 */
	public function searchExtensions($query) {
		$data	= array(
			'query'	=> $query
		);

		$results	= $this->sendRequest('searchExtensions', $data);

		foreach($results['extensions'] as $extension) {
			TodoyuSysmanagerRepositoryManager::saveRepoInfo($extension['ext_key'], $extension);
		}

		return $results;
	}




	/**
	 * Search for extension updates
	 *
	 * @throws	TodoyuSysmanagerRepositoryConnectionException
	 * @return	Array
	 */
	public function searchUpdates() {
		TodoyuSysmanagerRepositoryManager::clearRepoInfo();

		$updates= $this->sendRequest('searchUpdates');

		if( $updates['core'] ) {
			TodoyuSysmanagerRepositoryManager::saveRepoInfo('core', $updates['core']);
		}

		foreach($updates['extensions'] as $extension) {
			TodoyuSysmanagerRepositoryManager::saveRepoInfo($extension['ext_key'], $extension);
		}

		return $updates;
	}


	/**
	 * Download file from repository
	 *
	 * @param	String		$type
	 * @param	Integer		$idVersion
	 * @return	String		Path to local saved file
	 */
	public function download($type, $idVersion) {
		$data		= array(
			'type'		=> $type,
			'version'	=> intval($idVersion)
		);

		$responseData	= $this->sendRequest('download', $data);

		if( $responseData['data'] === false ) {
			$label	= Todoyu::Label('sysmanager.ext.repository.error.' . $responseData['message']);
			throw new TodoyuSysmanagerRepositoryException($label);
		}

		$tempName		= md5($idVersion . time());
		$tempFile		= TodoyuFileManager::pathAbsolute(PATH_CACHE . '/temp/' . $tempName);
		$fileData		= base64_decode($responseData['data']);

		TodoyuFileManager::saveFileContent($tempFile, $fileData);

		return $tempFile;
	}



	/**
	 * Register an extension for current domain
	 *
	 * @param	String		$extKey
	 * @return	Boolean
	 */
	public function registerForDomain($extKey) {
		$data	= array(
			'todoyuid'	=> TodoyuSysmanagerRepositoryManager::getTodoyuID(),
			'extension'	=> $extKey,
			'domain'	=> TodoyuServer::getDomain()
		);

		$response	= $this->sendRequest('register', $data, true);

		if( $response['registered'] ) {
			return true;
		} else {
			return Todoyu::Label('sysmanager.ext.repository.error.' . $response['message']) ;
		}
	}



	/**
	 * Send request to update server
	 *
	 * @throws	TodoyuSysmanagerRepositoryConnectionException
	 * @param	String		$action
	 * @param	Array		$data
	 * @return	Array
	 */
	private function sendRequest($action, array $data = array(), $noInfo = false) {
		$config	= Todoyu::$CONFIG['EXT']['sysmanager']['update'];

		$postData	= array(
			'action'	=> $action,
			'data'		=> $data
		);

		if( $noInfo !== true ) {
			$postData['info'] = $this->getInfo();
		} else {
			$postData['info'] = array();
		}

		TodoyuDebug::printInFireBug($postData, 'postData');
//		TodoyuDebug::printInFireBug(serialize($postData['data']), 'data');
//		TodoyuDebug::printInFireBug(serialize($postData['info']), 'info');

		try {
			$this->response = TodoyuRequest::sendPostRequest($config['host'], $config['get'], $postData, 'data');
		} catch(TodoyuException $e) {
			TodoyuLogger::logError('Cannot reach the repository. Server not available. (' . $e->getMessage() . ')');
			throw new TodoyuSysmanagerRepositoryConnectionException($e->getMessage(), $e->getCode(), $e);
		}

		$this->response['content_raw']	= $this->response['content'];
		$this->response['content']		= json_decode($this->response['content'], true);

		TodoyuDebug::printInFireBug($this->response['content'], 'response');
//		TodoyuDebug::printInFireBug($this->response['content_raw'], 'content_raw');

		return $this->response['content'];
	}



	/**
	 * Get info about current installation
	 *
	 * @return	Array
	 */
	private function getInfo() {
		$info		= array(
			'todoyuid'		=> TodoyuSysmanagerRepositoryManager::getTodoyuID(),
			'os'			=> PHP_OS,
			'ip'			=> TodoyuServer::getIP(),
			'domain'		=> TodoyuServer::getDomain(),
			'version'		=> array(
				'php'	=> PHP_VERSION,
				'mysql'	=> Todoyu::db()->getVersion(),
				'core'	=> TODOYU_VERSION
			),
			'extensions'	=> array(),
			'imported'		=> $this->getImportedExtensions()
		);

		$extKeys	= TodoyuExtensions::getInstalledExtKeys();
		TodoyuExtensions::loadAllExtinfo();

		foreach($extKeys as $extKey) {
			$info['extensions'][$extKey] = Todoyu::$CONFIG['EXT'][$extKey]['info']['version'];
		}

		return $info;
	}


	private function getImportedExtensions() {
		$extKeys= TodoyuExtensions::getNotInstalledExtKeys();
		$infos	= array();

		foreach($extKeys as $extKey) {
			$version	= TodoyuExtensions::getExtVersion($extKey);

			if( $version !== false ) {
				$infos[$extKey] = $version;
			}
		}

		return $infos;
	}

}

?>