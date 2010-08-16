<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2010, snowflake productions GmbH, Switzerland
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
 * [Enter Class Description]
 *
 * @package		Todoyu
 * @subpackage	[Subpackage]
 */
class TodoyuUpdaterSoapClient {

	/**
	 * Path to WSDL file
	 */
	const WSDL = 'http://ferni42.srv05/typo3conf/ext/todoyuupdate/soap/update.wsdl';

	/**
	 * SOAP client
	 *
	 * @var	SoapClient
	 */
	protected $client;

	/**
	 * Singleton instance
	 *
	 * @var	TodoyuUpdaterSoapClient
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
	 * @return	TodoyuUpdaterSoapClient
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
		$result	= $this->getClient()->browseExtensions($search, $order, $offset, $limit);

		return TodoyuArray::toArray($result);
	}



	/**
	 * Search for extension updates
	 *
	 * @return	Array
	 */
	public function searchUpdates() {
		$todoyuID		= 'skdjflkdfasdfasdfasdfdf234213';
		$serverInfo		= array(
			'OS'			=> PHP_OS,
			'phpVersion'	=> PHP_VERSION,
			'mysqlVersion'	=> Todoyu::db()->getVersion(),
			'ip_address'	=> TodoyuServer::getIP(),
			'domain'		=> TodoyuServer::getDomain()
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

		$result	= $this->getClient()->searchUpdates($todoyuID, $serverInfo, $extVersions, $coreVersion);

		return TodoyuArray::toArray($result);
	}

}

?>
