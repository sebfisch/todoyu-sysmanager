Todoyu.Ext.sysmanager.ExtConf = {
	
	onSave: function(form) {

		$(form).request({
			'parameters': {
				'cmd': 'save'
			},
			'onComplete': this.onSaved.bind(this)
		});
		
		return false;		
	},
	
	onSaved: function(response) {		
		if( response.hasTodoyuError() ) {
			Todoyu.notifyError('Formvalue invalid');
		} else {
			Todoyu.notifySuccess('Extension configuration saved', 3);
		}
		
		$('config-form').replace(response.responseText);
	}
	
};