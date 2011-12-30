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
 * @package		Todoyu
 * @subpackage	Sysmanager
 */
class TodoyuSysmanagerExtensionsActionController extends TodoyuActionController {

	/**
	 * Restrict access
	 *
	 * @param	Array		$params
	 */
	public function init(array $params) {
		Todoyu::restrict('sysmanager', 'general:extensions');

		TodoyuExtensions::loadAllAdmin();
	}



	/**
	 * Default request to load a tab in the extension manager
	 *
	 * @param	Array		$params
	 * @return	String
	 */
	public function tabviewAction(array $params) {
		return TodoyuSysmanagerExtManagerRenderer::renderModule($params);
	}



	/**
	 * Install an extension
	 *
	 * @param	Array		$params
	 * @return	String
	 */
	public function installAction(array $params) {
		$extKey		= $params['extension'];

			// Check whether the extension needs to be registered
		$major					= TodoyuSysmanagerExtManager::getMajorVersion($extKey);
		$registrationRequired	= TodoyuSysmanagerRepositoryManager::isRegistrationRequired($extKey, $major);
			// Commercial extensions have to be activated first
		if( $registrationRequired ) {
			TodoyuHeader::sendTodoyuHeader('registrationRequired', 1);
				// Stop here, force registration first
			return;
		}

			// Normal extension installation
		if( TodoyuSysmanagerExtInstaller::canInstall($extKey) ) {
				// Can be installed, do it!
			TodoyuSysmanagerExtInstaller::installExtension($extKey);
		} else {
			$installProblems	= TodoyuSysmanagerExtInstaller::getInstallProblems($extKey);

				// Send error header
			TodoyuHeader::sendTodoyuErrorHeader();

			TodoyuHeader::sendTodoyuHeader('installProblems', $installProblems);
		}

		$extInfos	= TodoyuSysmanagerExtManager::getExtInfos($extKey);
		TodoyuHeader::sendTodoyuHeader('extTitle', $extInfos['title']);
	}



	/**
	 * License an imported extension
	 *
	 * @param	Array	$params
	 */
	public function licenseImportedExtensionAction(array $params) {
		$extKey	= trim($params['extension']);
		$major	= TodoyuSysmanagerExtManager::getMajorVersion($extKey);

		$result	= TodoyuSysmanagerRepositoryManager::licenseExtension($extKey, $major);

		TodoyuHeader::sendTodoyuHeader('licensed', $result?1:0);
	}

	

	/**
	 * Uninstall an extension
	 *
	 * @param	Array		$params
	 * @return	String
	 */
	public function uninstallAction(array $params) {
		$extKey			= $params['extension'];
		$canUninstall	= TodoyuSysmanagerExtInstaller::canUninstall($extKey);

		if( $canUninstall ) {
			$extInfos	= TodoyuSysmanagerExtManager::getExtInfos($extKey);

			TodoyuSysmanagerExtInstaller::uninstallExtension($extKey);

			TodoyuHeader::sendTodoyuHeader('extTitle', $extInfos['title']);

			return TodoyuSysmanagerExtInstallerRenderer::renderUninstalledDialog($extKey);
		} else {
			$info	= TodoyuSysmanagerExtInstaller::getUninstallFailReason($extKey);

			TodoyuHeader::sendTodoyuErrorHeader();
			TodoyuHeader::sendTodoyuHeader('info', $info);
		}

		return '';
	}



	/**
	 * Download an extension packed in an archive (ZIP)
	 *
	 * @param	Array		$params
	 */
	public function downloadAction(array $params) {
		$extKey	= $params['extension'];

		TodoyuSysmanagerExtInstaller::downloadExtension($extKey);
	}



	/**
	 * Remove extension from server
	 *
	 * @param	Array		$params
	 */
	public function removeAction(array $params) {
		$extKey	= $params['extension'];

		$status	= TodoyuSysmanagerExtInstaller::removeExtensionFromServer($extKey);

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

		$tmpl	= 'ext/sysmanager/view/extension/import.tmpl';
		$data	= array(
			'form'	=> $form->render()
		);

		return Todoyu::render($tmpl, $data);
	}



	/**
	 * Get rendered extension update dialog
	 *
	 * @param	Array	$params
	 * @return	String
	 */
	public function showUpdateAction(array $params) {
		$ext	= $params['extension'];

		return TodoyuSysmanagerExtInstallerRenderer::renderUpdateDialog($ext);
	}



	/**
	 * Upload extension file to server
	 *
	 * @param	Array	$params
	 * @return	String
	 */
	public function uploadAction(array $params) {
		$uploadFile	= TodoyuRequest::getUploadFile('file', 'importExtension');
		$data		= $params['importExtension'];
		$override	= intval($data['override']) === 1;

		$success	= false;
		$archiveInfo= TodoyuSysmanagerExtInstaller::parseExtensionArchiveName($uploadFile['name']);

		if( $archiveInfo !== false ) {
			$canImport	= TodoyuSysmanagerExtImporter::canImportExtension($archiveInfo['ext'], $uploadFile['tmp_name'], $override);

			if( $canImport === true ) {
				$success 	= true;
			} else {
				$errorMsg	= $canImport;
			}

			if( $success ) {
				TodoyuSysmanagerExtImporter::importExtensionArchive($archiveInfo['ext'], $uploadFile['tmp_name'], $override);
			}

			$command	= 'window.parent.Todoyu.Ext.sysmanager.Extensions.Import.importFinished("' . $archiveInfo['ext'] . '", ' . ($success?'true':'false') . ', "' . $errorMsg . '");';
		} else {
			$errorMsg	= 'Name format of extension archive is invalid';
			$command	= 'window.parent.Todoyu.Ext.sysmanager.Extensions.Import.importFailed("' . $errorMsg . '");';
		}

		return TodoyuRenderer::renderUploadIFrameJsContent($command);
	}

}

?>