Ext.define('MetApp.controller.Produccion.ReportesProduccionController',{
	extend: 'Ext.app.Controller',
	
	views: [
		'MetApp.view.Produccion.Reportes.ReporteOrdenesPorSector',
		'MetApp.view.Produccion.Reportes.ReporteOrdenesPorCliente',
		'MetApp.view.Produccion.Reportes.ReporteHistoricoProduccion'
	],
	
	stores: [
		
	],
	
	refs: [
		{
			ref: 'ReporteHistorico',
			selector: 'ReporteHistoricoProduccion'	
		}
	],
	
	init: function(){
		var me = this;
		me.control({
			'viewport menuitem[itemId=historicoProduccion]': {
				click: this.ReporteHistoricoProduccion
			},
			'ReporteHistoricoProduccion button[itemId=printDateReport]': {
				click: this.GenerarReporteHistoricoProd
			},	
			'ReporteHistoricoProduccion button[itemId=btnCodigo1]': {
				click: this.BuscarCodigo
			},
			'ReporteHistoricoProduccion button[itemId=btnCodigo2]': {
				click: this.BuscarCodigo2
			},	
			'viewport menuitem[itemId=ordenesPorSector]': {
				click: this.ControlProduccion
			},	
			'ReporteOrdenesPorSector button[itemId=printDateReport]': {
				click: this.GenerarReporteOTSector
			},
			'viewport menuitem[itemId=ordenesPorCliente]': {
				click: this.ReporteOrdenesPorCliente
			},
			'ReporteOrdenesPorCliente button[itemId=clienteBtn]': {
				click: this.BuscarCliente
			},
			'ReporteOrdenesPorCliente button[itemId=clienteBtn2]': {
				click: this.BuscarCliente
			},		
			'ReporteOrdenesPorCliente button[itemId=printDateReport]': {
				click: this.GenerarReporteOTClientes
			},			
		});
	},
	
	
	ReporteHistoricoProduccion: function(btn){
		Ext.widget('ReporteHistoricoProduccion');
	},
	
	BuscarCodigo: function(btn){
		var win = Ext.widget('winarticulosearch');
		var btnOk = win.down('button');
		var me = this;
		
		btnOk.on('click', function(){
			var grid = win.down('grid');
			var selection = grid.getSelectionModel().getSelection()[0];
			var viewReporte = me.getReporteHistorico();
			
			viewReporte.queryById('codigo1').setValue(selection.data.codigo); 
			
			win.close();
		});
	},
	
	BuscarCodigo2: function(btn){
		var win = Ext.widget('winarticulosearch');
		var btnOk = win.down('button');
		var me = this;
		
		btnOk.on('click', function(){
			var grid = win.down('grid');
			var selection = grid.getSelectionModel().getSelection()[0];
			var viewReporte = me.getReporteHistorico();
			
			viewReporte.queryById('codigo2').setValue(selection.data.codigo); 
			
			win.close();
		});
	},
	
	GenerarReporteHistoricoProd: function(btn){
		var form=btn.up('form');
		form.getForm().submit({
			standardSubmit: true,
			target: '_blank',
			url: Routing.generate('mbp_produccion_reporteHistoricoOT')
		})
	},
	
	ReporteOrdenesPorCliente: function(btn){
		Ext.widget('ReporteOrdenesPorCliente');
	},
	
	BuscarCliente: function(btn){
		var winReporte = btn.up('window');
		var win = Ext.widget('clientesSearchGrid');
		var store = win.down('grid').getStore();
		store.load();
		
		var txt;
		if(btn.itemId == "clienteBtn"){
			txt = winReporte.queryById('clienteId');
		}else{
			txt = winReporte.queryById('clienteId2');
		}
		
		win.queryById('insertCliente').on('click', function(){
			var sel = win.down('grid').getSelectionModel().getSelection()[0];
			txt.setValue(sel.data.id);
			win.close();
		});
	},
	
	GenerarReporteOTClientes: function(btn){
		var form=btn.up('form');
		form.getForm().submit({
			standardSubmit: true,
			target: '_blank',
			url: Routing.generate('mbp_produccion_reporteOTPorClientes')
		})
	},
	
	ControlProduccion: function(btn){
		Ext.widget('ReporteOrdenesPorSector');
	},
	
	GenerarReporteOTSector: function(btn){
		var form = btn.up('form');
		var values = form.getValues();
		
		var urlReporte;
		
		if(values.tipo == null || values.tipo == ""){
			urlReporte = 'mbp_produccion_reporteOTPorSector';
		}else{
			urlReporte = 'mbp_produccion_reporteOTPorSectorFiltrado';
		}
		form.getForm().submit({
			standardSubmit: true,
			target: '_blank',
			url: Routing.generate(urlReporte)
		})
	}	
});




















