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
 * Render rights editor manager
 *
 * @package		Todoyu
 * @subpackage	Sysmanager
 */

class TodoyuRightsEditorManager {

	/**
	 * Check if an extension has a rights config XML file
	 * File: ext/EXTKEY/config/rights.xml
	 *
	 * @param	String		$extKey		Extension key
	 * @return	Bool
	 */
	public static function hasRightsConfig($extKey) {
		return is_file( PATH_EXT . '/' . $extKey . '/config/rights.xml');
	}



	/**
	 *  Read the rights.xml of an extension
	 *
	 * @param	String		$extKey		Extension key
	 * @return	Array
	 */
	public static function readExtRights($extKey) {
		if( self::hasRightsConfig($extKey) ) {
			$xmlFile	= TodoyuExtensions::getExtPath($extKey) . '/config/rights.xml';
			$rights		= self::readXML($xmlFile);
		} else {
			$rights		= array();
		}

		return $rights;
	}



	/**
	 * Read an XML file into a rights array
	 *
	 * @param	String		$xmlFile		Path to XML file
	 * @return	Array
	 */
	public static function readXML($xmlFile) {
		$xmlFile	= TodoyuFileManager::pathAbsolute($xmlFile);
		$localIdent	= md5($xmlFile);
		$data		= array();

		$xml		= simplexml_load_file($xmlFile);
		$labelFile	= (string)$xml['labels'];

			// Register locale file for rights
		TodoyuLocale::register($localIdent, $labelFile);

			// Load sections
		foreach($xml->section as $section) {
			$sectionKey	= (string)$section['key'];

			$data[$sectionKey] = array();

			$data[$sectionKey]['label']	= TodoyuLocale::getLabel($localIdent . '.section.' . $sectionKey);
			$data[$sectionKey]['rights']= array();


			foreach($section->allow as $allow) {
				$right	= (string)$allow['right'];
				$data[$sectionKey]['rights'][$right] = array(
					'right'		=> $right,
					'label'		=> TodoyuLocale::getLabel($localIdent . '.right.' . $right),
					'default'	=> explode(',', (string)$allow['default']),
					'depends'	=> explode(',', (string)$allow['depends']),
					'comment'	=> (string)$allow['comment']
				);
			}
		}

		return $data;
	}



	/**
	 * Get dependent rights of a right
	 *
	 * @param	Array		$rightsConfig
	 * @param	String		$rightToCheck
	 * @return	Array
	 */
	public static function getDependents(array $rightsConfig, $rightToCheck) {
		$dependents	= array();

		foreach($rightsConfig as $section => $rights) {
			foreach($rights as $right => $rightConfig) {
				if( in_array($rightToCheck, $rightConfig['depends']) ) {
					$dependents[] = $right;
				}
			}
		}

		return $dependents;
	}



	/**
	 * Get all dependencies between the rights
	 *
	 * @param	Array		$rightsConfig
	 * @return	Array
	 */
	public static function getAllDependencies(array $rightsConfig) {
		$dependencies	= array();

		foreach($rightsConfig as $section => $rights) {
			foreach($rights as $right => $rightConfig) {
				$dependencies[$right] = self::getDependents($rightsConfig, $right);
			}
		}

		return $dependencies;
	}



	/**
	 * Save group rights submitted by the editor
	 *
	 * @param	String		$extKey		Extension key
	 * @param	Array		$rights		Submitted rights form data
	 */
	public static function saveGroupRights($extKey, array $rights) {
		$extID	= TodoyuExtensions::getExtID($extKey);

//		TodoyuDebug::printInFirebug($rights, $extID);

			// Delete all stored rights for this extension
		TodoyuRightsManager::deleteExtensionRights($extID);

			// Add new rights
		foreach($rights as $rightName => $allowedGroups) {
			foreach($allowedGroups as $idGroup => $dummy) {
				TodoyuRightsManager::setRight($extID, $idGroup, $rightName);
			}
		}

		TodoyuRightsManager::reloadRights();
	}



	/**
	 * Get the current active extension to edit
	 * If non is selected yet, use sysmanager
	 *
	 * @return	String
	 */
	public static function getCurrentExtension() {
		$ext	= TodoyuPreferenceManager::getPreference(EXTID_SYSMANAGER, 'ext');

		if( $ext === false ) {
			$ext = 'sysmanager';
		}

		return $ext;
	}



	/**
	 * Save the currently edited extension
	 *
	 * @param	String		$ext
	 */
	public static function saveCurrentExtension($ext) {
		TodoyuPreferenceManager::savePreference(EXTID_SYSMANAGER, 'ext', $ext, 0, true);
	}



	/**
	 * Get default rights defined in the rights.xml for the fixed groups
	 *
	 * @param	Array		$rights		XML rights structure
	 * @return	Array		Active rights. Format: [RIGHT][GROUP] = true
	 */
	public static function getDefaultActiveRights(array $rights) {
		$fixed		= TodoyuUsergroupManager::getFixedUserGroups();
		$mapping	= array();

			// Map keys to current IDs in the database
		foreach($fixed as $fix) {
			$mapping[$fix['key']] = $fix['id'];
		}

		$activeRights = array();

		foreach($rights as $section) {
			foreach($section['rights'] as $right) {
				if( is_array($right['default']) ) {
					$activeRights[$right['right']] = array();
					foreach($right['default'] as $defaultFixGroup) {
						$activeRights[$right['right']][$mapping[$defaultFixGroup]] = true;
					}
				}
			}
		}

		return $activeRights;
	}



	/**
	 * Get custom set
	 *
	 * @param	Array	$rights
	 * @param	String	$ext
	 * @return	Array
	 */
	public static function getCurrentActiveRights(array $rights, $ext) {
		$groupRights= TodoyuRightsManager::getExtGroupRights($ext);

//		TodoyuDebug::printInFirebug($groupRights);

		$activeRights = array();

		foreach($groupRights as $idGroup => $rightKeys) {
			foreach($rightKeys as $rightKey) {
				$activeRights[$rightKey][$idGroup] = true;
			}
		}

		return $activeRights;
	}



	/**
	 * Get group informations about the groups defined in $groupIDs
	 *
	 * @param	Array		$groupIDs		IDs of the groups to the get information from
	 * @return	Array
	 */
	public static function getGroupInfos(array $groupIDs) {
		$groupIDs	= TodoyuDiv::intvalArray($groupIDs, true, true);

		$fields	= 'id, title, is_active';
		$table	= 'ext_user_group';
		$where	= 'deleted = 0';
		$order	= 'is_active DESC, title';

		if( sizeof($groupIDs) > 0 ) {
			$where .= ' AND id IN(' . implode(',', $groupIDs) . ')';
		}

		return Todoyu::db()->getArray($fields, $table, $where, '', $order);
	}

}


?>