<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2010, snowflake productions GmbH, Switzerland
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

	// Extension manager
if( allowed('sysmanager', 'general:extensions') ) {
	TodoyuAdminManager::addModule('extensions', 'LLL:sysmanager.menu.extensions', 'TodoyuExtManagerRenderer::renderModuleContent', 'TodoyuExtManagerRenderer::renderModuleTabs', 10);
}
	// Records manager
if( allowed('sysmanager', 'general:allrecords') ) {
	TodoyuAdminManager::addModule('records', 'LLL:sysmanager.menu.allRecords', 'TodoyuRecordsOverviewRenderer::renderModuleContent', 'TodoyuRecordsOverviewRenderer::renderModuleTabs', 20);
}

	// Rights manager
if( allowed('sysmanager', 'general:rights') ) {
	TodoyuAdminManager::addModule('rights', 'LLL:sysmanager.menu.rights', 'TodoyuRightsEditorRenderer::renderModuleContent', 'TodoyuRightsEditorRenderer::renderModuleTabs', 30);
}

	// Config manager
if( allowed('sysmanager', 'general:config') ) {
	TodoyuAdminManager::addModule('config', 'LLL:sysmanager.menu.config', 'TodoyuSystemConfigRenderer::renderModuleContent', 'TodoyuSystemConfigRenderer::renderModuleTabs', 40);
}

?>