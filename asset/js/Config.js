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
 * @module	Sysmanager
 */

/**
 * Sysmanager config area
 *
 * @class		Config
 * @namespace	Todoyu.Ext.sysmanager
 */
Todoyu.Ext.sysmanager.Config = {

	/**
	 * Click handler for sysmanager tabs
	 *
	 * @method	onTabClick
	 * @param	{Event}		event
	 * @param	{String}	tab
	 */
	onTabClick: function(event, tab) {
		var url		= Todoyu.getUrl('sysmanager', 'config');
		var options	= {
			'parameters': {
				'action':	'update',
				'tab':		tab
			},
			'onComplete': this.onTabLoaded.bind(this, tab)
		};

		Todoyu.Ui.updateContentBody(url, options);
	},



	/**
	 * Handler when tab was loaded
	 *
	 * @method	onTabLoaded
	 * @param	{String}		tab
	 * @param	{Ajax.Response}	response
	 */
	onTabLoaded: function(tab, response) {

	},



	/**
	 * Save system configuration form
	 *
	 * @method	saveSystemConfig
	 * @param	{Form}	form
	 */
	saveSystemConfig: function(form) {
		$(form).request({
			parameters: {
				action: 'saveSystemConfig'
			},
			onComplete: this.onSystemConfigSaved.bind(this)
		});
	},



	/**
	 * Handler when system config was saved
	 *
	 * @param	{Ajax.Response}	response
	 */
	onSystemConfigSaved: function(response) {
		if( response.hasTodoyuError() ) {
			Todoyu.notifyError('[LLL:sysmanager.ext.config.tab.systemconfig.failed]');
		} else {
			Todoyu.notifySuccess('[LLL:sysmanager.ext.config.tab.systemconfig.saved]');
		}

		Todoyu.Ui.setContentBody(response.responseText);
	},


	savePasswordStrength: function(form) {
		$(form).request({
			parameters: {
				action: 'savePasswordStrength'
			},
			onComplete: this.onPasswordStrengthSaved.bind(this)
		});
	},

	onPasswordStrengthSaved: function(response) {
		Todoyu.notifySuccess('[LLL:sysmanager.ext.config.tab.passwordstrength.saved]');
	}

};