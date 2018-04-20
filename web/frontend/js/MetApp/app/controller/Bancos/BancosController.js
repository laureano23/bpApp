Ext.define('MetApp.controller.Bancos.BancosController',{
	extend: 'Ext.app.Controller',	
	views: [
		'MetApp.view.Bancos.FormBancosView',
		'MetApp.view.Bancos.ConceptosBancoView',
		'MetApp.view.Bancos.MovimientosBancoView',
		'MetApp.view.CCProveedores.ChequeTerceros'
		
	],
	stores: [
		'MetApp.store.Personal.BancosStore',
		'MetApp.store.Bancos.ConceptosBancoStore',
		'MetApp.store.Bancos.MovimientosBancoStore',
		'MetApp.store.Proveedores.GridChequeTercerosStore',
		'MetApp.store.Bancos.CuentasBancoStore'
	],
	
	refs:[
	],
	
	init: function(){
		var me = this;
		
				
		me.control({
				'#tbBancos': {
					click: this.NewWinBancos
				},
				'FormBancosView combo[itemId=banco]': {
					change: this.DatosBancarios
				},
				'FormBancosView button[itemId=btnNew]': {
					click: this.NuevoBanco
				},
				'FormBancosView button[itemId=btnSave]': {
					click: this.GuardarBanco
				},
				'FormBancosView button[itemId=btnEdit]': {
					click: this.EditarBanco
				},
				'FormBancosView button[itemId=nuevaCuenta]': {
					click: this.NuevaCuenta
				},
				'FormBancosView actioncolumn[itemId=eliminar]': {
					click: this.EliminarCuenta
				},
				'FormBancosView button[itemId=btnDelete]': {
					click: this.EliminarBanco
				},
				'#conceptoBancos': {
					click: this.FormConceptoBancos
				},
				'ConceptosBancoView button[itemId=btnNew]': {
					click: this.NuevoConcepto
				},
				'ConceptosBancoView button[itemId=btnSave]': {
					click: this.GuardarConcepto
				},
				'ConceptosBancoView button[itemId=btnEdit]': {
					click: this.EditarConcepto
				},
				'ConceptosBancoView button[itemId=btnDelete]': {
					click: this.EliminarConcepto
				},				
				'#movBancos': {
					click: this.NewMovBancos
				},
				'MovimientosBancoView button[itemId=btnNew]': {
					click: this.NuevoMovimientoBancario
				},
				'MovimientosBancoView button[itemId=insertarCbte]': {
					click: this.InsertarComprobante
				},
				'MovimientosBancoView button[itemId=btnSave]': {
					click: this.GuardarMovimiento
				},
				'MovimientosBancoView button[itemId=chequeTerceros]': {
					click: this.GrillaChequeTerceros
				},
				'MovimientosBancoView button[itemId=btnDelete]': {
					click: this.QuitarElementoDeGrilla
				},
				'MovimientosBancoView button[itemId=btnEdit]': {
					click: this.EditarElementoGrilla
				},
		});		
	},
	
	EliminarBanco: function(btn){
		var win = btn.up('window');
		var idBanco = win.queryById('banco').getValue();
		
		console.log(idBanco);
		Ext.Msg.show({
			title: 'Atención',
			msg: 'Desea eliminar el banco?',
			buttons: Ext.Msg.YESNO,
			fn: function(resp){
				if(resp == 'yes'){
					Ext.Ajax.request({
						url: Routing.generate('mbp_bancos_EliminarBanco'),
						
						params: {
							idBanco: idBanco
						},
						
						success: function(resp){
							var form = win.down('form');
							form.getForm().reset();
							var storeBancos = win.queryById('banco').getStore();
							storeBancos.load();
						}
					})
				}
			}
		})
					
	},
	
	EliminarCuenta: function(grid, colIndex, rowIndex){
		var selection = grid.getStore().getAt(rowIndex);
		console.log(selection);
		
		Ext.Ajax.request({
			url: Routing.generate('mbp_bancos_EliminarCuenta'),
			
			params: {
				idCuenta: selection.data.idCuenta
			},
			
			success: function(resp){
				var store = grid.getStore();
				store.remove(selection);
			}
		})
	},
	
	NuevaCuenta: function(btn){
		var win = btn.up('window');
		var form = win.queryById('formCuentas');
		
		if(!form.isValid()) return;
		
		var values = form.getForm().getValues();
		var store = win.down('grid').getStore();
		store.loadRawData(values, 1);
		form.getForm().reset();
	},
	
	NewWinBancos: function(btn){
		var win = Ext.widget('FormBancosView');
		var store = win.queryById('banco').getStore();
		
	},
	
	DatosBancarios: function(combo){
		var form = combo.up('form');
		var gridStore = form.down('grid').getStore();
		Ext.Ajax.request({
			url: Routing.generate('mbp_bancos_ListarBanco'),
			
			params: {
				idBanco: combo.getValue()
			},
			
			success: function(resp){
				var jsonResp = Ext.JSON.decode(resp.responseText);
				form.getForm().setValues(jsonResp.data[0]);
				gridStore.loadData(jsonResp.data);
			},
			
			failure: function(){
				
			}
		})
	},
	
	NuevoBanco: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var botonera = win.queryById('botonera');
		var store = win.down('grid').getStore();
		
		store.removeAll();
		
		form.getForm().reset();
		form.query('field').forEach(function(field){
			field.setReadOnly(false);
		});
		
		botonera.nuevoItem(botonera);
		
		
	},
	
	GuardarBanco: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var botonera = win.queryById('botonera');
		var values = form.getForm().getValues();
		var storeCuentas = win.down('grid').getStore();
		
		
		var cuentas = [];
		var cuenta = { cbu: '', cuentaNro: '', cuentaTipo: '' };
		storeCuentas.each(function(rec){
			
			cuenta.cbu = rec.data.cbu;
			cuenta.cuentaNro = rec.data.cuentaNro;
			cuenta.cuentaTipo = rec.data.cuentaTipo;
			cuentas.push(rec.data);
		})
		
		
		if(!form.isValid()) return;
		
		Ext.Ajax.request({
			url: Routing.generate('mbp_bancos_NuevoBanco'),
			
			params: {
				data: Ext.JSON.encode(values),
				cuentas: Ext.JSON.encode(cuentas),
			},
			
			success: function(resp){
				var jsonResp = Ext.JSON.decode(resp.responseText);
				
				if(jsonResp.success == true){
					form.query('field').forEach(function(field){
						field.setReadOnly(true);
					});
					form.queryById('banco').setReadOnly(false);
					form.queryById('banco').setValue(jsonResp.idBanco);
					var store = form.queryById('banco').getStore();
					store.load();
					botonera.resetFn(botonera);
				}
			},
			
			failure: function(resp){
				var jsonResp = Ext.JSON.decode(resp.responseText);
				form.getForm().markInvalid(jsonResp.errors);
				
			}
		});
	},
	
	EditarBanco: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var botonera = win.queryById('botonera');		
		var values = form.getForm().getValues();
		var store = win.down('grid').getStore();
		
		store.removeAll();
		
		botonera.editarItem(botonera);
		
		form.query('field').forEach(function(field){
			field.setReadOnly(false);
		});
	},
	
	FormConceptoBancos: function(btn){
		var view = Ext.widget('ConceptosBancoView');
		var grid = view.down('grid');
		var store = grid.getStore();
		
		store.load();
	},
	
	NuevoConcepto: function(btn){
		var win = btn.up('window');
		var botonera = win.queryById('botonera');
		win.query('field').forEach(function(field){
			field.setReadOnly(false);
		});
		win.queryById('concepto').focus('', 20);
		
		botonera.nuevoItem(botonera);
		
	},
	
	GuardarConcepto: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var botonera = win.queryById('botonera');
		var store = win.down('grid').getStore();
		
		Ext.Ajax.request({
			url: Routing.generate('mbp_bancos_nuevoConceptoBanco'),
			
			params: {
				id: win.queryById('id').getValue(),
				concepto: win.queryById('concepto').getValue(),
				imputaDebe: win.queryById('imputaDebe').getValue(),
			},
			
			success: function(resp){
				var jsonResp = Ext.JSON.decode(resp.responseText);
				if(jsonResp.success == true){
					botonera.guardarItem(botonera);
					store.load();
					form.getForm().reset();
					win.query('field').forEach(function(field){
						field.setReadOnly(true);
					});
					win.down('grid').getSelectionModel().deselectAll();
				}				
			}
		})
	},
	
	EditarConcepto: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var selection = win.down('grid').getSelectionModel().getSelection()[0];
		var botonera = win.queryById('botonera');
		
		form.query('field').forEach(function(field){
			field.setReadOnly(false);
		});
		form.loadRecord(selection);
		botonera.editarItem(botonera);
	},
	
	EliminarConcepto: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var selection = form.down('grid').getSelectionModel().getSelection()[0];
		var botonera = win.queryById('botonera');
		var store = win.down('grid').getStore();
		
		Ext.Msg.show({
		     title:'Atencion',
		     msg: 'Desea borrar el registro?',
		     buttons: Ext.Msg.YESNO,
		     icon: Ext.Msg.QUESTION,
		     fn: function(btn){
		     	if(btn == 'yes'){
		     		Ext.Ajax.request({
						url: Routing.generate('mbp_bancos_eliminarConceptoBanco'),
						
						params: {
							id: selection.data.id
						},
						
						success: function(resp){
							var jsonResp = Ext.JSON.decode(resp.responseText);
							if(jsonResp.success == true){
								botonera.guardarItem(botonera);
								store.load();
								form.getForm().reset();
								win.queryById('concepto').setReadOnly(true);	
							}
						}
					})	
		     	}
		     }
		});			
	},
	
	NewMovBancos: function(btn){
		var win = Ext.widget('MovimientosBancoView');
		var me = this;
		
		//HOT KEY DE LA TABLA ORDEN DE MOVIMIENTO DE BANCOS
		var map = new Ext.util.KeyMap({
		    target: win.getId(),	
		   	binding: [
		   		{
		   			key: Ext.EventObject.F5,
		   			defaultEventAction: 'preventDefault',
		   			fn: function(){ 
		   				me.GuardarMovimiento(win.queryById('btnSave'));
		   			}
		   		},
		   		{
		   			key: Ext.EventObject.F1,
		   			defaultEventAction: 'preventDefault',
		   			fn: function(){ 
		   				me.EditarElementoGrilla(win.queryById('btnEdit'));
		   			}
		   		},
		   		{
		   			key: Ext.EventObject.F3,
		   			defaultEventAction: 'preventDefault',
		   			fn: function(){
		   				me.NuevoMovimientoBancario(win.queryById('btnNew'));
		   			}
		   		},
		   		{
		   			key: Ext.EventObject.F8,
		   			defaultEventAction: 'preventDefault',
		   			fn: function(){ 
		   				me.QuitarElementoDeGrilla(win.queryById('btnDelete'));
		   			}
		   		},
		   	]
		});	
	},
	
	NuevoMovimientoBancario: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var botonera = win.queryById('botonera');
		botonera.queryById('btnSave').setDisabled(false);
		
		form.query('.field').forEach(function(c){c.setReadOnly(false);}); //HABILITO TODOS LOS CAMPOS	
		
		var cuenta = win.queryById('cuenta');
		var store = cuenta.getStore();
		
		
		cuenta.focus('', 20);
		
	},
	
	InsertarComprobante: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var values = form.getForm().getValues();
		var grid = win.down('grid');
		var store = grid.getStore();		
		var contCbte = win.queryById('contCbte');
		
		store.add(values);
		
		contCbte.query('.field').forEach(function(c){c.reset()})
		win.queryById('obsCbte').reset();
	},
	
	GuardarMovimiento: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var values = form.getForm().getValues();
		var grid = win.down('grid');
		var store = grid.getStore();
		var comprobantes = [];		
		
		store.each(function(rec){
			comprobantes.push(rec.getData());
		});
		
		if(form.isValid()){
			Ext.Ajax.request({
				url: Routing.generate('mbp_bancos_guardarMovimientoBanco'),
				
				params: {
					operacion: Ext.JSON.encode(values),
					comprobantes: Ext.JSON.encode(comprobantes)
				},
				
				success: function(resp){
					var jsonResp = Ext.JSON.decode(resp.responseText);
					if(jsonResp.success == true){
						form.getForm().reset();
						form.query('.field').forEach(function(field){
							field.setReadOnly(true);
						});
						grid.removeAll();
						Ext.Msg.show({
						    title:'Atencion',
						    msg: 'La operacion se realizo correctamente',
						    buttons: Ext.Msg.OK,
						    icon: Ext.Msg.INFO
					   });
					}
				},
				
				failure: function(resp){
					
				}
			});	
		}		
		
	},
	
	GrillaChequeTerceros: function(btn){
		var winMovimientos = btn.up('window');
		var gridMovimientos = winMovimientos.down('grid');
		var storeMovimientos = gridMovimientos.getStore();
		var win = Ext.widget('ChequeTerceros');
		var gridCheques = win.down('grid');
		var storeCheques = gridCheques.getStore();
		
		Ext.Ajax.request({
			url: Routing.generate('mbp_proveedores_listaChequeTerceros'),
			
			success: function(resp){
				var jsonResp = Ext.JSON.decode(resp.responseText);
				storeCheques.loadRawData(jsonResp.data);
				storeCheques.suspendEvent('update');
			}
		});
		
		var btnAceptar = win.queryById('aceptar');
		
		btnAceptar.on('click', function(){
			var record = [];
			var i = 0;
			storeCheques.each(function(rec){			
				if(rec.get('marca') == true){
					var data = rec.getData();
					record[i] = {
						idChequeTerceros: data.id,
						numCbte: data.numero,
						banco: data.banco,
						importe: data.importe,
						fechaDiferida: data.diferido
					}				
					i++;	
				}			 
			});	
			
			storeMovimientos.add(record);
			win.close();
		});		
	},
	
	QuitarElementoDeGrilla: function(btn){
		var win = btn.up('window');
		var grid = win.down('grid');
		var store = grid.getStore();
		var selection = grid.getSelectionModel().getSelection()[0];
		
		store.remove(selection);
	},
	
	EditarElementoGrilla: function(btn){
		var win = btn.up('window');
		var grid = win.down('grid');
		var store = grid.getStore();
		var selection = grid.getSelectionModel().getSelection()[0];
		
		if(!selection){
			Ext.Msg.show({
			    title:'Atencion',
			    msg: 'Debe seleccionar primero un elemento de la grilla',
			    buttons: Ext.Msg.OK,
			    icon: Ext.Msg.ALERT
		   });
		   return;
		}
		
		if(selection.data.idChequeTerceros!=""){
			Ext.Msg.show({
			    title:'Atencion',
			    msg: 'No se puede editar un cheque de terceros',
			    buttons: Ext.Msg.OK,
			    icon: Ext.Msg.ALERT
		   });
		   return;
		}
		
		var form = win.down('form');
		form.loadRecord(selection);
		store.remove(selection);
	}
});










