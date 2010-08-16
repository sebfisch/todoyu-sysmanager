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
		return TodoyuExtManagerRenderer::renderModule($params);
	}



	/**
	 * Install an extension
	 *
	 * @param	Array		$params
	 * @return	String
	 */
	public function installAction(array $params) {
		$extKey	= $params['extension'];

		TodoyuExtInstaller::installExtension($extKey);

		$infos	= TodoyuExtManager::getExtInfos($extKey);

		TodoyuHeader::sendTodoyuHeader('extTitle', $infos['title']);
	}



	/**
	 * Uninstall an extension
	 *
	 * @param	Array		$params
	 * @return	String
	 */
	public function uninstallAction(array $params) {
		$extKey	= $params['extension'];

		if( TodoyuExtInstaller::canUninstall($extKey) ) {
			$extInfos	= TodoyuExtManager::getExtInfos($extKey);

			TodoyuExtInstaller::uninstallExtension($extKey);

			TodoyuHeader::sendTodoyuHeader('extTitle', $extInfos['title']);

			return TodoyuExtInstallerRenderer::renderUninstalledDialog($extKey);
		} else {
			$info	= TodoyuExtInstaller::getUninstallFailReason($extKey);

			TodoyuHeader::sendTodoyuErrorHeader();
			TodoyuHeader::sendTodoyuHeader('info', $info);
		}
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



	/**
	 * Remove extension from server
	 *
	 * @param	Array		$params
	 */
	public function removeAction(array $params) {
		$extKey	= $params['extension'];

		$status	= TodoyuExtInstaller::removeExtensionFromServer($extKey);

		if( $status === false ) {
			TodoyuHeader::sendTodoyuErrorHeader();
		}
	}



	/**
	 * Show dialog for extension import
	 *
	 * @param	Array		$params
	 * @return	String
	 */
	public function showImportAction(array $params) {
		$xmlPath= 'ext/sysmanager/config/form/extension-import.xml';
		$form	= TodoyuFormManager::getForm($xmlPath);
		$form->setUseRecordID(false);

		$tmpl	= 'ext/sysmanager/view/extension-import.tmpl';
		$data	= array(
			'form'	=> $form->render()
		);

		return render($tmpl, $data);
	}



	/**
	 * @param  $params
	 * @return String
	 */
	public function showUpdateAction(array $params) {
		$ext	= $params['extension'];

		return TodoyuExtInstallerRenderer::renderUpdateDialog($ext);
	}


	public function uploadAction(array $params) {
		$uploadFile	= TodoyuRequest::getUploadFile('file', 'importExtension');
		$data		= $params['importExtension'];
		$override	= intval($data['override']) === 1;

		$info	= TodoyuExtInstaller::importExtensionArchive($uploadFile, $override);

		$command	= 'window.parent.Todoyu.Ext.sysmanager.Extensions.Import.uploadFinished("' . $info['ext'] . '", ' . ($info['success']?'true':'false') . ', "' . $info['message'] . '");';

		return TodoyuRenderer::renderUploadIFrameJsContent($command);
	}

}

?>