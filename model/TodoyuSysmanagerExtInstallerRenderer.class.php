<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2012, snowflake productions GmbH, Switzerland
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
 * Render for extension installation
 *
 * @package		Todoyu
 * @subpackage	Sysmanager
 */
class TodoyuSysmanagerExtInstallerRenderer {

	/**
	 * Render dialog for extension update
	 *
	 * @param	String		$ext
	 * @return	String
	 */
	public static function renderUpdateDialog($ext) {
		$tmpl	= 'ext/sysmanager/view/extension/installed.tmpl';
		$data	= array(
			'ext'	=> $ext
		);

		return Todoyu::render($tmpl, $data);
	}



	/**
	 *
	 *
	 * @param	String	$ext
	 * @return	String
	 */
	public static function renderUninstalledDialog($ext) {
		$tmpl	= 'ext/sysmanager/view/extension/uninstalled.tmpl';
		$data	= array(
			'ext'	=> $ext
		);

		return Todoyu::render($tmpl, $data);
	}



	/**
	 * Render installation error notification message
	 *
	 * @param	String	$extKey
	 * @return	String
	 */
	public static function renderInstallationErrorMessage($extKey) {
// @todo	template was missing. added dummy now. check functionality + necessity of template.
		$tmpl	= 'ext/sysmanager/view/extension/install-error.tmpl';
		$data	= array(
			'extInfo'				=> TodoyuExtensions::getExtInfo($extKey),
			'missingDependencies'	=> TodoyuSysmanagerExtInstaller::getFailedDependencies($extKey)
		);

		return Todoyu::render($tmpl, $data);
	}

}

?>