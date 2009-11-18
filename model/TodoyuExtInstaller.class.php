<?php



class TodoyuExtInstaller {

	private static function saveInstalledExtensions() {
		$file	= PATH_LOCALCONF . '/extensions.php';
		$tmpl	= PATH_CORE . '/view/extensions.php.tmpl';
		$data	= array(
			'extensions'	=> $GLOBALS['CONFIG']['EXT']['installed']
		);

		TodoyuFileManager::saveTemplatedFile($file, $tmpl, $data);
	}


	public static function install($extKey) {
		$GLOBALS['CONFIG']['EXT']['installed'][] = $extKey;

		$GLOBALS['CONFIG']['EXT']['installed'] = array_unique($GLOBALS['CONFIG']['EXT']['installed']);

		self::saveInstalledExtensions();
	}


	public static function uninstall($extKey) {
		$installed	= array_flip($GLOBALS['CONFIG']['EXT']['installed']);

		unset($installed[$extKey]);

		$GLOBALS['CONFIG']['EXT']['installed']	= array_keys($installed);

		self::saveInstalledExtensions();
	}


}


?>