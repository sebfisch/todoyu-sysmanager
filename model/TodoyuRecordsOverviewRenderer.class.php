<?php


class TodoyuRecordsOverviewRenderer {

	public static function renderModule(array $params) {
		$recordInfos	= TodoyuRecordsOverviewManager::getAllRecordInfos();

		$tmpl	= 'ext/sysmanager/view/records-overview.tmpl';
		$data	= array(
			'tabs'		=> self::renderRecordlistTabs(),
			'extensions'=> $recordInfos
		);

//		TodoyuDebug::printHtml($recordInfos);

		return render($tmpl, $data);
	}


	public static function renderRecordlistTabs() {
		$listID		= 'record-tabs';
		$class		= 'tabs';
		$jsHandler	= 'Todoyu.Ext.sysmanager.RecordsOverview.onTabSelect.bind(Todoyu.Ext.sysmanager.Records)';
		$tabs		= self::getRecordlistTabs();
		$active		= 'list';

		return TodoyuTabheadRenderer::renderTabs($listID, $class, $jsHandler, $tabs, $active);

	}


	public static function getRecordlistTabs() {
		$tabs	= array();

		$tabs[] = array(
			'id'		=> 'list',
			'htmlId'	=> 'list',
			'class'		=> 'recordstab',
			'classKey'	=> 'list',
			'label'		=> 'All extension records'
		);

		return $tabs;
	}

}


?>