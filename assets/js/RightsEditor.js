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
Todoyu.Ext.sysmanager.RightsEditor = {
	/**
	 * Requireds and dependents
	 */
	require: {},



	/**
	 * Init
	 */
	init: function() {
		this.observeForm();
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
		this.observeRightsForm();
	},



	/**
	 * Install observers on each checkbox
	 */
	observeForm: function() {
		$('rightseditor-form').observe('change', this.onFormChange.bindAsEventListener(this));
		$('rightseditor-field-roles').observe('change', this.onRoleChange.bindAsEventListener(this));
	},



	/**
	 * Handler when form changes
	 * Called when roles or extension changes
	 * 
	 * @param	Event		event
	 */
	onFormChange: function(event) {
		this.updateMatrix();
	},



	/**
	 * Handler when the role selection changes
	 * Select all roles if none is selected. Prevents empty matrix
	 * Called before the form change. So we can update the selection just before the form is submitted
	 * 
	 * @param	Event		event
	 */
	onRoleChange: function(event) {
		var roles	= this.getRoles();
		
		if( roles.size() === 0 ) {
			$('rightseditor-field-roles').select('option').each(function(option){
				option.selected = true;
			})
		}
	},


	
	/**
	 * Update roles
	 */
	updateRoles: function() {
		$('rightseditor-form').request({
			'parameters': {
				'action':	'listing'
			},
			'onComplete': this.onMatrixUpdated.bind(this)
		});
	},



	/**
	 *	On roles updated handler
	 *
	 *	@param	Array	response
	 */
	onRolesUpdated: function(response) {
		$('rightseditor-form').update(response.responseText);
	},



	/**
	 * Update matrix
	 */
	updateMatrix: function() {
		$('rightseditor-form').request({
			'parameters': {
				'action':	'matrix'
			},
			'onComplete': this.onMatrixUpdated.bind(this)
		});
	},



	/**
	 *	On editor updated handler
	 *
	 *	@param	Array	response
	 */
	onMatrixUpdated: function(response) {
		$('rightsmatrix').update(response.responseText);
	},



	/**
	 *	Save rights over ajax, no reload
	 */
	saveRights: function() {
		$('rightsmatix-form').request({
			'parameters': {
				'action':	'save',
				'extension':this.getExtension()
			},
			'onComplete':	this.onRightsSaved.bind(this)
		});
	},



	/**
	 *	On saved handler
	 * 
	 *	@param	Array	response
	 */
	onRightsSaved: function(response) {
		Todoyu.notifySuccess('[LLL:sysmanager.rights.saved]');
	},

	
	
	

	/**
	 * Handler when group selection has changed
	 * @param	Event		event
	 */
	onRolesChange: function(event) {
		this.updateEditor();
	},



	/**
	 * Check dependencies for all rights
	 */
	initDependents: function() {
		var roles	= this.getRoles();
		
		if ( Todoyu.Helper.isset(roles) ) {
			this.require.each(function(roles, require){
				roles.each(function(require, idRole){
					this.checkDependents(require.key, idRole);
				}.bind(this, require));			
			}.bind(this, roles));
		}
	},



	/**
	 * Observe rights checkboxes
	 */
	observeRightsForm: function() {
		$('rightsmatix-form').observe('change', this.onRightChange.bindAsEventListener(this));
	},



	/**
	 * Handler when a right is changed
	 * 
	 * @param	Event		event
	 */
	onRightChange: function(event) {
		var checkbox	= event.findElement('input');

		var idParts	= checkbox.id.split('-');
		var right 	= idParts.slice(0,-1).join(':'); // Remove role ID and join section and right
		var idRole	= idParts.last();
		
		this.checkDependents(right, idRole);
	},



	/**
	 * Get checkbox element
	 * 
	 * @param	String		right
	 * @param	Integer		idRole
	 */
	checkbox: function(right, idRole) {
		return $(right.replace(/:/, '-') + '-' + idRole);
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
	checkRight: function(right, idRole, check) {
		this.checkbox(right, idRole).checked = check;
	},



	/**
	 * Check if a right checkbox is checked
	 * 
	 * @param	String		right
	 * @param	Integer		idGroup
	 */
	isRightChecked: function(right, idRole) {
		return this.checkbox(right, idRole).checked;
	},



	/**
	 * Enable/Disable a right checkbox
	 * 
	 * @param	String		right
	 * @param	Integer		idGroup
	 * @param	Bool		enable
	 */
	enableRight: function(right, idRole, enable) {
		this.checkbox(right, idRole).disabled = enable === false;
	},



	/**
	 * Check if a right checkbox is enabled
	 * 
	 * @param	String		right
	 * @param	Integer		idGroup
	 */
	isRightEnabled: function(right, idRole) {
		return this.checkbox(right, idRole).disabled !== true;
	},



	/**
	 * Check if a right is active
	 * Active = enabled and checked
	 * 
	 * @param	String		right
	 * @param	Integer		idGroup
	 */
	isRightActive: function(right, idRole) {
		return this.isRightEnabled(right, idRole) && this.isRightChecked(right, idRole);
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
	checkDependents: function(right, idRole) {
		var dependents	= this.getDependents(right);
		
			// Check if right is active
		var active = this.isRightActive(right, idRole);
			// Loop over all rights which depend on this right
		dependents.each(function(active, idRole, depRight){
				// If right is active, activate dependent
			if( active ) {
					// Only activate right if all other required parents are active too
				if( this.allRequiredsActive(depRight, idRole) ) {
					this.enableRight(depRight, idRole, true);
				}
			} else {
					// Disable right because required parent is not active
				this.enableRight(depRight, idRole, false);
			}
		}.bind(this, active, idRole));
	},



	/**
	 * Get selected roles
	 */
	getRoles: function() {
		return $F('rightseditor-field-roles');
	},



	/**
	 * @todo	comment
	 */
	getExtension: function() {
		return $F('rightseditor-field-extension');
	},



	/**
	 * Toggle right
	 *
	 * @param	String	right
	 */
	toggleRight: function(right) {		
		var checkboxes	= $('right-' + right.replace(/:/, '-')).select('input').findAll(function(input){
			return input.disabled === false;
		});
				
			// Toggle the checkboxes
		this.toggleCheckboxes(checkboxes);
			// Recheck all rights
		this.initDependents();
	},



	/**
	 * Toggle group
	 *
	 * @param	Integer		idRole
	 */
	toggleRoleRights: function(idRole) {
			// Get role rights checkboxes
		var checkboxes= $('rightsmatix-form').select('input[id$=-' + idRole + ']');
			// Toggle the checkboxes
		this.toggleCheckboxes(checkboxes);
			// Recheck all rights
		this.initDependents();
	},



	/**
	 * Toggle checkboxes
	 *
	 * @param	Array	checkboxes
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
	}

};