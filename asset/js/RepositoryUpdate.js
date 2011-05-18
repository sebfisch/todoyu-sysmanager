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

	/**
	 * @var	{Object}	Extension
	 */
	ext: Todoyu.Ext.sysmanager,

	/**
	 * @var	{Object}	Repository functions
	 */
	repo: Todoyu.Ext.sysmanager.Repository,


	/**
	 * Initialize
	 */
	init: function() {
		this.repo.installWarningsObservers();
	},



	/**
	 * Reload list with updates
	 *
	 * @param	{Function}	onComplete
	 */
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



	/**
	 * Show dialog for core update
	 */
	showCoreUpdateDialog: function() {
		var url		= this.repo.getUrl();
		var options	= {
			parameters: {
				action:		'coreUpdateDialog'
			}
		};

		this.repo.dialog = Todoyu.Popups.open('coreUpdate', 'Core Update', 600, url, options);
	},



	/**
	 * Show dialog for extension update
	 *
	 * @param	{String}	extkey
	 */
	showExtensionUpdateDialog: function(extkey) {
		this.repo.showExtensionDialog(extkey, 'updateDialog', 'Install Extension Update');
	},



	/**
	 * Install extension update from TER
	 *
	 * @method	installExtensionUpdate
	 * @param	{String}	extkey
	 */
	installExtensionUpdate: function(extkey) {
		if( confirm('Install this update?') ) {
			var url		= this.repo.getUrl();
			var options	= {
				parameters: {
					action:		'installExtensionUpdate',
					extkey:		extkey
				},
				onComplete: this.onExtensionUpdateInstalled.bind(this, extkey)
			};

			Todoyu.send(url, options);
		}
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
			this.repo.dialog.close();
			this.refreshUpdateList();
		}
	},



	/**
	 * Install update of todoyu core from given URL
	 *
	 * @method	installCoreUpdate
	 */
	installCoreUpdate: function() {
		if( confirm('Install core update?') ) {
			var url		= this.repo.getUrl();
			var options	= {
				parameters: {
					action:		'installCoreUpdate'
				},
				onComplete: this.onCoreUpdateInstalled.bind(this)
			};

			Todoyu.send(url, options);
		}
	},



	/**
	 * Callback after todoyu core update has been installed
	 *
	 * @method	onCoreUpdateInstalled
	 * @param	{Ajax.Response}		response
	 */
	onCoreUpdateInstalled: function(response) {
		if( response.hasTodoyuError() ) {
			var error	= response.getTodoyuErrorMessage();

			Todoyu.notifyError(error);
		} else {
			Todoyu.notifySuccess('Core update was installed');
			this.repo.dialog.close();

			new Todoyu.LoaderBox('update', {
				block: 	true,
				text: 	'Core was updated. Reload todoyu and rebuild cached. Please be patient',
				show:	true
			});

			setTimeout(location.reload, 1000);
		}
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