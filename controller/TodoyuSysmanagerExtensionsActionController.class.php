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
		$message= '';


		if( TodoyuExtInstaller::canUninstall($extKey) ) {
			TodoyuExtInstaller::uninstall($extKey);
			$extInfos	= TodoyuExtManager::getExtInfos($extKey);

			$message= 'Extension "' . htmlentities($extInfos['title']) . '" sucessfully uninstalled';
		} else {
			$message	= TodoyuExtInstaller::getUninstallFailReason($extKey);

			TodoyuHeader::sendTodoyuErrorHeader();
		}

		return $message;
	}

	public function downloadAction(array $params) {
		$extKey	= $params['extension'];

		TodoyuExtInstaller::downloadExtension($extKey);
	}

}

?>