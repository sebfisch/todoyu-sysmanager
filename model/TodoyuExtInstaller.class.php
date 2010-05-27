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
	public static function installExtension($extKey) {
		$extKeys	= TodoyuExtensions::getInstalledExtKeys();

			// Add extension key to list
		$extKeys[]	= $extKey;
			// Remove duplicate entries
		$extKeys	= array_unique($extKeys);

			// Save installed extensions
		self::saveInstalledExtensions($extKeys);

		
		TodoyuExtensions::addExtAutoloadPaths($extKey);

		self::updateDatabase();

		self::callExtensionInstaller($extKey, 'install');
	}



	/**
	 * Call database update. All necessary database updates are proceeded automatically
	 *
	 */	
	private static function updateDatabase() {
		TodoyuSQLManager::updateDatabaseFromTableFiles();
	}


	/**
	 * Call setup function of extension if available
	 *
	 * @param	String		$extKey
	 * @param	String		$action
	 */
	private static function callExtensionInstaller($extKey, $action = 'install') {
		$className	= 'Todoyu' . ucfirst(strtolower(trim($extKey))) . 'Setup';
		$method		= $action;
		
		if( class_exists($className, true) ) {
			if( method_exists($className, $method) ) {
				call_user_func(array($className, $method));
			}
		}
	}



	/**
	 * Uninstall an extension (update extension config file)
	 *
	 * @param  String		$extKey
	 */
	public static function uninstallExtension($extKey) {
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
	 * Check whether an extension can be uninstalled
	 * Check for: dependents, system
	 *
	 * @param	String		$extKey
	 * @return	Boolean
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

		$fileName	= self::buildExtensionArchiveName($extKey, $version['major'], $version['minor'], $version['revision']);
		$mimeType	= 'application/octet-stream';

			// Send file for download and delete temporary zip file after download
		TodoyuFileManager::sendFile($archivePath, $mimeType, $fileName);
		unlink($archivePath);
	}


	public static function buildExtensionArchiveName($extKey, $versionMajor, $versionMinor, $versionRevision) {
		return 'TodoyuExt_' . $extKey . '_' . $versionMajor . '.' . $versionMinor . '.' . $versionRevision . '_' . date('Y-m-d_H.i') . '.zip';
	}
	
	
	public static function importExtensionArchive(array $uploadFile, $override = false) {
		try {
				// Is file available in upload array
			if( $uploadFile === false ) {
				throw new Exception('File not found in upload array');
			}
				// Has an error occurred
			if( $uploadFile['error'] !== 0 ) {
				throw new Exception('Upload error');
			}

				// Check if import is possible with provided file
			$canImport	= TodoyuExtInstaller::canImportUploadedArchive($uploadFile, $override);
			if( $canImport !== true ) {
				throw new Exception('Can\'t import extension archive: ' . $canImport);
			}

			$archiveInfo	= TodoyuExtInstaller::parseExtensionArchiveName($uploadFile['name']);

			self::extractExtensionArchive($archiveInfo['ext'], $uploadFile['tmp_name']);

			$info	= array(
				'success'	=> true,
				'message'	=> '',
				'ext'		=> $archiveInfo['ext']
			);
		}  catch(Exception $e) {
			$info	= array(
				'success'	=> false,
				'message'	=> $e->getMessage(),
				'ext'		=> $archiveInfo['ext']
			);
		}

		return $info;
	}


	public static function parseExtensionArchiveName($extArchiveName) {
		$fileInfo	= explode('_', $extArchiveName);
		$version	= TodoyuString::getVersionInfo($fileInfo[2]);
		$date		= strtotime($fileInfo[3] . ' ' . $fileInfo[4]);

		$info	= array(
			'ext'		=> $fileInfo[1],
			'version'	=> $version,
			'date'		=> $date
		);

		return $info;
	}
	
	
	


	public static function canImportUploadedArchive(array $file, $override = false) {
		try {
			if( TodoyuExtInstaller::isValidExtArchive($file) !== true ) {
				throw new Exception('Invalid extension archive');
			}

			$fileInfo	= explode('_', $file['name']);
			$extName	= trim(strtolower($fileInfo[1]));
			$extDir		= TodoyuExtensions::getExtPath($extName);

			if( ! $override && is_dir($extDir) ) {
				throw new Exception('Extension already exists');
			}
		} catch(Exception $e) {
			return $e->getMessage();
		}

		return true;
	}


	public static function isValidExtArchive(array $file) {
		$info	= pathinfo($file['name']);

		try {
			if( $info['extension'] !== 'zip' ) {
				throw new Exception('Invalid archive extension');
			}
//			if( $file['type'] !== 'application/octet-stream' ) {
//				throw new Exception('Invalid content type');
//			}
			if( $file['size'] < 100 ) {
				throw new Exception('Invalid size. Too small for a real extension archive');
			}

			$archive= new ZipArchive();
			if( $archive->open($file['tmp_name']) !== true ) {
				throw new Exception('Can\'t open archive file');
			}

			if( $archive->statName('ext.php') === false ) {
				throw new Exception('ext.php not found in archive');
			}
		} catch(Exception $e) {
			if( $archive instanceof ZipArchive ) {
				$archive->close();
			}
			Todoyu::log('Invalid extension import: ' . $e->getMessage());
			return false;
		}

		return true;
	}


	public static function extractExtensionArchive($extension, $pathArchive) {
		$archive	= new ZipArchive();
		$archive->open($pathArchive);
		$extDir		= TodoyuExtensions::getExtPath($extension);

		$archive->extractTo($extDir);
	}



	/**
	 * Remove extension folder from server
	 *
	 * @param	String		$ext
	 * @return	Boolean
	 */
	public static function removeExtensionFromServer($ext) {
		$extPath	= TodoyuExtensions::getExtPath($ext);

		TodoyuFileManager::deleteFolder($extPath);

		return is_dir($extPath) === false;
	}

}

?>