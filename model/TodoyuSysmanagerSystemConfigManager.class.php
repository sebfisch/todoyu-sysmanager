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
 * System config manager
 *
 * @package		Todoyu
 * @subpackage	Sysmanager
 */
class TodoyuSysmanagerSystemConfigManager {

	/**
	 * Save uploaded image as logo
	 *
	 * @param	Array		$logoUploadData
	 * @return	Boolean
	 */
	public static function saveLogo(array $logoUploadData) {
		$success	= false;

		if( self::isValidImageUpload($logoUploadData) ) {
			$config	= TodoyuArray::assure(Todoyu::$CONFIG['EXT']['sysmanager']['logoUpload']);

			$pathDest	= TodoyuFileManager::pathAbsolute($config['path']);
			$width		= intval($config['width']);
			$height		= intval($config['height']);

			$success	= TodoyuImageManager::saveResizedImage($logoUploadData['tmp_name'], $pathDest, $width, $height, $logoUploadData['type']);
		}

		return $success;
	}



	/**
	 * Save data in system configuration file
	 *
	 * @param	Array		$formdata
	 */
	public static function saveSystemConfig(array $formdata) {
		$data	= array(
			'name'			=> $formdata['name'],
			'email'			=> $formdata['email'],
			'locale'		=> $formdata['locale'],
			'timezone'		=> $formdata['timezone'],
			'encryptionKey'	=> Todoyu::$CONFIG['SYSTEM']['encryptionKey']
		);

		TodoyuInstallerManager::saveSystemConfig($data, false);
	}



	/**
	 * Check whether uploaded image data is valid
	 *
	 * @param	Array		$imageUploadData
	 * @return	Boolean
	 */
	public static function isValidImageUpload(array $imageUploadData) {
		if( substr($imageUploadData['type'], 0, 6) !== 'image/' ) {
			return false;
		}

		if( intval($imageUploadData['error']) !== 0 ) {
			return false;
		}

		if( intval($imageUploadData['size']) === 0 ) {
			return false;
		}

		return true;
	}



	/**
	 * Get config array of locale options
	 *
	 * @param	TodoyuFormElement	$field
	 * @return	Array
	 */
	public static function getLocaleOptions() {
		$locales	= TodoyuLocaleManager::getLocaleKeys();
		$options	= array();
		$default	= TodoyuLocaleManager::getDefaultLocale();

		foreach($locales as $locale) {
			$options[] = array(
				'value'	=> $locale,
				'label'	=> Label('core.locale.' . $locale, $locale) . ' <=> ' . Label('core.locale.' . $locale, $default)
			);
		}

		return $options;
	}

}

?>