Ext.define('MetApp.controller.Clientes.CCClientesController',{
	extend: 'Ext.app.Controller',
	requires: [
		'MetApp.resources.ux.ParametersSingleton'
	],
	stores: [
		'MetApp.store.Finanzas.CCClientesStore',
		'MetApp.store.Finanzas.GrillaFacturacionStore',
		'MetApp.store.Finanzas.TiposPagoStore',
		'Finanzas.GridImputaFcStore'
	],
	views: [
		'CCClientes.CCClientes',
		'MetApp.view.CCClientes.Facturacion',
		'MetApp.view.CCClientes.Cobranza',
		'MetApp.resources.ux.MailerWindow'
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
			'CCClientes button[itemId=nuevaFc]': {
				click: this.AddNuevoFactura
			},
			'CCClientes button[itemId=nuevoCobro]': {
				click: this.AddNuevoCobro
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
			}
		});
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
		var me = this;
		var winFact = Ext.create('MetApp.view.CCClientes.Facturacion');
		var store = winFact.down('grid').getStore();	
		
		var subTotal=0;
		
		store.loadData([], false);
						
		/* ESCUCHA CAMBIOS DEL STORE PARA ACTUALIZAR SUBTOTALES Y TOTAL */
		store.on('datachanged', function(store){	
			var win = me.getFacturacion();
			var aux = 0;
			var fieldSub = win.queryById('subTotal');
			var fieldIva = win.queryById('iva');
			var fieldTotal = win.queryById('total');
			if(store.data.items.length == 0){
				fieldSub.setValue(0);
				fieldIva.setValue(0);
				fieldTotal.setValue(0);
			}else{
				store.each(function(rec){
					var data = rec.getData();				
					subTotal = data.parcial + aux;
					aux = subTotal;
				});
				
				fieldSub.setValue(subTotal);
				fieldIva.setValue(MetApp.resources.ux.ParametersSingleton.getIva() * fieldSub.getValue())
				fieldTotal.setValue(fieldSub.getValue() + fieldIva.getValue());
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
		
		switch(values.tipo){
			case "1":
				reporte = Routing.generate('mbp_CCClientes_generateFc');
				break;
			case "2":
				reporte = Routing.generate('mbp_CCClientes_generateFc');
				break;
			case "3":
				reporte = Routing.generate('mbp_CCClientes_generateFc');
				break;
			case '':
				reporte = Routing.generate('mbp_CCClientes_generateCobranza');
				break;
		}
		
		var myMask = new Ext.LoadMask(win, {msg:"Cargando..."});
		myMask.show();
		
		var idCliente = win.queryById('id').getValue();
		var ruta;
		if(values.tipo == "1" || values.tipo == "2" || values.tipo == "3"){			
			ruta = Routing.generate('mbp_CCClientes_verFc');
			Ext.Ajax.request({
				url: reporte,
				
				params: {
					tipo: values.tipo,
					idCliente: idCliente,
					idFactura: values.id
				},
				
				success: function(resp){					
					var WinReporte=Ext.create('Ext.Window', {
						  title: 'Factura',
						  width: 900,
						  height: 600,
						  layout: 'fit',
						  modal:true,										
						  html: '<iframe src='+ruta+' width="100%" height="100%"></iframe>'						  
					 });				
					WinReporte.show();
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
					idCobranza: values.id
				},
				
				success: function(resp){
					var WinReporte=Ext.create('Ext.Window', {
						  title: 'Cobranza',
						  width: 900,
						  height: 600,
						  layout: 'fit',
						  modal:true,										
						  html: '<iframe src='+ruta+' width="100%" height="100%"></iframe>'						  
					 });				
					WinReporte.show();
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
		adjunto.setValue(selection.data.concepto+selection.data.id);
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
					
					var tipo;
					if(data.haber > 0){
						tipo = 'cobranza';
					}else{
						tipo = 'factura'
					}
					
					var aData = {
						tipo: tipo,
						id: data.id
					}
					
					Ext.Ajax.request({
						url: Routing.generate('mbp_CCClientes_EliminarComprobante'),
						
						params: {
							data: Ext.JSON.encode(aData)
						},
						
						success: function(resp){
							var jsonResp = Ext.JSON.decode(resp.responseText);
							if(jsonResp.success == true){
								var store = grid.getStore();
								var rec = store.findRecord('id', data.id);
								store.remove(rec);	
							}
						}	
					});
		     	}	     	
		     }
		});
	},
	
	EnviarMail: function(btn){
		var form = btn.up('form');
		var values = form.getValues();		
		var ccView = this.getCCClientes();
		var grid = ccView.down('grid');
		var selection = grid.getSelectionModel().getSelection()[0];
		
		//DATOS PARA GENERAR COMPROBANTE
		var tipo = selection.data.tipo;
		var idCliente = ccView.queryById('id').getValue();
		var idFactura;
		var idCobranza;
		tipo == 'fa' ? idFactura = selection.data.id : idCobranza = selection.data.id;
		//AGREGO DATOS 
		values.tipo = tipo;
		values.idCliente = idCliente;
		values.idFactura = idFactura;
		values.idCobranza = idCobranza;
		
		Ext.Ajax.request({
			url: Routing.generate('mbp_CCClientes_MailComprobante'),
			
			params: values,
			
			success: function(resp){
			}
		});
	},
		
	BuscaArticulos: function(btn){
		var winFacturacion = btn.up('window');
		var winArt = Ext.create('MetApp.view.Articulos.WinArticuloSearch');		
		var btnInsert = winArt.queryById('insertArt');
		
		btnInsert.on('click', function(){
			var grid = winArt.down('grid');
			var selection = grid.getSelectionModel().getSelection()[0];
			var formArt = winFacturacion.queryById('formArticulos');
			
			formArt.loadRecord(selection);
			winArt.close();
			winFacturacion.queryById('cantidad').focus(true, 50);
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

		var myMask = new Ext.LoadMask(win, {msg:"Cargando..."});
		myMask.show();
		
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
		
		valuesDatosFc.idCliente = ccView.queryById('id').getValue();
		//arrayRecords.push(valuesDatosFc);
				
		Ext.Ajax.request({
			url: Routing.generate('mbp_CCClientes_guardarFc'),
			
			params: {
				data: Ext.JSON.encode(arrayRecords),
				fcData: Ext.JSON.encode(valuesDatosFc),	
			},
			
			success: function(resp){
				var decodeResp = Ext.JSON.decode(resp.responseText);
				if(decodeResp.success == true){
					store.removeAll();
					var storeCC = ccView.down('grid').getStore();
					storeCC.load();
					win.close();
					var btnCC = ccView.queryById('nuevaFc');
					me.DetalleComprobante(btnCC);
				}
				myMask.hide();
			},
			
			failure: function(resp){
				var decodeResp = Ext.JSON.decode(resp.responseText);
				myMask.hide();
				Ext.Msg.show({
				    title:'Atencion',
				    msg: 'Codigo: '+decodeResp.msg.code+'<br/>Error: '+decodeResp.msg.msg,
				    buttons: Ext.Msg.OK,
				    icon: Ext.Msg.INFO
				});
			}
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
		grid.getStore().removeAll();
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
			aplicados.push(rec.getData());
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





































