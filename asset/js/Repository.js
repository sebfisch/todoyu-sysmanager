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

/**
 * @module	Sysmanager
 */

Todoyu.Ext.sysmanager.Repository = {

	ext: Todoyu.Ext.sysmanager,

	dialog: null,

	/**
	 * Initialize repository
	 *
	 * @method	init
	 */
	init: function() {
		this.Search.init();
		this.Update.init();
	},



	/**
	 * Get repository URL
	 *
	 * @method	getUrl
	 * @return	{String}
	 */
	getUrl: function() {
		return Todoyu.getUrl('sysmanager', 'repository');
	},



	/**
	 * Open TER in new browser window
	 *
	 * @method	moreExtensionInfo
	 * @param	{String}	terLink
	 */
	showExtensionInTER: function(terLink) {
		window.open(terLink, '_blank');
	},



	/**
	 * Show dialog for an extension
	 *
	 * @param	{String}	extkey
	 * @param	{String}	action
	 * @param	{String}	title
	 */
	showExtensionDialog: function(extkey, action, title, callback) {
		callback	= callback || Prototype.emptyFunction;
		var url		= this.getUrl();
		var options	= {
			parameters: {
				action:		action,
				extension:	extkey
			},
			onComplete: callback
		};

		this.dialog = Todoyu.Popups.open(action, title, 600, url, options);
	},



	/**
	 * Close confirm dialog
	 */
	closeDialog: function() {
		if( this.dialog ) {
			this.dialog.close();
		}
	},



	/**
	 * Install observers on dependency and conflict lists
	 */
	installWarningsObservers: function() {
		$('content-body').select('.warning ul').each(function(list){
			var type = list.up('.dependency') ? 'dependency' : 'conflict';

			list.on('click', 'li', this.onWarningClick.bind(this, type));
		}, this);
	},


	/**
	 * Handler when clicked on a warning
	 *
	 * @param	{String}	type
	 * @param	{Event}		event
	 * @param	{Element}	element
	 */
	onWarningClick: function(type, event, element) {
		var extKey	= element.id.split('-').last();

		if( this.getViewName() === 'update' ) {
			if( type === 'conflict' ) {
				this.showInstalledExtensions();
			} else {
				this.showSearch(extKey);
			}
		} else {
			if( type === 'conflict' ) {
				this.showInstalledExtensions();
			} else {
				this.showSearch(extKey);
			}
		}
	},



	/**
	 * Show list with installed extensions
	 */
	showInstalledExtensions: function() {
		this.ext.Extensions.showList();
	},



	/**
	 * Show search with results for query
	 *
	 * @param	{String}	query
	 */
	showSearch: function(query) {
		this.ext.Extensions.showTab(null, 'search', null, function(query){
			this.Search.searchFor(query);
		}.bind(this, query));
	},



	/**
	 * Get name of current view (search or updat)
	 */
	getViewName: function() {
		return Todoyu.Tabs.getActiveKey('extension');
	}

};