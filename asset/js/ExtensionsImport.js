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
 * Extension import/upload
 *
 * @module	Sysmanager
 */
Todoyu.Ext.sysmanager.Extensions.Import = {

	/**
	 * Reference to extension
	 *
	 * @property	ext
	 * @type		Object
	 */
	ext: Todoyu.Ext.sysmanager,



	/**
	 * Show for for extension upload
	 *
	 * @method	showUploadForm
	 */
	showUploadForm: function() {
		var url		= Todoyu.getUrl('sysmanager', 'extensions');
		var options	= {
			parameters: {
				action: 'showimport'
			},
			onComplete: this.onImportShowed.bind(this)
		};

		Todoyu.Ui.updateContentBody(url, options);
	},



	/**
	 * Handler when extension upload form is showed
	 *
	 * @method	onImportShowed
	 * @param	{Ajax.Response}		response
	 */
	onImportShowed: function(response) {

	},



	/**
	 * Start extension upload
	 * Use an iframe to submit the file upload
	 *
	 * @method	startUpload
	 */
	startUpload: function() {
		if( $F('importExtension-field-file') !== '' ) {
			Todoyu.Form.addIFrame('import');

			$('importExtension-form').writeAttribute('target', 'upload-iframe-import');

			$('importExtension-form').submit();
		} else {
			alert('[LLL:sysmanager.ext.upload.noArchiveSelected]');
		}
	},



	/**
	 * Handler when upload is finished
	 * Function is called from the iFrame which submitted the file
	 *
	 * @method	uploadFinished
	 * @param	{String}	ext
	 * @param	{Boolean}	success
	 * @param	{String}	message
	 */
	uploadFinished: function(ext, success, message) {
		if( success === true ) {
			Todoyu.notifySuccess('[LLL:sysmanager.ext.upload.ok]: ' + ext);

			this.ext.Extensions.Install.showList();
		} else {
			Todoyu.notifyError('[LLL:sysmanager.ext.upload.error]: ' + ext + ' (' + message + ')');
		}
	}

};