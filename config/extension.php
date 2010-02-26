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

	// Configure sub tabs of admin module "rights and roles"
$CONFIG['EXT']['sysmanager']['rightsTabs'] = array(
	array(
		'id'	=> 'rights',
		'label'	=> 'LLL:sysmanager.rights.tab.rights'
	),
	array(
		'id'	=> 'roles',
		'label'	=> 'LLL:sysmanager.rights.tab.roles'
	)
);



	// Configure listing for roles
$CONFIG['EXT']['sysmanager']['listing']['roles'] = array(
	'name'		=> 'roles',
	'update'	=> 'sysmanager/role/listing',
	'dataFunc'	=> 'TodoyuRoleEditorManager::getRoleListingData',
	'columns'	=> array(
		'icon'		=> '',
		'title'		=> 'LLL:core.title',
		'description'=>'LLL:core.description',
		'persons'	=> 'LLL:sysmanager.roles.numPersons',
		'actions'	=> ''
	)
);

?>