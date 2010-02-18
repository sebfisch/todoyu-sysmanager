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

	// Add extension manager
if( allowed('sysmanager', 'general:extensions') ) {
	TodoyuAdminManager::addModule('extensions', 'LLL:sysmanager.menu.extensions', 'TodoyuExtManagerRenderer::renderModuleContent', 'TodoyuExtManagerRenderer::renderModuleTabs', 10, array('sysmanager', 'public'));
}

if( allowed('sysmanager', 'general:allrecords') ) {
		// Add records manager
	TodoyuAdminManager::addModule('records', 'LLL:sysmanager.menu.allRecords', 'TodoyuRecordsOverviewRenderer::renderModuleContent', 'TodoyuRecordsOverviewRenderer::renderModuleTabs', 20, array('sysmanager', 'public'));
}

if( allowed('sysmanager', 'general:rights') ) {
		// Add records manager
	TodoyuAdminManager::addModule('rights', 'LLL:sysmanager.menu.rights', 'TodoyuRightsEditorRenderer::renderModuleContent', 'TodoyuRightsEditorRenderer::renderModuleTabs', 30, array('sysmanager', 'public'));
}


//TodoyuPage::addExtAssets('sysmanager', 'public');

//TodoyuAdminManager::addModule('rights', 'Rights Management', 'TodoyuRightsEditorRenderer::renderModule', 60, array('sysmanager', 'public'));

?>