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

	init: function() {
		this.observeSearchForm();
	},

	getUrl: function() {
		return Todoyu.getUrl('sysmanager', 'updater');
	},

	observeSearchForm: function() {
		Todoyu.DelayedTextObserver.observe('extQuery', this.onQueryChanged.bind(this));
	},

	onQueryChanged: function(value, field) {
		this.updateResults(value);
	},

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

	onResultsUpdated: function(query, order, offset) {

	},

	installExtension: function(extkey, zipFile) {
		alert("Needs to be implemented");
	},

	moreExtensionInfo: function(terLink) {
		window.open(terLink, '_blank');
	},

	installCoreUpdate: function(pathArchive) {
		var url		= this.getUrl();
		var options	= {
			'parameters': {
				'action': 'installCoreUpdate',
				'file':	pathArchive
			},
			'onComplete': this.onCoreUpdateInstalled.bind(this)
		};

		Todoyu.Ui.updateContentBody(url, options);
	},

	onCoreUpdateInstalled: function(response) {

	}

};