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

	// Add extension manager
if( allowed('sysmanager', 'general:extensions') ) {
	TodoyuAdminManager::addModule('extensions', 'LLL:sysmanager.menu.extensions', 'TodoyuExtManagerRenderer::renderModuleContent', 'TodoyuExtManagerRenderer::renderModuleTabs', 10, array('sysmanager'));
}

if( allowed('sysmanager', 'general:allrecords') ) {
		// Add records manager
	TodoyuAdminManager::addModule('records', 'LLL:sysmanager.menu.allRecords', 'TodoyuRecordsOverviewRenderer::renderModuleContent', 'TodoyuRecordsOverviewRenderer::renderModuleTabs', 20, array('sysmanager'));
}

if( allowed('sysmanager', 'general:rights') ) {
		// Add records manager
	TodoyuAdminManager::addModule('rights', 'LLL:sysmanager.menu.rights', 'TodoyuRightsEditorRenderer::renderModuleContent', 'TodoyuRightsEditorRenderer::renderModuleTabs', 30, array('sysmanager'));
}

?>