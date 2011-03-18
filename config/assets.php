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
 * Assets configuration for sysmanager extension
 *
 * @package		Todoyu
 * @subpackage	SysManager
 */

Todoyu::$CONFIG['EXT']['sysmanager']['assets'] = array(
	'js' => array(
		array(
			'file'		=> 'ext/sysmanager/asset/js/Ext.js',
			'position'	=> 100
		),
		array(
			'file'		=> 'ext/sysmanager/asset/js/QuickCreateRole.js',
			'position'	=> 100
		),
		array(
			'file'		=> 'ext/sysmanager/asset/js/Extensions.js',
			'position'	=> 102
		),
		array(
			'file'		=> 'ext/sysmanager/asset/js/ExtensionsImport.js',
			'position'	=> 103
		),
		array(
			'file'		=> 'ext/sysmanager/asset/js/ExtensionsInstall.js',
			'position'	=> 103
		),
		array(
			'file'		=> 'ext/sysmanager/asset/js/Records.js',
			'position'	=> 103
		),
		array(
			'file'		=> 'ext/sysmanager/asset/js/ExtConf.js',
			'position'	=> 103
		),
		array(
			'file'		=> 'ext/sysmanager/asset/js/Rights.js',
			'position'	=> 101
		),
		array(
			'file'		=> 'ext/sysmanager/asset/js/RightsEditor.js',
			'position'	=> 102
		),
		array(
			'file'		=> 'ext/sysmanager/asset/js/Roles.js',
			'position'	=> 102
		),
		array(
			'file'		=> 'ext/sysmanager/asset/js/Config.js',
			'position'	=> 102
		),
		array(
			'file'		=> 'ext/sysmanager/asset/js/ConfigLogo.js',
			'position'	=> 103
		),
		array(
			'file'		=> 'ext/sysmanager/asset/js/Updater.js',
			'position'	=> 104
		)
	),
	'css' => array(
		array(
			'file'		=> 'ext/sysmanager/asset/css/ext.css',
			'media'		=> 'all',
			'position'	=> 100
		),
		array(
			'file'		=> 'ext/sysmanager/asset/css/extensions.css',
			'media'		=> 'all',
			'position'	=> 101
		),
		array(
			'file'		=> 'ext/sysmanager/asset/css/records.css',
			'media'		=> 'all',
			'position'	=> 102
		),
		array(
			'file'		=> 'ext/sysmanager/asset/css/recordsoverview.css',
			'media'		=> 'all',
			'position'	=> 102
		),
		array(
			'file'		=> 'ext/sysmanager/asset/css/rights.css',
			'media'		=> 'all',
			'position'	=> 102
		),
		array(
			'file'		=> 'ext/sysmanager/asset/css/config.css',
			'media'		=> 'all',
			'position'	=> 102
		),
		array(
			'file'		=> 'ext/sysmanager/asset/css/updater.css',
			'media'		=> 'all',
			'position'	=> 102
		)
	)
);


?>