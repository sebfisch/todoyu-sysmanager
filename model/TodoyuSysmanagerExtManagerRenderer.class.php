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
 * Extension management renderer
 *
 * @package		Todoyu
 * @subpackage	Sysmanager
 */
class TodoyuSysmanagerExtManagerRenderer {

	/**
	 * Render extension module
	 *
	 * @param	Array		$params
	 * @return	String
	 */
	public static function renderModule(array $params) {
		$extKey	= trim($params['extkey']);
		$tab	= trim($params['tab']);

		$tabs	= self::renderTabs($extKey, $tab);
		$body	= self::renderBody($extKey, $tab, $params);

		return TodoyuRenderer::renderContent($body, $tabs);
	}



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

		return self::renderBody($extKey, $tab, $params);
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
	public static function renderBody($extKey = '', $tab = '', array $params = array()) {
		switch($tab) {
			case 'info':
				$content = self::renderInfo($extKey, $params);
				break;

			case 'config':
				$content = self::renderConfig($extKey, $params);
				break;

			case 'imported':
				$content = self::renderImport($params);
				break;

			case 'update':
				$content = self::renderUpdate($params);
				break;

			case 'search':
				$content = self::renderSearch($params);
				break;

			case 'installed':
			default:
				$content =self::renderList($params);
				break;
		}

			// Call hook for possible content modifications
		$hookResults   = TodoyuHookManager::callHook('sysmanager', 'renderExtContent-' . $extKey, array($tab, $params, $content));
		if( is_array($hookResults) && ! empty($hookResults[0]) ) {
			$content	= $hookResults[0];
		}

		return $content;
	}



	/**
	 * Render tabs based on current settings
	 *
	 * @param	String		$extKey		Extension key
	 * @param	String		$tab		Active tab key
	 * @return	String
	 */
	public static function renderTabs($extKey = '', $tab = '') {
		$name		= 'extension';
		$class		= 'admin';
		$jsHandler	= 'Todoyu.Ext.sysmanager.Extensions.onTabClick.bind(Todoyu.Ext.sysmanager.Extensions)';
		$tabs		= TodoyuSysmanagerExtManager::getTabConfig($extKey, $tab);

		if( empty($tab) ) {
			$active	= $tabs[0]['id'];
		} elseif( empty($extKey) ) {
			$active	= $tab;
		} else {
			$active	= $extKey . '_' . $tab;
		}

		return TodoyuTabheadRenderer::renderTabs($name, $tabs, $jsHandler, $active, $class);
	}



	/**
	 * Render extension list
	 *
	 * @param	Array		$params
	 * @return	String
	 */
	public static function renderList(array $params = array()) {
		$tmpl		= 'ext/sysmanager/view/extension-list-installed.tmpl';
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
		$info	= TodoyuSysmanagerExtManager::getExtInfos($extKey);

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
		return TodoyuSysmanagerExtConfRenderer::renderConfig($extKey);
	}



	/**
	 * Render install
	 *
	 * @param	String	$extKey
	 * @param	Array $params
	 * @return	String
	 */
	public static function renderImport(array $params = array()) {
		$notInstalled	= TodoyuExtensions::getNotInstalledExtKeys();
		$tmpl			= 'ext/sysmanager/view/extension-list-imported.tmpl';
		$data			= array(
			'extensions' => array()
		);

		sort($notInstalled);

		foreach($notInstalled as $extension) {
			$data['extensions'][$extension] = TodoyuExtensions::getExtInfo($extension);
		}

		return render($tmpl, $data);
	}



	/**
	 * Render updates listing
	 *
	 * @param	Array	$params
	 * @return	String
	 */
	public static function renderUpdate(array $params = array()) {
		return TodoyuSysmanagerRepositoryRenderer::renderUpdate($params);
	}



	/**
	 *
	 *
	 * @param	Array	$params
	 * @return	String
	 */
	public static function renderSearch(array $params = array()) {
		return TodoyuSysmanagerRepositoryRenderer::renderSearch($params);
	}

}

?>