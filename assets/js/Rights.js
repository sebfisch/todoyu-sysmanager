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

Todoyu.Ext.sysmanager.Rights = {



	/**
	 * Enter description here...
	 *
	 */
	init: function() {
		this.installObservers();
	},



	/**
	 * Enter description here...
	 *
	 */
	installObservers: function() {
		Event.observe('rightseditor-groups', 'change', this.updateEditor.bindAsEventListener(this))
	},



	/**
	 * Enter description here...
	 *
	 */
	updateEditor: function() {
		$('rightseditor-form').request({
			'parameters': {
				'cmd': 'updateMatrix'
			},
			'onComplete': function(response) {
				$('grouprights').update(response.responseText);
			}
		});
	},
	
	onEditorUpdated: function(response) {
		
	},



	/**
	 * Enter description here...
	 *
	 * @param unknown_type right
	 */
	toggleRight: function(right) {
		var checkboxes = $('right-' + right).select('input');

		this.toggleCheckboxes(checkboxes);
	},



	/**
	 * Enter description here...
	 *
	 * @param unknown_type idGroup
	 */
	toggleGroup: function(idGroup) {
		var checkboxes= $('rightseditor-rightsform').select('input[id$='+idGroup+']');

		this.toggleCheckboxes(checkboxes);
	},



	/**
	 * Enter description here...
	 *
	 * @param unknown_type checkboxes
	 */
	toggleCheckboxes: function(checkboxes) {
		this.allOn	= true;

		checkboxes.each(function(checkbox) {
			if( checkbox.checked != true ) {
				this.allOn = false;
				return;
			}
		}.bind(this));

		checkboxes.each(function(checkbox) {
			checkbox.checked = !this.allOn;
		}.bind(this));

		this.allOn = true;
	},



	/**
	 * Enter description here...
	 *
	 */
	save: function() {
		$('rightseditor-rightsform').request({
			'parameters': {
				'cmd': 'save'
			},
			onComplete: this.onSaved.bind(this)
		});
	},



	/**
	 * Enter description here...
	 *
	 */
	onSaved: function(response) {

	}
};