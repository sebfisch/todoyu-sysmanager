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
class TodoyuSysmanagerExtManager {

	/**
	 * Get extension module tab configuration
	 *
	 * @param	String		$extKey
	 * @param	String		$tab
	 * @return	String
	 */
	public static function getTabConfig($extKey = '', $tab = '') {
		$extKey	= trim($extKey);
		$tabs	= array();
		$config	= Todoyu::$CONFIG['EXT']['sysmanager']['extensionTabs'];

			// Listing tab
		if( Todoyu::allowed('sysmanager', 'general:extensions') ) {
			$tabs[] = $config['installed'];
		}


			// If an extension is selected, add editor tabs
		if( $extKey !== '' ) {
				// Config
			$tab		= $config['config'];
			$tab['id']	= $extKey . '_config';
			$tabs[] 	= $tab;

				// Info
			$tab			= $config['info'];
			$tab['id']		= $extKey . '_info';
			$tab['label']	= $extKey . '.ext.ext.title';
			$tabs[]			= $tab;
		} else {
				// Browse market / download tab
			if( Todoyu::allowed('sysmanager', 'extensions:download') ) {
				$tabs[] = $config['search'];
			}
				// Update tab
			if( Todoyu::allowed('sysmanager', 'extensions:update') ) {
				$tabs[] = $config['update'];
			}
				// Installer tab
			if( Todoyu::allowed('sysmanager', 'extensions:install') ) {
				$tabs[] = $config['imported'];
			}
		}

		return $tabs;
	}



	/**
	 * Ext information about an extension provided by the config array
	 *
	 * @param	String		$extKey
	 * @param	Boolean		$load
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
	 * Add record config for automatic record editing in sysmanager extension manager
	 *
	 * @param	String		$extKey
	 * @param	String		$recordName
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
	 * Check whether given record type defines a callback for determining per record whether it is allowed to be deleted
	 * isDeletable not defined						=> Deletion allowed (no restriction)
	 * isDeletable is boolean TRUE					=> Deletion allowed
	 * isDeletable callback is function reference	=> Deletion is restricted
	 * isDeletable is boolean FALSE					=> Deletion is forbidden
	 *
	 * @param	String	$extKey
	 * @param	String	$recordName
	 * @return	Boolean
	 */
	public static function isRecordConfigRestrictingDeletion($extKey, $recordName) {
		$recordConfig	= self::getRecordConfig($extKey, $recordName);

		if( ! array_key_exists('isDeletable', $recordConfig) || $recordConfig['isDeletable'] === true ) {
			return false;
		}

		return true;
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
	 * Get all extension record configurations
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

			// Get records list
		if( TodoyuFunction::isFunctionReference($funcRef) ) {
			$data	= TodoyuFunction::callUserFunction($funcRef, $params);
		}

			// Add isDeletable flag
		if( self::isRecordConfigRestrictingDeletion($extKey, $recordName) ) {
			$isDeletableFuncRef	= Todoyu::$CONFIG['EXT']['sysmanager']['records'][$extKey][$recordName]['isDeletable'];
			if( TodoyuFunction::isFunctionReference($isDeletableFuncRef) ) {
					// Check deletion allowance for all records
				foreach($data as $index => $record) {
					$data[$index]['isDeletable']	= TodoyuFunction::callUserFunction($isDeletableFuncRef, $record['id']);
				}
			}
		} else {
				// Allow deletion for all records
			foreach($data as $index => $record) {
				$data[$index]['isDeletable']	= true;
			}
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
		$xmlPath	= TodoyuSysmanagerExtConfManager::getXmlPath($extKey);

		return TodoyuFileManager::isFile($xmlPath);
	}



	/**
	 * Parse major version from a version string
	 *
	 * @param	String	$versionString
	 * @return	Integer
	 */
	public static function parseMajorVersion($versionString) {
		$parts	= explode('.', $versionString);

		return intval($parts[0]);
	}



	/**
	 * Get major version of an extension
	 *
	 * @param	String		$extKey
	 * @return	Integer
	 */
	public static function getMajorVersion($extKey) {
		$extVersion	= TodoyuExtensions::getExtVersion($extKey);

		return self::parseMajorVersion($extVersion);
	}

}

?>