Ext.define('MetApp.controller.Bancos.ReportesController',{
	extend: 'Ext.app.Controller',	
	views: [
		'MetApp.view.Reportes.ReporteHistoricoMovBancarios'
		
	],
	stores: [
	],
	
	refs:[
	],
	
	init: function(){
		var me = this;
		
				
		me.control({
				'viewport menuitem[itemId=reporteHistMovBancarios]': {
					click: this.formMovBancos
				}
		});		
	},
	
	formMovBancos: function(btn){
		var view = Ext.widget('ReporteHistoricoMovBancarios');
		
		//view.queryById('concepto1').getStore().load();
		//view.queryById('banco1').getStore().load();
	}
});










