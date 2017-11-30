Ext.define('MetApp.controller.Produccion.ReportesProduccionController',{
	extend: 'Ext.app.Controller',
	
	views: [
		'MetApp.view.Produccion.Reportes.ReporteOrdenesPorSector'
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
		});
	},
	
	ControlProduccion: function(btn){
		Ext.widget('ReporteOrdenesPorSector');
	},
	
	GenerarReporteOTSector: function(btn){
		var win = btn.up('window');
		var form = btn.up('form');
		var values = form.getValues();
		
		 Ext.Ajax.request({
		 	url: Routing.generate('mbp_produccion_reporteOTPorSector'),
		 	
		 	params: {
		 		desde: values.desde,
		 		hasta: values.hasta
		 	},
		 	
		 	success: function(resp){
		 		var jsonResp = Ext.JSON.decode(resp.responseText);
		 		
		 		if(jsonResp.success == true){
		 			var ruta = Routing.generate('mbp_produccion_verOTPorSector');
					
					var myMask = new Ext.LoadMask(win, {msg:"Cargando..."});
					myMask.show();			
					
					window.open(ruta, 'location=yes,height=800,width=1200,scrollbars=yes,status=yes');	
		 		}
		 		myMask.hide();
		 	}
		 });
	}
	
});



















