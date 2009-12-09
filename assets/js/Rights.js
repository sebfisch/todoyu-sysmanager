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

/**
 * Rights mask manager
 */
Todoyu.Ext.sysmanager.Rights = {
	/**
	 * Requireds and dependents
	 */
	require: {},
		
	/**
	 * Init
	 */
	init: function() {
		this.observeGroups();
	},
	
	/**
	 * Init matrix with dependencies and install observers
	 * 
	 * @param	JSON	require
	 */
	initMatrix: function(require) {
			// Set required mapping
		this.require = $H(require);
		
			// Disable all dependents whichs required right is not set
		this.initDependents();
		
			// Observe rights checkboxes for change
		this.observeRights();
	},
	
	
	
	/**
	 * Check dependencies for all rights
	 */
	initDependents: function() {
		this.require.each(function(require){
			this.checkDependents(require.key);
		}.bind(this));
	},
	
	

	/**
	 * Install observers on each checkbox
	 */
	observeGroups: function() {
		$('rightseditor-groups').observe('change', this.onGroupChange.bindAsEventListener(this));
	},
	
	
	
	/**
	 * Observe rights checkboxes
	 */
	observeRights: function() {
		$('rightseditor-rightsform').select('input').each(function(item) {
			$(item).observe('change', this.onRightChange.bind(this));
		}.bind(this));
	},
	
	
	
	/**
	 * Handler when group selection has changed
	 * @param	Event		event
	 */
	onGroupChange: function(event) {
		this.updateEditor();
	},
	
	
	
	/**
	 * Handler when a right changes
	 * 
	 * @param	Event		event
	 */
	onRightChange: function(event) {
		var info	= event.element().id.split('-');
		var right 	= info.first();
		var idGroup	= info.last();
		
		this.checkDependents(right);
	},
	
	
	
	/**
	 * Get all rights which are required for the right
	 * 
	 * @param	String		right
	 */
	getRequireds: function(right) {
		return this.require.get(right);
	},
	
	
	
	/**
	 * Get all dependent rights of a right
	 * 
	 * @param	String		right
	 */
	getDependents: function(right) {
		var dependents = [];
		
		this.require.each(function(rightRequire){			
			if( rightRequire.value.include(right) ) {
				dependents.push(rightRequire.key)
			}			
		});
		
		return dependents;
	},
	
	
	
	/**
	 * Check/uncheck right checkbox
	 * 
	 * @param	String		right
	 * @param	Integer		idGroup
	 * @param	Bool		check
	 */
	checkRight: function(right, idGroup, check) {
		$(right + '-' + idGroup).checked = check;
	},
	
	
	
	/**
	 * Check if a right checkbox is checked
	 * 
	 * @param	String		right
	 * @param	Integer		idGroup
	 */
	isRightChecked: function(right, idGroup) {
		return $(right + '-' + idGroup).checked;
	},
	
	
	
	/**
	 * Enable/Disable a right checkbox
	 * 
	 * @param	String		right
	 * @param	Integer		idGroup
	 * @param	Bool		enable
	 */
	enableRight: function(right, idGroup, enable) {
		$(right + '-' + idGroup).disabled = enable === false;
	},
	
	
	
	/**
	 * Check if a right checkbox is enabled
	 * 
	 * @param	String		right
	 * @param	Integer		idGroup
	 */
	isRightEnabled: function(right, idGroup) {
		return $(right + '-' + idGroup).disabled !== true;
	},
	
	
	
	/**
	 * Check if a right is active
	 * Active = enabled and checked
	 * 
	 * @param	String		right
	 * @param	Integer		idGroup
	 */
	isRightActive: function(right, idGroup) {
		return this.isRightEnabled(right, idGroup) && this.isRightChecked(right, idGroup);
	},
	
	
	
	/**
	 * Activate a right. Enabled and checked
	 * 
	 * @param	String		right
	 * @param	Integer		idGroup
	 * @param	Bool		active
	 */
	activateRight: function(right, idGroup, active) {
		this.enableRight(right, idGroup);
		this.checkRight(right, idGroup);
	},
	
	
	
	/**
	 * Check if all required rights for a right are currently active
	 * 
	 * @param	Stirng		right
	 * @param	Integer		idGroup
	 */
	allRequiredsActive: function(right, idGroup) {
		var requireds = this.getRequireds(right);
		
		return requireds.all(function(idGroup, reqRight){
			return this.isRightActive(reqRight, idGroup);
		}.bind(this, idGroup));
	},
	
	
	
	/**
	 * Check dependent rights of a right
	 * Enable or disable them by dependencies
	 * 
	 * @param	String		right
	 */
	checkDependents: function(right) {
		var groups		= this.getGroups();
		var dependents	= this.getDependents(right);
		
		this.getGroups().each(function(right, idGroup){
				// Check if right is active
			var active = this.isRightActive(right, idGroup);
				// Loop over all rights which depend on this right
			dependents.each(function(active, idGroup, depRight){
					// If right is active, activate dependent
				if( active ) {
						// Only activate right if all other required parents are active too
					if( this.allRequiredsActive(depRight, idGroup) ) {
						this.enableRight(depRight, idGroup, true);
					}
				} else {
						// Disable right because required parent is not active
					this.enableRight(depRight, idGroup, false);
				}
			}.bind(this, active, idGroup));
		}.bind(this, right));
	},
	
	
	
	/**
	 * Get selected usergroups
	 */
	getGroups: function() {
		return $F('rightseditor-groups');
	},



	/**
	 * Update editor
	 */
	updateEditor: function() {
		$('rightseditor-form').request({
			'parameters': {
				'action':	'updateMatrix'
			},
			'onComplete': this.onEditorUpdated.bind(this)
		});
	},
	
	
	
	/**
	 *	On editor updated handler
	 *
	 *	@param	Array	response
	 */
	onEditorUpdated: function(response) {
		$('grouprights').update(response.responseText);
	},



	/**
	 * Toggle right
	 *
	 *	@param	String	right
	 */
	toggleRight: function(right) {
		var checkboxes	= $('right-' + right).select('input').findAll(function(input){
			return input.disabled === false;
		});
				
			// Toggle the checkboxes
		this.toggleCheckboxes(checkboxes);
			// Recheck all rights
		this.initDependents();
	},



	/**
	 *	Toggle group
	 *
	 *	@param	String	idGroup
	 */
	toggleGroup: function(idGroup) {
		var checkboxes= $('rightseditor-rightsform').select('input[id$='+idGroup+']');

			// Toggle the checkboxes
		this.toggleCheckboxes(checkboxes);
			// Recheck all rights
		this.initDependents();
	},



	/**
	 *	Toggle checkboxes
	 *
	 *	@param	Array	checkboxes
	 */
	toggleCheckboxes: function(checkboxes) {
		this.allOn	= true;

			// Check if all checkboxes are currently checked
		checkboxes.each(function(checkbox) {
			if( checkbox.checked != true ) {
				this.allOn = false;
				return;
			}
		}.bind(this));

			// Check or uncheck all
		checkboxes.each(function(checkbox) {
			checkbox.checked = !this.allOn;
		}.bind(this));
	},



	/**
	 *	Save rights over ajax, no reload
	 */
	save: function() {
		$('rightseditor-rightsform').request({
			'parameters': {
				'action':	'save'
			},
			onComplete: this.onSaved.bind(this)
		});
	},



	/**
	 *	On saved handler
	 * 
	 *	@param	Array	response
	 */
	onSaved: function(response) {
		Todoyu.notifySuccess('[LLL:sysmanager.rights.saved]');
	}
	
};