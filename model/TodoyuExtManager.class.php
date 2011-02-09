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
 * Extension management
 *
 * @package		Todoyu
 * @subpackage	Sysmanager
 */
class TodoyuExtManager {

	/**
	 * Get extension module tab configuration
	 *
	 * @param	String		$extKey
	 * @return	String
	 */
	public static function getTabConfig($extKey = '', $tab = '') {
		$extKey	= trim($extKey);
		$tabs	= array();

			// Listing tab
		$tabs[] = array(
			'id'		=> 'list',
			'label'		=> Label('sysmanager.tabs.extensions')
		);


			// If an extension is selected, add editor tabs
		if( $extKey !== '' ) {
				// Config
			$tabs[] = array(
				'id'		=> $extKey . '_config',
				'label'		=> 'LLL:sysmanager.tabs.config',
				'class'		=> 'config'
			);
				// Info
			$tabs[] = array(
				'id'		=> $extKey . '_info',
				'label'		=> $extKey,
				'class'		=> 'info'
			);
		} else {
				// Update tab
			$tabs[] = array(
				'id'		=> 'browse',
				'label'		=> 'LLL:sysmanager.tabs.browse',
				'class'		=> 'browse'
			);
				// Update tab
			$tabs[] = array(
				'id'		=> 'update',
				'label'		=> 'LLL:sysmanager.tabs.update',
				'class'		=> 'update'
			);
				// Installer tab
			$tabs[] = array(
				'id'		=> 'import',
				'label'		=> 'LLL:sysmanager.tabs.import',
				'class'		=> 'import'
			);
		}

		return $tabs;
	}



	/**
	 * Ext information about an extension provided by the config array
	 *
	 * @param	String		$extKey
	 * @return	Array
	 */
	public static function getExtInfos($extKey, $load = false) {
		if( $load ) {
			$path	= TodoyuExtensions::getExtPath($extKey, 'config/extinfo.php');

			if( is_file($path) ) {
				include_once($path);
			}
		}

		return TodoyuExtensions::getExtInfo($extKey);
	}



	/**
	 * Check whether an extension is a system extension
	 *
	 * @param	String		$extKey
	 * @return	Boolean
	 */
	public static function isSysExt($extKey) {
		$extInfos	= self::getExtInfos($extKey);

		return $extInfos['constraints']['system'] === true;
	}



	/**
	 * Add record config for automatic record editing in admin extension manager
	 *
	 * @param	String		$extKey
	 * @param	Array		$config
	 */
	public static function addRecordConfig($extKey, $recordName, array $config) {
		Todoyu::$CONFIG['EXT']['sysmanager']['records'][$extKey][$recordName] = $config;
	}



	/**
	 * Get record type config
	 *
	 * @param	String		$extKey
	 * @param	String		$recordName
	 * @return	Array
	 */
	public static function getRecordConfig($extKey, $recordName) {
		TodoyuExtensions::loadAllAdmin();
		
		$config = Todoyu::$CONFIG['EXT']['sysmanager']['records'][$extKey][$recordName];

		if( ! is_array($config) ) {
			$config = array();
		}

		return $config;
	}



	/**
	 * Get label for a record element
	 *
	 * @param	String		$ext
	 * @param	String		$recordName
	 * @param	Integer		$idRecord
	 * @return	String
	 */
	public static function getRecordObjectLabel($ext, $recordName, $idRecord) {
		$config	= self::getRecordConfig($ext, $recordName);
		$class	= $config['object'];

		if( class_exists($class, true) ) {
			$object	= new $class($idRecord);

			if( method_exists($object, 'getLabel') ) {
				return $object->getLabel();
			}
		}

		return 'ID: ' . $idRecord;
	}



	/**
	 * Get all record configs
	 *
	 * @param	String		$extKey
	 * @return	Array
	 */
	public static function getRecordConfigs($extKey) {
		TodoyuExtensions::loadAllAdmin();

		$config	= Todoyu::$CONFIG['EXT']['sysmanager']['records'][$extKey];

		if( ! is_array($config) ) {
			$config = array();
		}

		return $config;
	}



	/**
	 * Get all extensio record configurations
	 *
	 * @return	Array
	 */
	public static function getAllRecordsConfig() {
		$extKeys	= TodoyuExtensions::getInstalledExtKeys();
		$extRecords	= array();

		foreach($extKeys as $extKey) {
			$records	= self::getRecordConfigs($extKey);

			if( sizeof($records) > 0 ) {
				$extRecords[$extKey] = $records;
			}
		}

		return $extRecords;
	}



	/**
	 * Get record types
	 *
	 * @param	String	$extKey
	 * @return	Array
	 */
	public static function getRecordTypes($extKey) {
		$config	= self::getRecordConfigs($extKey);

		return array_keys($config);
	}



	/**
	 * Get record list data
	 *
	 * @param	String	$extKey
	 * @param	String	$recordName
	 * @param	Array	$params
	 * @return	unknown
	 */
	public static function getRecordListData($extKey, $recordName, array $params = array()) {
		$funcRef	= Todoyu::$CONFIG['EXT']['sysmanager']['records'][$extKey][$recordName]['list'];
		$data		= array();

		if( TodoyuFunction::isFunctionReference($funcRef) ) {
			$data	= TodoyuFunction::callUserFunction($funcRef, $params);
		}

		return $data;
	}



	/**
	 * Check whether extension has something to configure
	 *
	 * @todo	How are configs registered? Add Check
	 *
	 * @param	string	$extKey
	 * @return	Boolean
	 */
	public static function extensionHasConfig($extKey) {
		$xmlPath	= TodoyuExtConfManager::getXmlPath($extKey);

		return TodoyuFileManager::isFile($xmlPath);
	}
}

?>