Ext.define('MetApp.controller.Utilitarios.HojaDeRutaController',{
	extend: 'Ext.app.Controller',
	stores: [
		'MetApp.store.Utilitarios.HojaDeRutaStore',
	],
	views: [
		'MetApp.view.Utilitarios.HojaDeRutaView'
	],
	
	refs:[
	],
	
	init: function(){
        var me = this;		
		me.control({
			'viewport menuitem[itemId=tbHojaDeRuta]': {
				click: this.AddHojaRutaWin
			},
			'HojaDeRutaView button[itemId=buscar]': {
				click: this.BuscarDestino
            },
            'HojaDeRutaView button[itemId=insert]': {
				click: this.NuevoDestino
            },
            'HojaDeRutaView actioncolumn[itemId=editar]': {
				click: this.EditarDestino
            },
            'HojaDeRutaView actioncolumn[itemId=borrar]': {
				click: this.EliminarDestino
			},
		});
	},
	
	AddHojaRutaWin: function(){
        var win=Ext.widget('HojaDeRutaView');
        win.down('grid').getStore().load();
    },

    BuscarDestino: function(btn){
        var win = btn.up('window');
        var origen = win.queryById('origen');
        var form=win.down('form');
        var values=form.getForm().getValues();
        
        var winSearch;
        if(values.origen == 'cliente'){
            winSearch=Ext.widget('clientesSearchGrid');
        }else{
            winSearch=Ext.widget('ProveedoresSearchGrid');
        }
        var btnIsert=winSearch.down('button');
        btnIsert.on('click', function(){
            var selection=winSearch.down('grid').getSelectionModel().getSelection();
            selection=selection[0];
            form.queryById('nombre').setValue(selection.data.rsocial);
            form.queryById('domicilio').setValue(selection.data.direccion);

            winSearch.close();
            form.queryById('horarios').focus('', 10);
        });
    },

    NuevoDestino: function(btn){
        var form=btn.up('form');
        var values = form.getForm().getValues();
        var store = btn.up('window').down('grid').getStore();
        var model;
        if(values.idViaje == 0){
            model=Ext.create('MetApp.model.Utilitarios.HojaDeRutaModel');
            model.set(values);
            model.set('estado', 'Pendiente');
            store.add(model);
        }else{
            model=store.findRecord('idViaje', values.idViaje);
            model.set(values);
        }

        form.getForm().reset();
    },

    EditarDestino: function(grid, colIndex, rowIndex){
        var store = grid.getStore();
		var selection = store.getAt(rowIndex);
        var form=grid.up('window').down('form');
        
        form.loadRecord(selection);
    },

    EliminarDestino: function(grid, colIndex, rowIndex){
        var store = grid.getStore();
		var selection = store.getAt(rowIndex);
        
        store.remove(selection);
    }
});