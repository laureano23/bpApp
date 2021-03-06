Ext.define('MetApp.controller.Security.UserParamsController',{	
	extend: 'Ext.app.Controller',
	id: 'UserParamsController',
	store: ['Parametros.UserParamsStore'],
	views: ['Principal.MyViewport'],
	
	/*
	 * Realizo la consulta al servidor para obtener los roles
	 */
	init: function(){
		var me = this;
		me.control({
			'#MyViewport button[itemId=tbArticulos]': {
				contextmenu: this.ConfigPanel
			},
		});		
	},

	ConfigPanel: function(btn){
		console.log(btn);
	}
});

