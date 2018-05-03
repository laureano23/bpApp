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
		})		
	},
	
	ControlRecepcion: function(btn){
		var win=Ext.widget('ControlRecepcionView');
		var store=win.down('grid').getStore();
		store.load();
	}
		
});














