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

/**
 * Recordsoverview renderer
 *
 * @package		Todoyu
 * @subpackage	Sysmanager
 */
class TodoyuRecordsOverviewRenderer {

	/**
	 * Render content for records overview admin module
	 *
	 * @param	Array		$params
	 * @return	String
	 */
	public static function renderModuleContent(array $params) {
		$recordInfos	= TodoyuRecordsOverviewManager::getAllRecordInfos();

		$tmpl	= 'ext/sysmanager/view/records-overview.tmpl';
		$data	= array(
			'extensions'=> $recordInfos
		);

		return render($tmpl, $data);
	}



	/**
	 * Render tabs for module
	 *
	 * @param	Array		$params
	 * @return	String
	 */
	public static function renderModuleTabs(array $params) {
		return self::renderRecordlistTabs();
	}



	/**
	 * Render recordlist tab
	 *
	 * @return unknown
	 */
	public static function renderRecordlistTabs() {
		$name		= 'record';
		$jsHandler	= 'Todoyu.Ext.sysmanager.RecordsOverview.onTabSelect.bind(Todoyu.Ext.sysmanager.Records)';
		$tabs		= self::getRecordlistTabs();
		$active		= 'list';

		return TodoyuTabheadRenderer::renderTabs($name, $tabs, $jsHandler, $active);
	}



	/**
	 * Get tab config array for recordlist
	 * At the moment its only a title tab without any useful function
	 *
	 * @return	Array
	 */
	public static function getRecordlistTabs() {
		$tabs	= array();

		$tabs[] = array(
			'id'		=> 'list',
			'label'		=> 'All extension records'
		);

		return $tabs;
	}

}

?>