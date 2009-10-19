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
TodoyuAdminManager::addModule('extensions', 'Extensions', 'TodoyuExtManagerRenderer::renderModule', 50, array('sysmanager', 'public'));

	// Add records manager
TodoyuAdminManager::addModule('records', 'All Records', 'TodoyuRecordsOverviewRenderer::renderModule', 60, array('sysmanager', 'public'));



//TodoyuAdminManager::addModule('rights', 'Rights Management', 'TodoyuRightsEditorRenderer::renderModule', 60, array('sysmanager', 'public'));

?>