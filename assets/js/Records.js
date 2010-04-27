/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2010, snowflake productions gmbh
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

Todoyu.Ext.sysmanager.Extensions.Records = {

	/**
	 * Ext shortcut
	 *
	 * @var	{Object}	ext
	 */
	ext:	Todoyu.Ext.sysmanager,

	url:	Todoyu.getUrl('sysmanager', 'records'),

	extKey:	'',
	type:	'',



	/**
	 * Show type list
	 *	
	 * @param	{String}	extKey
	 */
	showTypeList: function(extKey) {
		var options = {
			'parameters': {
				'action':	'listRecordTypes',
				'extKey':	extKey
			}
		};

		Todoyu.Ui.replace('list', this.url, options);
	},



	/**
	 * Show type records
	 *
	 * @param	{String}	extKey
	 * @param	{String}	type
	 */
	showTypeRecords: function(extKey, type) {
		var options	= {
			'parameters': {
				'action':	'listTypeRecords',
				'extKey':	extKey,
				'type':		type
			}
		};
		
		Todoyu.Ui.updateContentBody(this.url, options);
	},



	/**
	 * Add record (create and edit)
	 *
	 * @param	{String}	extKey
	 * @param	{String}	type
	 */
	add: function(extKey, type) {
		this.edit(extKey, type, 0);
	},



	/**
	 * Enter description here...
	 *
	 * @param unknown_type recordID
	 */
	edit: function(extKey, type, idRecord)	{

		var options = {
			'parameters': {
				'action':	'edit',
				'extKey':	extKey,
				'type':		type,
				'record':	idRecord
			},
			'onComplete': this.onEdit.bind(this, extKey, type, idRecord)
		};

		if( Todoyu.exists('record-list') ) {
			var target = 'record-list';
		} else {
			var target = $('content').select('form').first().getAttribute('id');
		}

		Todoyu.Ui.replace(target, this.url, options);
	},



	/**
	 * On edit handler
	 *	
	 * @param	{String}	extKey
	 * @param	{String}	type
	 * @param	{Number}	idRecord
	 * @param	{Array}	response
	 */
	onEdit: function(extKey, type, idRecord, response) {
		
	},



	/**
	 * Remove record
	 *
	 * @param {String}	extKey
	 * @param {String}	type
	 * @param {Number}	idRecord
	 */
	remove: function(extKey, type, idRecord)	{
		if( confirm('[LLL:sysmanager.records.delete.confirm]') ) {
			var options = {
				'parameters': {
					'action':	'delete',
					'extKey':	extKey,
					'type':		type,
					'record':	idRecord
				},
				'onComplete': this.onRemoved.bind(this, extKey, type, idRecord)
			};

			Todoyu.send(this.url, options);
		}
	},



	/**
	 * On removed (record) handler
	 *
	 * @param	{String}	extKey
	 * @param	{String}	type
	 * @param	{Number}	idRecord
	 * @param	{Array}	response
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
	save: function(form, extKey, type)	{
		
		$(form).request ({
			'parameters': {
				'action':	'save',
				'extKey':	extKey,
				'type':		type
			},
			'onComplete': this.onSaved.bind(this, form, extKey, type)
		});

		return false;
	},



	/**
	 * On saved handler
	 *
	 * @param	{String}	form
	 * @param	{String}	extKey
	 * @param	{String}	type
	 * @param	{Array}	Response
	 */
	onSaved: function(form, extKey, type, response) {
		if( response.hasTodoyuError() ) {
			Todoyu.notifyError('[LLL:sysmanager.records.saved.fail]');
			$(form.id).update(response.responseText);
		} else {
			Todoyu.notifySuccess('[LLL:sysmanager.records.saved]');
			this.showTypeRecords(extKey, type);
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