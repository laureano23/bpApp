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
			var myMask = new Ext.LoadMask(win, {msg:"Cargando..."});
			//myMask.show();
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
					//myMask.hide();
				},
				
				failure: function(form, resp){
					//myMask.hide();
					var errores=[];
					var jsonResp = Ext.JSON.decode(resp.response.responseText);
					
					
					var msg = Ext.create('Ext.window.MessageBox', {
				        autoScroll: true
				    });
										
					msg.show({
						title:'Errores de validación',
					    msg: jsonResp.msg.errorColeccion == "" ? jsonResp.msg.errorLote : jsonResp.msg.errorColeccion,
					    buttons: Ext.Msg.OK,
					});
				}
			});
		}
	}
})





















