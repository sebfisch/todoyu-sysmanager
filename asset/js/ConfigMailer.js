/****************************************************************************
 * todoyu is published under the BSD License:
 * http://www.opensource.org/licenses/bsd-license.php
 *
 * Copyright (c) 2012, snowflake productions GmbH, Switzerland
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
 * @module	Sysmanager
 */

/**
 * Mailer settings
 */
Todoyu.Ext.sysmanager.Config.Mailer = {

	/**
	 * Handler when changing mailer type: update mailer settings fieldset
	 *
	 * @method	onChangeMailer
	 * @param	{Element}		field
	 */
	onChangeMailer: function(field) {
		var mailer	= $F(field);

		var container	= $('systemconfig-0-fieldset-mailer');
		var url		= Todoyu.getUrl('sysmanager', 'config');
		var options	= {
			parameters: {
				action:	'changeMailer',
				mailer:	mailer
			}
		};

		Todoyu.Ui.replace(container, url, options);
	},



	/**
	 * Handler when toggling authentication requirement of SMTP on/off - hide/show credentials fields
	 *
	 * @method	onChangeSmtpRequiresAuth
	 * @param	{Element}					field
	 */
	onChangeSmtpRequiresAuth: function(field) {
		var requiresAuth	= !!$F(field);

		var method	= requiresAuth ? 'show' : 'hide';

		$('formElement-systemconfig-0-field-smtp-username')[method]();
		$('formElement-systemconfig-0-field-smtp-password')[method]();
	}

};