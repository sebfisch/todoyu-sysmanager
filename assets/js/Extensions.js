/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2010, snowflake productions GmbH, Switzerland
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

	ext: Todoyu.Ext.sysmanager,

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
	 * @param	{String}	extKey
	 * @param	{String}	tab
	 * @param	{Object}	params
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
	 * @param	{String}	tabKey
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
	 * @param	{String}	extKey
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
	 * @param	{String}	extKey
	 * @param	{Object}	response
	 */
	onInstalled: function(extKey, response) {
		var extName	= response.responseText;

		Todoyu.notifySuccess('[LLL:sysmanager.extension.installExtension.ok]: ' + extName);

		this.showList();
	},

	

	/**
	 * Download given extension
	 *
	 * @param	{String}	extKey
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
	 * @param	{String}		extKey
	 */
	showRights: function(extKey) {
		location.href = 'index.php?ext=admin&mod=rights&extension=' + extKey;
	},



	/**
	 * Remove an extension from the server (delete all files)
	 *
	 * @param	{String}		extKey
	 */
	remove: function(extKey) {
		if( confirm('[LLL:sysmanager.extension.remove.confirm]') ) {
			var url		= Todoyu.getUrl('sysmanager', 'extensions');
			var options	= {
				'parameters': {
					'action':	'remove',
					'extension':extKey
				},
				'onComplete':	this.onRemoved.bind(this, extKey)
			};

			Todoyu.send(url, options);
		}
	},



	/**
	 * Handler when an extension is removed from the server
	 *
	 * @param	{String}			extKey
	 * @param	{Ajax.Response}		response
	 */
	onRemoved: function(extKey, response) {
		if( response.hasTodoyuError() ) {
			Todoyu.notifyError('Removing extension failed')
		} else {
			Todoyu.notifySuccess('Extension was sucessfully removed from server');
		}

		this.Install.showList();
	}
	
};