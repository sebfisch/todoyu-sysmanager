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

Todoyu.Ext.sysmanager.Extensions = {


	/**
	 * Enter description here...
	 *
	 * @param	String	extKey
	 * @param	String	tab
	 * @param unknown_type params
	 */
	showTab: function(extKey, tab, params) {
		var url		= Todoyu.getUrl('sysmanager', 'extensions');
		var options	= {
			'parameters': {
				'action': 'tabview',
				'tab': tab,
				'extension': extKey
			}
		};

		if( typeof(params) === 'object' ) {
			options.parameters = $H(options.parameters).merge(params).toObject();
		}

		Todoyu.Ui.updateContent(url, options);
	},



	/**
	 * On tab click handler
	 *
	 * @param unknown_type event
	 * @param	String	tabKey
	 */
	onTabClick: function(event, tabKey) {
		var li		= event.findElement('li');
		var extKey	= li.id.split('-')[1];

		if( extKey == 'none' ) {
			extKey = '';
		}

		this.showTab(extKey, tabKey);
	}
};