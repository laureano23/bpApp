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
		console.log(btn);
		Ext.widget('ReporteOrdenesPorSector');
	},
	
	GenerarReporteOTSector: function(btn){
		 
	}
	
});




















