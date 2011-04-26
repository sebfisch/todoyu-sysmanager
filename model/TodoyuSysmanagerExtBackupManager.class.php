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
 * [Enter Class Description]
 *
 * @package		Todoyu
 * @subpackage	Sysmanager
 */
class TodoyuSysmanagerExtBackupManager {

	public static function createExtensionBackup($extKey) {
		$archivePath	= TodoyuSysmanagerExtArchiver::createExtensionArchive($extKey);
		$extInfo		= TodoyuExtensions::getExtInfo($extKey);
		$version		= TodoyuString::getVersionInfo($extInfo['version']);
		$fileName		= TodoyuSysmanagerExtInstaller::buildExtensionArchiveName($extKey, $version['major'], $version['minor'], $version['revision']);

		return self::addFileToBackupArchive($archivePath, $fileName);
	}


	public static function createCoreBackup() {

	}



	private static function addFileToBackupArchive($tempFile, $fileName) {
		TodoyuFileManager::makeDirDeep('backup');
		$pathBackup	= TodoyuFileManager::pathAbsolute('backup/' . $fileName);

		rename($tempFile, $pathBackup);

		return $pathBackup;
	}




}

?>