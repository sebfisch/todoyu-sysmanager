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

Todoyu.Ext.sysmanager.RecordsOverview = {

	/**
	 * Ext shortcut
	 *
	 * @var	{Object}	ext
	 */
	ext:	Todoyu.Ext.sysmanager,



	/**
	 * On tab select handler
	 *
	 * @param	event
	 * @param	{String}	tabKey
	 */
	onTabSelect: function(event, tabKey) {
		// Do nothing
	},



	/**
	 * @todo	comment
	 */
	showRecords: function(ext, type) {
		location.href = '?ext=admin&mod=extensions&extkey=' + ext + '&tab=records&type=' + type;
	}

};