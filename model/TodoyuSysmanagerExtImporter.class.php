<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2012, snowflake productions GmbH, Switzerland
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
 * Sysmanager extension exporter
 *
 * @package		Todoyu
 * @subpackage	Sysmanager
 */
class TodoyuSysmanagerExtImporter {

	/**
	 * Verify as extension archive and import uploaded file into ext system
	 *
	 * @param	String		$extKey
	 * @param	String		$pathArchive
	 * @return	Boolean
	 */
	public static function importExtensionArchive($extKey, $pathArchive) {
		$extDir		= TodoyuExtensions::getExtPath($extKey);

		try {
			return TodoyuArchiveManager::extractTo($pathArchive, $extDir);
		} catch(TodoyuException $e) {
			return false;
		}
	}



	/**
	 * Check whether uploaded archive file can be imported into system as extension
	 *
	 * @throws	TodoyuException
	 * @param	String		$extKey
	 * @param	String		$pathArchive
	 * @param	Boolean		$override
	 * @return	Boolean
	 */
	public static function canImportExtension($extKey, $pathArchive, $override = false) {
		try {
			$checkResult	= self::isValidExtArchive($pathArchive);
			if( $checkResult !== true ) {
				throw new TodoyuException('Invalid extension archive: ' . $checkResult);
			}

			if( self::extensionDirExists($extKey) && !$override ) {
				throw new TodoyuException('Extension already exists');
			}
		} catch(TodoyuException $e) {
			return $e->getMessage();
		}

		return true;
	}


	/**
	 * Check whether an extension directory exists
	 *
	 * @param $extKey
	 * @return bool
	 */
	public static function extensionDirExists($extKey) {
		$extDir		= TodoyuExtensions::getExtPath($extKey);

		return is_dir($extDir);
	}



	/**
	 * Check whether given archive file contains a valid todoyu extension
	 *
	 * @throws	TodoyuException
	 * @param	String		$pathArchive
	 * @return	Boolean
	 */
	public static function isValidExtArchive($pathArchive) {
		$pathArchive	= TodoyuFileManager::pathAbsolute($pathArchive);

		try {
			if( filesize($pathArchive) < 100 ) {
				throw new TodoyuException('Invalid size. Too small for a real extension archive');
			}

			$archive= new ZipArchive();
			if( $archive->open($pathArchive) !== true ) {
				throw new TodoyuException('Can\'t open archive file');
			}

			$checkFile	= 'config/boot.php';

			if( !$archive->statName($checkFile) ) {
				throw new TodoyuException($checkFile . ' not found in archive');
			}
		} catch(TodoyuException $e) {
			if( isset($archive) && $archive instanceof ZipArchive ) {
				$archive->close();
			}
			TodoyuLogger::logError('Invalid extension import: ' . $e->getMessage());
			return $e->getMessage();
		}

		if( isset($archive) && $archive instanceof ZipArchive ) {
			$archive->close();
		}

		return true;
	}

}

?>