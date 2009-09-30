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
		$extKey	= trim($extKey);
		
			// Listing tab
		$tabs = array(
			array(
				'id'		=> 'none',
				'htmlId'	=> 'exttab-none-list',
				'key'		=> 'list',
				'classKey'	=> 'list',
				'class'		=> '',
				'label'		=> 'Extensions'
			)
		);

			// If an extension is selected, add editor tabs
		if( $extKey !== '' ) {
			$tabs[] = array(
				'id'		=> 'info',
				'htmlId'	=> 'exttab-' . $extKey . '-info',
				'key'		=> 'info',
				'classKey'	=> 'info',
				'class'		=> '',
				'label'		=> 'Info: ' . $extKey
			);
			$tabs[] = array(
				'id'		=> 'config',
				'htmlId'	=> 'exttab-' . $extKey . '-config',
				'key'		=> 'config',
				'classKey'	=> 'config',
				'class'		=> '',
				'label'		=> 'Config'
			);
			$tabs[] = array(
				'id'		=> 'rights',
				'htmlId'	=> 'exttab-' . $extKey . '-rights',
				'key'		=> 'rights',
				'classKey'	=> 'rights',
				'class'		=> '',
				'label'		=> 'Rights'
			);
			$tabs[] = array(
				'id'		=> 'records',
				'htmlId'	=> 'exttab-' . $extKey . '-records',
				'key'		=> 'records',
				'classKey'	=> 'records',
				'class'		=> '',
				'label'		=> 'Records'
			);
		}

			// Installer tab
		$tabs[] = array(
			'id'		=> 'install',
			'htmlId'	=> 'exttab-none-install',
			'key'		=> 'install',
			'classKey'	=> 'install',
			'class'		=> '',
			'label'		=> 'Install/Update'
		);

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

		if(! is_array($config))	{
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
		$listFunc	= explode('::', $funcRef);
		$data		= array();

		if( method_exists($listFunc[0], $listFunc[1]) ) {
			$data = call_user_func($listFunc, $params);
		}

		return $data;
	}



	/**
	 * Checks if extension has something to configure
	 *
	 * @todo	How are configs registered? Add Check
	 *
	 * @param	string	$extKey
	 * @return	Bool
	 */
	public static function extensionHasConfig($extKey)	{

		return false;
	}


}

?>