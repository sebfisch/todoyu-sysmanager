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
	 * Show extension list in systmanager
	 */
	showList: function() {
		this.showTab();
		this.activateTab('list');
	},



	/**
	 * Show given extension tab in sysmanager
	 *
	 * @param	String	extKey
	 * @param	String	tab
	 * @param	Object	params
	 */
	showTab: function(extKey, tab, params) {
		var url		= Todoyu.getUrl('sysmanager', 'extensions');
		var options	= {
			'parameters': {
				'action':	'tabview',
				'tab':		tab,
				'extkey':	extKey
			},
			'onComplete': this.onTabShowed.bind(this, extKey, tab, params)
		};

		if( typeof(params) === 'object' ) {
			options.parameters = $H(options.parameters).merge(params).toObject();
		}

		Todoyu.Ui.updateContent(url, options);
	},



	/**
	 * Activate given tab in admin area
	 */
	activateTab: function(tab) {
		Todoyu.Tabs.setActive('exttab-none-' + tab);		
	},



	/**
	 * Evoked upon completion of loading of tab
	 *
	 * @todo	complete or remove
	 */
	onTabShowed: function(extKey, tab, params) {
		
	},



	/**
	 * On tab click handler
	 *
	 * @param	Ojbect	event
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



	/**
	 * Evoke installation of given extension
	 * 
	 * @param	String	extKey 
	 */
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



	/**
	 * Handler to be called after ext. installation.
	 * Shows list of extensions and installation response notification
	 * 
	 * @param	String	extKey
	 * @param	Object	response
	 */
	onInstalled: function(extKey, response) {
			// Reload main navi, init notification to be shown after
		var url				= Todoyu.getUrl('sysmanager', 'extensions');
		var notification	= 'Extension sucessfully installed: ' + response.responseText;

		var options	= {
			'parameters': { 'action': 'reloadmainnavi' },
			'onComplete': this.onNaviReloadedAfterUnAndInstall.bind(this, notification)
		};
		var target	= 'navi-main';
		Todoyu.Ui.update(target, url, options);
	},



	/**
	 * Evoke deinstallation of given extension
	 * 
	 * @param	String	extKey
	 */
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



	/**
	 * Handler to be called after ext. deinstallation.
	 * Show notification of deinstallation success / failure
	 *
	 * @param	String	extKey
	 * @param	Object	response
	 */
	onUninstalled: function(extKey, response) {
		if( response.hasTodoyuError() ) {
			Todoyu.notifyError(response.responseText, 0);
		} else {
				// Reload main navi, init notification to be shown after
			var url		= Todoyu.getUrl('sysmanager', 'extensions');
			var options	= {
				'parameters': { 'action': 'reloadmainnavi' },
				'onComplete': this.onNaviReloadedAfterUnAndInstall.bind(this, response.responseText)
			};
			var target	= 'navi-main';
			Todoyu.Ui.update(target, url, options);
		}
	},


	
	/**
	 * Handler being called after main navi is reload after successful un- / install, shows given notification and extension list
	 * 
	 * @param	String	notification
	 */
	onNaviReloadedAfterUnAndInstall: function(notification) {
		Todoyu.notifySuccess(notification);
		this.showList();
	},



	/**
	 * Display extension import form
	 */
	showImportForm: function() {
		Effect.BlindDown('extension-import-form');
	},



	/**
	 * Download given extension
	 *
	 * @param	String	extKey
	 */
	download: function(extKey) {
		Todoyu.goTo('sysmanager', 'extensions', {
			'action':		'download',
			'extension':	extKey
		});
	}
};