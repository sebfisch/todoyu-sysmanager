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

class TodoyuSysmanagerExtensionsActionController extends TodoyuActionController {

	/**
	 * @var	String		Extension parameter of the request
	 */
	protected $extKey;

	/**
	 * @var	String		Type parameter of the request
	 */
	protected $type;



	/**
	 * Set extKey and type on request start because its used by all functions
	 *
	 * @param	Array		$params
	 */
	public function init(array $params) {
		restrictAdmin();

		TodoyuExtensions::loadAllAdmin();

		$this->extKey	= $params['extKey'];
		$this->type		= $params['type'];
	}



	/**
	 * Default request to load a tab in the extension manager
	 *
	 * @param	Array		$params
	 * @return	String
	 */
	public function tabviewAction(array $params) {
		return TodoyuExtManagerRenderer::renderContent($params);
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

			$message	= $extInfos['title'];
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


	public function showImportAction(array $params) {
		return 'Import screen';		
	}

}

?>