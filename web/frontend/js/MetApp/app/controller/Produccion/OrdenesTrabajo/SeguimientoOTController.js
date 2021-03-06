Ext.define('MetApp.controller.Produccion.OrdenesTrabajo.SeguimientoOTController', {
	extend: 'Ext.app.Controller',
	
	views: [
		'MetApp.view.Produccion.OrdenesTrabajo.SeguimientoOTView',
		'MetApp.view.Produccion.OrdenesTrabajo.VerCodigoOTView'
	],
	
	stores: [
		
	],
	
	refs: [
        {
            ref: 'SeguimientoView',
            selector: 'SeguimientoOTView' // view xtype (alias: 'widget.grid') or selector
        }
    ],
	
	
	init: function(){
		var me = this;
		me.control({
			'#seguimientoOT': {
				click: this.SeguimientoOtView
			},
			'SeguimientoOTView button[itemId=buscar]': {
				click: this.BuscarOT
			},
			'SeguimientoOTView button[itemId=buscarArt]': {
				click: this.BuscarArt
			},
			'SeguimientoOTView actioncolumn[itemId=otImprimir]': {
				click: this.ImprimirOT
			},
			'SeguimientoOTView button[itemId=ver]': {
				click: this.VerOT
			},
			'VerCodigoOTView textfield[itemId=filtroCodigo]': {
				keyup: this.FiltrarCodigo
			},
			'VerCodigoOTView button[itemId=verOT]': {
				click: this.DevolverOT
			},
			'VerCodigoOTView': {
				close: this.CerrarWin
			}
		});
	},
	
	VerOT: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		if(!form.isValid()) return;
		
		form.getForm().submit({
			url: Routing.generate('mbp_produccion_generarOt'),
			standardSubmit: true,
			target: '_blank',
		})
	},
	
	CerrarWin: function(win){
		win.down('grid').getStore().clearFilter();
	},
		
	SeguimientoOtView: function(btn){
		var win = Ext.widget('SeguimientoOTView');
		var store = win.down('grid').getStore();
		store.removeAll();
	},
	
	BuscarOT: function(btn){
		var win = btn.up('window');
		var ot = win.queryById('otNum').getValue();
		var store = win.down('grid').getStore();
		store.loadRawData([]);
		
		Ext.Ajax.request({
			url: Routing.generate('mbp_produccion_seguimientoOT'),
			
			params: {
				ot: ot
			},
			
			success: function(resp){
				
				var jsonResp = Ext.JSON.decode(resp.responseText);
				store.loadRawData(jsonResp.data);
				win.queryById('descripcion').setValue(jsonResp.datosOt.descripcion);
				win.queryById('cantidad').setValue(jsonResp.datosOt.cantidad);
				win.queryById('estado').setValue(jsonResp.datosOt.estado);
				win.queryById('fechaEntrega').setValue(jsonResp.datosOt.fechaEntrega);
			},
			
		})
	},
	
	BuscarArt: function(btn){
		var view = Ext.widget('VerCodigoOTView');
		var grid = view.down('grid');
		var store = grid.getStore();
		store.clearFilter(true);
		var myMask = new Ext.LoadMask(view, {msg:"Cargando..."});
		myMask.show();
		
		Ext.Ajax.request({
			url: Routing.generate('mbp_produccion_ListarOrdenes'),
			
						
			success: function(resp){
				myMask.hide();
				var jsonResp = Ext.JSON.decode(resp.responseText);
				if(jsonResp.success == true){
					store.loadData(jsonResp.data);	
				}						
			},
			
			failure: function(resp){
				myMask.hide();
			}
		});
	},
	
	FiltrarCodigo: function(txt){
		var win = txt.up('window');
		var store = win.down('grid').getStore();
		store.clearFilter(true);
		store.filter('codigo', txt.getValue());
	},
	
	DevolverOT: function(btn){
		var me = this;
		var win = btn.up('window');
		var grid = win.down('grid');
		var selection = grid.getSelectionModel().getSelection()[0];
		var data = selection.data;
		
		var seguimientoView = me.getSeguimientoView();
		
		
		seguimientoView.queryById('otNum').setValue(data.otNum);
		seguimientoView.queryById('codigo').setValue(data.codigo);
		seguimientoView.queryById('descripcion').setValue(data.descripcion);
		
		var btnBuscar = seguimientoView.queryById('buscar');
		btnBuscar.fireEvent('click', btnBuscar);
		
		win.close();
	},
	
	ImprimirOT: function(grid, colIndex, rowIndex){
		var selection = grid.getStore().getAt(rowIndex);
		var form=Ext.create('Ext.form.Panel');

		form.getForm().submit({
			url: Routing.generate('mbp_produccion_generarOt'),
			standardSubmit: true,
			target: '_blank',
			params: {
				ot: selection.data.otNum
			}
		})
	}
});


















