<?php



class TodoyuExtInstaller {

	private static function writeExtensionsFile(array $extensions) {
		$file	= PATH_LOCALCONF . '/extensions.php';
		$tmpl	= 'ext/sysmanager/view/extensions.php.tmpl';
		$data	= array(
			'extensions'	=> $extensions
		);

		TodoyuFileManager::saveTemplatedFile($file, $tmpl, $data);
	}

	public static function setInstalledExtensions(array $extensions) {
			// Update global config array
		$GLOBALS['CONFIG']['EXT']['installed'] = $installed;

			// Update config file
		self::writeExtensionsFile($extensions);
	}


	public static function install($extKey) {
			// Get installed extensions
		$installed	= $GLOBALS['CONFIG']['EXT']['installed'];

			// Add extension key to list
		$installed[] = $extKey;

			// Remove duplicate entries
		$installed = array_unique($installed);

			// Save installed extensions
		self::setInstalledExtensions($installed);
	}


	public static function uninstall($extKey) {
			// Get installed extensions with extkey as array key
		$installed	= array_flip($GLOBALS['CONFIG']['EXT']['installed']);

			// Remove extension key from list
		unset($installed[$extKey]);

			// Get the list of extensionkeys
		$installed	= array_keys($installed);

			// Save installed extensions
		self::setInstalledExtensions($installed);
	}

	public static function canUninstall($extKey) {
		return TodoyuExtensions::hasDependents($extKey) === false;
	}


}


?>