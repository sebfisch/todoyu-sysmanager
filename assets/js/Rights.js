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

Todoyu.Ext.sysmanager.Rights = {

	ext: Todoyu.Ext.sysmanager,

	/**
	 * @todo	comment
	 * 
	 * @param	unknown		event
	 * @param	unknown		tab
	 */
	onTabClick: function(event, tab) {
		var url		= Todoyu.getUrl('sysmanager', 'rights');
		var options	= {
			'parameters': {
				'action':	'tab',
				'tab':		tab
			},
			'onComplete': this.onTabLoaded.bind(this, tab)
		}

		Todoyu.Ui.updateContentBody(url, options);		
	},



	/**
	 * @todo	comment
	 * 
	 * @param	unknown		tab
	 * @param	unknown		response
	 */
	onTabLoaded: function(tab, response) {

	}

};