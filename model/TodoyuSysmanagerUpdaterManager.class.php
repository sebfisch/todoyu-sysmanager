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
 * Manage updates
 *
 * @package		Todoyu
 * @subpackage	Sysmanager
 */
class TodoyuSysmanagerUpdaterManager {

	/**
	 * Check whether the update server is reachable
	 *
	 * @return	Boolean
	 */
	public static function isUpdateServerReachable() {
		$updater	= new TodoyuSysmanagerUpdaterRequest();

		return $updater->isServerReachable();
	}



	/**
	 * Get unique todoyu ID
	 *
	 * @return	String
	 */
	public static function getTodoyuID() {
		return trim(Todoyu::$CONFIG['SETTINGS']['updater']['todoyuid']);
	}


	/**
	 * Get last used search query
	 *
	 * @return	String
	 */
	public static function getLastQuery() {
		return TodoyuSysmanagerPreferences::getPref('updaterQuery');
	}



	/**
	 * Save last used search query
	 *
	 * @param  $query
	 * @return void
	 */
	public static function saveLastQuery($query) {
		TodoyuSysmanagerPreferences::savePref('updaterQuery', trim($query), 0, true);
	}



	/**
	 * Install extension update
	 *
	 * @param	String		$urlUpdate
	 * @param	String		$extkey
	 * @return	Boolean
	 */
	public static function installExtensionUpdate($ext, $urlHash) {
//		$pathExtract	= PATH . '/ext/' . $extkey;
		$pathExtract	= PATH_CACHE . '/temp/dummytodoyu/ext/' . $ext;
		$urlUpdate		= TodoyuSysmanagerUpdaterManager::hash2path($urlHash);

		return self::downloadAndExtractUpdate($urlUpdate, $pathExtract);
	}



	/**
	 * Install core update. Extract update files over local files
	 *
	 * @param	String		$urlUpdate			URL to update archive
	 * @return	Boolean
	 */
	public static function installCoreUpdate($urlHash) {
//		$pathExtract	= PATH;
		$pathExtract	= PATH_CACHE . '/temp/dummytodoyu';
		$urlUpdate		= TodoyuSysmanagerUpdaterManager::hash2path($urlHash);

		return self::downloadAndExtractUpdate($urlUpdate, $pathExtract);
	}


	public static function installExtension($extKey, $archiveHash) {
		$archivePath= self::hash2path($archiveHash);
		$localPath	= TodoyuFileManager::saveLocalCopy($archivePath);

		$importInfo	= TodoyuSysmanagerExtInstaller::importExtensionArchive($localPath, true);

		TodoyuDebug::printInFireBug($archivePath, '$archivePath');
		TodoyuDebug::printInFireBug($localPath, '$localPath');
		TodoyuDebug::printInFireBug($importInfo, '$importInfo');

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



	/**
	 * Replace given (core- / extension- update) file paths with their hashes (MD5)
	 *
	 * @param	Array	$updates
	 * @return	Array
	 */
	public static function replaceFilepathsWithHashes(array $updates) {
		if( $updates['core'] ) {
			$updates['core']['archive'] = self::path2hash($updates['core']['archive']);
		}

		foreach($updates['extensions'] as $index => $extension) {
			$updates['extensions'][$index]['archive'] = self::path2hash($extension['archive']);
		}

		return $updates;
	}



	/**
	 * Generate MD5 hash to given path and store in session data
	 *
	 * @param	String	$path
	 * @return	String
	 */
	public static function path2hash($path) {
		$hash	= md5($path);

		TodoyuSession::set('updater/path/' . $hash, $path);

		return $hash;
	}



	/**
	 * Get path of given hash from session data
	 *
	 * @param	String	$hash
	 * @return	Mixed
	 */
	public static function hash2path($hash) {
		return TodoyuSession::get('updater/path/' . $hash);
	}

}

?>