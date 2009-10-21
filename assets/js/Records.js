/***************************************************************
*  Copyright notice
*
*  (c) 2009 snowflake productions gmbh
*  All rights reserved
*
*  This script is part of the todoyu project.
*  The todoyu project is free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License, version 2,
*  (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html) as published by
*  the Free Software Foundation;
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

Todoyu.Ext.sysmanager.Extensions.Records = {

	ext: Todoyu.Ext.sysmanager,
	
	url: Todoyu.getUrl('sysmanager', 'records'),
	
	extKey: '',
	type:	'',
	

	showTypeList: function(extKey) {
		var options = {
			'parameters': {
				'cmd': 'listRecordTypes',
				'extKey': extKey
			}
		};

		Todoyu.Ui.replace('list', this.url, options);
	},


	showTypeRecords: function(extKey, type) {
		var options	= {
			'parameters': {
				'cmd': 'listTypeRecords',
				'extKey': extKey,
				'type': type
			}
		};
		
		Todoyu.Ui.replace('list', this.url, options);
	},



	
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
				'cmd':		'edit',
				'extKey':	extKey,
				'type':		type,
				'record':	idRecord				
			},
			'onComplete': this.onEdit.bind(this, extKey, type, idRecord)
		};

		Todoyu.Ui.replace('record-list', this.url, options);
	},
	
	onEdit: function(extKey, type, idRecord, response) {
		
	},



	/**
	 * Enter description here...
	 *
	 * @param unknown_type idRecord
	 */
	remove: function(extKey, type, idRecord)	{
		if( confirm('Delete record?') ) {
			var options = {
				'parameters': {
					'cmd':		'delete',
					'extKey':	extKey,
					'type':		type,
					'record':	idRecord				
				},
				'onComplete': this.onRemoved.bind(this, extKey, type, idRecord)
			}
	
			Todoyu.send(this.url, options);
		}
	},
	
	onRemoved: function(extKey, type, idRecord, response) {
		this.showTypeRecords(extKey, type);
	},






	/**
	 * Enter description here...
	 *
	 * @param unknown_type form
	 */
	save: function(form, extKey, type)	{
		
		$(form).request ({
			'parameters': {
				'cmd': 'save',
				'extKey': extKey,
				'type': type
			},
			'onComplete': this.onSaved.bind(this, form, extKey, type)
		});
		

		return false;
	},
	
	onSaved: function(form, extKey, type, response) {
		if( response.hasTodoyuError() ) {
			Todoyu.notifyError('Saving record failed');
			$(form.id).update(response.responseText);
		} else {
			Todoyu.notifySuccess('Record saved');
			this.showTypeRecords(extKey, type);
		}
	},



	/**
	 * Enter description here...
	 *
	 */
	closeForm: function(extKey, type)	{
		this.showTypeRecords(extKey, type);
	}
};