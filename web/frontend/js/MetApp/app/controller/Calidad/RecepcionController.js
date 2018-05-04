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
		console.log("hola");
		btn.on('click', function(btn){
			var store=win.down('grid').getStore();
			var arr=new Array();
			store.each(function(rec){
				var data=rec.getData();
				if(data.estadoCalidad != ""){
					arr.push(data);
				}
				
			});
			
			store.sync();
			/*Ext.Ajax.request({
				url: Routing.generate("mbp_calidad_controlIngreso"),
				
				params: {
					data: Ext.JSON.encode(arr)
				},
				
				success: function(resp){
					store.load();
				}
			});
			console.log(arr);
			console.log("chau");*/
		});
	},
	
	ControlRecepcion: function(btn){
		var win=Ext.widget('ControlRecepcionView');
		var store=win.down('grid').getStore();
		store.load();
	}
		
});














