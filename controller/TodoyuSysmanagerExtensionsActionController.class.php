<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 snowflake productions gmbh
*  All rights reserved
*
*  This script is part of the todoyu project.
*  The todoyu project is free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License, version 2,
*  (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html) as published by
*  the Free Software Foundation;
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

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

		return TodoyuExtManagerRenderer::renderContent($params); //($extKey, $tab, $params);
	}



	/**
	 * Install an extension
	 *
	 * @param	Array		$params
	 * @return	String
	 */
	public function installAction(array $params) {
		$extKey	= $params['extension'];

		TodoyuExtInstaller::install($extKey);

		$infos	= TodoyuExtManager::getExtInfos($extKey);

		return $infos['title'];
	}



	/**
	 * Uninstall an extension
	 *
	 * @param	Array		$params
	 * @return	String
	 */
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



	/**
	 * Download an extension packed in an archive (zip)
	 *
	 * @param	Array		$params
	 */
	public function downloadAction(array $params) {
		$extKey	= $params['extension'];

		TodoyuExtInstaller::downloadExtension($extKey);
	}

}

?>