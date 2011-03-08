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
 * Client for soap requests
 *
 * @package		Todoyu
 * @subpackage	Sysmanager
 */
class TodoyuSysmanagerUpdaterSoapClient {

	/**
	 * Path to WSDL file
	 */
	const WSDL = 'http://todoyucom.srv05/typo3conf/ext/todoyuupdate/soap/update.wsdl';

	/**
	 * SOAP client
	 *
	 * @var	SoapClient
	 */
	protected $client;

	/**
	 * Singleton instance
	 *
	 * @var	TodoyuSysmanagerUpdaterSoapClient
	 */
	private static $instance;

	/**
	 * SOAP options
	 *
	 * @var	Array
	 */
	protected $options	= array(
		'trace'		=> true,
		'cache_wsdl'=> WSDL_CACHE_NONE,
		'features'	=> SOAP_SINGLE_ELEMENT_ARRAYS
	);



	/**
	 * Get instance of the updater
	 *
	 * @return	TodoyuSysmanagerUpdaterSoapClient
	 */
	public static function getInstance() {
		if( is_null(self::$instance) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}



	/**
	 * Private constructor for the singleton
	 */
	private function __construct() {
	}



	/**
	 * @todo	comment
	 * @return	Array
	 */
	public function getOptions() {
		return $this->options;
	}



	/**
	 * Set SOAP client options
	 *
	 * @param	Array		$options
	 */
	public function setOptions(array $options) {
		$this->options = array_merge($this->options, $options);
	}



	/**
	 * Get SOAP client
	 *
	 * @return	SoapClient
	 */
	public function getClient() {
		if( is_null($this->client) ) {
			$this->client = new SoapClient(self::WSDL, $this->getOptions());
		}

		return $this->client;
	}



	/**
	 * Search extensions on the server
	 *
	 * @param	String		$search
	 * @param	String		$order
	 * @param	Integer		$offset
	 * @param	Integer		$limit
	 * @return	Array
	 */
	public function searchExtensions($search = '', $order = '', $offset = 0, $limit = 30) {
		try {
			$result	= $this->getClient()->searchExtensions($search, $order, $offset, $limit);
		} catch(SoapFault $e) {
			Todoyu::log('TodoyuUpdater: searchExtensions failed: ' . $e->getMessage());
			TodoyuDebug::printHtml($this->getClient()->__getLastResponse(), 'Last response');
		}

		return TodoyuArray::toArray($result);
	}



	/**
	 * Search for extension updates
	 *
	 * @return	Array
	 */
	public function searchUpdates() {
		$todoyuID		= '2196d233029d48ecc95ff16f010e06a5';
		$serverInfo		= array(
			'OS'			=> (string)PHP_OS,
			'phpVersion'	=> (string)PHP_VERSION,
			'mysqlVersion'	=> (string)Todoyu::db()->getVersion(),
			'ip'			=> (string)TodoyuServer::getIP(),
			'domain'		=> (string)TodoyuServer::getDomain()
		);

		$coreVersion	= TODOYU_VERSION;
		$extVersions	= array();

		$extKeys	= TodoyuExtensions::getInstalledExtKeys();
		TodoyuExtensions::loadAllExtinfo();

		foreach($extKeys as $extKey) {
			$extVersions[] = array(
				'extkey'	=> $extKey,
				'version'	=> Todoyu::$CONFIG['EXT'][$extKey]['info']['version']
			);
		}

		$updateInput = array(
			'todoyuid'				=> $todoyuID,
			'ServerInfo'			=> $serverInfo,
			'ExtensionVersionList'	=> $extVersions,
			'coreVersion'			=> $coreVersion
		);

		try {
			$result	= $this->getClient()->searchUpdates($updateInput);
		} catch(SoapFault $e) {
			TodoyuDebug::printInFireBug($e->getMessage(), 'Error');
			TodoyuDebug::printInFireBug($e->getTrace(), 'Trace');
			TodoyuDebug::printInFireBug($this->getClient()->__getLastResponse(), 'response');

			if( $this->hasSoapDebug() ) {
				die($this->getClient()->__getLastResponse());
			}
		}

//		TodoyuDebug::printInFireBug($this->getClient()->__getLastResponseHeaders(), 'headers');

//		$headerString	= $this->getClient()->__getLastResponseHeaders();
//		$headers	= TodoyuString::extractHeadersFromString($headerString);
//		TodoyuDebug::printHtml(unserialize($headers['args']), 'arguments');
//		TodoyuDebug::printInFireBug(unserialize($headers['args']), 'arguments');
//		TodoyuDebug::printInFirebug($this->getClient()->__getLastRequest(), 'request');

		return TodoyuArray::toArray($result);
	}



	/**
	 * Check whether client has SOAP debug available
	 *
	 * @return	Boolean
	 */
	private function hasSoapDebug() {
		$headerString	= $this->getClient()->__getLastResponseHeaders();
		$headers		= TodoyuString::extractHeadersFromString($headerString);

		return array_key_exists('x-todoyuupdate-soapdebug', $headers);
	}

}

?>
