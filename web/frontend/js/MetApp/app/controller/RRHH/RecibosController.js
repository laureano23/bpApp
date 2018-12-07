Ext.define('MetApp.controller.RRHH.RecibosController', {
	extend: 'Ext.app.Controller',
	views: [
		'MetApp.view.RRHH.DatosLiquidacion',
		'MetApp.view.RRHH.TablaLiquidacion',
		'MetApp.view.RRHH.ConceptosWinSearch',
		'MetApp.view.RRHH.Reportes.ReporteLibroSueldos',
		'MetApp.view.RRHH.Reportes.ReporteResumenAguinaldo',
		'MetApp.view.RRHH.TablaReliquidacion',
		'RRHH.Reportes.ReporteResumenLiquidaciones'
	],
	
	refs: [
		{
			ref:'datosLiquidacion',
			selector: 'datosLiquidacion'
		}
	],
	
	stores: [
		'MetApp.store.Personal.ConceptosStore',
		'MetApp.store.Personal.LiquidacionStore',
		'MetApp.store.Personal.BancosStore'
	],
	
	init: function(){
		var me = this;
		
		me.control({
			'#tablaNuevoPago': {
				click: this.NuevaTabla
			},
			'#winImprimeRecibos': {
				click: this.WinImprimeRecibos
			},			
			'reporteRecibos button[itemId=imprimir]': {
				click: this.ImprimeRecibos
			},
			'datosLiquidacion button[itemId=nuevaLiquidacion]': {
				click: this.NuevaLiquidacion
			},
			'datosLiquidacion datefield[itemId=fechaHasta]': {
				blur: this.CompruebaFecha
			},
			'liquidacionTabla button[itemId=searchPersonal]': {
				click: this.BuscaEmpleado
			},
			'liquidacionTabla button[itemId=buscaConcepto]': {
				click: this.BuscaConcepto
			},
			'liquidacionTabla button[itemId=btnNew]': {
				click: this.InsertaConcepto
			}
			,
			'liquidacionTabla button[itemId=btnSave]': {
				click: this.GuardaConceptos
			},
			'liquidacionTabla button[itemId=btnEdit]': {
				click: this.EditaConceptos
			},
			'liquidacionTabla button[itemId=btnDelete]': {
				click: this.EliminaConceptos
			},			
			'#reporteLibroSueldos': {
				click: this.WinReporteLibroSueldos
			},
			'reporteLibroSueldos button[itemId=imprimir]': {
				click: this.ImprimirLibroSueldos
			},
			'#reporteResumenAguinaldo': {
				click: this.WinReporteResumenAguinaldo
			},
			'ReporteResumenAguinaldo button[itemId=imprimir]': {
				click: this.ImprimeResumenAguinaldo
			},
			'#reporteResumenLiquidaciones': {
				click: this.WinReporteResumenLiquidaciones
			},	
			'ReporteResumenLiquidaciones button[itemId=imprimir]': {
				click: this.ImprimeResumenLiquidaciones
			},		
			'#tablaReliquidarPeriodo': {
				click: this.WinReliquidarPeriodo
			},
			'tablaReliquidacion button[itemId=reliquidar]': {
				click: this.ReliquidarPeriodo
			}
		})
	},
	
	//VALIDACION PARA LAS FECHAS DE PAGO
	CompruebaFecha: function(fechaHasta){
		var win = fechaHasta.up('window');
		var fechaDesde = win.queryById('fechaDesde');
		if(fechaHasta.getValue() < fechaDesde.getValue()){
			Ext.Msg.show({
				title: 'Atencion',
				msg: 'La fecha HASTA debe ser mayor a la fecha DESDE',
				buttons: Ext.Msg.OK,				
			});
			fechaHasta.reset();
		}
	},
		
	NuevaTabla: function(btn){
		var win = Ext.create('MetApp.view.RRHH.DatosLiquidacion');
	},
	
	NuevaLiquidacion: function(btn){
		var form = btn.up('window').down('form');
		var me = this;
				
		if(form.isValid()){
			var values = form.getValues();
			var tabla = Ext.create('MetApp.view.RRHH.TablaLiquidacion');
			//HOT KEY DE LA TABLA LIQUIDACION
			var map = new Ext.util.KeyMap({
			    target: tabla.getId(),	
			   	binding: [
			   		{
			   			key: Ext.EventObject.F5,
			   			defaultEventAction: 'preventDefault',
			   			fn: function(){ 
			   				me.GuardaConceptos(tabla.queryById('btnSave'));
			   			}
			   		},
			   		{
			   			key: Ext.EventObject.F1,
			   			defaultEventAction: 'preventDefault',
			   			fn: function(){ 
			   				me.EditaConceptos(tabla.queryById('btnEdit'));
			   			}
			   		},
			   		{
			   			key: Ext.EventObject.F3,
			   			defaultEventAction: 'preventDefault',
			   			fn: function(){
			   				me.InsertaConcepto(tabla.queryById('btnNew'));
			   			}
			   		},
			   		{
			   			key: Ext.EventObject.F8,
			   			defaultEventAction: 'preventDefault',
			   			fn: function(){ 
			   				me.EliminaConceptos(tabla.queryById('btnDelete'));
			   			}
			   		},
			   	]
			});	
			
			//CARGO EL STORE CON LOS CONCEPTOS
			var storeConcepto = Ext.getStore('MetApp.store.Personal.ConceptosStore');
			storeConcepto.load();
			
			var store = Ext.create('MetApp.store.Personal.ConceptosStore');	
			store.proxy.extraParams.variable = 0;	
			store.load();
			var storeLiquidacion = tabla.down('grid').getStore();
			
			//REMUEVO CUALQUIER DATO DEL STORE PREVIO
			storeLiquidacion.removeAll();
			
			//SETEO PARAMETROS DE LIQUIDACION EN LA TABLA			
			var liquidacionFecha = tabla.queryById('liquidacionFecha');
			liquidacionFecha.setText('Liquidacion: ' + values.fechaDesde +' a ' + values.fechaHasta);
			var periodo = tabla.queryById('periodo');
			var codigoPeriodo = tabla.queryById('codigoPeriodo');
			var fechaFin = tabla.queryById('fechaFin');	
			var compensatorio = tabla.queryById('compensatorio');
			var fechaPago = tabla.queryById('fechaPago');
			var descripcion = tabla.queryById('descripcion');
			var anioLiquidacion = tabla.queryById('anioLiquidacion');
			var textPeriodo;		
			var textCodigoPeriodo;	
			switch(values.pagoTipo){
				case 1:	
					textPeriodo = '1° Quincena';
					textCodigoPeriodo = 1;
					break;
				case 2:
					textPeriodo = '2° Quincena';
					textCodigoPeriodo = 2;
					break;
				case 3:
					textPeriodo = 'Mes';
					textCodigoPeriodo = 3;
					break;
				case 4:
					textPeriodo = 'Vacaciones';
					textCodigoPeriodo = 4;
					break;
				case 5:
					textPeriodo = 'SAC';
					textCodigoPeriodo = 5;
					break;
				case 6:
					textPeriodo = 'Otros';
					textCodigoPeriodo = 6;
					break;
				case 7:
					textPeriodo = 'Premios 1°';
					textCodigoPeriodo = 7;
					break;
				case 8:
					textPeriodo = 'Premios 2°';
					textCodigoPeriodo = 8;
					break;
				case 9:
					textPeriodo = 'Liquidación final';
					textCodigoPeriodo = 9;
					break;
				default:
			}
			codigoPeriodo.setValue(textCodigoPeriodo)
			periodo.setText(textPeriodo);
			fechaFin.setValue(values.fechaHasta);
			values.compensatorio == 'on' ? compensatorio.setValue(values.compensatorio) : '';
			fechaPago.setValue(values.fechaPago);			
			descripcion.setValue(values.descripcion);
			var dateParsed = Ext.Date.parse(values.fechaPago, 'd/m/Y');
			anioLiquidacion.setValue(Ext.Date.format(dateParsed, 'Y'));
		}			
	},
	
	BuscaEmpleado: function(btn){
		var winLiquidacion = btn.up('window');
		var storeLiquidacion = Ext.getStore('MetApp.store.Personal.LiquidacionStore');
		var tabla = Ext.create('MetApp.view.RRHH.SearchGridPersonal');
		var grid = tabla.down('grid');
		var store = grid.getStore();
		store.load();
				
		tabla.queryById('insertPersona').on('click', function(btn){
			var selection = grid.getSelectionModel().getSelection()[0];
			winLiquidacion.queryById('empleado').setValue(selection.data.nombre);
			winLiquidacion.queryById('idEmpleado').setValue(selection.data.idP);
						
			//ACTIVO BOTON PARA BUSCAR CONCEPTO
			var btnCon = winLiquidacion.queryById('buscaConcepto').setDisabled(false);
						
			//VERIFICA SI HAY CONCEPTOS PREVIOS GUARDADOS EN ESE PERIODO
			var idPersonal = winLiquidacion.queryById('idEmpleado');
			var codPeriodo = winLiquidacion.queryById('codigoPeriodo');
			var fecha = winLiquidacion.queryById('fechaFin');
			var compensatorio = winLiquidacion.queryById('compensatorio').getValue(); 
			
			var data = {
				idPersonal: idPersonal.value,
				codPeriodo: codPeriodo.value,
				mes: Ext.Date.format(fecha.value, 'n'),
				anio: Ext.Date.format(fecha.value, 'Y'),
				compensatorio: compensatorio
			};
			
			Ext.Ajax.request({
				url: Routing.generate('mbp_personal_conceptosLectura'),
								
				params: data,
				
				success: function(resp){
					var gridLiquidacion = winLiquidacion.down('grid');
					
					var jsonData = Ext.JSON.decode(resp.responseText);	
					storeLiquidacion.loadData([], false);
					storeLiquidacion.loadData(jsonData.data);
					
					if(jsonData.data != ""){
						data.idRecibo = jsonData.data[0].idRecibo;
					}	
					
					btnCon.focus();
					
					if(jsonData.success == 'info'){
						Ext.Msg.show({
						    title:'Atencion',
						    msg: jsonData.msg,
						    buttons: Ext.Msg.YESNO,
						    icon: Ext.Msg.QUESTION,	
						    fn:function(btn){
								if(btn == 'yes'){
									//BORRAMOS TODOS LOS DATOS DEL PERIODO
									Ext.Ajax.request({
										url: Routing.generate('mbp_personal_recibosEliminarPeriodo'),
										
										params: data,
										
										success: function(resp){
											var jsonData = Ext.JSON.decode(resp.responseText);
											if(jsonData.success == true){
												storeLiquidacion.loadData([], false);
											}		
										}
									});
								}
								if(btn == 'no'){
									var botonera = winLiquidacion.queryById('botonera');
									botonera.busquedaItem(botonera);
									botonera.queryById('btnSave').setDisabled(false);	
								}
							}	   
						});
					}
				}
			});			
			tabla.close();
		});
	},
	
	BuscaConcepto: function(btn){
		var winLiquidacion = btn.up('window');
		var winSearch = Ext.create('MetApp.view.RRHH.ConceptosWinSearch');
		var gridSearch = winSearch.down('grid');
		var store = gridSearch.getStore();
		store.load();
		
		winSearch.queryById('insertConcepto').on('click', function(btn){
			var selection = gridSearch.getSelectionModel().getSelection()[0];
			winLiquidacion.queryById('concepto').setValue(selection.data.descripcion);
			winLiquidacion.queryById('idConcepto').setValue(selection.data.id);
			winLiquidacion.queryById('unidad').setValue(selection.data.unidad);
			winSearch.close();
			
			var cantidad = winLiquidacion.queryById('cantidad');
			cantidad.focus(true, 100);
		});
	},
	
	InsertaConcepto: function(btn){
		var win = btn.up('window');
		var grid = win.down('grid');
		var form = win.down('form');
		var storeConcepto = Ext.getStore('MetApp.store.Personal.ConceptosStore');
		
		var idRecibo = form.queryById('idRecibo').getValue();
		var idConcepto = form.queryById('idConcepto').getValue();
		var cantidad = form.queryById('cantidad').getValue();
        var record = storeConcepto.findRecord('id', idConcepto);
        var compensatorio = win.queryById('compensatorio').getValue();
        var anioLiquidacion = win.queryById('anioLiquidacion').getValue();
                
        var storeLiquidacion = win.down('grid').getStore();
		var idEmpleado = win.queryById('idEmpleado').getValue();
				
		//DATOS QUE VAMOS A ENVIAR EN LA CABECERA
		record.data.cantidad = cantidad;
		record.data.idP = idEmpleado;
		record.data.compensatorio = compensatorio;
		record.data.anioLiquidacion = anioLiquidacion;
				
		if(form.isValid()){
			Ext.Ajax.request({
				url: Routing.generate('mbp_personal_calculaConcepto'),
				
				params: record.data,
				
				success: function(resp){
					var liquidacionModel;
					var importe = 0;
					//SI ES UN CONCEPTO FIJO TOMAMOS PARA EL CALCULO EL IMPORTE FIJO, SINO EL IMPORTE DE LA CATEGORIA DEL EMPLEADO
					record.data.fijo == 1 ? importe = record.data.importe : importe = record.data.empleadoSueldo;
					//DECODIFICO LA RESPUESTA
					var jsonResp = Ext.JSON.decode(resp.responseText);
					if(idRecibo > 0){
						liquidacionModel = storeLiquidacion.findRecord('idRecibo', idRecibo);	
						liquidacionModel.beginEdit();
						liquidacionModel.set('idRecibo', idRecibo);					
						liquidacionModel.set('idConcepto', record.data.id);					
						liquidacionModel.set('descripcionConcepto', record.data.descripcion);
						liquidacionModel.set('unidad', record.data.unidad);
						liquidacionModel.set('cantidadConcepto', cantidad);					
						liquidacionModel.set('importe', record.data.importe);					
						liquidacionModel.set('subTotal', jsonResp.subTotal);
						compensatorio == true ? liquidacionModel.set('compensatorio', 1) : liquidacionModel.set('compensatorio', 0);
						liquidacionModel.endEdit();					 
					}else{
						liquidacionModel = Ext.create('MetApp.model.Personal.LiquidacionModel');
						liquidacionModel.beginEdit();
						liquidacionModel.set('idRecibo', idRecibo);					
						liquidacionModel.set('idConcepto', record.data.id);					
						liquidacionModel.set('descripcionConcepto', record.data.descripcion);
						liquidacionModel.set('unidad', record.data.unidad);
						liquidacionModel.set('cantidadConcepto', cantidad);					
						liquidacionModel.set('importe', record.data.importe);					
						liquidacionModel.set('subTotal', jsonResp.subTotal);
						compensatorio == true ? liquidacionModel.set('compensatorio', 1) : liquidacionModel.set('compensatorio', 0);
						liquidacionModel.endEdit();
						storeLiquidacion.add(liquidacionModel);
					}
					form.getForm().reset();
					win.queryById('btnSave').setDisabled(false);
					win.queryById('buscaConcepto').focus();	
				}
			})		
		}	
	},
	
	GuardaConceptos: function(btn){
		var win = btn.up('window');
		var datosLiquidacion = this.getDatosLiquidacion();
		var grid = win.down('grid');
		var store = grid.getStore();
		var arrayRecords = [];	
		var storeRecibos = Ext.create('MetApp.store.Personal.RecibosStore');
		var proxyRecibos = storeRecibos.getProxy();
		
		//ESCUCHA EVENTOS DE ESCRITURA EN EL STORE
		storeRecibos.on('write', function(store, operation){
			if(operation.success == true){
				win.queryById('empleado').setValue('');
				grid.getStore().removeAll();				
				win.queryById('searchPersonal').focus('', 10);
			}			
		});
		
		/*SETEA PARAMETROS EXTRAS*/
		var fechaFin = win.queryById('fechaFin').getValue();
		var fechaObj = new Date();		
						
		var idP = win.queryById('idEmpleado').getValue();
		var periodo = win.queryById('codigoPeriodo').getValue();
		var mes = Ext.Date.format(fechaFin, 'm');		
		var anio = Ext.Date.format(fechaFin, 'Y');
		var compensatorio = win.queryById('compensatorio').getValue();
		var banco = datosLiquidacion.queryById('banco').getValue();
		var fechaPago = win.queryById('fechaPago').getValue();
		var descripcion = win.queryById('descripcion').getValue();
		
		/* PARAMETROS DEL PROXY */
		proxyRecibos.setExtraParam('idP', idP);
		proxyRecibos.setExtraParam('periodo', periodo);
		proxyRecibos.setExtraParam('mes', mes);
		proxyRecibos.setExtraParam('anio', anio);
		proxyRecibos.setExtraParam('compensatorio', compensatorio);
		proxyRecibos.setExtraParam('banco', banco);
		proxyRecibos.setExtraParam('fechaPago', fechaPago.toLocaleDateString());
		proxyRecibos.setExtraParam('descripcion', descripcion);
				
		store.each(function(rec) {				    
	        arrayRecords.push(rec.getData());		    
		});
		
		
		storeRecibos.add(arrayRecords);	
			
	},
	
	EditaConceptos: function(btn){
		var win = btn.up('window');
		var grid = win.down('grid');
		var store = grid.getStore();
		var selection = grid.getSelectionModel().getSelection()[0];
		var form = win.down('form');
		
		if(!selection){
			Ext.Msg.show({
			    title:'Atencion',
			    msg: 'Seleccione primero un registro de la grilla',
			    buttons: Ext.Msg.OK,
			    icon: Ext.Msg.QUESTION,		   
			});
		}else{
			form.loadRecord(selection);	
			store.loadData([], false);
		}
	},
	
	EliminaConceptos: function(btn){
		var win = btn.up('window');
		var grid = win.down('grid');
		var store = grid.getStore();
		var selection = grid.getSelectionModel().getSelection();
				
		//PARAMETROS
		var idRecibo = {
			'idRecibo': selection[0].data.idRecibo
		}; 
		
		if(idRecibo.idRecibo == 0){
			store.remove(selection);
		}else{
			Ext.Ajax.request({
				url: Routing.generate('mbp_personal_recibosEliminar'),
				
				params: idRecibo,
				
				success: function(resp){
					var jsonResp = Ext.JSON.decode(resp.responseText);
					if(jsonResp = true){
						store.remove(selection);			
					}else{
							
					}	
				}
			});
		}
	},
	
	WinImprimeRecibos: function(btn){
		var win = Ext.create('MetApp.view.RRHH.Reportes.ReporteRecibos');
	},
	
	ImprimeResumenAguinaldo: function(btn){
		var form=btn.up('form');
		form.getForm().submit({
			standardSubmit: true,
			target: '_blank',
			url: Routing.generate('mbp_personal_crearResumenAguinaldo')
		})
	},
	
	ImprimeRecibos: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var values = form.getValues();
		
		var storeRecibos = Ext.create('MetApp.store.Personal.RecibosStore');
		var proxyRecibos = storeRecibos.getProxy();
		proxyRecibos.setExtraParam('pagoTipo', values.pagoTipo);
		proxyRecibos.setExtraParam('mes', values.mes);
		proxyRecibos.setExtraParam('anio', values.anio);
		proxyRecibos.setExtraParam('compensatorio', values.compensatorio);
		
		var myMask = new Ext.LoadMask(win, {msg:"Cargando..."});
		myMask.show();
		
		storeRecibos.load({
			callback: function(records, operation, success) {
		        var ruta = Routing.generate('mbp_personal_recibosPdf');
		        if(success == true){
		        	window.open(ruta, 'location=yes,height=800,width=1200,scrollbars=yes,status=yes');
		        }		        
				 myMask.hide();
		    }
		});
	},
	
	WinReporteLibroSueldos: function(btn){
		var win = Ext.create('MetApp.view.RRHH.Reportes.ReporteLibroSueldos');
	},
	
	ImprimirLibroSueldos: function(btn){
		var form=btn.up('form');
		form.getForm().submit({
			standardSubmit: true,
			target: '_blank',
			url: Routing.generate('mbp_personal_reporteLibroSueldos')
		})
	},
	
	WinReporteResumenAguinaldo: function(btn){
		Ext.widget('ReporteResumenAguinaldo');
	},

	WinReporteResumenLiquidaciones: function(btn){
		Ext.widget('ReporteResumenLiquidaciones');
	},

	ImprimeResumenLiquidaciones: function(btn){
		var form=btn.up('form');
		form.getForm().submit({
			standardSubmit: true,
			target: '_blank',
			url: Routing.generate('mbp_personal_crearResumenLiquidaciones')
		})
	},
	
	WinReliquidarPeriodo: function(btn){
		var win = Ext.create('MetApp.view.RRHH.TablaReliquidacion');
	},
	
	ReliquidarPeriodo: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var values = form.getValues();
		
		Ext.Msg.show({
		    title:'Atencion',
		    msg: 'Esto eliminará todos los registros del periodo seleccionado. Desea continuar?',
		    buttons: Ext.Msg.YESNO,
		    icon: Ext.Msg.QUESTION,
		    fn: function(btn){
		    	if(btn == 'yes'){
		    		//MASCARA LOADING
		    		var myMask = new Ext.LoadMask(win, {msg:"Cargando..."});
					myMask.show();
		    		Ext.Ajax.request({
		    			url: Routing.generate('mbp_personal_recibosReliquidar'),
		    			params: values,
		    			success: function(resp){
		    				decodeData = Ext.JSON.decode(resp.responseText);
		    				if(decodeData.success == 'info'){
		    					Ext.Msg.show({
								    title:'Atencion',
								    msg: decodeData.msg,
								    buttons: Ext.Msg.OK,
								    icon: Ext.Msg.INFO,
								})
		    				}
		    				if(decodeData.success == true){
		    					Ext.Msg.show({
								    title:'Info',
								    msg: decodeData.msg,
								    buttons: Ext.Msg.OK,
								    icon: Ext.Msg.INFO,
								})
		    				}
		    				myMask.hide();
		    			}
		    		})
		    	}
		    }		   
		});
	}
})





















