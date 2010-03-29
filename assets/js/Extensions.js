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
		Todoyu.Tabs.setActive('extension', tab);
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
		var extKey, tab;
		
		if( tabKey.indexOf('_') !== -1 ) {
			var parts	= tabKey.split('_');
			extKey	= parts[0];
			tab		= parts[1];
		} else {
			extKey	= '';
			tab		= tabKey;
		}

		this.showTab(extKey, tab);		
	},



	/**
	 * Evoke installation of given extension
	 *
	 * @param	String	extKey
	 */
	install: function(extKey) {
		if( confirm('[LLL:sysmanager.extension.installExtension.confirm]') ) {
			var url		= Todoyu.getUrl('sysmanager', 'extensions');
			var options	= {
				'parameters': {
					'action':		'install',
					'extension':	extKey
				},
				'onComplete': this.onInstalled.bind(this, extKey)
			};

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
		var extName	= response.responseText;

		Todoyu.notifySuccess('[LLL:sysmanager.extension.installExtension.ok]: ' + extName);

		this.showList();
	},



	/**
	 * Evoke deinstallation of given extension
	 *
	 * @param	String	extKey
	 */
	uninstall: function(extKey) {
		if( confirm('[LLL:sysmanager.extension.uninstallExtension.confirm]') ) {
			var url		= Todoyu.getUrl('sysmanager', 'extensions');
			var options	= {
				'parameters': {
					'action':		'uninstall',
					'extension':	extKey
				},
				'onComplete': this.onUninstalled.bind(this, extKey)
			};

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
			Todoyu.notifyError('[LLL:sysmanager.extension.uninstallExtension.error]', 0);
		} else {
			var extName	= response.responseText;

			Todoyu.notifySuccess('[LLL:sysmanager.extension.uninstallExtension.ok]: ' + extName);

			this.showList();
		}
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
	},



	/**
	 * Show rights of given extension
	 * 
	 * @param	String		extKey
	 */
	showRights: function(extKey) {
		location.href = 'index.php?ext=admin&mod=rights&extension=' + extKey;
	}
};