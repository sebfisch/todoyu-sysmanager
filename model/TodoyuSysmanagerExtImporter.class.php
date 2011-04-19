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
 * @subpackage	[Subpackage]
 */
class TodoyuSysmanagerExtImporter {

	/**
	 * Extract archive file at given path into extension folder named after given extension key
	 *
	 * @param	String		$extKey
	 * @param	String		$pathArchive
	 */
	private static function extractExtensionFromArchive($extKey, $pathArchive) {
		$archive	= new ZipArchive();
		$archive->open($pathArchive);
		$extDir		= TodoyuExtensions::getExtPath($extKey);

		$archive->extractTo($extDir);
	}


	/**
	 * Verify as extension archive and import uploaded file into ext system
	 *
 	 * @throws	Exception
 	 * @param	Array		$uploadFile
 	 * @param	Boolean		$override
	 * @return	Array
	 */
	public static function importExtensionArchive($extKey, $pathArchive) {
		self::extractExtensionFromArchive($extKey, $pathArchive);
	}



	/**
	 * Check whether uploaded archive file can be imported into system as extension
	 *
	 * @throws	Exception
	 * @param	Array		$file
	 * @param	Boolean		$override
	 * @return	Boolean
	 */
	public static function canImportExtension($extKey, $pathArchive, $override = false) {
		try {
			$checkResult	= self::isValidExtArchive($pathArchive);
			if( $checkResult !== true ) {
				throw new TodoyuException('Invalid extension archive: ' . $checkResult);
			}

			$extDir		= TodoyuExtensions::getExtPath($extKey);

			if( ! $override && is_dir($extDir) ) {
				throw new TodoyuException('Extension already exists');
			}
		} catch(TodoyuException $e) {
			return $e->getMessage();
		}

		return true;
	}



	/**
	 * Check whether given archive file contains a valid todoyu extension
	 *
	 * @throws	Exception
	 * @param	String		$pathArchive
	 * @return	Boolean
	 */
	public static function isValidExtArchive($pathArchive) {
		$pathArchive	= TodoyuFileManager::pathAbsolute($pathArchive);

		try {
			if( filesize($pathArchive) < 100 ) {
				throw new Exception('Invalid size. Too small for a real extension archive');
			}

			$archive= new ZipArchive();
			if( $archive->open($pathArchive) !== true ) {
				throw new Exception('Can\'t open archive file');
			}

			$checkFile	= 'config' . DIR_SEP . 'boot.php';

			if( $archive->statName($checkFile) === false ) {
				throw new Exception($checkFile . ' not found in archive');
			}
		} catch(Exception $e) {
			if( isset($archive) && $archive instanceof ZipArchive ) {
				$archive->close();
			}
			Todoyu::log('Invalid extension import: ' . $e->getMessage());
			return $e->getMessage();
		}

		return true;
	}




}

?>