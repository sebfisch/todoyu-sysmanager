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

Todoyu.Ext.sysmanager.Repository.Update = {

	ext: Todoyu.Ext.sysmanager,

	repo: Todoyu.Ext.sysmanager.Repository,

	popup: null,

	init: function() {

	},

	closePopup: function() {
		if( this.popup ) {
			this.popup.close();
		}
	},


	refreshUpdateList: function(onComplete) {
		var url		= this.repo.getUrl();
		var options	= {
			parameters: {
				action:		'refreshUpdateList'
			},
			onComplete:	onComplete || Prototype.emptyFunction
		};

		Todoyu.Ui.updateContentBody(url, options);
	},


	showCoreUpdateDialog: function() {

	},

	showExtensionUpdateDialog: function(extkey) {
		var url		= this.repo.getUrl();
		var options	= {
			parameters: {
				action:		'updateDialog',
				extension:	extkey
			}
		};

		this.popup = Todoyu.Popups.open('update', 'Install Extension Update', 500, url, options);
	},



	/**
	 * Install extension update from TER
	 *
	 * @method	installExtensionUpdate
	 * @param	{String}	extkey
	 */
	installExtensionUpdate: function(extkey) {
		var url		= this.repo.getUrl();
		var options	= {
			parameters: {
				action:		'installExtensionUpdate',
				extkey:		extkey
			},
			onComplete: this.onExtensionUpdateInstalled.bind(this, extkey)
		};

		Todoyu.send(url, options);
	},



	/**
	 * Callback after extension update has been installed
	 *
	 * @method	onExtensionUpdateInstalled
	 * @param	{String}	extkey
	 */
	onExtensionUpdateInstalled: function(extkey, response) {
		if( response.hasTodoyuError() ) {
			var error	= response.getTodoyuErrorMessage();

			Todoyu.notifyError(error);
		} else {
			Todoyu.notifySuccess('Extension update was installed');
			this.popup.close();
			this.refreshUpdateList();
		}
	},



	/**
	 * Install update of todoyu core from given URL
	 *
	 * @method	installCoreUpdate
	 * @param	{String}	archiveHash
	 */
	installCoreUpdate: function(archiveHash) {
		var url		= this.getUrl();
		var options	= {
			parameters: {
				action:		'installCoreUpdate',
				archive:	archiveHash
			},
			onComplete: this.onCoreUpdateInstalled.bind(this)
		};

		Todoyu.send(url, options);
	},



	/**
	 * Callback after todoyu core update has been installed
	 *
	 * @method	onCoreUpdateInstalled
	 * @param	{Ajax.Response}		response
	 */
	onCoreUpdateInstalled: function(response) {

	},



	/**
	 * Show TER extension update details in new window
	 *
	 * @method	showExtensionUpdateDetails
	 * @param	{String}	terLink
	 */
	showExtensionUpdateDetails: function(terLink) {
		this.repo.showExtensionInTER(terLink);
	}

};