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
		var win = btn.up('window');
		var form = win.down('form');
		var values = form.getForm().getValues();
		
		form.getForm().isValid();
		
		Ext.Ajax.request({
			url: Routing.generate('mbp_produccion_reporteHistoricoOT'),
			
			params: {
				desde: values.desde,
				hasta: values.hasta,
				codigo1: values.codigo1,
				codigo2: values.codigo2,
			},
			
			success: function(resp){
				var jsonResp = Ext.JSON.decode(resp.responseText);
				if(jsonResp.success == true){
					var ruta = Routing.generate('mbp_produccion_verHistoricoOt');
					
					var myMask = new Ext.LoadMask(win, {msg:"Cargando..."});
					myMask.show();			
					
					window.open(ruta, 'location=yes,height=800,width=1200,scrollbars=yes,status=yes');
					myMask.hide();
				}
			},
			
			failure: function(resp){
				myMask.hide();
			}
		});
	},
	
	ReporteOrdenesPorCliente: function(btn){
		Ext.widget('ReporteOrdenesPorCliente');
	},
	
	BuscarCliente: function(btn){
		var winReporte = btn.up('window');
		var win = Ext.widget('clientesSearchGrid');
		var store = win.down('grid').getStore();
		store.load();
		
		console.log(btn);
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
		var win = btn.up('window');
		var form = btn.up('form');
		var values = form.getValues();
		
		if(!form.isValid()) return;
		
		var urlReporte, urlPdf;
		
		
		urlReporte = 'mbp_produccion_reporteOTPorClientes';
		urlPdf = 'mbp_produccion_verOTPorCliente';
		
		
		 Ext.Ajax.request({
		 	url: Routing.generate(urlReporte),
		 	
		 	params: {
		 		desde: values.desde,
		 		hasta: values.hasta,
		 		cliente1: values.clienteId,
		 		cliente2: values.clienteId2
		 	},
		 	
		 	success: function(resp){
		 		var jsonResp = Ext.JSON.decode(resp.responseText);
		 		
		 		if(jsonResp.success == true){
		 			var ruta = Routing.generate(urlPdf);
					
					var myMask = new Ext.LoadMask(win, {msg:"Cargando..."});
					myMask.show();			
					
					window.open(ruta, 'location=yes,height=800,width=1200,scrollbars=yes,status=yes');	
		 		}
		 		myMask.hide();
		 	}
		 });
	},
	
	ControlProduccion: function(btn){
		Ext.widget('ReporteOrdenesPorSector');
	},
	
	GenerarReporteOTSector: function(btn){
		var win = btn.up('window');
		var form = btn.up('form');
		var values = form.getValues();
		
		var urlReporte, urlPdf;
		
		if(values.tipo == null || values.tipo == ""){
			urlReporte = 'mbp_produccion_reporteOTPorSector';
			urlPdf = 'mbp_produccion_verOTPorSector';
		}else{
			urlReporte = 'mbp_produccion_reporteOTPorSectorFiltrado';
			urlPdf = 'mbp_produccion_verOTPorSectorFiltrado';
		}
		
		 Ext.Ajax.request({
		 	url: Routing.generate(urlReporte),
		 	
		 	params: {
		 		desde: values.desde,
		 		hasta: values.hasta,
		 		sector: values.tipo,
		 	},
		 	
		 	success: function(resp){
		 		var jsonResp = Ext.JSON.decode(resp.responseText);
		 		
		 		if(jsonResp.success == true){
		 			var ruta = Routing.generate(urlPdf);
					
					var myMask = new Ext.LoadMask(win, {msg:"Cargando..."});
					myMask.show();			
					
					window.open(ruta, 'location=yes,height=800,width=1200,scrollbars=yes,status=yes');	
		 		}
		 		myMask.hide();
		 	}
		 });
	}
	
});




















