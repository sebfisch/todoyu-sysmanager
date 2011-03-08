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
 * Extension installer
 *
 * @package		Todoyu
 * @subpackage	Sysmanager
 */
class TodoyuSysmanagerExtInstaller {

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
			// Add given ext key to  list of installed extensions
		$extKeys	= TodoyuExtensions::getInstalledExtKeys();
		$extKeys[]	= $extKey;

			// Remove duplicate entries
		$extKeys	= array_unique($extKeys);

			// Save installed extensions config file (config/extensions.php)
		self::saveInstalledExtensions($extKeys);


		TodoyuExtensions::addExtAutoloadPaths($extKey);

		self::updateDatabase();

		self::callExtensionInstaller($extKey, 'install');
	}



	/**
	 * Call database update. All necessary database updates are proceeded automatically
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
	 * @param	String		$extKey
	 */
	public static function uninstallExtension($extKey) {
		self::callExtensionSetup($extKey, 'uninstall');

			// Get installed extensions with ext key as array key
		$installed	= array_flip(Todoyu::$CONFIG['EXT']['installed']);

			// Remove extension key from list
		unset($installed[$extKey]);

			// Get the list of extension keys
		$installed	= array_keys($installed);

			// Save installed extensions
		self::saveInstalledExtensions($installed);
	}



	/**
	 * Check whether an extension can be installed
	 *
	 * @param	String		$extKey
	 * @return	Boolean
	 */
	public static function canInstall($extKey) {
			// Check whether dependencies are met
		$canInstall = ! self::hasFailedDependencies($extKey);

			// Check whether any conflicting extensions are already installed
		if( $canInstall ) {
			$canInstall	= ! self::wouldConflict($extKey);
		}

		return $canInstall;
	}



	/**
	 * Check whether given extension would conflict with any other already installed extension
	 *
	 * @todo	not needed yet, implement
	 * @param	String	$extKey
	 * @return	Boolean
	 */
	public static function wouldConflict($extKey) {
//		$conflicting	= self::getConflicts($extKey);

		return false;
	}



	/**
	 * Check whether all extensions which the extension of the given key depends on are installed
	 *
	 * @param	String		$extKey
	 * @return	Boolean
	 */
	public static function hasFailedDependencies($extKey) {
		$missingDependencies	= self::getFailedDependencies($extKey);

		return count($missingDependencies) > 0;
	}



	/**
	 * Get all status array (required vs. installed version) of all missing extensions which are dependencies of given extension
	 *
	 * @param	String	$extKey
	 * @return	Array
	 */
	public static function getFailedDependencies($extKey) {
		$missingDependencies	= array();

			// Are there any dependencies?
		if( TodoyuExtensions::hasDependencies($extKey) ) {
			$dependencies	= TodoyuExtensions::getDependencies($extKey);

			foreach($dependencies as $neededExtKey => $neededExtVersion) {
					// Required dependency installed?
				$dependencyMet		= TodoyuExtensions::isInstalled($neededExtKey);
				$installedExtVersion= $dependencyMet ? TodoyuExtensions::getVersion($neededExtKey) : '';

					// Installed ext version up-to-date of required version?
				if( $dependencyMet && ! TodoyuNumeric::isVersionAtLeast($installedExtVersion, $neededExtVersion) ) {
					$dependencyMet  = false;
				}

				if( ! $dependencyMet ){
					$missingDependencies[$neededExtKey]	= array(
						'versionRequired'	=> $neededExtVersion,
						'versionInstalled'	=> $installedExtVersion,
					);
				}
			}
		}

		return $missingDependencies;
	}



	/**
	 * Get textual list of failed dependencies of given extension
	 *
	 * @param	String	$extKey
	 * @return	String
	 */
	public static function getFailedDependenciesList($extKey) {
		$list				= array();
		$failedDependencies	= self::getFailedDependencies($extKey);

		foreach($failedDependencies as $extKey => $conformance) {
			$list[]= $extKey . ' version ' . $conformance['versionRequired'];
		}

		return implode(', ', $list);
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
				throw new TodoyuSysmanagerInstallerException(Label('sysmanager.ext.extension.installExtension.error.core') . ': ' . TODOYU_VERSION . ' < ' . $constraints['core']);
			}
		}

			// Check if all dependencies are ok
		foreach($depends as $extKey => $requiredVersion) {
			if( ! TodoyuExtensions::isInstalled($extKey) ) {
				throw new TodoyuSysmanagerInstallerException(Label('sysmanager.ext.extension.installExtension.error.missing') . ': ' . $extKey);
			}
			$installedVersion	= TodoyuExtensions::getVersion($extKey);

			if( version_compare($requiredVersion, $installedVersion) === 1 ) {
				throw new TodoyuSysmanagerInstallerException(Label('sysmanager.ext.extension.installExtension.error.lowVersion') . ': ' . $extKey . ' - ' . $installedVersion . ' < ' . $requiredVersion);
			}
		}

			// Check if the extension conflicts with an installed one
		$installedConflicts	= TodoyuExtensions::getConflicts($ext);

		if( sizeof($installedConflicts) > 0 ) {
			throw new TodoyuSysmanagerInstallerException(Label('sysmanager.ext.extension.installExtension.error.conflicts') . ': ' . implode(', ', $installedConflicts));
		}

			// Check if the extension has conflicts with an installed extension
		$extConflicts	= TodoyuArray::assure($constraints['conflicts']);
		$installedExts	= TodoyuExtensions::getInstalledExtKeys();
		$foundConflicts	= array_intersect($extConflicts, $installedExts);

		if( sizeof($foundConflicts) > 0 ) {
			throw new TodoyuSysmanagerInstallerException(Label('sysmanager.ext.extension.installExtension.error.conflicts') . ': ' . implode(', ', $foundConflicts));
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
			$extInfos	= TodoyuSysmanagerExtManager::getExtInfos($extKey);

			$message	= 'Cannot uninstall extension "' . htmlentities($extInfos['title'], ENT_QUOTES, 'UTF-8') . '" (' . $extKey . ').<br>The following extensions depend on it: ' . implode(', ', $dependents);
		} elseif( TodoyuExtensions::isSystemExtension($extKey) ) {
			$extInfos	= TodoyuSysmanagerExtManager::getExtInfos($extKey);
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
		$archivePath= TodoyuSysmanagerExtArchiver::createExtensionArchive($extKey);
		$extInfo	= TodoyuExtensions::getExtInfo($extKey);
		$version	= TodoyuString::getVersionInfo($extInfo['version']);

		$fileName	= self::buildExtensionArchiveName($extKey, $version['major'], $version['minor'], $version['revision']);
		$mimeType	= 'application/octet-stream';

			// Send file for download and delete temporary ZIP file after download
		TodoyuFileManager::sendFile($archivePath, $mimeType, $fileName);
		unlink($archivePath);
	}



	/**
	 * Assemble filename for an archive file of an extension with the given credentials
	 *
	 * @param	String	$extKey
	 * @param	String	$versionMajor
	 * @param	String	$versionMinor
	 * @param	String	$versionRevision
	 * @return	String
	 */
	public static function buildExtensionArchiveName($extKey, $versionMajor, $versionMinor, $versionRevision) {
		return 'TodoyuExt_' . $extKey . '_' . $versionMajor . '.' . $versionMinor . '.' . $versionRevision . '_' . date('Y-m-d_H.i') . '.zip';
	}



	/**
	 * Verify as extension archive and import uploaded file into ext system
	 *
 	 * @throws	Exception
 	 * @param	Array		$uploadFile
 	 * @param	Boolean		$override
	 * @return	Array
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
			$canImport	= TodoyuSysmanagerExtInstaller::canImportUploadedArchive($uploadFile, $override);
			if( $canImport !== true ) {
				throw new Exception('Can\'t import extension archive: ' . $canImport);
			}

			$archiveInfo	= TodoyuSysmanagerExtInstaller::parseExtensionArchiveName($uploadFile['name']);

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
	 * Parse given (archive's) filename: extract attributes: ext, version, data
	 *
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
	 * Check whether uploaded archive file can be imported into system as extension
	 *
	 * @throws	Exception
	 * @param	Array		$file
	 * @param	Boolean		$override
	 * @return	Boolean
	 */
	public static function canImportUploadedArchive(array $file, $override = false) {
		try {
			if( TodoyuSysmanagerExtInstaller::isValidExtArchive($file) !== true ) {
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
	 * Check whether given archive file contains a valid todoyu extension
	 *
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

			if( $archive->statName('config/boot.php') === false ) {
				throw new Exception('config/boot.php not found in archive');
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
	 * Extract archive file at given path into extension folder named after given extension key
	 *
	 * @param	String		$extKey
	 * @param	String		$pathArchive
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