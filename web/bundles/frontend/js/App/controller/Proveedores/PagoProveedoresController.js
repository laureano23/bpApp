Ext.define('MetApp.controller.Proveedores.PagoProveedoresController',{
	extend: 'Ext.app.Controller',
	requires: [
		'MetApp.resources.ux.ParametersSingleton'
	],
	stores: [
		'MetApp.store.Finanzas.BancosStore',
		'MetApp.store.Proveedores.PagoProveedoresStore',
		'MetApp.store.Proveedores.GridChequeTercerosStore',
		'MetApp.store.Proveedores.GridImputaFcStore'
	],
	views: [
		'MetApp.view.CCProveedores.CCProveedores',
		'MetApp.view.CCProveedores.FacturaProveedor',
		'MetApp.view.CCProveedores.PagoProveedores',
		'MetApp.view.CCProveedores.ChequeTerceros',
	],
	refs:[
		{
			ref:'CCProveedores',
			selector: 'CCProveedores'
		},
		{
			ref: 'PagoProveedores',
			selector: 'PagoProveedores'
		}
	],
	
	init: function(){
		var me = this;
		me.control({			
			'PagoProveedores button[itemId=btnNew]': {
				click: this.InsertaEnGrilla
			},
			'PagoProveedores button[itemId=btnEdit]': {
				click: this.EditarGrilla
			},
			'PagoProveedores button[itemId=btnDelete]': {
				click: this.BorrarGrilla
			},
			'PagoProveedores button[itemId=btnSave]': {
				click: this.GuardarPago
			},
			'PagoProveedores button[itemId=chequesTerceros]': {
				click: this.ChequeTerceros
			},
			'PagoProveedores button[itemId=imputarFacturas]': {
				click: this.GridImputarFacturas
			},
			'PagoProveedores grid[itemId=gridImputaFc]': {
				edit: this.ImputaFacturas
			},
			'ChequeTerceros button[itemId=aceptar]': {
				click: this.InsertaChequeTerceros
			},			
		});
	},
	
	InsertaEnGrilla: function(btn){
		var win = btn.up('window');
		var grid = win.down('grid');
		var store = grid.getStore();
		var form = win.down('form');
		var values = form.getValues();
				
		if(form.isValid() == true){
			var model = Ext.create('MetApp.model.Proveedores.PagoProveedoresModel');
			model.set(values);
			store.add(model);
			form.getForm().reset();	
		}
	},
	
	EditarGrilla: function(btn){
		var win = btn.up('window');
		var grid = win.down('grid');
		var store = grid.getStore();
		var selection = grid.getSelectionModel().getSelection()[0];
		var form = win.down('form');
		
		
		store.remove(selection);
		form.loadRecord(selection);		
	},
	
	BorrarGrilla: function(btn){
		var win = btn.up('window');
		var grid = win.down('grid');
		var store = grid.getStore();
		var selection = grid.getSelectionModel().getSelection()[0];
		var txtTotalAPagar = win.queryById('totalAPagar');
		txtTotalAPagar.setValue(0);
		var totalAPagar = txtTotalAPagar.getValue();
		
		store.remove(selection);
		store.each(function(rec){
			totalAPagar = totalAPagar + rec.data.importe;
		});	
		
		txtTotalAPagar.setValue(totalAPagar);
	},
	
	GuardarPago: function(btn){
		var win = btn.up('window');
		var grid = win.down('grid');
		var store = grid.getStore();
		var ccProv = this.getCCProveedores();
		var idProv = ccProv.queryById('id').getValue();
		var storeCC = ccProv.down('grid').getStore();
		var totalImputado = win.queryById('totalImputado').getValue();
		var totalAPagar = win.queryById('totalAPagar').getValue();
		var idOP = win.queryById('idOP').getValue();
		
		
		//STORE DE FACTURAS A IMPUTAR
		var storeFcImputar = win.down('grid[itemId=gridImputaFc]').getStore();
		
		var arrayRecords = [];
		var fcImputar = [];
		store.each(function(rec) {				    
	        arrayRecords.push(rec.getData());		    
		});
		
		storeFcImputar.each(function(rec){
			var data = rec.getData();
			if(data.aplicar > 0){
				fcImputar.push(data);	
			}			
		});
		
		if(totalImputado > 0){
			if(totalImputado != totalAPagar){
				Ext.Msg.show({
					title: 'Atencion',
					msg: 'El total imputado y el total a pagar deben ser iguales',
					buttons: Ext.Msg.OK,
				})
				return;
			}
		}
		
		Ext.Ajax.request({
			url: Routing.generate('mbp_proveedores_nuevoPago'),
			
			params: {
				data: Ext.JSON.encode(arrayRecords),
				fcImputar: Ext.JSON.encode(fcImputar),
				idProv: idProv,
				listener: 'nuevoPago',
				idOP: idOP
			},
			
			success: function(resp){
				var status = Ext.JSON.decode(resp.responseText);
				if(status.success == true){
					store.loadData([]);
					storeCC.load();
					storeFcImputar.load();
					win.queryById('totalImputado').reset();
					win.queryById('totalAPagar').reset();
					Ext.Msg.show({
						title: 'Atencion',
						msg: 'La orden de pago se generó con éxito',
						buttons: Ext.Msg.OK,
					});
					
					//MOSTRAMOS LA OP
					Ext.Ajax.request({
						url: Routing.generate('mbp_proveedores_reporteDetallePago'),
						
						params: {
							idOp: status.idOrdenPago
						},
						
						success: function(resp){
							var status = Ext.JSON.decode(resp.responseText);
							if(status.success == true){
								var ruta = Routing.generate('mbp_proveedores_verReporteDetallePago');
								window.open(ruta, 'location=yes,height=800,width=1200,scrollbars=yes,status=yes');
							}
						}
					});
				}
			}
		});
	},
	
	ChequeTerceros: function(btn){
		var win = Ext.widget('ChequeTerceros');		
		var grid = win.down('grid');		
		var store = grid.getStore();
		
		Ext.Ajax.request({
			url: Routing.generate('mbp_proveedores_listaChequeTerceros'),
			
			success: function(resp){
				var jsonResp = Ext.JSON.decode(resp.responseText);
				var models = [];
				var i=0;
				for(i; i<jsonResp.data.length; i++){
					models[i] = (jsonResp.data[i]);
				}
				store.loadRawData(models);
				store.suspendEvent('update');
			}
		});
	},
	
	InsertaChequeTerceros: function(btn){		
		var me = this;
		var winPagoProv = me.getPagoProveedores();
		var storePagoProv = winPagoProv.down('grid').getStore();
		var winCheques = btn.up('window');
		var storeCheques = winCheques.down('grid').getStore();
		var record = [];
		var i = 0;
		
		storeCheques.each(function(rec){			
			if(rec.get('marca') == true){
				var data = rec.getData();
				record[i] = {
					idCheque: data.id,
					formaPago: 'CHEQUE DE TERCEROS',
					numero: data.numero,
					banco: data.banco,
					importe: data.importe,
					diferido: data.diferido
				}				
				i++;	
			}			 
		});		
		storePagoProv.add(record, false);
		winCheques.close();
	},
	
	GridImputarFacturas: function(btn)
	{
		var me = this;
		var win = btn.up('window');
		var winCC = me.getCCProveedores();
		var idProv = winCC.queryById('id').getValue();
		var grid = win.down('grid[itemId=gridImputaFc]');
		var store = grid.getStore();
		var proxy = store.getProxy();
		
		proxy.setExtraParam('idProv', idProv);
		store.load({
			callback: function(){
				
			}
		});	
	},
	
	ImputaFacturas: function(gridPlugin)
	{
		var me = this;		
		var win = me.getPagoProveedores();
		var grid = win.down('grid[itemId=gridImputaFc]');
		var store = grid.getStore();
		var totalImputado = 0;
		var txtTotal = win.queryById('totalImputado');
		
		store.each(function(rec){
			totalImputado = totalImputado + rec.data.aplicar;
		});
		txtTotal.setValue(totalImputado);
	}
})





































