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
		var win = btn.up('window');
		var form = win.down('form');
		var myMask = new Ext.LoadMask(win, {msg:"Cargando..."});
		
		if(form.isValid()){			
			myMask.show();
		}
		
		form.submit({
			url: Routing.generate('mbp_Reportes_MovimientosBancos'),
			
			success: function(resp){
				var ruta = Routing.generate('mbp_Reportes_VerReporteMovBancos');						
				window.open(ruta, 'location=yes,height=800,width=1200,scrollbars=yes,status=yes');
				
				myMask.hide();
			},
			
			failure: function(resp){
				myMask.hide();
			}
		});
		
		
	}
});










