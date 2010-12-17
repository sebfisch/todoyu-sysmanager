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

Todoyu.Ext.sysmanager.Updater = {

	/**
	 * Initialize updater
	 */
	init: function() {
		this.observeSearchForm();
	},



	/**
	 * Get updater URL
	 *
	 * @return	{String}
	 */
	getUrl: function() {
		return Todoyu.getUrl('sysmanager', 'updater');
	},



	/**
	 * Install observer on search form 
	 */
	observeSearchForm: function() {
		Todoyu.DelayedTextObserver.observe('extQuery', this.onQueryChanged.bind(this));
	},



	/**
	 *
	 * @param	{String}	value
	 * @param	{String}	field
	 */
	onQueryChanged: function(value, field) {
		this.updateResults(value);
	},



	/**
	 * @todo	comment
	 * @param	{String}	query
	 * @param	{String}	order
	 * @param	{Number}	offset
	 */
	updateResults: function(query, order, offset) {
		var url		= this.getUrl();
		var options	= {
			'parameters': {
				'action': 'search',
				'query': query,
				'order': order,
				'offset': offset || 0
			},
			'onComplete': this.onResultsUpdated.bind(this, query, order, offset)
		};
		var target	= 'updater-search-results';

		Todoyu.Ui.update(target, url, options);
	},



	/**
	 * @todo	implement,comment
	 *
	 * @param	{String}	query
	 * @param	{String}	order
	 * @param	{Number}	offset
	 */
	onResultsUpdated: function(query, order, offset) {

	},



	/**
	 * @todo	comment
	 *
	 * @param	{String}	extkey
	 * @param	{String}	zipFile
	 */
	installExtension: function(extkey, zipFile) {
		alert("Needs to be implemented");
	},



	/**
	 * @todo	comment
	 *
	 * @param	{String}	terLink
	 */
	moreExtensionInfo: function(terLink) {
		window.open(terLink, '_blank');
	},



	/**
	 * @todo	comment
	 *
	 * @param terLink
	 */
	showExtensionUpdateDetails: function(terLink) {
		window.open(terLink, '_blank');
	},



	/**
	 * @todo	comment
	 * @param	{String}	extkey
	 * @param	{String}	urlHash
	 */
	installExtensionUpdate: function(extkey, urlHash) {
		var url		= this.getUrl();
		var options	= {
			'parameters': {
				'action': 'installExtensionUpdate',
				'extkey': extkey,
				'hash':	urlHash
			},
			'onComplete': this.onExtensionUpdateInstalled.bind(this, extkey)
		};

		Todoyu.Ui.updateContentBody(url, options);
	},



	/**
	 * @todo	Comment
	 *
	 * @param	{String}	extkey
	 */
	onExtensionUpdateInstalled: function(extkey) {
		alert("Extension was installed: " + extkey);
	},



	/**
	 * @todo	comment
	 *
	 * @param	{String}	urlHash
	 */
	installCoreUpdate: function(urlHash) {
		var url		= this.getUrl();
		var options	= {
			'parameters': {
				'action': 'installCoreUpdate',
				'hash':	urlHash
			},
			'onComplete': this.onCoreUpdateInstalled.bind(this)
		};

		Todoyu.Ui.updateContentBody(url, options);
	},



	/**
	 * @todo	implement, comment
	 *
	 * @param	{Object}	response
	 */
	onCoreUpdateInstalled: function(response) {

	}

};