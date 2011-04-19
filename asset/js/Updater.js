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

Todoyu.Ext.sysmanager.Updater = {

	ext: Todoyu.Ext.sysmanager,

	/**
	 * Initialize updater
	 *
	 * @method	init
	 */
	init: function() {
		this.observeSearchForm();
	},



	/**
	 * Get updater URL
	 *
	 * @method	getUrl
	 * @return	{String}
	 */
	getUrl: function() {
		return Todoyu.getUrl('sysmanager', 'updater');
	},



	/**
	 * Install observer on search form
	 *
	 * @method	observeSearchForm
	 */
	observeSearchForm: function() {
		new Todoyu.DelayedTextObserver('search-field-query', this.onQueryChanged.bind(this));
	},



	/**
	 * Get query string from search form
	 *
	 * @return	{String}
	 */
	getQuery: function() {
		return $F('search-field-query').trim();
	},



	/**
	 * Handler when updater query has changed- evoke update of results list
	 *
	 * @method	onQueryChanged
	 * @param	{String}	value
	 * @param	{String}	field
	 */
	onQueryChanged: function(field,value) {
		this.updateResults();
	},



	/**
	 * Update search results from given query
	 *
	 * @method	updateResults
	 * @param	{String}	order
	 * @param	{Number}	offset
	 */
	updateResults: function(order, offset) {
		order	= order || '';
		offset	= offset || 0;

		var url		= this.getUrl();
		var options	= {
			parameters: {
				action: 'search',
				query:	this.getQuery(),
				order:	order,
				offset: offset
			},
			onComplete: this.onResultsUpdated.bind(this, this.getQuery(), order, offset)
		};
		var target	= 'updater-search-results';

		Todoyu.Ui.update(target, url, options);
	},



	/**
	 * Callback after search results have been updated
	 *
	 * @method	onResultsUpdated
	 * @param	{String}	query
	 * @param	{String}	order
	 * @param	{Number}	offset
	 */
	onResultsUpdated: function(query, order, offset) {

	},



	/**
	 * Install extension from TER
	 *
	 * @method	installExtension
	 * @param	{String}	extkey
	 * @param	{String}	archiveHash
	 */
	installExtension: function(extkey, archiveHash) {
		if( confirm('Install this extension?') ) {
			var url		= Todoyu.getUrl('sysmanager', 'updater');
			var options = {
				parameters: {
					action: 'installTerExtension',
					extkey:	extkey,
					archive:archiveHash
				},
				onComplete: this.onExtensionInstalled.bind(this, extkey)
			};

			Todoyu.send(url, options);
		}
	},



	/**
	 * Handler when extension was installed
	 *
	 * @param	{String}		extKey
	 * @param	{Ajax.Response}	response
	 */
	onExtensionInstalled: function(extKey, response) {
		if( response.hasTodoyuError() ) {
			var error	= response.getTodoyuHeader('message');
			Todoyu.notifyError(error);
		} else {
			Todoyu.notifySuccess('Extension was successfully installed');

			Effect.SlideUp('updater-search-ext-' + extKey);
		}
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
	 * Show TER extension update details in new window
	 *
	 * @method	showExtensionUpdateDetails
	 * @param	{String}	terLink
	 */
	showExtensionUpdateDetails: function(terLink) {
		window.open(terLink, '_blank');
	},



	/**
	 * Install extension update from TER
	 *
	 * @method	installExtensionUpdate
	 * @param	{String}	extkey
	 * @param	{String}	archiveHash
	 */
	installExtensionUpdate: function(extkey, archiveHash) {
		var url		= this.getUrl();
		var options	= {
			parameters: {
				action:		'installExtensionUpdate',
				extkey:		extkey,
				hash:		archiveHash
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
			var error	= response.getTodoyuHeader('message');

			Todoyu.notifyError(error);
		} else {
			Todoyu.notifySuccess('Extension update was installed');
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

	}

};