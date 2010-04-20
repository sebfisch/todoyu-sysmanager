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

Todoyu.Ext.sysmanager.QuickCreateRole = {

	/**
	 * Evoked upon opening of role quick create wizard popup
	 */
	onPopupOpened: function() {

	},



	/**
	 * Save role
	 *
	 * @param	unknown		form
	 */
	save: function(form) {
		$(form).request ({
				'parameters': {
					'action':	'save'
				},
				'onComplete': this.onSaved.bind(this)
			});

		return false;
	},



	/**
	 * On saved handle
	 *
	 *	@param	{Object}		response
	 */
	onSaved: function(response) {
		if( response.hasTodoyuError() ) {
				// Saving role failed
			Todoyu.notifyError('[LLL:sysmanager.role.saved.error]');
			Todoyu.Headlet.QuickCreate.updatePopupContent(response.responseText);
		} else {
				// Saving succeeded
			var idRole	= response.getTodoyuHeader('idRole');
			Todoyu.Hook.exec('onRoleSaved', idRole);

			Todoyu.Popup.close('quickcreate');
			Todoyu.Ext.sysmanager.RightsEditor.updateEditor();
			Todoyu.notifySuccess('[LLL:sysmanager.role.saved]');
		}
	}

};