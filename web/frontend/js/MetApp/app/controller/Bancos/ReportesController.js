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
				},
				'ReporteHistoricoMovBancarios button[itemId=printDateReport]': {
					click: this.printMovBancos
				}
		});		
	},
	
	formMovBancos: function(btn){
		var view = Ext.widget('ReporteHistoricoMovBancarios');
	},
	
	printMovBancos: function(btn){
		var form=btn.up('form');
		form.getForm().submit({
			standardSubmit: true,
			target: '_blank',
			url: Routing.generate('mbp_Reportes_MovimientosBancos')
		})		
	}
});










