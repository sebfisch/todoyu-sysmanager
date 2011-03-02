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

	// Extension manager
if( allowed('sysmanager', 'general:extensions') ) {
	TodoyuAdminManager::addModule('extensions', 'LLL:sysmanager.ext.menu.extensions', 'TodoyuSysmanagerExtManagerRenderer::renderModule', 10);
}
	// Records manager
if( allowed('sysmanager', 'general:records') ) {
	TodoyuAdminManager::addModule('records', 'LLL:sysmanager.ext.menu.records', 'TodoyuSysmanagerExtRecordRenderer::renderModule', 20);
}

	// Rights manager
if( allowed('sysmanager', 'general:rights') ) {
	TodoyuAdminManager::addModule('rights', 'LLL:sysmanager.ext.menu.rights', 'TodoyuSysmanagerRightsEditorRenderer::renderModule', 30);
}

	// Config manager
if( allowed('sysmanager', 'general:config') ) {
	TodoyuAdminManager::addModule('config', 'LLL:sysmanager.ext.menu.config', 'TodoyuSysmanagerSystemConfigRenderer::renderModule', 40);
}

?>