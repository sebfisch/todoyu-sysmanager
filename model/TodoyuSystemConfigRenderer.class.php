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
 * System Config Renderer
 *
 * @package		Todoyu
 * @subpackage	Sysmanager
 */
class TodoyuSystemConfigRenderer {

	/**
	 * Render module content
	 *
	 * @param	Array	$params
	 * @return	String
	 */
	public static function renderModule(array $params) {
		$tabs	= self::renderTabs($params);
		$body	= self::renderBody($params);

		return TodoyuRenderer::renderContent($body, $tabs);
	}



	/**
	 * Render module body content
	 *
	 * @param	Array	$params
	 * @return	String
	 */
	private static function renderBody(array $params) {
		return self::renderBodyLogo($params);
	}



	/**
	 * Render body content for logo upload
	 *
	 * @param	Array	$params
	 * @return	String
	 */
	private static function renderBodyLogo(array $params) {
		$xmlPath= 'ext/sysmanager/config/form/config-logo.xml';
		$form	= TodoyuFormManager::getForm($xmlPath);

		$form->setUseRecordID(false);

		return $form->render();
	}



	/**
	 * Render tabs
	 * 
	 * @param	Array	$params
	 * @return	String
	 */
	private static function renderTabs(array $params) {
		$name		= 'config';
		$jsHandler	= 'Todoyu.Ext.sysmanager.Config.onTabClick.bind(Todoyu.Ext.sysmanager.Config)';
		$tabs		= TodoyuArray::assure(Todoyu::$CONFIG['EXT']['sysmanager']['configTabs']);

		foreach($tabs as $tab) {
			$active	= $tab['id'];
			break;
		}

		return TodoyuTabheadRenderer::renderTabs($name, $tabs, $jsHandler, $active);
	}
}

?>