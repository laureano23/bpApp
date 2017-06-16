Ext.define('MetApp.controller.RRHH.LiquidacionEnLoteController', {
	extend: 'Ext.app.Controller',
	views: [
		'RRHH.LiquidacionEnLoteView'
	],
	
	refs: [
	],
	
	stores: [
		'MetApp.store.Finanzas.BancosStore'
	],
	
	init: function(){
		var me = this;
		
		me.control({
			'#tablaNuevoPagoEnLote': {
				click: this.NuevoPagoEnLote
			},
			'LiquidacionEnLoteView button[itemId=liquidarLote]': {
				click: this. LiquidarLote
			}
		})
	},
	
	NuevoPagoEnLote: function(btn){
		var win = Ext.widget('LiquidacionEnLoteView');
		var storeBancos = win.queryById('comboBancos').getStore();
		storeBancos.load();
	},
	
	LiquidarLote: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		
		if(form.getForm().isValid()){
			form.submit({
				url: Routing.generate('mbp_personal_LiquidarEnLote'),
				
				success: function(formR, action){
					var jsonResp = Ext.JSON.decode(action.response.responseText);
					if(jsonResp.success == true){
						Ext.Msg.show({
							title:'Atención',
						    msg: 'El proceso se realizó con éxito',
						    buttons: Ext.Msg.OK,
						    icon: Ext.Msg.INFO
						});
					}
					//form.getForm().reset();
				},
				
				failure: function(form, resp){
					var errores=[];
					var jsonResp = Ext.JSON.decode(resp.response.responseText);
					
					var errorColeccion = JSON.stringify(Ext.JSON.decode(jsonResp.msg.errorColeccion));
					errorColeccion = errorColeccion.replace(/,/g, "<br />");	
					var errorLote = JSON.stringify(Ext.JSON.decode(jsonResp.msg.errorLote));
					errorLote = errorLote.replace(/,/g, "<br />");
					
					errores[0] = errorColeccion;
					errores[1] = errorLote;
					
					var msg = Ext.create('Ext.window.MessageBox', {
				        autoScroll: true
				    });
					
					msg.show({
						title:'Errores de validación',
					    msg: errores,
					    buttons: Ext.Msg.OK,
					});
				}
			});
		}
	}
})





















