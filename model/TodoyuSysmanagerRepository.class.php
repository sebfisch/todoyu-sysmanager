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
	 * Check whether TER server is reachable
	 *
	 * @return	Boolean
	 */
	public function isServerReachable() {
		try {
			$this->sendRequest('checkConnection', array(), true);
		} catch(TodoyuException $e) {
			return false;
		}

		return true;
	}



	/**
	 * Search for extensions on the update server
	 *
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
	 * @return	Array
	 */
	public function searchUpdates() {
		$data	= array();
		$updates= $this->sendRequest('searchUpdates', $data);

		TodoyuSysmanagerRepositoryManager::clearRepoInfo();

		if( $updates['core'] ) {
			TodoyuSysmanagerRepositoryManager::saveRepoInfo('core', $updates['core']);
		}

		foreach($updates['extensions'] as $extension) {
			TodoyuSysmanagerRepositoryManager::saveRepoInfo($extension['ext_key'], $extension);
		}

		return $updates;
	}



	/**
	 * Send request to update server
	 *
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
		}

		TodoyuDebug::printInFireBug($postData, 'postData');

		$this->response = TodoyuRequest::sendPostRequest($config['host'], $config['get'], $postData, 'data');

		$this->response['content_raw']	= $this->response['content'];
		$this->response['content']		= json_decode($this->response['content'], true);

		TodoyuDebug::printInFireBug($this->response['content'], 'response');

		return $this->getResponseContent();
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
			'extensions'	=> array()
		);

		$extKeys	= TodoyuExtensions::getInstalledExtKeys();
		TodoyuExtensions::loadAllExtinfo();

		foreach($extKeys as $extKey) {
			$info['extensions'][$extKey] = Todoyu::$CONFIG['EXT'][$extKey]['info']['version'];
		}

		return $info;
	}

}

?>