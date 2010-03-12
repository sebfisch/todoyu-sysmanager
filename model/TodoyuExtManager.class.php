<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 snowflake productions gmbh
*  All rights reserved
*
*  This script is part of the todoyu project.
*  The todoyu project is free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License, version 2,
*  (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html) as published by
*  the Free Software Foundation;
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

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
	public static function getTabConfig($extKey = '') {
		$extKey		= trim($extKey);
		$installed	= TodoyuExtensions::isInstalled($extKey);

			// Listing tab
		$tabs = array(
			array(
				'id'		=> 'list',
				'label'		=> Label('sysmanager.tabs.extensions')
			)
		);

			// If an extension is selected, add editor tabs
		if( $extKey !== '' ) {
			$tabs[] = array(
				'id'		=> $extKey . '_info',
				'label'		=> $extKey,
				'class'		=> 'info'
			);

			if( $installed === true ) {
				$tabs[] = array(
					'id'		=> $extKey . '_config',
					'label'		=> 'LLL:sysmanager.tabs.config',
					'class'		=> 'config'
				);
//				$tabs[] = array(
//					'id'		=> 'rights',
//					'label'		=>'LLL:sysmanager.tabs.rights'
//				);
				$tabs[] = array(
					'id'		=> $extKey . '_records',
					'label'		=> 'LLL:sysmanager.tabs.records',
					'class'		=> 'records'
				);
			}
		} else {
			/*
				// Update tab
			$tabs[] = array(
				'id'		=> 'update',
				'htmlId'	=> 'exttab-none-update',
				'key'		=> 'update',
				'classKey'	=> 'update',
				'class'		=> '',
				'label'		=> 'Update'
			);
			*/
				// Installer tab
			$tabs[] = array(
				'id'		=> 'install',
				'label'		=> 'Install',
				'class'		=> 'install'
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
	public static function getExtInfos($extKey) {
		return TodoyuExtensions::getExtInfo($extKey);
	}



	/**
	 * Check if an extension is a system extension
	 *
	 * @param	String		$extKey
	 * @return	Bool
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
		$GLOBALS['CONFIG']['EXT']['sysmanager']['records'][$extKey][$recordName] = $config;
	}



	/**
	 * Get record type config
	 *
	 * @param	String	$extKey
	 * @param	String	$recordName
	 * @return	Array
	 */
	public static function getRecordTypeConfig($extKey, $recordName)	{
		$config = $GLOBALS['CONFIG']['EXT']['sysmanager']['records'][$extKey][$recordName];

		if( ! is_array($config) )	{
			$config = array();
		}

		return $config;
	}



	/**
	 * Get all record configs
	 *
	 * @param	String		$extKey
	 * @return	Array
	 */
	public static function getRecordConfigs($extKey) {
		$config	= $GLOBALS['CONFIG']['EXT']['sysmanager']['records'][$extKey];

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
		$funcRef	= $GLOBALS['CONFIG']['EXT']['sysmanager']['records'][$extKey][$recordName]['list'];
		$data		= array();

		if( TodoyuDiv::isFunctionReference($funcRef) ) {
			$data	= TodoyuDiv::callUserFunction($funcRef, $params);
		}

		return $data;
	}



	/**
	 * Check if extension has something to configure
	 *
	 * @todo	How are configs registered? Add Check
	 *
	 * @param	string	$extKey
	 * @return	Bool
	 */
	public static function extensionHasConfig($extKey)	{
		$xmlPath	= TodoyuExtConfManager::getXmlPath($extKey);

		return TodoyuFileManager::isFile($xmlPath);
	}
}

?>