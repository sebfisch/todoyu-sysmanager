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

Todoyu.Ext.sysmanager.Repository.Search = {

	ext: Todoyu.Ext.sysmanager,

	repo: Todoyu.Ext.sysmanager.Repository,

	init: function() {
		this.observeForm();
		this.repo.installWarningsObservers();
	},



	/**
	 * Install observer on search form
	 *
	 * @method	observeSearchForm
	 */
	observeForm: function() {
		new Todoyu.DelayedTextObserver('search-field-query', this.onQueryChanged.bind(this));
	},



	/**
	 * Handler when search query has changed- evoke update of results list
	 *
	 * @method	onQueryChanged
	 * @param	{String}	value
	 * @param	{String}	field
	 */
	onQueryChanged: function(field, value) {
		this.updateResults();
	},



	/**
	 * Get query string from search form
	 *
	 * @return	{String}
	 */
	getQuery: function() {
		return $F('search-field-query').strip();
	},



	/**
	 * Set query value
	 *
	 * @param	{String}	query
	 */
	setQuery: function(query) {
		$('search-field-query').value = query.strip();
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

		var url		= this.repo.getUrl();
		var options	= {
			parameters: {
				action: 'search',
				query:	this.getQuery(),
				order:	order,
				offset: offset
			},
			onComplete: this.onResultsUpdated.bind(this, this.getQuery(), order, offset)
		};
		var target	= 'repository-search-results';

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
		this.repo.installWarningsObservers();
	},



	/**
	 * Search for a query
	 *
	 * @param	{String}	query
	 */
	searchFor: function(query) {
		this.setQuery(query);
		this.updateResults();
	},



	/**
	 * Show dialog to install extension
	 *
	 * @param	{String}	extkey
	 */
	showExtensionInstallDialog: function(extkey) {
		this.repo.showExtensionDialog(extkey, 'installDialog', 'Install New Extension', this.onDialogLoaded.bind(this));
	},



	/**
	 * Enable license acceptance toggle
	 *
	 * @param	{Ajax.Response}	response
	 * @param	{Todoyu.Popup}	popup
	 */
	onDialogLoaded: function(response, popup) {
		var accept	= popup.getContent().down('.acceptlicense input');

		if( accept ) {
			accept.on('click', function(){
				popup.getContent().down('button.install').disabled = !accept.checked;
			});
		}
	},




	/**
	 * Install extension from TER
	 *
	 * @method	installExtension
	 * @param	{String}	extkey
	 */
	installExtension: function(extkey) {
		if( confirm('Install this extension?') ) {
			var url		= this.repo.getUrl();
			var options = {
				parameters: {
					action: 'installTerExtension',
					extkey:	extkey
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
			this.ext.closeDialog();

			Effect.SlideUp('repository-search-ext-' + extKey);
		}
	}

};