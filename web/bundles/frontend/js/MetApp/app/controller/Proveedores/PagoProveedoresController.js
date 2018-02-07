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
		'MetApp.view.Proveedores.FormasPagoView'
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
			'PagoProveedores combobox[itemId=formaPago]': {
				change: this.EvaluarConceptoBancario
			},
			'PagoProveedores numberfield[itemId=totalAPagar]': {
				change: this.CalcularDiferencia
			},
			'PagoProveedores numberfield[itemId=totalImputado]': {
				change: this.CalcularDiferencia
			},						
			'#tbFormasPago': {
				click: this.AddFormasPagoView
			},
			'FormasPagoView button[itemId=btnNew]': {
				click: this.NuevaFormaPago
			},
			'FormasPagoView button[itemId=btnSave]': {
				click: this.GuardarFormaPago
			},
			'FormasPagoView button[itemId=btnEdit]': {
				click: this.EditarFormaPago
			},
			'FormasPagoView button[itemId=btnDelete]': {
				click: this.EliminarFormaPago
			},
		});
	},
	
	InsertaEnGrilla: function(btn){
		var me = this;
		var win = btn.up('window');
		var grid = win.down('grid');
		var store = grid.getStore();
		var form = win.down('form');
		var values = form.getValues();
				
		if(form.isValid() == true){
			var model = Ext.create('MetApp.model.Proveedores.PagoProveedoresModel');
			
			//VALIDAMOS SI ES UN CHEQUE PROPIO O TRANSFERENCIA DEBE ASOCIARSE UN BANCO
			if(values.conceptoBancario == true){
				if(values.banco==""){
					Ext.Msg.show({
					     title:'Atencion',
					     msg: 'Debe seleccionar un banco para este concepto',
					     buttons: Ext.Msg.OK,
					     icon: Ext.Msg.ALERT
					});
					return;
				}
			}
			
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
		var dif = win.queryById('diferencia').getValue();
		
		
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
		
		if(dif != 0){
			Ext.Msg.show({
				title: 'Atencion',
				msg: 'El total imputado no coincide con el total a pagar. Desea guardar de todas maneras?',
				buttons: Ext.Msg.YESNO,
				fn: function(resp){
					console.log(resp);
					if(resp == 'yes'){
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
					}
				}
			})
			return;			
		}				
	},
	
	ChequeTerceros: function(btn){
		var me = this;
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
		
		var btnAceptar = win.queryById('aceptar');
		btnAceptar.on('click', function(){			
			var winPagoProv = me.getPagoProveedores();
			var storePagoProv = winPagoProv.down('grid').getStore();
			var storeCheques = win.down('grid').getStore();
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
			win.close();
		})
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
		
		//actualizamos el calculo de retencion
		var gridPagos = win.down('grid[itemId=gridPP]');
		var storePagos = gridPagos.getStore();
		var retencionIIBB = storePagos.findRecord('retencionIIBB', true);
		var ccProv = this.getCCProveedores();
		var idProv = ccProv.queryById('id').getValue();
		
		if(retencionIIBB != null){
			Ext.Ajax.request({
				url: Routing.generate('mbp_proveedores_verificarRetencion'),
				
				params: {
					totalImputado: totalImputado,
					idProv: idProv
				},
				
				success: function(resp){
					var decodeResp = Ext.JSON.decode(resp.responseText);
					console.log(decodeResp);
					var rec = storePagos.findRecord('retencionIIBB', true);
					if(decodeResp.aplicaRetencion == true && decodeResp.retencion > 0){
						
						rec.set('importe', decodeResp.retencion);
					}else{
						//storePagos.remove(rec);
					}
					
					me.CalcularDiferencia(win.queryById('totalImputado'));
				}
			})
		}
	},
	
	EvaluarConceptoBancario: function(field){
		var win = field.up('window');
		var store = field.getStore();
		var rec = store.findRecord('descripcion', field.getValue());
		
		if(rec== null){
			return;
		}
		
		win.queryById('conceptoBancario').setValue(rec.data.conceptoBancario);
	},
	
	AddFormasPagoView: function(btn){
		var win = Ext.widget('FormasPagoView');
		win.queryById('conceptoBancario').getStore().load();
		win.down('grid').getStore().load();
	},
	
	NuevaFormaPago: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		
		form.getForm().reset();
		
		form.query('.field').forEach(function(field){
			field.setReadOnly(false);
		});
		
		form.queryById('conceptoBancario').getStore().load();
		win.queryById('btnSave').setDisabled(false);
	},
	
	GuardarFormaPago: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var grid = win.down('grid');
		var store = grid.getStore();
		var values = form.getValues();
		var botonera = win.queryById('botonera');
		
		Ext.Ajax.request({
			url: Routing.generate('mbp_proveedores_nuevaFormaPago'),
			
			params: {
				data: Ext.JSON.encode(values)
			},
			
			success: function(resp){
				var jsonResp = Ext.JSON.decode(resp.responseText);
				if(jsonResp.success == true){
					store.load();
					botonera.guardarItem(botonera);
				}
			},
			
			failure: function(btn){
				
			}
		});
	},
	
	EditarFormaPago: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var grid = win.down('grid');
		var selection = grid.getSelectionModel().getSelection()[0];
		var data = selection.getData();
		var botonera = win.queryById('botonera');
		
		botonera.editarItem(botonera);
		form.query('.field').forEach(function(field){
			field.setReadOnly(false);
		});
		
		form.queryById('id').setValue(data.id);
		form.queryById('formaPago').setValue(data.descripcion);
		
		var combo = form.queryById('conceptoBancario');
		var concepto = combo.getStore().findRecord('id', data.conceptoBancario);
		combo.select(concepto);
	},
	
	EliminarFormaPago: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var grid = win.down('grid');
		var store = grid.getStore();
		var selection = grid.getSelectionModel().getSelection()[0];
		var values = selection.getData();
		
		Ext.Ajax.request({
			url: Routing.generate('mbp_proveedores_EliminarFormaPago'),
			
			params: {
				data: Ext.JSON.encode(values)
			},
			
			success: function(resp){
				var jsonResp = Ext.JSON.decode(resp.responseText);
				if(jsonResp.success == true){
					store.load();
				}
			},
			
			failure: function(btn){
				
			}
		});
	},
	
	CalcularDiferencia: function(txt){
		var win = txt.up('window');
		var val = txt.getValue();
		var val2 = 0;
		
		console.log(txt);
		
		if(txt.itemId == 'totalAPagar'){
			val2 = win.queryById('totalImputado').getValue();
		}else{
			val2 = win.queryById('totalAPagar').getValue();
		}
		
		win.queryById('diferencia').setValue(val - val2);
	}
})





































