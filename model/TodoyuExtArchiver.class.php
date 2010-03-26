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
 * Extension archiver
 * Pack a whole extension into a zip archive file
 *
 * @package		Todoyu
 * @subpackage	Sysmanager
 */
class TodoyuExtArchiver {

	/**
	 * Create a extension archive (zip file) in cache and return the path to it
	 *
	 * @param	String		$extKey
	 * @return	String		Path to archive in cache
	 */
	public static function createExtensionArchive($extKey) {
		$randomFile	= md5(uniqid($extKey, microtime(true))) . '.zip';
		$tempPath	= TodoyuFileManager::pathAbsolute('cache/downloads/' . $randomFile);
		$extPath	= TodoyuExtensions::getExtPath($extKey);
		$archive	= new ZipArchive();

		$archive->open($tempPath, ZipArchive::CREATE);

		self::addFolderToArchive($archive, $extPath, $extPath);

		$archive->close();

		return $tempPath;
	}



	/**
	 * Add a folder (and subelements) to an archive
	 *
	 * @param	ZipArchive		$archive
	 * @param	String			$pathToFolder		Path to folder which elements should be added
	 * @param	String			$baseFolder			Base folder defined to root for the archive. Base path will be removed from internal archive path
	 * @param	Bool			$recursive			Add also all subfolders and files
	 */
	private static function addFolderToArchive(ZipArchive &$archive, $pathToFolder, $baseFolder, $recursive = true) {
		$files		= TodoyuFileManager::getFilesInFolder($pathToFolder);

			// Add files
		foreach($files as $file) {
			$filePath	= $pathToFolder . DIRECTORY_SEPARATOR . $file;
			$relPath	= str_replace($baseFolder . DIRECTORY_SEPARATOR, '', $filePath);

			$archive->addFile($filePath, $relPath);
		}

			// Add folders if recursive is enabled
		if( $recursive ) {
			$folders	= TodoyuFileManager::getFoldersInFolder($pathToFolder);
				// Add folders
			foreach($folders as $folder) {
				$folderPath	= $pathToFolder . DIRECTORY_SEPARATOR . $folder;
				$relPath	= str_replace($baseFolder . DIRECTORY_SEPARATOR, '', $folderPath);

				$archive->addEmptyDir($relPath);

				self::addFolderToArchive($archive, $folderPath, $baseFolder, true);
			}
		}
	}

}

?>