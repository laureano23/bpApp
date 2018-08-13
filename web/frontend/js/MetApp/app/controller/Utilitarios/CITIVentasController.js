Ext.define('MetApp.controller.Utilitarios.CITIVentasController',{
	extend: 'Ext.app.Controller',
	stores: [
	],
	views: [
		'MetApp.view.Utilitarios.CITIVentasView'
	],
	
	refs:[
	],
	
	init: function(){
		var me = this;
		
		me.control({
			'viewport menuitem[itemId=tbCITIVentas]': {
				click: this.AddCITIVentasWin
			},
			'CITIVentasView button[itemId=printReport]': {
				click: this.DescargarCitiVentas
			},
		});
	},
	
	AddCITIVentasWin: function(btn){
		Ext.widget('CITIVentasView');
	},

	DescargarCitiVentas: function(btn){
		var form = btn.up('form');

		form.getForm().submit({
			url: Routing.generate('mbp_finanzas_txt_citiVentas'),
			success: function(form, resp){
				console.log(resp);
				var jsonResp = Ext.JSON.decode(resp.response.responseText);
				var ruta = Routing.generate('mbp_finanzas_txt_retenciones_percepciones_servir', {nombreArchivo: jsonResp.nombreArchivo});
				window.open(ruta, '_blank, location=yes,height=800,width=1200,scrollbars=yes,status=yes');				
				var ruta2 = Routing.generate('mbp_finanzas_txt_retenciones_percepciones_servir', {nombreArchivo: jsonResp.nombreArchivo2});
				window.open(ruta2, '_blank2, location=yes,height=800,width=1200,scrollbars=yes,status=yes');
			}
		})
	}
})





































