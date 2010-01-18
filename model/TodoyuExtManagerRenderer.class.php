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
 * Extension management renderer
 *
 * @package		Todoyu
 * @subpackage	Sysmanager
 */
class TodoyuExtManagerRenderer {

	/**
	 * Render extension management module content
	 *
	 * @param	Array		$params		All request params
	 * @return	String
	 */
	public static function renderModuleContent(array $params) {
		restrict('sysmanager', 'general:extensions');

		$extKey	= $params['extkey'];
		$tab	= $params['tab'];

		return self::renderTabView($extKey, $tab, $params);
	}



	/**
	 * Render extension manager tabs
	 *
	 * @param	Array		$params
	 * @return	String
	 */
	public static function renderModuleTabs(array $params) {
		$extKey	= $params['extkey'];
		$tab	= $params['tab'];

		return self::renderTabs($extKey, $tab);
	}



	/**
	 * Render tabbed view
	 *
	 * @param	String		$extKey			Extension key of active extension
	 * @param	String		$tab			Active tab
	 * @param	Array		$params			Request parameters
	 * @return	String		HTML content for tabbed view
	 */
	public static function renderTabView($extKey = null, $tab = null, array $params = array()) {
		$content	= self::renderTabs($extKey, $tab);

		switch($tab) {

			case 'info':
				$content .= self::renderInfo($extKey, $params);
				break;

			case 'config':
				$content .= self::renderConfig($extKey, $params);
				break;

			case 'rights':
				$content .= self::renderRights($extKey, $params);
				break;

			case 'records':
				$content .= self::renderRecords($extKey, $params);
				break;

			case 'install':
				$content .= self::renderInstall($params);
				break;

			case 'update':
				$content .= self::renderUpdate($params);
				break;

			default:
				$content .=self::renderList($params);
				break;
		}

		return $content;
	}



	/**
	 * Render tabs based on current settings
	 *
	 * @param	String		$extKey		Extension key
	 * @param	String		$active		Active tab key
	 * @return	String
	 */
	public static function renderTabs($extKey = null, $active = null) {
		$htmlID		= 'extension-tabs';
		$class		= 'admin tabs';
		$jsHandler	= 'Todoyu.Ext.sysmanager.Extensions.onTabClick.bind(Todoyu.Ext.sysmanager.Extensions)';
		$tabs		= TodoyuExtManager::getTabConfig($extKey);

		if( is_null($active) )	{
			$active = $tabs[0]['id'];
		}

		return TodoyuTabheadRenderer::renderTabs($htmlID, $class, $jsHandler, $tabs, $active);
	}



	/**
	 * Render extension list
	 *
	 * @param	Array		$params
	 * @return	String
	 */
	public static function renderList(array $params = array()) {
		$tmpl		= 'ext/sysmanager/view/extension-list.tmpl';
		$data		= array(
			'extensions' => array()
		);

		$extensions	= TodoyuExtensions::getInstalledExtKeys();
		sort($extensions);

		foreach($extensions as $extension) {
			$data['extensions'][$extension] = TodoyuExtensions::getExtInfo($extension);
		}

		return render($tmpl, $data);
	}



	/**
	 * Render extension info
	 *
	 * @param	String		$extKey		Extension key
	 * @param	Array		$params		Request parameters
	 * @return	String
	 */
	public static function renderInfo($extKey, array $params = array()) {
		$info	= TodoyuExtManager::getExtInfos($extKey);

		$tmpl	= 'ext/sysmanager/view/extension-info.tmpl';
		$data	= array(
			'ext' 	=> $info,
			'extKey'=> $extKey
		);

		return render($tmpl, $data);
	}



	/**
	 * Render extension info
	 *
	 * @param	String		$extKey		Extension key
	 * @param	Array		$params		Request parameters
	 * @return	String
	 */
	public static function renderConfig($extKey, array $params = array()) {
		return TodoyuExtConfRenderer::renderConfig($extKey);
	}



	/**
	 * Render rights
	 *
	 * @param	String	$extKey
	 * @param	Array	$params
	 * @return	String
	 */
	public static function renderRights($extKey, array $params = array()) {
		return TodoyuRightsEditorRenderer::renderExtRightsEditor($extKey);
	}



	/**
	 * Render records
	 *
	 * @param	String	$extKey
	 * @param	Array	$params
	 * @return	String
	 */
	public static function renderRecords($extKey, array $params = array()) {
		return TodoyuExtRecordRenderer::renderRecords($extKey, $params);
	}



	/**
	 * Render install
	 *
	 * @param	String	$extKey
	 * @param	Array $params
	 * @return	String
	 */
	public static function renderInstall(array $params = array()) {
		$notInstalled	= TodoyuExtensions::getNotInstalledExtKeys();
		$tmpl			= 'ext/sysmanager/view/extension-list-notinstalled.tmpl';
		$data			= array(
			'extensions' => array()
		);

		sort($notInstalled);

		foreach($notInstalled as $extension) {
			$data['extensions'][$extension] = TodoyuExtensions::getExtInfo($extension);
		}

		return render($tmpl, $data);
	}


	public static function renderUpdate(array $params = array()) {
		return 'update';
	}

}

?>