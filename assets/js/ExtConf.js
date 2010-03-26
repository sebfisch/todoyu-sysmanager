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

Todoyu.Ext.sysmanager.ExtConf = {

	/**
	 * On save handler
	 * 
	 *	@param	String	form
	 */
	onSave: function(form) {

		$(form).request({
			'parameters': {
				'action':	'save'
			},
			'onComplete': this.onSaved.bind(this)
		});
		
		return false;
	},



	/**
	 *	On saved handler
	 *
	 *	@param	Array	response
	 */
	onSaved: function(response) {		
		if( response.hasTodoyuError() ) {
			Todoyu.notifyError('[LLL:sysmanager.extconf.savingFailed]');

			$('config-form').replace(response.responseText);
		} else {
			Todoyu.notifySuccess('[LLL:sysmanager.extconf.saved]', 3);
		}
	}

};