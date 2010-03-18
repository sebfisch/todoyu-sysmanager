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
		$GLOBALS['CONFIG']['EXT']['installed'] = $extensions;

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
		$installed	= $GLOBALS['CONFIG']['EXT']['installed'];

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
		$installed	= array_flip($GLOBALS['CONFIG']['EXT']['installed']);

			// Remove extension key from list
		unset($installed[$extKey]);

			// Get the list of extensionkeys
		$installed	= array_keys($installed);

			// Save installed extensions
		self::saveInstalledExtensions($installed);
	}



	/**
	 * @todo	check and implement or remove
	 *
	 * @param	String	$extKey
	 */
	public static function canInstall($extKey) {

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
		$version	= TodoyuDiv::getVersionInfo($extInfo['version']);

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