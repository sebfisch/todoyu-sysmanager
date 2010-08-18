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
 * @subpackage	Sysmanager
 */
class TodoyuUpdateManager {

	/**
	 * Check whether the update server is reachable
	 * Tries to download a reference file to verify the connection
	 *
	 * @return	Boolean
	 */
	public static function isUpdateServerReachable() {
		$url	= Todoyu::$CONFIG['EXT']['sysmanager']['update']['connectionCheckUrl'];
		$options= array(
			'onlyHeaders'	=> true
		);

		$headers	= TodoyuFileManager::downloadFile($url, $options);

		return $headers !== false && stristr($headers['status'], '200 OK') !== false;
	}



	/**
	 * Install extension update
	 *
	 * @param	String		$urlUpdate
	 * @param	String		$extkey
	 * @return	Boolean
	 */
	public static function installExtensionUpdate($urlUpdate, $extkey) {
//		$pathExtract	= PATH . '/ext/' . $extkey;
		$pathExtract	= PATH_CACHE . '/temp/dummytodoyu/ext/' . $extkey;

		return self::downloadAndExtractUpdate($urlUpdate, $pathExtract);
	}



	/**
	 * Install core update. Extract update files over local files
	 *
	 * @param	String		$urlUpdate			URL to update archive
	 * @return	Boolean
	 */
	public static function installCoreUpdate($urlUpdate) {
//		$pathExtract	= PATH;
		$pathExtract	= PATH_CACHE . '/temp/dummytodoyu';


		return self::downloadAndExtractUpdate($urlUpdate, $pathExtract);
	}


	/**
	 * Download external archive file and extract it into the cache folder
	 *
	 * @param	String		$urlArchive
	 * @return	Boolean		Success
	 */
	public static function downloadAndExtractUpdate($urlArchive, $extractTo) {
		$tempID		= md5(uniqid().$urlArchive);
		$tempFile	= PATH_CACHE . '/temp/update/' . $tempID . '.zip';

		$download	= TodoyuFileManager::saveLocalCopy($urlArchive, $tempFile);

		if( $download === false ) {
			Todoyu::log('Download of update failed: ' . $urlArchive, TodoyuLogger::LEVEL_ERROR);

			return false;
		} else {
			TodoyuArchiveManager::extract($tempFile, $extractTo);

			return true;
		}
	}
}

?>
