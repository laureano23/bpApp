Ext.define('MetApp.controller.Clientes.CCClientesController',{
	extend: 'Ext.app.Controller',
	requires: [
		'MetApp.resources.ux.ParametersSingleton'
	],
	stores: [
		'MetApp.store.Finanzas.CCClientesStore',
		'MetApp.store.Finanzas.GrillaFacturacionStore',
		'MetApp.store.Finanzas.TiposPagoStore',
		'MetApp.store.Finanzas.GridImputaFcStore',
		'MetApp.store.Articulos.RemitosPendientesStore',
	],
	views: [
		'CCClientes.CCClientes',
		'MetApp.view.CCClientes.Facturacion',
		'MetApp.view.CCClientes.Cobranza',
		'MetApp.resources.ux.MailerWindow',
		'Articulos.Stock.Remitos.RemitosPendientesView',
		'MetApp.view.CCProveedores.BalanceView',
		'MetApp.view.CCClientes.NotasCCView',
		'MetApp.view.CCClientes.ImputarFacturaView'
	],
	refs:[
		{
			ref:'CCClientes',
			selector: 'CCClientes'
		},
		{
			ref:'facturacion',
			selector: 'facturacion'
		}
	],
	
	init: function(){
		var me = this;	
		
		me.control({
			'#tbCCClientes': {
				click: this.AddCCClientesTb
			},			
			'#CCClientes button[itemId=buscaCliente]': {
				click: this.AddTablaClientes
			},
			'CCClientes button[itemId=balance]': {
				click: this.CrearBalance
			},
			'CCClientes button[itemId=nuevaFc]': {
				click: this.AddNuevoFactura
			},
			'CCClientes button[itemId=nuevoCobro]': {
				click: this.AddNuevoCobro
			},
			'CCClientes button[itemId=notas]': {
				click: this.NuevaNota
			},
			'CCClientes actioncolumn[itemId=detalle]': {
				click: this.DetalleComprobante
			},
			'CCClientes actioncolumn[itemId=mail]': {
				click: this.MailComprobante
			},
			'CCClientes actioncolumn[itemId=eliminar]': {
				click: this.EliminarComprobante
			},
			'CCClientes button[itemId=detalleCliente]': {
				click: this.DetalleCliente
			},
			'MailerWindow button[itemId=enviarMail]': {
				click: this.EnviarMail
			},
			'facturacion button[itemId=buscarArt]': {
				click: this.BuscaArticulos
			},
			'facturacion button[itemId=btnNew]': {
				click: this.InsertaArticulo
			},
			'facturacion button[itemId=btnEdit]': {
				click: this.EditaArticulo
			},
			'facturacion button[itemId=btnDelete]': {
				click: this.BorraArticulo
			},
			'facturacion button[itemId=btnSave]': {
				click: this.GuardarFactura
			},
			'facturacion button[itemId=btnRemito]': {
				click: this.RemitosPendientes
			},
			'facturacion textfield[itemId=descuentoFijo]': {
				change: this.CalcularDescuento
			},
			'facturacion textfield[itemId=percepcionIIBB]': {
				change: this.PercepcionManual
			},
			'facturacion checkbox[itemId=sinIva]': {
				change: this.SacarIVA
			},
			'facturacion combobox[itemId=tipo]': {
				change: this.AsociarFacturaView
			},
			'cobranza button[itemId=btnEdit]': {
				click: this.EditaItemCob
			},
			'cobranza button[itemId=btnNew]': {
				click: this.NuevoItemCob
			},
			'cobranza button[itemId=btnDelete]': {
				click: this.EliminaItemCob
			},
			'cobranza button[itemId=btnSave]': {
				click: this.GuardarCobranza
			},
			'cobranza numberfield[itemId=reciboNum]': {
				blur: this.ValidarRecibo
			},
			'cobranza button[itemId=imputar]': {
				click: this.ImputarFacturas
			},
			'cobranza combobox[itemId=formaPago]': {
				select: this.SetReadOnlyCuenta
			},
			'ImputarFacturaView button[itemId=insertar]': {
				click: this.InsertarFcAsociada
			},
			'ImputarFacturaView checkcolumn': {
				checkchange: this.CalcularTotal
			},
		});
	},

	CalcularTotal: function(check, rowIndex, checked){
		var win=check.up('window');
		var store=win.down('grid').getStore();
		var rec=store.getAt(rowIndex);
		var total=win.queryById('total');

		if(checked){
			total.setValue(total.getValue()+rec.data.haber);
		}else{
			total.setValue(total.getValue()-rec.data.haber);
		}
	},

	InsertarFcAsociada: function(btn){
		
		var winAsociacion=btn.up('window');
		var grid=winAsociacion.down('grid');
		var store=grid.getStore();
		var recs=[];
		var winFact=this.getFacturacion();
		var txtFcAsociada=winFact.queryById('compAsociados');
		txtFcAsociada.reset();

		store.each(function(rec){
			if(rec.data.asociado){
				if(txtFcAsociada.getValue()==""){
					txtFcAsociada.setValue(rec.data.id);
				}else{
					txtFcAsociada.setValue(txtFcAsociada.getValue()+", "+rec.data.id);
				}
			}			
		});
		winAsociacion.close();
	},

	AsociarFacturaView: function(combo){
		var rec=combo.getStore().findRecord('id', combo.getValue());
		if(rec.data.esNotaCredito){
			var win=Ext.widget('ImputarFacturaView');
			var store=win.down('grid').getStore();
			var winCC = this.getCCClientes();

			Ext.Ajax.request({
				url: Routing.generate('mbp_CCClientes_listarFacturasParaAsociar'),
	
				params: {
					idCliente: winCC.queryById('id').getValue()
				},
	
				success: function(resp){
					var jsonResp = Ext.JSON.decode(resp.responseText);
					store.loadData(jsonResp.items);
				},
	
				failure: function(resp){
	
				}
			});

		}
	},

	PercepcionManual: function(txt){
		var win=txt.up('window');
		var subTotal=win.queryById('subTotal');
		var iva=win.queryById('iva');
		var total=win.queryById('total');

		total.setValue(subTotal.getValue() + iva.getValue() + txt.getValue());
	},
	
	DetalleCliente: function(btn){
		var winCC=btn.up('window');
		var rec=winCC.down('form').getForm().getRecord();
		var win=Ext.widget('clientestb');
		
		win.down('form').loadRecord(rec);
	},
	
	NuevaNota: function(btn){
		var win=btn.up('window');
		var winNota=Ext.widget('NotasCCView');
		var btn=winNota.queryById('guardar');
		var rec=win.down('form').getForm().getRecord();
		var notas=winNota.queryById('notasCC');
		
		notas.setValue(rec.data.notasCC);
		btn.on('click', function(btn){
			var form=winNota.down('form');
			var values=form.getForm().getValues();
			rec.set(values);
			rec.save({
				callback: function (records, operation, success) {
	                if (operation.success) {
	                    Ext.Msg.alert('Info', "Guardado exitosamente!");
	                }
	            }	
			})			
		});
	},
	
	SacarIVA: function(check){
		var win = check.up('window');
		var val = win.queryById('sinIva').getValue();
		var subTotal = win.queryById('subTotal');
		var iva = win.queryById('iva');
		var total = win.queryById('total');
				
		if(val == true){
			iva.setValue(0);
			total.setValue(subTotal.getValue());
		}else{
			iva.setValue(subTotal.getValue() * MetApp.resources.ux.ParametersSingleton.getIva());
			total.setValue(subTotal.getValue() + iva.getValue());
		}
			
	},
	
	CalcularDescuento: function(txt){
		var win = txt.up('window');
		var desc = txt.getValue();		
		var subTotal = win.queryById('subTotal');
		var iva = win.queryById('iva');
		var percepcion = win.queryById('percepcionIIBB');
		var total = win.queryById('total');
		var store = win.down('grid').getStore();
		var aux=0;
		var sub=0;
		
		store.each(function(rec){
			rec.data.parcial = rec.data.cantidad * rec.data.precio;
			var data = rec.getData();									
			sub = data.parcial + aux;
			aux = sub;
		});
		
		subTotal.setValue(sub * (100-desc)/100);
		iva.setValue(subTotal.getValue() * MetApp.resources.ux.ParametersSingleton.getIva());
		total.setValue(iva.getValue() + subTotal.getValue() + percepcion.getValue());
	},
	
		
	CrearBalance: function(btn){
		var win = Ext.widget('BalanceView');
		var form = win.down('form');
		var ccView = btn.up('window');
		var idCliente = ccView.queryById('id').getValue();
		var btnSave = win.queryById('guardar');
		
		
		btnSave.on('click', function(){
			if(!form.isValid()) return;
			var values = form.getForm().getValues();
			Ext.Ajax.request({
				url: Routing.generate('mbp_CCClientes_CrearBalance'),
				
				params: {
					neto: values.neto,
					obs: values.observaciones,
					idCliente: idCliente,
					fecha: values.fecha
				},
				
				success: function(resp){
					var jsonResp = Ext.JSON.decode(resp.responseText);
					if(jsonResp.success == true){
						win.close();
						ccView.down('grid').getStore().load();
					}
				}
			})
		})
	},
	
	SetReadOnlyCuenta: function(combo, rec){
		var win = combo.up('window');
		var cuenta = win.queryById('cuenta');
		if(rec[0].data.depositaEnCuenta == 1){
			cuenta.setReadOnly(false);
			cuenta.allowBlank = false;
		}else{
			cuenta.setReadOnly(true);
			cuenta.allowBlank = true;
		}
	},
		
	AddCCClientesTb: function(btn){
		var win = Ext.widget('CCClientes');
		var grid = win.down('grid');
		var model = grid.getView();
		var selectionModel = grid.getSelectionModel();
		var store = grid.getStore();
		var row;
		store.loadData([], false);
		store.on('load', function(st, rec, succ){
			if(succ == true){
				row = selectionModel.select(0);
				grid.getView().scrollBy(0, 999999, true);				
			}
		});
		
		model.on('highlightitem', function(view, node){
			var domEl = new Ext.dom.Element(node);
			selectionModel.deselectAll();
			selectionModel.select(domEl.dom.rowIndex);			
		});
		
		
		
		win.queryById('buscaCliente').focus('', 20);
	},
	
	AddTablaClientes: function(btn){
		var win = btn.up('window');
		var grid = win.down('grid');
				
		var gridClientes = Ext.widget('clientesSearchGrid');
		var grClientes = gridClientes.down('grid');
		var store = grClientes.getStore();
		store.load();
		var btnInsert = gridClientes.queryById('insertCliente');
		
		btnInsert.on('click', function(){
			var gridC = gridClientes.down('grid');
			var selection = gridC.getSelectionModel().getSelection()[0];
			var data  = selection.getData();
			var formDatosCliente = win.down('form');
			
			formDatosCliente.loadRecord(selection);
			
			var store = grid.getStore();
			var proxy = store.getProxy();
			
			proxy.setExtraParam('idCliente', selection.data.id);
			store.load();
			gridClientes.close();
			win.queryById('btnCnt').setDisabled(false);
		});
	},
	
	AddNuevoFactura: function(btn){

		var cc = btn.up('window');
		var descuento = cc.queryById('descuentoFijo').getValue();
		var me = this;
		var winFact = Ext.create('MetApp.view.CCClientes.Facturacion');
		var store = winFact.down('grid').getStore();	
		var comboTipo = winFact.queryById('tipo');
		var storeTipo = comboTipo.getStore();
		
		
		storeTipo.load({
			callback: function(){
				comboTipo.select(1);		
			}
		})
		
		winFact.queryById('descuentoFijo').setValue(descuento);
		var subTotal=0;
		
		store.loadData([], false);
						
		/* ESCUCHA CAMBIOS DEL STORE PARA ACTUALIZAR SUBTOTALES Y TOTAL */
		store.on('datachanged', function(store){			
			var win = me.getFacturacion();
			var winCC = me.getCCClientes();
			console.log(winCC);
			var form=win.queryById('datosFc');
			var aux = 0;
			var fieldSub = win.queryById('subTotal');
			var fieldIva = win.queryById('iva');
			var fieldTotal = win.queryById('total');
			var totalIva=0;
			var descuento = win.queryById('descuentoFijo').getValue();
			if(store.data.items.length == 0){
				fieldSub.setValue(0);
				fieldIva.setValue(0);
				fieldTotal.setValue(0);
			}else{				
				store.each(function(rec){					
					rec.data.parcial = rec.data.cantidad * rec.data.precio;
					var data = rec.getData();									
					subTotal = data.parcial + aux;
					aux = subTotal;
					if(rec.data.ivaGrabado){
						totalIva+=MetApp.resources.ux.ParametersSingleton.getIva()*rec.data.cantidad * rec.data.precio * (1-descuento/100);
					}
				});
				
				//restamos el descuento				
				subTotal -= subTotal * descuento / 100;

				fieldSub.setValue(subTotal);
				fieldIva.setValue(totalIva);

				//calculamos percepcion
				form.getForm().submit({
					url: Routing.generate('mbp_CCClientes_calcularPercepcion'),
					params: {
						subTotal: subTotal,
						clienteId: winCC.queryById('id').getValue()
					},
					success: function(form, action){
						var jsonResp=Ext.JSON.decode(action.response.responseText);
						var percepcion=win.queryById('percepcionIIBB');
						percepcion.setValue(jsonResp.percepcion);

						fieldTotal.setValue(fieldSub.getValue() + fieldIva.getValue() + percepcion.getValue());
					}
				});				
			}
		});	
		
		winFact.queryById('buscarArt').focus('', 20);
	},
	
	DetalleComprobante: function(btn){				
		var win = btn.up('window');
		var grid = win.down('grid');
		var selection = grid.getSelectionModel().getSelection()[0];
		
		if(selection == undefined){
			Ext.Msg.show({
			     title:'Atencion',
			     msg: 'Seleccine el comprobante de la grilla',
			     buttons: Ext.Msg.OK,
			     icon: Ext.Msg.INFO
			});
		}
		var values = selection.getData();
		var reporte = '';
		if(values.idF > 0){
			reporte = Routing.generate('mbp_CCClientes_generateFc');
		}else{
			reporte = Routing.generate('mbp_CCClientes_generateCobranza');
		}
		
		var myMask = new Ext.LoadMask(grid, {msg:"Cargando..."});
		myMask.show();
		
		var idCliente = win.queryById('id').getValue();
		var ruta;
		if(values.idF > 0){		
			ruta = Routing.generate('mbp_CCClientes_verFc');
			Ext.Ajax.request({
				url: reporte,
				
				params: {
					tipo: values.tipo,
					idCliente: idCliente,
					idFactura: values.idF
				},
				
				success: function(resp){
					window.open(ruta, 'location=yes,height=800,width=1200,scrollbars=yes,status=yes');
					myMask.hide();	
				},
				failure: function(resp){
					myMask.hide();
				}
			});	
		}else{			
			ruta = Routing.generate('mbp_CCClientes_verCobranza');			
			Ext.Ajax.request({
				url: reporte,
				
				params: {
					tipo: values.tipo,
					idCliente: idCliente,
					idCobranza: values.idCob
				},
				
				success: function(resp){
					window.open(ruta, 'location=yes,height=800,width=1200,scrollbars=yes,status=yes');
					myMask.hide();	
				},
				failure: function(resp){
					myMask.hide();
				}
			});
		}
	},
	
	MailComprobante: function(btn){
		var win = Ext.create('MetApp.resources.ux.MailerWindow');
		var ccView = this.getCCClientes();
		var grid = ccView.down('grid');
		
		var selection = grid.getSelectionModel().getSelection()[0];
		var adjunto = win.queryById('adjunto');
		
		//SETEO ADJUNTO
		adjunto.setValue(selection.data.concepto);
	},
	
	EliminarComprobante: function(btn){
		var me = this;
		var msg = Ext.Msg.show({
		     title:'Atencion',
		     msg: 'Esta por eliminar un comprobante, esta seguro?',
		     buttons: Ext.Msg.YESNO,
		     icon: Ext.Msg.ALERT,
		     fn: function(btn){
		     	if(btn == 'yes'){
		     		var ccView = me.getCCClientes();
					var grid = ccView.down('grid');
										
					var selection = grid.getSelectionModel().getSelection()[0];
					var data = selection.getData();
					
					Ext.Ajax.request({
						url: Routing.generate('mbp_CCClientes_EliminarComprobante'),
						
						params: {
							idCobranza: data.idCob,
							idBalance: data.idF
						},
						
						success: function(resp){
							var jsonResp = Ext.JSON.decode(resp.responseText);
							if(jsonResp.success == true){
								var store = grid.getStore();
								//store.remove(selection);
								store.load();	
							}
						}	
					});
		     	}	     	
		     }
		});
	},
	
	EnviarMail: function(btn){
		var form = btn.up('form');
		
		if(!form.isValid()) return;
		
		var values = form.getValues();		
		var ccView = this.getCCClientes();
		var grid = ccView.down('grid');
		var selection = grid.getSelectionModel().getSelection()[0];
		
		//DATOS PARA GENERAR COMPROBANTE
		var tipo = selection.data.tipo;
		var idCliente = ccView.queryById('id').getValue();
		var idFactura;
		var idCobranza;
		//AGREGO DATOS 
		values.tipo = tipo;
		values.idCliente = idCliente;
		values.idFactura = selection.data.idF;
		values.idCobranza = selection.data.idCob;
		
		var myMask = new Ext.LoadMask(form, {msg:"Enviando..."});
		myMask.show();
		
		Ext.Ajax.request({
			url: Routing.generate('mbp_CCClientes_generateFc'),
			
			params: values,
			
			failure: function(resp){
				myMask.hide();
			},
			
			success: function(resp){
				Ext.Ajax.request({
					url: Routing.generate('mbp_CCClientes_MailComprobante'),
					
					params: values,
					
					success: function(resp){
						myMask.hide();
					},
					
					failure: function(resp){
						myMask.hide();
					}
				});
			}
		})
		
				
	},
		
	BuscaArticulos: function(btn){
		var winFacturacion = btn.up('window');
		var winArt = Ext.create('MetApp.view.Articulos.WinArticuloSearch');		
		var btnInsert = winArt.queryById('insertArt');
		
		btnInsert.on('click', function(){
			var grid = winArt.down('grid');
			var selection = grid.getSelectionModel().getSelection()[0];
			var formArt = winFacturacion.queryById('formArticulos');
			var monedaFc = winFacturacion.queryById('moneda').getValue();
			
			var precio=selection.data.precio;
			if(selection.data.monedaPrecio == "true" && monedaFc==0){
				precio = selection.data.precio*winFacturacion.queryById('tipoCambio').getValue();
			}
			
			formArt.loadRecord(selection);
			formArt.queryById('precio').setValue(precio);
			winArt.close();
			
			if(selection.data.codigo == 'ZZZ'){
				winFacturacion.queryById('descripcion').focus(true, 50);
			}else{
				winFacturacion.queryById('cantidad').focus(true, 50);	
			}			
			
		});
	},
	
	InsertaArticulo: function(btn){
		var winFacturacion = btn.up('window');
		var grid = winFacturacion.down('grid');
		var store = grid.getStore();		
		var record = Ext.create('MetApp.model.Finanzas.GrillaFacturacionModel');
		var formArt = winFacturacion.queryById('formArticulos');
		var articulo = formArt.getValues('','','',true);		
						
		record.set('codigo', articulo.codigo);
		record.set('descripcion', articulo.descripcion);
		record.set('cantidad', articulo.cantidad);
		
				
		record.set('precio', articulo.precio);
		record.set('costo', articulo.costo);
		record.set('parcial', articulo.precio * articulo.cantidad);
			
		store.add(record);
		formArt.getForm().reset();
		
		winFacturacion.queryById('buscarArt').focus();
	},
	
	EditaArticulo: function(btn){
		var win = btn.up('window');
		var grid = win.down('grid');
		var store = grid.getStore();
		var selection = grid.getSelectionModel().getSelection()[0];
		if(!selection){
			Ext.Msg.show({
			     title:'Atencion',
			     msg: 'Debe seleccionar un elemento de la grilla',
			     buttons: Ext.Msg.OK,
			     icon: Ext.Msg.WARNING
			});
			return;
		}
		var form = win.down('form');
		store.remove(selection);
		form.loadRecord(selection);
		win.queryById('codigo').setValue(selection.data.codigo);
		win.queryById('descripcion').setValue(selection.data.descripcion);
		win.queryById('cantidad').setValue(selection.data.cantidad);
		win.queryById('precio').setValue(selection.data.precio);
		win.queryById('costo').setValue(selection.data.costo);
	},
	
	BorraArticulo: function(btn){
		var win = btn.up('window');
		var grid = win.down('grid');
		var store = grid.getStore();
		var selection = grid.getSelectionModel().getSelection()[0];
		if(!selection){
			Ext.Msg.show({
			     title:'Atencion',
			     msg: 'Debe seleccionar un elemento de la grilla',
			     buttons: Ext.Msg.OK,
			     icon: Ext.Msg.WARNING
			});
			return;
		}
		store.remove(selection);
	},
	
	GuardarFactura: function(btn){
		var me = this;
		var win = btn.up('window');
		var grid = win.down('grid');
		var store = grid.getStore();
		var arrayRecords = [];
		var formDatosFc = win.down('form[itemId=datosFc]')
		var valuesDatosFc = formDatosFc.getValues();
		var ccView = this.getCCClientes();

				
		if(store.data.items.length == 0){
			Ext.Msg.show({
			     title:'Atencion',
			     msg: 'No hay ningun articulo cargado en la lista',
			     buttons: Ext.Msg.OK,
			     icon: Ext.Msg.ALERT
			});
			return;
		}
		
		
		
		if(formDatosFc.isValid() == false){
			Ext.Msg.show({
			     title:'Atencion',
			     msg: 'Debe cargar la fecha de facturacion',
			     buttons: Ext.Msg.OK,
			     icon: Ext.Msg.ALERT
			});
			return;
		}
				
		store.each(function(rec) {				    
	        arrayRecords.push(rec.getData());		    
		});
		
		var comboMoneda = win.queryById('moneda');
		var moneda = comboMoneda.getValue();
		valuesDatosFc.idCliente = ccView.queryById('id').getValue();
		var descuento = win.queryById('descuentoFijo').getValue();
		
		var myMask = new Ext.LoadMask(grid, {msg:"Cargando..."});
		myMask.show();
		
		if(moneda == 1){
			Ext.Msg.prompt('Tipo de cambio', 'Ingrese tipo de cambio', function(btn, value){
				if(btn == 'ok'){
					valuesDatosFc.tipoCambio = value;
				}else{ 
					myMask.hide();
					return; 
				}
				
				Ext.Ajax.request({
					url: Routing.generate('mbp_CCClientes_guardarFc'),
					
					params: {
						data: Ext.JSON.encode(arrayRecords),
						fcData: Ext.JSON.encode(valuesDatosFc),	
						descuentoFijo: descuento,
						percepcion: win.queryById('percepcionIIBB').getValue()
					},
					
					
					success: function(resp){
						var decodeResp = Ext.JSON.decode(resp.responseText);
						if(decodeResp.success == true){
							store.removeAll();
							var grid = ccView.down('grid');
							var storeCC = grid.getStore();
							storeCC.load({
								callback: function(){
									var lastRecord = storeCC.count() - 1;						
									grid.getSelectionModel().select(lastRecord);
									win.close();
									var btnCC = ccView.queryById('nuevaFc');
									me.DetalleComprobante(btnCC);
								}
							});
							
						}
						myMask.hide();
					},
					
					failure: function(resp){
						var decodeResp = Ext.JSON.decode(resp.responseText);
						myMask.hide();
						Ext.Msg.show({
						    title:'Atencion',
						    msg: 'Codigo: '+decodeResp.code+'<br/>Error: '+decodeResp.msg,
						    buttons: Ext.Msg.OK,
						    icon: Ext.Msg.INFO
						});
					}
				});
			})
		}else{
			Ext.Ajax.request({
				url: Routing.generate('mbp_CCClientes_guardarFc'),
				
				params: {
					data: Ext.JSON.encode(arrayRecords),
					fcData: Ext.JSON.encode(valuesDatosFc),	
					descuentoFijo: descuento,
					percepcion: win.queryById('percepcionIIBB').getValue()
				},
				
				
				success: function(resp){
					var decodeResp = Ext.JSON.decode(resp.responseText);
					if(decodeResp.success == true){
						store.removeAll();
						var grid = ccView.down('grid');
						var storeCC = grid.getStore();
						storeCC.load({
							callback: function(){
								var lastRecord = storeCC.count() - 1;						
								grid.getSelectionModel().select(lastRecord);
								win.close();
								var btnCC = ccView.queryById('nuevaFc');
								me.DetalleComprobante(btnCC);
							}
						});
						
					}
					myMask.hide();
				},
				
				failure: function(resp){
					var decodeResp = Ext.JSON.decode(resp.responseText);					
					myMask.hide();
					Ext.Msg.show({
					    title:'Atencion',
					    msg: 'Codigo: '+decodeResp.code+'<br/>Error: '+decodeResp.msg,
					    buttons: Ext.Msg.OK,
					    icon: Ext.Msg.INFO
					});
				}
			});
		}
	},
	
	RemitosPendientes: function(btn){
		var viewFacturacion = btn.up('window');
		var ccView = this.getCCClientes();
		var view = Ext.widget('RemitosPendientesView');
		var tc = viewFacturacion.queryById('tipoCambio').getValue();
		
		var grid = view.down('grid');
		var store = grid.getStore();
		
		Ext.Ajax.request({
			url: Routing.generate('mbp_articulos_remitosPendientesFacturacion'),
			
			params: {
				idCliente: ccView.queryById('id').getValue() 
			},
			
			success: function(resp){
				var jsonResp = Ext.JSON.decode(resp.responseText);
				store.loadRawData(jsonResp);
			},
			
			failure: function(resp){
				
			}
		});
		
		view.down('button').on('click', function(){
			var selection = grid.getStore().findRecord('facturado', true, 0, true);
			var gridFacturacion = viewFacturacion.down('grid');
			var storeFacturacion = gridFacturacion.getStore();
			var data={'items': []};
			store.each(function(rec){
				if(rec.data.facturado == true){
					if(tc > 0 && rec.data.monedaPrecio == true){ //si el tc es > 0 y el articulo está en U$D
						rec.data.precio = rec.data.precio * tc; 
					}
					rec.data.parcial = rec.data.cantidad * rec.data.precio;
					data.items.push(rec.data);	
				}				
			});
			storeFacturacion.loadRawData(data);
			
			view.close();
		});
		
	},
	
	AddNuevoCobro: function(btn){
		var winCobranza = Ext.create('MetApp.view.CCClientes.Cobranza');
		var grid = winCobranza.down('grid');
		var store = grid.getStore();
		var me = this;
		var total = winCobranza.queryById('totalCobrar');
		var totalImput = winCobranza.queryById('totalImputado');
		
		winCobranza.queryById('reciboNum').focus('', 20);
		
		//HOT KEY DE LA TABLA FACTURACION
		var map = new Ext.util.KeyMap({
		    target: winCobranza.getId(),	
		   	binding: [
		   		{
		   			key: Ext.EventObject.F1,
		   			defaultEventAction: 'preventDefault',
		   			fn: function(){ 
		   				me.EditaItemCob(winCobranza.queryById('btnEdit'));
		   			}
		   		},
		   		{
		   			key: Ext.EventObject.F3,
		   			defaultEventAction: 'preventDefault',
		   			fn: function(){
		   				me.NuevoItemCob(winCobranza.queryById('btnNew'));
		   			}
		   		},
		   		{
		   			key: Ext.EventObject.F8,
		   			defaultEventAction: 'preventDefault',
		   			fn: function(){ 
		   				me.EliminaItemCob(winCobranza.queryById('btnDelete'));
		   			}
		   		},
		   		{
		   			key: Ext.EventObject.F5,
		   			defaultEventAction: 'preventDefault',
		   			fn: function(){ 
		   				me.GuardarCobranza(winCobranza.queryById('guardar'));
		   			}
		   		},
		   	]
		});	

		//LISTENER PARA CALCULAR EL IMPORTE TOTAL A COBRAR
		store.on('datachanged', function(st, opts){
			var totalAPagar = 0;

			store.each(function(rec){
				totalAPagar = totalAPagar + rec.data.importe;
			});	
			total.setValue(totalAPagar);
		});

		var gridImputaFc = winCobranza.queryById('gridImputaFc');
		var storeImputaFc = gridImputaFc.getStore();

		storeImputaFc.removeAll(); //limpiamos el store

		storeImputaFc.on('update', function(st, opts){ 
			var totalAImputar = 0;

			storeImputaFc.each(function(rec){
				totalAImputar = totalAImputar + rec.data.aplicar;
			});	
			totalImput.setValue(totalAImputar);
		});
		
	},
	
	NuevoItemCob: function(btn){
		var win = btn.up('window');
		var grid = win.down('grid');
		var store = grid.getStore();
		var formItemCob = win.down('form[itemId=formItemCob]');
		var values = formItemCob.getValues();
		
		if(formItemCob.isValid() == true){
			var itemCob = Ext.create('MetApp.model.Finanzas.GrillaPagosModel');
			itemCob.set(values);
	
			store.add(itemCob);
			formItemCob.getForm().reset();	
		}		
	},
	
	EditaItemCob: function(btn){
		var win = btn.up('window');
		var grid = win.down('grid');
		var formItemCob = win.down('form[itemId=formItemCob]');
		var selection = grid.getSelectionModel().getSelection()[0];
		
		formItemCob.loadRecord(selection);
		grid.getStore().remove(selection);
	},
	
	EliminaItemCob: function(btn){
		var win = btn.up('window');
		var grid = win.down('grid');
		var selection = grid.getSelectionModel().getSelection()[0];
		
		grid.getStore().remove(selection);
	},
	
	GuardarCobranza: function(btn){
		var win = btn.up('window');
		var grid = win.down('grid');
		var store = grid.getStore();
		var formDatosCob = win.down('form[itemId=formDatosCob]');
		var storeAplicados = win.queryById('gridImputaFc').getStore();
		
		var valDatosCob = formDatosCob.getValues();
		var arrayData = [];
		var aplicados = [];
		var viewCC = this.getCCClientes();
		
		
		store.each(function(rec){
			arrayData.push(rec.getData());
		});
		
		storeAplicados.each(function(rec){
			if(rec.data.aplicar > 0){
				aplicados.push(rec.getData());	
			}			
		});
		
		var countRec = store.getCount();
		
		if(countRec == 0){
			Ext.Msg.show({
			     title:'Atencion',
			     msg: 'No hay elementos cargados en la grilla',
			     buttons: Ext.Msg.OK,
			     icon: Ext.Msg.INFO
			});
		}
		
		
		if(formDatosCob.isValid() == true && countRec > 0){
			
			
			if(win.queryById('totalCobrar').getValue() != win.queryById('totalImputado').getValue()){
				
				Ext.Msg.show({
				     title:'Atencion',
				     msg: 'El total a cobrar no es igual al imputado, desea continuar? ',
				     buttons: Ext.Msg.YESNO,
				     icon: Ext.Msg.INFO,
				     fn: function(btn){
				     	if(btn == 'yes'){
				     		 Ext.Ajax.request({
								url: Routing.generate('mbp_Cobranza_NuevaCobranza'),
								
								params: {
									data: Ext.JSON.encode(arrayData),
									aplicados: Ext.JSON.encode(aplicados),
									cliente: viewCC.queryById('id').getValue(),
									datosRecibo: Ext.JSON.encode(valDatosCob)
								},
								
								success: function(resp){
									var decodeResp = Ext.JSON.decode(resp.responseText);
									if(decodeResp.success == true){
										store.removeAll();		
										storeAplicados.removeAll();			
										var storeCC = viewCC.down('grid').getStore();
										storeCC.load();
										formDatosCob.getForm().reset();
										win.queryById('totalCobrar').setValue(0);
										win.queryById('totalImputado').setValue(0);	
										
										win.down('form').getForm().reset();									
									}
								}
							});
		
				     	}else{}
				     }
				});
			}else{
				 Ext.Ajax.request({
					url: Routing.generate('mbp_Cobranza_NuevaCobranza'),
					
					params: {
						data: Ext.JSON.encode(arrayData),
						aplicados: Ext.JSON.encode(aplicados),
						cliente: viewCC.queryById('id').getValue(),
						datosRecibo: Ext.JSON.encode(valDatosCob)
					},
					
					success: function(resp){
						var decodeResp = Ext.JSON.decode(resp.responseText);
						if(decodeResp.success == true){
							store.removeAll();		
							storeAplicados.removeAll();			
							var storeCC = viewCC.down('grid').getStore();
							storeCC.load();
							formDatosCob.getForm().reset();
							win.queryById('totalCobrar').setValue(0);
							win.queryById('totalImputado').setValue(0);
							
							win.down('form').getForm().reset();	
						}
					}
				});
			}
		}
	},
	
	ValidarRecibo: function(txt){
		var reciboNum = txt.getValue();
		var win = txt.up('window');
		var ptoVta = win.queryById('ptoVta').getValue();
		var formDatosCob = win.queryById('formDatosCob');
		
		Ext.Ajax.request({
			url: Routing.generate('mbp_CCClientes_ValidarRecibo'),
			
			params: {
				reciboNum: reciboNum,
				ptoVta: ptoVta
			},
			
			success: function(resp){
				var jsonResp = Ext.JSON.decode(resp.responseText);
				if(jsonResp.success == false){
					Ext.Msg.show({
					     title:'Atencion',
					     msg: jsonResp.msg,
					     buttons: Ext.Msg.OK,
					     icon: Ext.Msg.INFO
					});
					formDatosCob.getForm().reset();
				}
			}
		});
		
	},

	ImputarFacturas: function(btn){
		var winCobranza = btn.up('window');
		var grillaImputacion = winCobranza.queryById('gridImputaFc');
		var winCC = this.getCCClientes();

		Ext.Ajax.request({
			url: Routing.generate('mbp_CCClientes_ListarFcParaImputar'),

			params: {
				idCliente: winCC.queryById('id').getValue()
			},

			success: function(resp){
				var jsonResp = Ext.JSON.decode(resp.responseText);
				grillaImputacion.getStore().loadData(jsonResp.items);
			},

			failure: function(resp){

			}
		});
	}
})





































