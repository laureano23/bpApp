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
			'ControlRecepcionView actioncolumn[itemId=detalle]': {
				click: this.DetalleControl
			},
		})		
	},
	
	DetalleControl: function(btn){
		var win=btn.up('window');
		var grid=win.down('grid');
		var sel=grid.getSelectionModel().getSelection()[0];
		
		
		Ext.Msg.prompt('Control', 'Ingrese control realizado:', function(btn, text){
		    if (btn == 'ok'){
		    	console.log(text);
				sel.data.detalleControl=text;
				console.log(sel);
				grid.getView().refreshNode(sel.index);        
		    }
		},this, true, sel.data.detalleControl);
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














