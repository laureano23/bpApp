Ext.define('MetApp.controller.Produccion.ReportesProduccionController',{
	extend: 'Ext.app.Controller',
	
	views: [
		'MetApp.view.Produccion.Reportes.ReporteOrdenesPorSector',
		'MetApp.view.Produccion.Reportes.ReporteOrdenesPorCliente'
	],
	
	stores: [
		
	],
	
	init: function(){
		var me = this;
		me.control({
			'viewport menuitem[itemId=ordenesPorSector]': {
				click: this.ControlProduccion
			},	
			'ReporteOrdenesPorSector button[itemId=printDateReport]': {
				click: this.GenerarReporteOTSector
			},
			'viewport menuitem[itemId=ordenesPorCliente]': {
				click: this.ReporteOrdenesPorCliente
			},	
			'ReporteOrdenesPorCliente button[itemId=printDateReport]': {
				click: this.GenerarReporteOTClientes
			},			
		});
	},
	
	ReporteOrdenesPorCliente: function(btn){
		Ext.widget('ReporteOrdenesPorCliente');
	},
	
	GenerarReporteOTClientes: function(btn){
		var win = btn.up('window');
		var form = btn.up('form');
		var values = form.getValues();
		
		var urlReporte, urlPdf;
		
		
		urlReporte = 'mbp_produccion_reporteOTPorClientes';
		urlPdf = 'mbp_produccion_verOTPorCliente';
		
		
		 Ext.Ajax.request({
		 	url: Routing.generate(urlReporte),
		 	
		 	params: {
		 		desde: values.desde,
		 		hasta: values.hasta,
		 	},
		 	
		 	success: function(resp){
		 		var jsonResp = Ext.JSON.decode(resp.responseText);
		 		
		 		if(jsonResp.success == true){
		 			var ruta = Routing.generate(urlPdf);
					
					var myMask = new Ext.LoadMask(win, {msg:"Cargando..."});
					myMask.show();			
					
					window.open(ruta, 'location=yes,height=800,width=1200,scrollbars=yes,status=yes');	
		 		}
		 		myMask.hide();
		 	}
		 });
	},
	
	ControlProduccion: function(btn){
		Ext.widget('ReporteOrdenesPorSector');
	},
	
	GenerarReporteOTSector: function(btn){
		var win = btn.up('window');
		var form = btn.up('form');
		var values = form.getValues();
		
		var urlReporte, urlPdf;
		
		if(values.tipo == null || values.tipo == ""){
			urlReporte = 'mbp_produccion_reporteOTPorSector';
			urlPdf = 'mbp_produccion_verOTPorSector';
		}else{
			urlReporte = 'mbp_produccion_reporteOTPorSectorFiltrado';
			urlPdf = 'mbp_produccion_verOTPorSectorFiltrado';
		}
		
		 Ext.Ajax.request({
		 	url: Routing.generate(urlReporte),
		 	
		 	params: {
		 		desde: values.desde,
		 		hasta: values.hasta,
		 		sector: values.tipo,
		 	},
		 	
		 	success: function(resp){
		 		var jsonResp = Ext.JSON.decode(resp.responseText);
		 		
		 		if(jsonResp.success == true){
		 			var ruta = Routing.generate(urlPdf);
					
					var myMask = new Ext.LoadMask(win, {msg:"Cargando..."});
					myMask.show();			
					
					window.open(ruta, 'location=yes,height=800,width=1200,scrollbars=yes,status=yes');	
		 		}
		 		myMask.hide();
		 	}
		 });
	}
	
});




















