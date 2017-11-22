Ext.define('MetApp.controller.Produccion.Maquinas.MaquinasController', {
	extend: 'Ext.app.Controller',
	
	stores: [
		'Produccion.Programacion.MaquinasStore'
	],
	
	views: [
		'Produccion.Maquinas.MaquinasTabla',
		'Produccion.Maquinas.SearchGridMaquinas'
	],
	
	init: function(){
		var me = this;
		me.control({
			'#maquinas': {
				click: this.AddMaquinaTable
			},
			'maquinasTabla [itemId=buscaMaquina]': {
				click: this.BuscaMaquinas
			}
		});
	},
	
	AddMaquinaTable: function(btn){
		Ext.widget('maquinasTabla');
	},
	
	BuscaMaquinas: function(btn){
		winGrid = Ext.widget('maquinasSearchGrid');
		store = winGrid.down('grid').getStore();
		button = winGrid.queryById('insertMaquina');
		form = btn.up('form');
		
		button.on({
			click: function(){
				grid = winGrid.down('grid');
				selection = grid.getSelectionModel().getSelection()[0];
				form.loadRecord(selection);
				winGrid.close();
			}
		});
		
		store.load();
		
		
	},
	
	
});
