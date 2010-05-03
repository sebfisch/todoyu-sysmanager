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

/**
 * Extension installation
 */
Todoyu.Ext.sysmanager.Extensions.Install = {

	ext: Todoyu.Ext.sysmanager,


	/**
	 * Show list of not installed extensions
	 */
	showList: function() {
		this.ext.Extensions.showTab('', 'install');
	},

	

	/**
	 * Install an extension
	 *
	 * @param	{String}	ext
	 */
	install: function(ext) {
		if( confirm('[LLL:sysmanager.extension.installExtension.confirm]') ) {
			var url		= Todoyu.getUrl('sysmanager', 'extensions');
			var options	= {
				'parameters': {
					'action': 'install',
					'extension': ext
				},
				'onComplete': this.onInstalled.bind(this, ext)
			};

			Todoyu.send(url, options);
		}
	},



	/**
	 * Handler when extension is installed
	 *
	 * @param	{String}			ext
	 * @param	{Ajax.Response}		response
	 */
	onInstalled: function(ext, response) {
		var title	= response.getTodoyuHeader('extTitle');

		Todoyu.notifySuccess('Extension installed: ' + title);

		this.showUpdate(ext);
	},



	/**
	 * Show update dialog for an extension
	 *
	 * @param	{String}	ext
	 */
	showUpdate: function(ext) {
		var url		= Todoyu.getUrl('sysmanager', 'extensions');
			var options	= {
				'parameters': {
					'action': 'showUpdate',
					'extension': ext
				},
				'onComplete': this.onUpdateShowed.bind(this, ext)
			};

			Todoyu.Ui.updateContentBody(url, options);
	},


	/**
	 * Handler when update dialog for an extension is displayed
	 *
	 * @param	{String}			ext
	 * @param	{Ajax.Response}		respone
	 */
	onUpdateShowed: function(ext, respone) {

	},



	/**
	 * Uninstall an extension
	 *
	 * @param	{String}		ext
	 */
	uninstall: function(ext) {
		if( confirm('[LLL:sysmanager.extension.uninstallExtension.confirm]') ) {
			var url		= Todoyu.getUrl('sysmanager', 'extensions');
			var options	= {
				'parameters': {
					'action':		'uninstall',
					'extension':	ext
				},
				'onComplete': this.onUninstalled.bind(this, ext)
			};

			Todoyu.send(url, options);
		}
	},



	/**
	 * Handler when extension is uninstalled
	 *
	 * @param	{String}		extKey
	 * @param	{Ajax.Response}	response
	 */
	onUninstalled: function(extKey, response) {
		if( response.hasTodoyuError() ) {
			var info	= response.getTodoyuHeader('info');

			Todoyu.notifyError('[LLL:sysmanager.extension.uninstallExtension.error]: ' + info, 0);
		} else {
			var extName	= response.getTodoyuHeader('extTitle');// response.responseText;

			Todoyu.notifySuccess('[LLL:sysmanager.extension.uninstallExtension.ok]: ' + extName);

			Todoyu.Ui.setContentBody(response.responseText);
		}
	}
	
};