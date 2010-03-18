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

/**
 * Render extensions
 *
 * @package		Todoyu
 * @subpackage	Sysmanager
 */

class TodoyuSysManagerExtensionsRenderer {

	/**
	 * Render module
	 *
	 * @param	Array	$params
	 * @return	String
	 */
	public static function renderModule($params) {
		$show	= $params['show'];

		switch( $show ) {
				// Show detailed extensions list
			case 'detail':
				$ext	= $params['extkey'];
				$content= self::renderExtensionDetail($ext);
				break;


				// Default: plain listing of extensions
			default:
				$content	= self::renderExtensionList();
				break;

		}


		return $content;
	}



	/**
	 * Render extensions list
	 *
	 * @return	String
	 */
	private static function renderExtensionList() {
		$tmpl		= 'ext/sysmanager/view/extensionlist.tmpl';
		$content	= '';
		$data		= array('extensions' => array());

		$extensions	= TodoyuExtensions::getInstalledExtKeys();

		sort($extensions);

		foreach($extensions as $extension) {
			$data['extensions'][$extension] = TodoyuExtensions::getExtInfo($extension);
		}

		return render($tmpl, $data);
	}



	/**
	 * Render detailed extensions list
	 *
	 * @todo	check parameter and implement the function
	 * @param	String	$ext
	 * @return	String
	 */
	public static function renderExtensionDetail($ext) {

		return 'info';
	}

}

?>