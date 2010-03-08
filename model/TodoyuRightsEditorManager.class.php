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
			$rights		= self::readXML($extKey, $xmlFile);
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
	public static function readXML($extKey, $xmlFile) {
		$xmlFile	= TodoyuFileManager::pathAbsolute($xmlFile);
		$localeKey	= $extKey . '-rights';
		$data		= array();

		$xml		= simplexml_load_file($xmlFile);
		$labelFile	= TodoyuExtensions::getExtPath($extKey) . '/locale/rights.xml';

			// Register locale file for rights
		TodoyuLanguage::register($localeKey, $labelFile);

			// Load sections
		foreach($xml->section as $section) {
			$sectionName	= (string)$section['name'];

			$data[$sectionName] = array();

			$data[$sectionName]['label']	= TodoyuLanguage::getLabel($localeKey . '.' . $sectionName);
			$data[$sectionName]['rights']	= array();

			if( $section['require'] ) {
				$sectionRequire	= explode(',', $section['require']);
			} else {
				$sectionRequire	= array();
			}

			foreach($section->right as $right) {
				$rightName = (string)$right['name'];

				$data[$sectionName]['rights'][$rightName] = array(
					'right'		=> $rightName,
					'full'		=> $sectionName . ':' . $rightName,
					'label'		=> TodoyuLanguage::getLabel($localeKey . '.' . $sectionName . '.' . $rightName),
					'comment'	=> TodoyuLanguage::getLabelIfExists($localeKey . '.' . $sectionName . '.' . $rightName . '.comment'),
					'require'	=> array()
				);

				$rightRequire	= $right['require'] ? explode(',', $right['require']) : array() ;

				$data[$sectionName]['rights'][$rightName]['require'] = array_merge($sectionRequire, $rightRequire);
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
	 * Extract the required info from rights
	 *
	 * @param	Array		$rightsConfig		Rights with sections
	 * @return	Array
	 */
	public static function extractRequiredInfos(array $rightsConfig) {
		$require = array();

		foreach($rightsConfig as $sectionName => $section) {
			foreach($section['rights'] as $right) {
				$require[$sectionName . ':' . $right['right']] = $right['require'];
			}
		}

		return $require;
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
	 * Save role rights
	 *
	 * @param	String		$extKey		Extension key
	 * @param	Array		$rights		Submitted rights
	 */
	public static function saveRoleRights($extKey, array $rights) {
		$extID	= TodoyuExtensions::getExtID($extKey);

			// Delete all stored rights for this extension
		TodoyuRightsManager::deleteExtensionRights($extID);

			// Add new rights
		foreach($rights as $rightName => $allowedRoles) {
			foreach($allowedRoles as $idRole => $dummy) {
				TodoyuRightsManager::setRight($extID, $idRole, $rightName);
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
	 * Get custom set
	 *
	 * @param	Array	$rights
	 * @param	String	$ext
	 * @return	Array
	 */
	public static function getCurrentActiveRights(array $rights, $ext) {
		$roleRights		= TodoyuRightsManager::getExtRoleRights($ext);
		$activeRights 	= array();

		foreach($roleRights as $idRole => $rightKeys) {
			foreach($rightKeys as $rightKey) {
				$activeRights[$rightKey][$idRole] = true;
			}
		}

		return $activeRights;
	}

}

?>