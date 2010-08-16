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

Todoyu.Ext.sysmanager.Records = {

	/**
	 * Ext shortcut
	 *
	 * @var	{Object}	ext
	 */
	ext:	Todoyu.Ext.sysmanager,

	url:	Todoyu.getUrl('sysmanager', 'records'),

	extKey:	'',

	type:	'',


	onTabClick: function(event, tab) {
		if( tab === 'all' ) {
			this.update();
		} else {
			var parts	= tab.split('-');

			this.update.apply(this, parts);
		}
	},


	update: function(extKey, type, idRecord, callback) {
		var url		= Todoyu.getUrl('sysmanager', 'records');
		var options	= {
			'parameters': {
				'action': 	'update',
				'extkey':	extKey,
				'type':		type,
				'record':	idRecord
			},
			'onComplete': this.onUpdated.bind(this, extKey, type, idRecord, callback)
		};

		Todoyu.Ui.updateContent(url, options);
	},

	onUpdated: function(extKey, type, idRecord, callback, response) {
		if( typeof callback === 'function' ) {
			callback(extKey, type, idRecord, response);
		}
	},


	/**
	 * Show types of extension
	 *
	 * @param	{String}	extKey
	 */
	showExtensionTypes: function(extKey) {
		this.update(extKey);
	},



	/**
	 * Show all records of a type
	 *
	 * @param	{String}	extKey
	 * @param	{String}	type
	 */
	showTypeRecords: function(extKey, type) {
		this.update(extKey, type);
	},




	/**
	 * Show type list
	 *
	 * @param	{String}	ext
	 */
	showTypeList: function(ext) {
		var options = {
			'parameters': {
				'action':	'listRecordTypes',
				'extkey':	ext
			}
		};

		Todoyu.Ui.replace('list', this.url, options);
	},





	/**
	 * Add record (create and edit)
	 *
	 * @param	{String}	ext
	 * @param	{String}	type
	 */
	add: function(ext, type) {
		this.edit(ext, type, -1);
	},



	/**
	 * Open given record's editing
	 *
	 * @param	{String}	ext
	 * @param	{String}	type
	 * @param	{Number}	idRecord
	 */
	edit: function(ext, type, idRecord)	{
		this.update(ext, type, idRecord, this.onEdit.bind(this));
	},



	/**
	 * On edit handler
	 *
	 * @param	{String}	extKey
	 * @param	{String}	type
	 * @param	{Number}	idRecord
	 * @param	{Array}		response
	 */
	onEdit: function(extKey, type, idRecord, response) {

	},



	/**
	 * Remove record
	 *
	 * @param {String}	ext
	 * @param {String}	type
	 * @param {Number}	idRecord
	 */
	remove: function(ext, type, idRecord)	{
		if( confirm('[LLL:sysmanager.records.delete.confirm]') ) {
			var options = {
				'parameters': {
					'action':	'delete',
					'extkey':	ext,
					'type':		type,
					'record':	idRecord
				},
				'onComplete': this.onRemoved.bind(this, ext, type, idRecord)
			};

			Todoyu.send(this.url, options);
		}
	},



	/**
	 * On removed (record) handler
	 *
	 * @param	{String}		extKey
	 * @param	{String}		type
	 * @param	{Number}		idRecord
	 * @param	{Ajax.Response}	response
	 */
	onRemoved: function(extKey, type, idRecord, response) {
		this.showTypeRecords(extKey, type);
	},



	/**
	 * Save record
	 *
	 * @param	{String}	form
	 * @param	{String}	extKey
	 * @param	{String}	type
	 */
	save: function(form, ext, type)	{
		$(form).request ({
			'parameters': {
				'action':	'save',
				'extkey':	ext,
				'type':		type
			},
			'onComplete': this.onSaved.bind(this, form, ext, type)
		});

		return false;
	},



	/**
	 * On saved handler
	 *
	 * @param	{String}			form
	 * @param	{String}			ext
	 * @param	{String}			type
	 * @param	{Ajax.Response}		response
	 */
	onSaved: function(form, ext, type, response) {
		if( response.hasTodoyuError() ) {
			Todoyu.notifyError('[LLL:sysmanager.records.saved.fail]');
			$(form.id).update(response.responseText);
		} else {
			Todoyu.notifySuccess('[LLL:sysmanager.records.saved]');
			this.showTypeRecords(ext, type);
		}
	},



	/**
	 * Close form
	 *
	 * @param	{String}	extKey
	 * @param	{String}	type
	 */
	closeForm: function(extKey, type)	{
		this.showTypeRecords(extKey, type);
	}

};