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


	showList: function() {
		this.showTab();
	},
	

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
				'action':		'tabview',
				'tab':			tab,
				'extension':	extKey
			},
			'onComplete': this.onTabShowed.bind(this, extKey, tab, params)
		};

		if( typeof(params) === 'object' ) {
			options.parameters = $H(options.parameters).merge(params).toObject();
		}

		Todoyu.Ui.updateContent(url, options);
	},

	
	onTabShowed: function(extKey, tab, params) {
		
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
	},
	
	install: function(extKey) {
		if( confirm('[LLL:sysmanager.extensions.install.confirm]') ) {
			var url		= Todoyu.getUrl('sysmanager', 'extensions');
			var options	= {
				'parameters': {
					'action':		'install',
					'extension':	extKey
				},
				'onComplete': this.onInstalled.bind(this, extKey)				
			}
			
			Todoyu.send(url, options);		
		}	
	},
	
	onInstalled: function(extKey, response) {
		this.showList();
		Todoyu.notifySuccess('Extension sucessfully installed: ' + response.responseText);
		
	},
	
	uninstall: function(extKey) {
		if( confirm('[LLL:sysmanager.extensions.uninstall.confirm]') ) {
			var url		= Todoyu.getUrl('sysmanager', 'extensions');
			var options	= {
				'parameters': {
					'action':		'uninstall',
					'extension':	extKey
				},
				'onComplete': this.onUninstalled.bind(this, extKey)				
			}
			
			Todoyu.send(url, options);		
		}		
	},
	
	onUninstalled: function(extKey, response) {
		if( response.hasTodoyuError() ) {
			Todoyu.notifyError(response.responseText, 0);
		} else {
			Todoyu.notifySuccess(response.responseText);
			this.showList();
		}	
	},
	
	showImportForm: function() {
		Effect.BlindDown('extension-import-form');
	},
	
	download: function(extKey) {
		Todoyu.goTo('sysmanager', 'extensions', {
			'action':		'download',
			'extension':	extKey
		});
	}
};