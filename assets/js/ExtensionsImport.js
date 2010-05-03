/****************************************************************************
 * todoyu is published under the BSD License:
 * http://www.opensource.org/licenses/bsd-license.php
 *
 * Copyright (c) 2010, snowflake productions GmbH, Switzerland
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

Todoyu.Ext.sysmanager.Extensions.Import = {

	ext: Todoyu.Ext.sysmanager,

	showUploadForm: function() {
		var url		= Todoyu.getUrl('sysmanager', 'extensions');
		var options	= {
			'parameters': {
				'action': 'showimport'
			},
			'onComplete': this.onImportShowed.bind(this)
		};

		Todoyu.Ui.updateContentBody(url, options);
	},

	onImportShowed: function(response) {

	},

	startUpload: function() {
		Todoyu.Form.addIFrame('upload');

		$('upload-form').writeAttribute('target', 'upload-iframe-upload')

		$('upload-form').submit();
	},

	uploadFinished: function(ext, success, message) {
		if( success === true ) {
			Todoyu.notifySuccess('Extension "' + ext + '" successfully imported');

			this.ext.Extensions.Install.showUpdate(ext);
		} else {
			Todoyu.notifyError('Import of extension "' + ext + '" failed: ' + message);
		}
	}
	
};