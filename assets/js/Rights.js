/**
 * @author ferni
 */
Todoyu.Ext.sysmanager.Rights = {
	
	ext: Todoyu.Ext.sysmanager,
	
	onTabClick: function(event, tab) {
		var url		= Todoyu.getUrl('sysmanager', 'rights');
		var options	= {
			'parameters': {
				'action':	'tab',
				'tab':		tab				
			},
			'onComplete': this.onTabLoaded.bind(this, tab)
		}
		
		Todoyu.Ui.updateContentBody(url, options);		
	},
	
	onTabLoaded: function(tab, response) {
		
	}	
	
};