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
 * Request to update server
 *
 * @package		Todoyu
 * @subpackage	Sysmanager
 */
class TodoyuSysmanagerUpdaterRequest {

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


	public function isServerReachable() {
		try {
			$this->sendRequest('checkConnection');
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
			'query'	=> $query,
			'ignore'=> TodoyuExtensions::getInstalledExtKeys()
		);

		$results	= $this->sendRequest('searchExtensions', $data);

		foreach($results['extensions'] as $index => $extension) {
			$results['extensions'][$index]['archive_hash'] = TodoyuSysmanagerUpdaterManager::path2hash($extension['archive']);
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

		if( $updates['core'] ) {
			$updates['core']['archive'] = TodoyuSysmanagerUpdaterManager::path2hash($updates['core']['archive']);
		}

		foreach($updates['extensions'] as $index => $extension) {
			$updates['extensions'][$index]['archive_hash'] = TodoyuSysmanagerUpdaterManager::path2hash($extension['archive']);
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
	private function sendRequest($action, array $data = array()) {
		$config	= Todoyu::$CONFIG['EXT']['sysmanager']['update'];

		$postData	= array(
			'action'	=> $action,
			'info'		=> $this->getInfo(),
			'data'		=> $data
		);

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
			'todoyuid'		=> TodoyuSysmanagerUpdaterManager::getTodoyuID(),
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
			$info['extensions'][] = array(
				'extkey'	=> $extKey,
				'version'	=> Todoyu::$CONFIG['EXT'][$extKey]['info']['version']
			);
		}

		return $info;
	}

}

?>