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

Todoyu.Ext.sysmanager.Repository = {

	ext: Todoyu.Ext.sysmanager,

	/**
	 * Initialize repository
	 *
	 * @method	init
	 */
	init: function() {
		this.Search.init();
		this.Update.init();
	},



	/**
	 * Get repository URL
	 *
	 * @method	getUrl
	 * @return	{String}
	 */
	getUrl: function() {
		return Todoyu.getUrl('sysmanager', 'repository');
	},



	/**
	 * Open TER in new browser window
	 *
	 * @method	moreExtensionInfo
	 * @param	{String}	terLink
	 */
	showExtensionInTER: function(terLink) {
		window.open(terLink, '_blank');
	}

};