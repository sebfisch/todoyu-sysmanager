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
		Todoyu.DelayedTextObserver.observe('extQuery', this.onQueryChanged.bind(this));
	},



	/**
	 * Handler when updater query has changed- evoke update of results list
	 *
	 * @method	onQueryChanged
	 * @param	{String}	value
	 * @param	{String}	field
	 */
	onQueryChanged: function(value, field) {
		this.updateResults(value);
	},



	/**
	 * Update search results from given query
	 *
	 * @method	updateResults
	 * @param	{String}	query
	 * @param	{String}	order
	 * @param	{Number}	offset
	 */
	updateResults: function(query, order, offset) {
		var url		= this.getUrl();
		var options	= {
			parameters: {
				action: 'search',
				'query': query,
				'order': order,
				'offset': offset || 0
			},
			onComplete: this.onResultsUpdated.bind(this, query, order, offset)
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
	 * @todo	implement!
	 *
	 * @method	installExtension
	 * @param	{String}	extkey
	 * @param	{String}	zipFile
	 */
	installExtension: function(extkey, zipFile) {
		alert("Needs to be implemented");
	},



	/**
	 * Open TER in new browser window
	 *
	 * @method	moreExtensionInfo
	 * @param	{String}	terLink
	 */
	moreExtensionInfo: function(terLink) {
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
	 * Install extension update
	 *
	 * @method	installExtensionUpdate
	 * @param	{String}	extkey
	 * @param	{String}	urlHash
	 */
	installExtensionUpdate: function(extkey, urlHash) {
		var url		= this.getUrl();
		var options	= {
			parameters: {
				action:	'installExtensionUpdate',
				'extkey':	extkey,
				'hash':		urlHash
			},
			onComplete: this.onExtensionUpdateInstalled.bind(this, extkey)
		};

		Todoyu.Ui.updateContentBody(url, options);
	},



	/**
	 * Callback after extension update has been installed
	 *
	 * @method	onExtensionUpdateInstalled
	 * @param	{String}	extkey
	 */
	onExtensionUpdateInstalled: function(extkey) {
		alert("Extension was installed: " + extkey);
	},



	/**
	 * Install update of todoyu core from given URL
	 *
	 * @method	installCoreUpdate
	 * @param	{String}	urlHash
	 */
	installCoreUpdate: function(urlHash) {
		var url		= this.getUrl();
		var options	= {
			parameters: {
				action:	'installCoreUpdate',
				'hash':		urlHash
			},
			onComplete: this.onCoreUpdateInstalled.bind(this)
		};

		Todoyu.Ui.updateContentBody(url, options);
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