Ext.define('MetApp.controller.Articulos.ParametrosController',{
	extend: 'Ext.app.Controller',	
	views: [
		'MetApp.view.Articulos.ParametrosForm'
		],
	stores: [],
	
	init: function(){
		
		var me = this;
		me.control({
				'viewport menuitem[itemId=formParametros]': {
					click: this.formParametros
				},
				'ParametrosForm button[itemId=guardar]': {
					click: this.guardarParametros
				},
		});		
	},
	
	formParametros: function(btn){
		var win = Ext.widget('ParametrosForm');
		var form = win.down('form');
		form.load({
			url: Routing.generate('mbp_finanzas_listarParametros'),
			
			success: function(f, action){
				var jsonResp = Ext.JSON.decode(action.response.responseText);
				var combo = form.down('combobox');
				var storeProv = combo.getStore();
				storeProv.load({
					callback: function(){
						combo.select(jsonResp.data.provincia);
					}
				})
			}
		});
	},
	
	guardarParametros: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		
		form.submit({
			url: Routing.generate('mbp_finanzas_guardarParametros'),
			
			success: function(f, action){
				Ext.Msg.show({
					title: 'Info',
					msg: 'Datos guardados',
					buttons: Ext.Msg.OK
				});
			},
			
			failure: function(f, action){
				var jsonResp = Ext.JSON.decode(action.response.responseText);
				
				if(jsonResp.tipo == "validacion"){
					form.markAsInvalid(jsonResp.errors);
				}else{
					Ext.Msg.show({
						title: 'Atención',
						msg: jsonResp.msg,
						buttons: Ext.Msg.OK
					});
				}	
			}
		})
	}
});










