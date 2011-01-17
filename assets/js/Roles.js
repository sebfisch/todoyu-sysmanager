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
 * Role manager
 */
Todoyu.Ext.sysmanager.Roles = {

	/**
	 * Extension
	 */
	ext: Todoyu.Ext.sysmanager,


	/**
	 * Edit given role
	 *
	 * @param	{Number}		idRole
	 */
	edit: function(idRole) {
		var url		= Todoyu.getUrl('sysmanager', 'role');
		var options	= {
			'parameters': {
				'action':	'edit',
				'role':		idRole
			},
			'onComplete':	this.onEdit.bind(this, idRole)
		};

		Todoyu.Ui.updateContentBody(url, options);
	},



	/**
	 * Handler evoked onEdit
	 *
	 * @param	{Number}		idRole
	 * @param	{Ajax.Response}		response
	 */
	onEdit: function(idRole, response) {

	},



	/**
	 * Delete given role from DB
	 *
	 * @param	{Number}		idRole
	 */
	remove: function(idRole) {
		if( confirm('[LLL:sysmanager.roles.delete.confirm]') ) {
			var url		= Todoyu.getUrl('sysmanager', 'role');
			var options	= {
				'parameters': {
					'action':	'delete',
					'role':		idRole
				},
				'onComplete': this.onRemoved.bind(this, idRole)
			};

			Todoyu.send(url, options);
		}
	},



	/**
	 * Handler to be evoked after removal of role
	 *
	 * @param	{Number}		idRole
	 * @param	{Ajax.Response}		response
	 */
	onRemoved: function(idRole, response) {
		this.updateList();
	},



	/**
	 * Update list of roles
	 */
	updateList: function() {
		var url		= Todoyu.getUrl('sysmanager', 'role');
		var options	= {
			'parameters': {
				'action':	'listing'
			}
		};

		Todoyu.Ui.updateContentBody(url, options);
	},



	/**
	 * Save role from given form
	 *
	 * @param	{Array}	form
	 */
	save: function(form) {
		$(form).request({
			'parameters': {
				'action':	'save'
			},
			'onComplete': this.onSaved.bind(this)
		});
	},



	/**
	 * Handler being evoked after saving of role to database
	 *
	 * @param	{Ajax.Response}		response
	 */
	onSaved: function(response) {
		if( response.hasTodoyuError() ) {
			Todoyu.notifyError('[LLL:sysmanager.roles.saved.error]');
			Todoyu.Ui.setContentBody(response.responseText);
		} else {
			Todoyu.notifySuccess('[LLL:sysmanager.roles.saved.ok]');
			this.showList();
		}
	},



	/**
	 * Show roles list
	 */
	showList: function() {
		var url		= Todoyu.getUrl('sysmanager', 'role');
		var options	= {
			'parameters': {
				'action':	'listing'
			},
			'onComplete':	this.onListShowed.bind(this)
		};

		Todoyu.Ui.updateContentBody(url, options);
	},



	/**
	 * Callback after roles listing is shown
	 *
	 * @param	{Ajax.Response}		response
	 */
	onListShowed: function(response) {

	}

};