<?php

class TodoyuSysmanagerExtensionsActionController extends TodoyuActionController {

	/**
	 * Default request to load a tab in the extension manager
	 *
	 * @param	Array		$params
	 * @return	String
	 */
	public function tabviewAction(array $params) {
		$extKey	= $params['extension'];
		$tab	= $params['tab'];

		return TodoyuExtManagerRenderer::renderTabView($extKey, $tab, $params);
	}


	public function installAction(array $params) {
		$extKey	= $params['extension'];

		TodoyuExtInstaller::install($extKey);

		$infos	= TodoyuExtManager::getExtInfos($extKey);

		return $infos['title'];
	}


	public function uninstallAction(array $params) {
		$extKey	= $params['extension'];

		TodoyuExtInstaller::uninstall($extKey);

		$infos	= TodoyuExtManager::getExtInfos($extKey);

		return $infos['title'];
	}

}

?>