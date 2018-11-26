Ext.define('MetApp.controller.Utilitarios.VerificacionComprobanteController',{
	extend: 'Ext.app.Controller',
	stores: [
	],
	views: [
		'MetApp.view.Utilitarios.CITIVentasView',
		'MetApp.view.Utilitarios.VerificacionComprobanteView'
	],
	
	refs:[
	],
	
	init: function(){
		var me = this;
		
		me.control({
			'viewport menuitem[itemId=tbVerificacionCbtes]': {
				click: this.AddFormVerificacion
			},
			'VerificacionComprobanteView button[itemId=btnSubmit]': {
				click: this.SubmitFormVerificacion
			},
		});
	},
	
	AddFormVerificacion: function(btn){
		Ext.widget('VerificacionComprobanteView');
	},

	SubmitFormVerificacion: function(btn){
		var form=btn.up('form');
		var win=btn.up('window');
		var resField=win.queryById('res');
		form.getForm().submit({
			url: Routing.generate('mbp_CCClientes_recuperarComp'),
			success: function(form, action){
				console.log(action);
				console.log(form);
				var jsonResp=Ext.JSON.decode(action.response.responseText);
				console.log(jsonResp);
				resField.setValue(JSON.stringify(jsonResp.info, null, 4));
			}
		})
	}
})





































