Ext.define('MetApp.controller.Produccion.Programacion.ProgramacionController', {
	extend: 'Ext.app.Controller',
	
	views: [
		'Produccion.Programacion.Programacion',
		'Produccion.Programacion.FormProgramacion',
	],
	
	stores: [
		'Produccion.Programacion.ProgramacionStore',
		'Produccion.Programacion.FormulaMoStore'
	],
	
	refs: [
		{
			ref: 'winProg',
			selector: '#tablaProgramacion'	
		}
	],
	
	init: function(){
		var me = this;
		me.control({
			'#programarPedidos': {
				click: this.TablaProgramacion
			},
			'programacion grid[itemId=gridProgramacion] dataview': {
				beforedrop: this.CargaGrillaProgramacion
			},
			'programacion button[action=calcular]': {
				click: this.CalculaProgramacion
			},		
			'programacion textfield[itemId=maq]': {
				focus: this.CargaMaquina
			},
			'programacion grid[itemId=gridProgramacion]': {
				cellclick: this.MaquinaSelect
			},
			'programacion button[action=actSave]': {
				click: this.GuardaMaquinas
			},
			'programacion button[action=reportePrograma]': {
				click: this.ReportePrograma
			},
			'programacion button[action=editarProg]': {
				click: this.FormEditarProg
			},
			'formProgramacion button[action=progRec]': {
				click: this.FormProgRec
			}
		});
	},	
	
	/*
	 * TABLA DE PROGRAMACION ABMC
	 */
	TablaProgramacion: function(){
		var programacionWin = Ext.widget('programacion');
		var grid = programacionWin.queryById('gridProgramacion');
		var store = programacionWin.queryById('gridPedidos').getStore();
		store.load();	
		
		var editBtn = programacionWin.queryById('editarProg');
		//BTN EDIT ENABLED
		grid.on('select', function(){			
			editBtn.setDisabled(false);
		});
		
		//BTN EDIT DISABLED
		grid.on('blur', function(){
			editBtn.setDisabled(true);
		});
		
		
	},
	
	CargaGrillaProgramacion: function(node, data, overModel, dropPosition, dropHandlers, eOpts){
		dropHandlers.cancelDrop();
		var win = this.getWinProg();
		var grid = win.queryById('gridProgramacion');
		var cant = win.queryById('cant');
		cant.setValue(data.records[0].data.cantidad);
		Ext.Ajax.request({
			url: Routing.generate('mbp_produccion_formulasMo_list'),
			params: {
				codigo: data.records[0].data.codigo,
				cantidad: data.records[0].data.cantidad
			},
			method: 'GET',
			success: function(resp){
				var store = grid.getStore();
				var data = Ext.decode(resp.responseText);
				store.loadRawData(data);	
				var processor = Ext.create('MetApp.resources.ux.RequestMessageProcessor');
				processor.Message(resp, resp);
			}
		});
	},
	
	CalculaProgramacion: function(btn){
		var win = btn.up('window');
		var fechaProg = win.queryById('fechaProgramar');
		var fechaProg = win.queryById('fechaProgramar');
		var grid = win.queryById('gridProgramacion');
		var store = grid.getStore();
		var length = grid.getStore().getCount();
		var records = [];
		for(i=0; i<length; i++){			
			var rec = grid.getView().getRecord(i).getData();
			records[i] = rec;
		}
		var fechaProgramar = win.queryById('fechaProgramar').getValue();
		var horaProgramar = win.queryById('horaProgramar').getValue();			
		var cantidad = win.queryById('cant').getValue();
		var data = [];
		data[0] = records;
		data[1] = fechaProgramar;
		data[2] = horaProgramar;
		data[3] = cantidad;
		
		info = Ext.JSON.encode(data);
		
		if(fechaProg.isValid()){
			Ext.Ajax.request({
				url: Routing.generate('mbp_produccion_programacion_calculo'),
				params: {
					data: info
				},
				success: function(resp){
					console.log(resp);
					var data = Ext.decode(resp.responseText);
					store.loadRawData(data);	
					var processor = Ext.create('MetApp.resources.ux.RequestMessageProcessor');
					processor.Message(resp, resp);
				}
			});
			
			fechaProg.isValid() ? store.load() : null;	
		}
	},
	
	MaquinaSelect: function(cell, td, cellIndex, record, tr, rowIndex, e, eOpts ){
		if(cellIndex == 7){
			var window = cell.up('window');
			var maqTable = Ext.create('MetApp.view.Produccion.Maquinas.SearchGridMaquinas');
			var grid = maqTable.down('grid');
			var store = grid.getStore();
			var gridProg = cell.up('grid');
			var storeProg = gridProg.getStore();		
			var btnAcept = maqTable.queryById('insertMaquina');
			var recN = storeProg.getCount();
			
			store.load();
			
			btnAcept.on('click', function(btn){
				var rec = grid.getSelectionModel().getSelection()[0];
				var sel = cell.getSelectionModel().getSelection()[0];
				sel.beginEdit();	//COMIENZA EDIT
				sel.set('maquina', rec.data.descripcion);
				sel.set('idMaquina', rec.data.id);
				sel.endEdit();		//END EDIT
				maqTable.close();	
			});	
		}		
	},
	
	FormEditarProg: function(btn){
		var winProg = btn.up('window');
		var gridProg = winProg.queryById('gridProgramacion');
		var selection = gridProg.getSelectionModel().getSelection();
		var record = selection[0].getData();
		
		var winFormProg = Ext.widget('formProgramacion');
		var formProg = winFormProg.queryById('formProg');
		
		formProg.queryById('operacion').setValue(record.idOperacion.descripcion);
		var tiempoU = new Date(record.tiempo.date);
		formProg.queryById('tiempoU').setValue(tiempoU);
		
	},
	
	FormProgRec: function(btn){
		var form = btn.up('form');
		if(form.isValid()){
			var data = [];
			data[0] = record;
			data[1] = fechaProgramar;
			data[2] = horaProgramar;
			
			info = Ext.JSON.encode(data);
			
			proxy.api.read = Routing.generate('mbp_produccion_programacion_calculo');
			proxy.setExtraParam('data', info);
		}
	},
	
	ReportePrograma: function(btn){
		//USAMOS EL MISMO REPORTE DE PEDIDO DE CLIENTES
		var winReporte = Ext.create('MetApp.view.Produccion.Pedidos.Reportes.ReportePedidos',{
			title: 'Programacion'
		});
		var btnReport = winReporte.queryById('newReportePedidos');			
		
		
		btnReport.on('click', function(){
			var form = winReporte.down('form');
			var data = form.getForm().getValues();
			var jsonVal = Ext.JSON.encode(data);
			Ext.Ajax.request({
				url: Routing.generate('mbp_produccion_programacion_reporte'),
				
				params: {
					data: jsonVal
				},
				
				/*success: function(resp, opt){
					var jsonReporte = Ext.JSON.decode(resp.responseText);
					Reporte=jsonReporte.reporte;
					
					var ruta = Routing.generate('mbp_produccion_reporte_pedidoPdf');
									
					var WinReporte=Ext.create('Ext.Window', {
					  title: 'Pedido de clientes',
					  width: 900,
					  height: 700,
					  layout: 'fit',
					  modal:true,										
					  html: '<iframe src='+ruta+' width="100%" height="100%"></iframe>'						  
				  });
				  WinReporte.show();
				}*/
			});
		});
	}
});










