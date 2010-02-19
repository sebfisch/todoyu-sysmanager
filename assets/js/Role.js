Todoyu.Ext.sysmanager.Role = {
	
	ext: Todoyu.Ext.sysmanager,
	
	edit: function(idRole) {
		var url		= Todoyu.getUrl('sysmanager', 'role');
		var options	= {
			'parameters': {
				'action':	'edit',
				'role':		idRole		
			},
			'onComplete':	this.onEdit.bind(this, idRole)				
		};
		
		Todoyu.Ui.updateContentBody(url, options);
	},
	
	onEdit: function(idRole, response) {
		
	},
	
	remove: function(idRole) {
		if( confirm('[LLL:sysmanager.role.delete.confirm]') ) {
			var url		= Todoyu.getUrl('sysmanager', 'role');
			var options	= {
				'parameters': {
					'action':	'delete',
					'role':		idRole
				},
				'onComplete': this.onRemoved.bind(this, idRole)
			};
			
			Todoyu.send(url, options);
		}
	},
	
	onRemoved: function(idRole, response) {
		
	},
	
	save: function(form) {
		$(form).request({
			'parameters': {
				'action':	'save'
			},
			'onComplete': this.onSaved.bind(this)
		});
	},
	
	onSaved: function(response) {
		if( response.hasTodoyuError() ) {
			Todoyu.notifyError('[LLL:sysmanager.roles.saved.error]');
			Todoyu.Ui.setContentBody(response.responseText);
		} else {
			Todoyu.notifySuccess('[LLL:sysmanager.roles.saved.ok]');
			this.showList();
		}
	},
	
	showList: function() {
		var url		= Todoyu.getUrl('sysmanager', 'role');
		var options	= {
			'parameters': {
				'action':	'listing'				
			},
			'onComplete':	this.onListShowed.bind(this)
		};
		
		Todoyu.Ui.updateContentBody(url, options);
	},
	
	onListShowed: function(response) {
		
	}
	
};