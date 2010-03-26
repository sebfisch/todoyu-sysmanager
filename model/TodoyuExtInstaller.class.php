<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2010, snowflake productions gmbh
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
 * Extension installer
 *
 * @package		Todoyu
 * @subpackage	Sysmanager
 */
class TodoyuExtInstaller {

	/**
	 * Write extensions.php config file
	 *
	 * @param	Array		$extensions
	 */
	private static function writeExtensionsFile(array $extensions) {
		$file	= PATH_LOCALCONF . '/extensions.php';
		$tmpl	= 'ext/sysmanager/view/extensions.php.tmpl';
		$data	= array(
			'extensions'	=> $extensions
		);

		TodoyuFileManager::saveTemplatedFile($file, $tmpl, $data);
	}



	/**
	 * Save extensions as installed in extensions.php config file
	 *
	 * @param	Array		$extensions
	 */
	public static function saveInstalledExtensions(array $extensions) {
			// Update global config array
		Todoyu::$CONFIG['EXT']['installed'] = $extensions;

			// Update config file
		self::writeExtensionsFile($extensions);
	}



	/**
	 * Install an extension (update extension config file)
	 *
	 * @param	String		$extKey
	 */
	public static function install($extKey) {
			// Get installed extensions
		$installed	= Todoyu::$CONFIG['EXT']['installed'];

			// Add extension key to list
		$installed[] = $extKey;

			// Remove duplicate entries
		$installed = array_unique($installed);

			// Save installed extensions
		self::saveInstalledExtensions($installed);
	}



	/**
	 * Uninstall an extension (update extension config file)
	 *
	 * @param  String		$extKey
	 */
	public static function uninstall($extKey) {
			// Get installed extensions with extkey as array key
		$installed	= array_flip(Todoyu::$CONFIG['EXT']['installed']);

			// Remove extension key from list
		unset($installed[$extKey]);

			// Get the list of extensionkeys
		$installed	= array_keys($installed);

			// Save installed extensions
		self::saveInstalledExtensions($installed);
	}



	/**
	 * Check if an extension can be uninstalled
	 * Check for: dependents, system
	 *
	 * @param	String		$extKey
	 * @return	Bool
	 */
	public static function canUninstall($extKey) {
		$noDependents	= TodoyuExtensions::hasDependents($extKey) === false;
		$notSystem		= TodoyuExtensions::isSystemExtension($extKey) === false;

		return $noDependents && $notSystem;
	}



	/**
	 * Get error message for failed uninstall
	 *
	 * @param	String		$extKey
	 * @return	String
	 */
	public static function getUninstallFailReason($extKey) {
		$message	= 'Unknown problem';

		if( TodoyuExtensions::hasDependents($extKey) ) {
			$dependents	= TodoyuExtensions::getDependents($extKey);
			$extInfos	= TodoyuExtManager::getExtInfos($extKey);

			$message	= 'Cannot uninstall extension "' . htmlentities($extInfos['title']) . '" (' . $extKey . ').<br>The following extensions depend on it: ' . implode(', ', $dependents);
		} elseif( TodoyuExtensions::isSystemExtension($extKey) ) {
			$extInfos	= TodoyuExtManager::getExtInfos($extKey);
			$message	= '"' . htmlentities($extInfos['title']) . '" is a system extension and cannot be uninstalled';
		}

		return $message;
	}



	/**
	 * Download an extension: Pack all extension files into an archive and send it to the browser
	 *
	 * @param	String		$extKey
	 */
	public static function downloadExtension($extKey) {
		$archivePath= TodoyuExtArchiver::createExtensionArchive($extKey);
		$extInfo	= TodoyuExtensions::getExtInfo($extKey);
		$version	= TodoyuString::getVersionInfo($extInfo['version']);

		$fileName	= 'TXA_' . $extKey . '_' . $version['major'] . '-' . $version['minor'] . '-' . $version['revision'] . '_' . date('YmdHis') . '.zip';
		$filesize	= filesize($archivePath);

		TodoyuHeader::sendHeader('Content-type', 'application/octet-stream');
		TodoyuHeader::sendHeader('Content-disposition', 'attachment; filename=' . $fileName);
		TodoyuHeader::sendHeader('Content-length', $filesize);
		TodoyuHeader::sendNoCacheHeaders();

			// Send file for download and delete temporary zip file after download
		TodoyuFileManager::sendFile($archivePath);
		unlink($archivePath);
	}

}

?>