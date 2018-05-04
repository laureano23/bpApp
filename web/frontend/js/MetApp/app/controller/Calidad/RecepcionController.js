Ext.define('MetApp.controller.Calidad.RecepcionController',{
	extend: 'Ext.app.Controller',
	views: [
		'MetApp.view.Calidad.ControlRecepcionView'
		],
	stores: [
		'MetApp.store.Calidad.ControlRecepcionStore'
	],
	refs: [
	],
	
	init: function(){
		var me = this;
		me.control({
			'viewport menuitem[itemId=controlRecepcion]': {
				click: this.ControlRecepcion
			},
			'ControlRecepcionView button[itemId=guardar]': {
				click: this.GuardarCambiosRecepcion
			},
		})		
	},
	
	GuardarCambiosRecepcion: function(btn){
		var win=btn.up('window');
		var btn=win.down('button');
		var store=win.down('grid').getStore();
			
		store.sync({
			success: function(resp){
				store.load();
			}
		});		
	},
	
	ControlRecepcion: function(btn){
		var win=Ext.widget('ControlRecepcionView');
		var store=win.down('grid').getStore();
		store.load();
	}
		
});














