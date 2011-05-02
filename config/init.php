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

/* ------------------------------------
	Tabs for rights & roles
   ------------------------------------ */
Todoyu::$CONFIG['EXT']['sysmanager']['rightsTabs'] = array(
	array(
		'id'	=> 'rights',
		'label'	=> 'LLL:sysmanager.ext.rights.tab.rights'
	),
	array(
		'id'	=> 'roles',
		'label'	=> 'LLL:sysmanager.ext.rights.tab.roles'
	)
);



/* ------------------------------------
	Tabs for system configuration
   ------------------------------------ */
Todoyu::$CONFIG['EXT']['sysmanager']['configTabs'] = array(
	array(
		'id'	=> 'systemconfig',
		'label'	=> 'LLL:sysmanager.ext.config.tab.systemconfig'
	),
	array(
		'id'	=> 'passwordstrength',
		'label'	=> 'LLL:sysmanager.ext.config.tab.passwordstrength'
	),
	array(
		'id'	=> 'logo',
		'label'	=> 'LLL:sysmanager.ext.config.tab.logo'
	),
	array(
		'id'	=> 'repository',
		'label'	=> 'LLL:sysmanager.ext.config.tab.repository'
	)
);



/* ------------------------------------
	Tabs for extension manager
   ------------------------------------ */
Todoyu::$CONFIG['EXT']['sysmanager']['extensionTabs'] = array(
	'installed'	=> array(
		'id'		=> 'installed',
		'label'		=> 'sysmanager.ext.tabs.extensions'
	),
	'config'	=> array(
		'id'		=> '_config',
		'label'		=> 'sysmanager.ext.tabs.config',
		'class'		=> 'config'
	),
	'info'		=> array(
		'id'		=> '_info',
		'label'		=> '',
		'class'		=> 'info'
	),
	'search'	=> array(
		'id'		=> 'search',
		'label'		=> 'sysmanager.ext.tabs.search',
	),
	'update'	=> array(
		'id'		=> 'update',
		'label'		=> 'sysmanager.ext.tabs.update'
	),
	'imported'	=> array(
		'id'		=> 'imported',
		'label'		=> 'sysmanager.ext.tabs.imported'
	)
);



/* -----------------------------------------
	Settings for uploadable company logo
   ----------------------------------------- */
Todoyu::$CONFIG['EXT']['sysmanager']['logoUpload'] = array(
	'width'	=> 190,
	'height'=> 60,
	'path'	=> 'config/img/logo.png'
);

/* ------------------------------------
	Settings for extension update
   ------------------------------------ */
Todoyu::$CONFIG['EXT']['sysmanager']['update'] = array(
	'host'		=> 'todoyuneu.srv05',
	'get'		=> '?eID=todoyuupdate'
);



/* ------------------------------------
	Configure listing for roles
   ------------------------------------ */
Todoyu::$CONFIG['EXT']['sysmanager']['listing']['roles'] = array(
	'name'		=> 'roles',
	'update'	=> 'sysmanager/role/listing',
	'dataFunc'	=> 'TodoyuSysmanagerRoleEditorManager::getRoleListingData',
	'columns'	=> array(
		'icon'		=> '',
		'title'		=> 'LLL:core.global.title',
		'description'=>'LLL:core.global.description',
		'persons'	=> 'LLL:sysmanager.ext.roles.numPersons',
		'actions'	=> ''
	),
	'truncate'	=> array(
		'title'			=> 30,
		'description'	=> 20
	)
);

?>