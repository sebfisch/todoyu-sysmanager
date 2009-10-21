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


	showRecordTypes: function(extKey) {
		var options	= {
			'parameters': {
				'cmd': 'listTypes',
				'extKey': extKey
			}
		};
		
		Todoyu.Ui.replace('list', this.url, options);
	},


	/**
	 * Enter description here...
	 *
	 * @param unknown_type extKey
	 * @param unknown_type type
	 */
	showTypeList: function(extKey, type) {
		//this.type = type;
		//this.extKey = extKey;

		//this.ext.Extensions.showTab(extKey, 'records', {'type': type});
		//return;

		var options = {
			'parameters': {
				'cmd': 'listExtTypes',
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
		var options = {
			'parameters': {
				'cmd':		'delete',
				'extKey':	extKey,
				'type':		type,
				'record':	idRecord				
			}
		}

		Todoyu.Ui.replace('list', this.url, options);
	},






	/**
	 * Enter description here...
	 *
	 * @param unknown_type form
	 */
	saveRecord: function(form)	{
		$(form).request ({
				'parameters': {
					'cmd': 'saveRecord',
					'form': form.name,
					'extKey': this.extKey,
					'type': this.type
				},
				'onComplete': function(response)	{
					var JSON = response.responseJSON;

					if(JSON.saved == true)	{
						Todoyu.Ext.sysmanager.Extensions.Records.closeForm();
					} else {
						$(form.id).update(JSON.formHTML);
					}
				}
			});

		return false;
	},



	/**
	 * Enter description here...
	 *
	 */
	closeForm: function()	{
		this.showList(this.extKey, this.type);
	},




	/**
	 * Enter description here...
	 *
	 */
	backtoRecordTypeList: function()	{
		this.ext.Extensions.showTab(this.extKey, 'records', {'type': this.type});
	}

};