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
	 * Load constraints config from new installed extension (not loaded)
	 *
	 * @param	String		$extKey
	 * @return§Array
	 */
	public static function getConstraintsOfNewExtension($extKey) {
		$pathInfo	= TodoyuExtensions::getExtPath($extKey, 'config/extinfo.php');
		$constraints= array();

		if( is_file($pathInfo) ) {
			include($pathInfo);

			$constraints	= TodoyuArray::assure(Todoyu::$CONFIG['EXT'][$extKey]['info']['constraints']);
		}

		return $constraints;
	}



	/**
	 * Install an extension (update extension config file)
	 *
	 * @param	String		$extKey
	 */
	public static function installExtension($extKey) {
		$newExtConstraints	= self::getConstraintsOfNewExtension($extKey);

		try {
			self::checkConstraints($extKey, $newExtConstraints);

			$extKeys	= TodoyuExtensions::getInstalledExtKeys();

				// Add extension key to list
			$extKeys[]	= $extKey;
				// Remove duplicate entries
			$extKeys	= array_unique($extKeys);

				// Save installed extensions
			self::saveInstalledExtensions($extKeys);

				// Add extension class paths to current auto load paths
			TodoyuExtensions::addExtAutoloadPaths($extKey);

				// Run database update script
			self::updateDatabase();

				// Include extension config into current script to make it fully available during this request
			$extFile	= TodoyuExtensions::getExtPath($extKey, 'ext.php');
			if( is_file($extFile) ) {
				include($extFile);
			}

				// Call the extension setup class of the new extension
			self::callExtensionSetup($extKey, 'install');
		} catch(TodoyuInstallerException $e) {
			Todoyu::log($e->getMessage(), TodoyuLogger::LEVEL_FATAL);
			return $e->getMessage();
		}

		return true;
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
	private static function callExtensionSetup($extKey, $action = 'install') {
		$className	= 'Todoyu' . ucfirst(strtolower(trim($extKey))) . 'Setup';
		$method		= $action;

		TodoyuDebug::printInFireBug($className, 'callExtensionInstaller');

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
		self::callExtensionSetup($extKey, 'uninstall');

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


	public static function canInstall($extKey) {
		$constraints	= self::getConstraintsOfNewExtension($extKey);

		try {
			self::checkConstraints($extKey, $constraints);
		} catch(TodoyuInstallerException $e) {
			TodoyuDebug::printInFireBug($e->getMessage());

			throw new Exception($e->getMessage(), $e->getCode(), $e);
		}

		return true;
	}


	/**
	 * Check constraints of the extension
	 * Core version, dependent extensions, conflicts
	 *
	 * @throws	TodoyuInstallerException
	 * @param	String		$ext
	 * @param	Array		$constraints
	 * @return	Boolean
	 */
	public static function checkConstraints($ext, array $constraints = null) {
		$depends	= TodoyuArray::assure($constraints['depends']);

			// Load constraints if not given
		if( is_null($constraints) ) {
			$constraints	= TodoyuExtensions::getExtInfo($ext);
		}


			// Check core version
		if( isset($constraints['core']) ) {
			if( version_compare($constraints['core'], TODOYU_VERSION) === 1 ) {
				throw new TodoyuInstallerException(Label('sysmanager.extension.installExtension.error.core') . ': ' . TODOYU_VERSION . ' < ' . $constraints['core']);
			}
		}


			// Check if all dependencies are ok
		foreach($depends as $extKey => $requiredVersion) {
			if( ! TodoyuExtensions::isInstalled($extKey) ) {
				throw new TodoyuInstallerException(Label('sysmanager.extension.installExtension.error.missing') . ': ' . $extKey);
			}
			$installedVersion	= TodoyuExtensions::getVersion($extKey);

			if( version_compare($requiredVersion, $installedVersion) === 1 ) {
				throw new TodoyuInstallerException(Label('sysmanager.extension.installExtension.error.lowVersion') . ': ' . $extKey . ' - ' . $installedVersion . ' < ' . $requiredVersion);
			}
		}


			// Check if the extension conflicts with an installed one
		$installedConflicts	= TodoyuExtensions::getConflicts($ext);

		if( sizeof($installedConflicts) > 0 ) {
			throw new TodoyuInstallerException(Label('sysmanager.extension.installExtension.error.conflicts') . ': ' . implode(', ', $installedConflicts));
		}


			// Check if the extension has conflicts with an installed extension
		$extConflicts	= TodoyuArray::assure($constraints['conflicts']);
		$installedExts	= TodoyuExtensions::getInstalledExtKeys();
		$foundConflicts	= array_intersect($extConflicts, $installedExts);

		if( sizeof($foundConflicts) > 0 ) {
			throw new TodoyuInstallerException(Label('sysmanager.extension.installExtension.error.conflicts') . ': ' . implode(', ', $foundConflicts));
		}

		return true;
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

			$message	= 'Cannot uninstall extension "' . htmlentities($extInfos['title'], ENT_QUOTES, 'UTF-8') . '" (' . $extKey . ').<br>The following extensions depend on it: ' . implode(', ', $dependents);
		} elseif( TodoyuExtensions::isSystemExtension($extKey) ) {
			$extInfos	= TodoyuExtManager::getExtInfos($extKey);
			$message	= '"' . htmlentities($extInfos['title'], ENT_QUOTES, 'UTF-8') . '" is a system extension and cannot be uninstalled';
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



	/**
	 * @todo	comment
	 * @param  $extKey
	 * @param  $versionMajor
	 * @param  $versionMinor
	 * @param  $versionRevision
	 * @return string
	 */
	public static function buildExtensionArchiveName($extKey, $versionMajor, $versionMinor, $versionRevision) {
		return 'TodoyuExt_' . $extKey . '_' . $versionMajor . '.' . $versionMinor . '.' . $versionRevision . '_' . date('Y-m-d_H.i') . '.zip';
	}


	/**
	 * @todo	comment
	 * @throws	Exception
	 * @param	Array		$uploadFile
	 * @param	Boolean		$override
	 * @return
	 */
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



	/**
	 * @todo	comment
	 * @param	String		$extArchiveName
	 * @return	Array
	 */
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



	/**
	 * @todo	comment
	 * @throws	Exception
	 * @param	Array		$file
	 * @param	Boolean		$override
	 * @return	Boolean
	 */
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



	/**
	 * @todo	comment
	 * @throws	Exception
	 * @param	Array		$file
	 * @return	Boolean
	 */
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



	/**
	 * @todo	comment
	 * @param	String		$extKey
	 * @param  String		$pathArchive
	 */
	public static function extractExtensionArchive($extKey, $pathArchive) {
		$archive	= new ZipArchive();
		$archive->open($pathArchive);
		$extDir		= TodoyuExtensions::getExtPath($extKey);

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