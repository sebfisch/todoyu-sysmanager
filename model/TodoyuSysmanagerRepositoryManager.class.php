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
class TodoyuSysmanagerRepositoryManager {

	/**
	 * Show notification about a connection error
	 *
	 */
	public static function notifyConnectionError() {
		TodoyuNotification::notifyError('Cannot connect the extension repository. Are connections to other servers blocked?');
	}



	/**
	 * Get unique todoyu ID
	 *
	 * @return	String
	 */
	public static function getTodoyuID() {
		return trim(Todoyu::$CONFIG['SETTINGS']['repository']['todoyuid']);
	}



	/**
	 * Get last used search query
	 *
	 * @return	String
	 */
	public static function getLastSearchKeyword() {
		return TodoyuSysmanagerPreferences::getPref('repositoryQuery');
	}



	/**
	 * Save last used search query
	 *
	 * @param  $query
	 * @return void
	 */
	public static function saveLastSearchKeyword($query) {
		TodoyuSysmanagerPreferences::savePref('repositoryQuery', trim($query), 0, true);
	}



	/**
	 * Install extension update
	 *
	 * @param	String		$urlUpdate
	 * @param	String		$extkey
	 * @return	Boolean
	 */
	public static function installExtensionUpdate($extKey) {
		try {
			$update		= self::getRepoInfo($extKey);
//			TodoyuDebug::printInFireBug($update, 'update');
			$urlArchive	= $update['version']['archive'];

			if( is_null($urlArchive) ) {
				throw new TodoyuException('Archive download URL not found');
			}

				// Create a backup from the extension
			TodoyuSysmanagerBackupManager::createExtensionBackup($extKey);

				// Get extension information before update
			$extInfo		= TodoyuExtensions::getExtInfo($extKey);

				// Download and import extension
			self::downloadAndImportExtension($extKey, $urlArchive, true);

				// Execute the update callback
			TodoyuSysmanagerExtInstaller::updateExtension($extKey, $extInfo['version']);
		} catch(TodoyuException $e) {
			return $e->getMessage();
		}

		return true;
	}



	/**
	 * Install a new extension from TER
	 *
	 * @param	String		$extKey
	 * @param	String		$archiveHash
	 * @return	Boolean|String
	 */
	public static function installExtensionFromTER($extKey) {
		try {
				// Get url from hash map
			$update		= self::getRepoInfo($extKey);
			$urlArchive	= $update['version']['archive'];

			if( is_null($urlArchive) ) {
				throw new TodoyuException('Archive hash not found');
			}

				// Download and install extension
			self::downloadAndImportExtension($extKey, $urlArchive);
			TodoyuSysmanagerExtInstaller::installExtension($extKey);
		} catch(TodoyuException $e) {
			return $e->getMessage();
		}

		return true;
	}



	/**
	 * Install core update. Extract update files over local files
	 *
	 * @param	String		$urlUpdate			URL to update archive
	 * @return	Boolean
	 */
	public static function installCoreUpdate() {
		$update		= self::getRepoInfo('core');
		$urlArchive	= $update['archive'];

		set_time_limit(100);

		try {
			if( is_null($urlArchive) ) {
				throw new TodoyuException('Archive hash not found');
			}

				// Backup Core
			TodoyuSysmanagerBackupManager::createCoreBackup();
				// Download and import core update
			self::downloadAndImportCoreUpdate($urlArchive);
		} catch(TodoyuException $e) {
			return $e->getMessage();
		}

		return true;
	}



	/**
	 * Download and import (install) a core update
	 *
	 * @throws	TodoyuException
	 * @param	String	$urlArchive
	 * @return	Boolean
	 */
	private static function downloadAndImportCoreUpdate($urlArchive) {
		$pathArchive= self::downloadArchive($urlArchive);

		self::importCoreUpdate($pathArchive);

		return true;
	}




	/**
	 * Import the core update from an archive
	 *
	 * @throws	TodoyuException
	 * @param 	String				$pathArchive
	 */
	private static function importCoreUpdate($pathArchive) {
		$pathTemp	= TodoyuFileManager::pathAbsolute('cache/update/' . md5(time()));

			// Extract archive
		$archive	= new ZipArchive();
		$archive->open($pathArchive);

		$success	= $archive->extractTo($pathTemp);

		$archive->close();

		if( $success === false ) {
			throw new TodoyuException('Extraction of core update archive failed');
		}

			// Prepare and import update
		$pathUpdate	= TodoyuFileManager::pathAbsolute($pathTemp . '/todoyu');
		$pathExtract= PATH . '/cache/xxx';

		self::removeLocalElementsFromCoreUpdate($pathUpdate);

		TodoyuDebug::printInFireBug('Updated into cache instead real core!');

		TodoyuFileManager::copyRecursive($pathUpdate, $pathExtract, true, true);
		TodoyuFileManager::deleteFolder($pathTemp);

		TodoyuCacheManager::clearAllCache();
	}




	/**
	 * Remove folders and files from core update which should not be updated
	 *
	 * @param	String		$pathCoreUpdate			Path to temporary core update folder
	 */
	private static function removeLocalElementsFromCoreUpdate($pathCoreUpdate) {
			// Remove folders which should not be overwritten
		$ignore	= array('cache', 'config', 'files', 'ext', 'install/config/LAST_VERSION');

		foreach($ignore as $element) {
			$pathElement	= TodoyuFileManager::pathAbsolute($pathCoreUpdate . '/' . $element);

			if( is_dir($pathElement) ) {
				TodoyuFileManager::deleteFolder($pathElement);
			} elseif( is_file($pathElement) ) {
				TodoyuFileManager::deleteFile($pathElement);
			}
		}
	}



	/**
	 * Download external archive file and extract it into the cache folder
	 *
	 * @param	String		$urlArchive
	 * @return	Boolean		Success
	 */
	private static function downloadAndImportExtension($ext, $urlArchive, $isUpdate = false) {
		$override	= $isUpdate;
		$pathArchive= self::downloadArchive($urlArchive);
		$canImport	= TodoyuSysmanagerExtImporter::canImportExtension($ext, $pathArchive, $override);

		if( $canImport !== true ) {
			throw new TodoyuException($canImport);
		}

		TodoyuSysmanagerExtImporter::importExtensionArchive($ext, $pathArchive);

		return true;
	}



	/**
	 * Download an archive from an URL to local hard drive
	 *
	 * @throws	TodoyuException
	 * @param	String		$urlArchive
	 * @return	String		Path to local archive
	 */
	private static function downloadArchive($urlArchive) {
		$localPath	= TodoyuFileManager::saveLocalCopy($urlArchive);

		if( $localPath === false ) {
			throw new TodoyuException('Download of update archive failed: ' . $urlArchive);
		}

		return $localPath;
	}



	/**
	 * Save path to archive of extension or core
	 *
	 * @param	String		$key
	 */
	public static function saveRepoInfo($key, array $data) {
		TodoyuSession::set('repository/info/' . $key, $data);
	}



	/**
	 * Get path to archive of extension or core
	 *
	 * @param	String		$key
	 * @return	Array
	 */
	public static function getRepoInfo($key) {
		return TodoyuArray::assure(TodoyuSession::get('repository/info/' . $key));
	}



	/**
	 * Clear all data from repository info session
	 *
	 */
	public static function clearRepoInfo() {
		TodoyuSession::remove('repository/info');
	}

}

?>