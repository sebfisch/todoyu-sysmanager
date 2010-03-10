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

/**
 * Page configuration for sysmanager extension
 *
 * @package		Todoyu
 * @subpackage	SysManager
 */

$CONFIG['EXT']['sysmanager']['assets'] = array(
		// Default assets: loaded all over the installation always
	'default' => array(
		'js' => array(

		),
		'css' => array(
			array(
				'file'		=> 'ext/sysmanager/assets/css/global.css',
				'media'		=> 'all',
				'position'	=> 100
			),
		)
	),

		// Public assets: basic assets for this extension
	'public' => array(
		'js' => array(
			array(
				'file'		=> 'ext/sysmanager/assets/js/Ext.js',
				'position'	=> 100
			),
				// Add creation engines to quick create headlet
			array(
				'file'		=> 'ext/sysmanager/assets/js/HeadletQuickCreateRole.js',
				'position'	=> 100
			),
			array(
				'file'		=> 'ext/sysmanager/assets/js/Extensions.js',
				'position'	=> 102
			),
			array(
				'file'		=> 'ext/sysmanager/assets/js/Records.js',
				'position'	=> 103
			),
			array(
				'file'		=> 'ext/sysmanager/assets/js/RecordsOverview.js',
				'position'	=> 103
			),
			array(
				'file'		=> 'ext/sysmanager/assets/js/ExtConf.js',
				'position'	=> 103
			),
			array(
				'file'		=> 'ext/sysmanager/assets/js/Rights.js',
				'position'	=> 101
			),
			array(
				'file'		=> 'ext/sysmanager/assets/js/RightsEditor.js',
				'position'	=> 102
			),
			array(
				'file'		=> 'ext/sysmanager/assets/js/Role.js',
				'position'	=> 102
			)
		),
		'css' => array(
			array(
				'file'		=> 'ext/sysmanager/assets/css/ext.css',
				'media'		=> 'all',
				'position'	=> 100
			),
			array(
				'file'		=> 'ext/sysmanager/assets/css/extensions.css',
				'media'		=> 'all',
				'position'	=> 101
			),
			array(
				'file'		=> 'ext/sysmanager/assets/css/records.css',
				'media'		=> 'all',
				'position'	=> 102
			),
			array(
				'file'		=> 'ext/sysmanager/assets/css/recordsoverview.css',
				'media'		=> 'all',
				'position'	=> 102
			),
			array(
				'file'		=> 'ext/sysmanager/assets/css/rights.css',
				'media'		=> 'all',
				'position'	=> 102
			),
			array(
				'file'		=> 'ext/sysmanager/assets/css/roles.css',
				'media'		=> 'all',
				'position'	=> 102
			)
		)
	)

);


?>