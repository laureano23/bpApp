Ext.define('MetApp.resources.ux.ParametersSingleton',{
	singleton: true,
	config: {
		iva: 0,
		dolarOficial: 0,	
	},	
	initParams: function(){		
		/*Ext.Ajax.request({
			url: Routing.generate('mbp_finanzas_initParams'),
			
			success: function(resp){
				console.log(resp);
				jsonResp = Ext.JSON.decode(resp.responseText);
				//return jsonResp;
				console.log(jsonResp);
			}
		});*/
	},
	
	constructor: function(config){
		this.initConfig(config);
	}	
});
