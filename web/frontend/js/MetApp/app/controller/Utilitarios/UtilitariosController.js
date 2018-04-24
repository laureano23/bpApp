Ext.define('MetApp.controller.Utilitarios.UtilitariosController',{
	extend: 'Ext.app.Controller',
	stores: [
	
	],
	views: [
		'MetApp.view.Utilitarios.TxtRetencionesView'
	],
	refs:[
	],
	
	init: function(){
		var me = this;
		me.control({
			'viewport menuitem[itemId=tbRetenciones]': {
				click: this.AddRetencionesWin
			},
			'TxtRetencionesView button[itemId=descargar]': {
				click: this.DescargarTxtRetencion
			},
			'viewport menuitem[itemId=tbPercepciones]': {
				click: this.AddRetencionesWin
			},
		});
	},
	
	AddRetencionesWin: function(btn){
		Ext.widget('TxtRetencionesView');
	},
	
	DescargarTxtRetencion: function(btn){
		var win=btn.up('window');
		var form=win.down('form');
		var values=form.getForm().getValues();
		
		if(values.tipo == 2){//tipo 2 son percepciones, tipo 1 son retenciones
			form.submit({
				clientValidation: true,
				method: 'POST',
				url: Routing.generate('mbp_finanzas_txt_percepciones'),
				
				success: function(form, action){
					var jsonResp=Ext.JSON.decode(action.response.responseText);
					var ruta = Routing.generate('mbp_finanzas_txt_retenciones_percepciones_servir', {nombreArchivo: jsonResp.nombreArchivo});
			    	window.open(ruta, '_blank, location=yes,height=800,width=1200,scrollbars=yes,status=yes');
				},
				
				failure: function(res){
					
				}
			})
		}else{
			form.submit({
				clientValidation: true,
				method: 'POST',
				url: Routing.generate('mbp_finanzas_txt_retenciones'),
				
				success: function(form, action){
					var jsonResp=Ext.JSON.decode(action.response.responseText);
					var ruta = Routing.generate('mbp_finanzas_txt_retenciones_percepciones_servir', {nombreArchivo: jsonResp.nombreArchivo});
			    	window.open(ruta, '_blank, location=yes,height=800,width=1200,scrollbars=yes,status=yes');
				},
				
				failure: function(res){
					
				}
			})	
		}
		
		
		
	}
	
	
})





































