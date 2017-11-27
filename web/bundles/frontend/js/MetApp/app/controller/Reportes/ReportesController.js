Ext.define('MetApp.controller.Reportes.ReportesController',{
	extend: 'Ext.app.Controller',
	stores: [
	
	],
	views: [
		'MetApp.view.Reportes.RepoIVAVentas',
		'MetApp.view.Reportes.RepoIVACompras'
	],
	refs:[
	],
	
	init: function(){
		var me = this;
		me.control({
			'#reporteIVAVentas': {
				click: this.AddReporteIVAVentas
			},
			'RepoIVAVentas button[itemId=printDateReport]': {
				click: this.ImprimirIvaVentas
			},
			'#reporteIVACompras': {
				click: this.AddReporteIVACompras
			},
			'RepoIVACompras button[itemId=printReport]': {
				click: this.ImprimirIvaCompras
			},
		});
	},
	
	AddReporteIVAVentas: function(btn){
		Ext.widget('RepoIVAVentas');
	},
	
	ImprimirIvaVentas: function(btn){
		var win = btn.up('window');
		var form = btn.up('form');
		var values = form.getValues();
		var myMask = new Ext.LoadMask(win, {msg:"Cargando..."});
				
		if(form.isValid()){
			myMask.show();
			Ext.Ajax.request({
				url: Routing.generate('mbp_CCClientes_LibroIVAVentas'),
				
				params: {
					desde: values.desde,
					hasta: values.hasta
				},
				
				success: function(resp){
					var jsonResp = Ext.JSON.decode(resp.responseText);
					if(jsonResp.success == true){
						var ruta = Routing.generate('mbp_CCClientes_VerLibroIVAVentas');
						
						window.open(ruta, '_blank', 'location=yes,height=800,width=1200,scrollbars=yes,status=yes');
					}
					myMask.hide();
				},
				
				failure: function(resp){
					myMask.hide();
				}
			});
		}
	},
	
	AddReporteIVACompras: function(btn){
		Ext.widget('RepoIVACompras');
	},
	
	ImprimirIvaCompras: function(btn){
		var win = btn.up('window');
		var form = btn.up('form');
		var values = form.getValues();
		var myMask = new Ext.LoadMask(win, {msg:"Cargando..."});
				
		if(form.isValid()){
			myMask.show();
			Ext.Ajax.request({
				url: Routing.generate('mbp_proveedores_ReporteLibroIVACompras'),
				
				params: {
					desde: values.desde,
					hasta: values.hasta
				},
				
				success: function(resp){
					var jsonResp = Ext.JSON.decode(resp.responseText);
					if(jsonResp.success == true){
						var ruta = Routing.generate('mbp_proveedores_VerReporteLibroIVACompras');
						
						window.open(ruta, 'location=yes,height=800,width=1200,scrollbars=yes,status=yes');
					}
					myMask.hide();
				},
				
				failure: function(resp){
					myMask.hide();
				}
			});
		}
	}
})





































