Ext.define('MetApp.controller.RRHH.PersonalController', {
	extend: 'Ext.app.Controller',
	views: [
		'MetApp.view.RRHH.Personal.TablaPersonal',
		'MetApp.view.RRHH.Personal.PersonalWin',
		'MetApp.view.RRHH.SearchGridPersonal',
		'MetApp.view.RRHH.Personal.TabDatosFijos'
	],
	stores: [
		'MetApp.store.Personal.ProvinciasStore',
		'MetApp.store.Personal.PartidosStore',
		'MetApp.store.Personal.LocalidadesStore',
		'MetApp.store.Personal.CategoriasStore',
		'MetApp.store.Personal.SindicatosStore',
		'MetApp.store.Personal.PersonalStore',
		'MetApp.store.Produccion.Programacion.SectoresStore',
		'MetApp.store.Personal.PersonalDatosFijosStore',	
		'MetApp.view.RRHH.Personal.TabOtrosDatos'	
	],
	
	init: function(){
		var me = this;
		me.control({
			'#tablaPersonal': {
				click: this.TablaPersonal
			},
			'personalWin combobox[itemId=comboProv]': {
				select: this.FilterPartido
			},
			'personalWin combobox[itemId=comboPartido]': {
				select: this.FilterLocalidad
			},
			'personalWin combobox[itemId=comboCategoria]': {
				focus: this.FilterCategoria
			},
			'personalWin button[itemId=buscaEmp]': {
				click: this.BuscaEmpleado
			},
			'personalWin container[itemId=btnDatosPersonales] button[action=actNew]': {
				click: this.NuevoEmpleado
			},
			'personalWin container[itemId=btnDatosPersonales] button[action=actSave]': {
				click: this.GuardarEmpleado
			},
			'personalWin container[itemId=btnDatosPersonales] button[action=actEdit]': {
				click: this.EditarEmpleado
			},
			'personalWin container[itemId=btnDatosPersonales] button[action=actResetForm]': {
				click: this.ResetForm
			},
			'personalWin container[itemId=btnDatosPersonales] button[action=actDelete]': {
				click: this.BorrarRegistro
			},
			'personalWin button[itemId=btnSearchConcepto]': {
				click: this.BuscaConcepto
			},
			
			/*			 
			 * TAB CONCEPTOS
			 * */
			'personalWin form[itemId=formDatosFijos] button[action=actSave]': {
				click: this.GuardarDatoFijo
			},
			'personalWin grid[itemId=gridDatosFijos] actioncolumn[itemId=eliminarDatoFijo]': {
				click: this.EliminarDatoFijo
			},
			
			/*
			 *TAB OTROS DATOS
			 * */
			'personalWin container[itemId=btnOtrosDatos] button[action=actEdit]': {
				click: this.EditarDatosAdicionales
			},
			'personalWin container[itemId=btnOtrosDatos] button[action=actSave]': {
				click: this.GuardarDatosAdicionales
			},
		});		
	},
	
	TablaPersonal: function(btn){
		
		var me = this;		
		var tabPersonal = Ext.widget('personalWin');		
		var storePersonal = Ext.create('MetApp.store.Personal.PersonalStore');
		var storeSindicato = tabPersonal.queryById('comboSindicato').getStore();
		var storeCategoria = tabPersonal.queryById('comboCategoria').getStore();
		var storeSector = tabPersonal.queryById('comboSector').getStore();
		var storeProvincia = tabPersonal.queryById('comboProv').getStore();
		
		storePersonal.load();
		storeSindicato.load();
		storeCategoria.load();
		storeSector.load();
		storeProvincia.load();
		
		var map = new Ext.util.KeyMap({
		    target: tabPersonal.getId(),	
		   	binding: [
		   		{
		   			key: Ext.EventObject.F5,
		   			defaultEventAction: 'preventDefault',
		   			fn: function(){ 
		   				me.GuardarEmpleado(tabPersonal.queryById('btnSave'));
		   			}
		   		},
		   		{
		   			key: Ext.EventObject.F1,
		   			defaultEventAction: 'preventDefault',
		   			fn: function(){ 
		   				me.EditarEmpleado(tabPersonal.queryById('btnEdit'));
		   			}
		   		},
		   		{
		   			key: Ext.EventObject.F3,
		   			defaultEventAction: 'preventDefault',
		   			fn: function(){
		   				me.NuevoEmpleado(tabPersonal.queryById('btnNew'));
		   			}
		   		},
		   		{
		   			key: Ext.EventObject.F8,
		   			defaultEventAction: 'preventDefault',
		   			fn: function(){ 
		   				me.BorrarRegistro(tabPersonal.queryById('btnDelete'));
		   			}
		   		},
		   	]
		});	
		
	},
	
	FilterPartido: function(combo){
		var win = combo.up('window');
		var storePartido = win.queryById('comboPartido').getStore();
		var provId = win.queryById('comboProv').getValue();
		
		if(provId == null){
			console.log('entramos');
			Ext.Msg.show({
				title: 'Atención',
				msg: 'Seleccione una provincia primero',
				buttons: Ext.Msg.OK,
     			icon: Ext.Msg.ALERT
			})
		}else{
			storePartido.getProxy().setExtraParam('provinciaId', provId);
			storePartido.load();	
		}
		
		
	},
	
	FilterLocalidad: function(combo){
		var win = combo.up('window');
		var storeLocalidad = win.queryById('comboLocalidad').getStore();
		var partidoId = win.queryById('comboPartido').getValue();
		
		if(partidoId == null){
			Ext.Msg.show({
				title: 'Atención',
				msg: 'Seleccione un partido primero',
				buttons: Ext.Msg.OK,
     			icon: Ext.Msg.ALERT
			})
		}else{
			storeLocalidad.getProxy().setExtraParam('partidoId', partidoId);
			storeLocalidad.load();	
		}
	},

	FilterCategoria: function(combo){
		var win = combo.up('window');
		var storeCategoria = combo.getStore();
		var sindicatoId = win.queryById('comboSindicato').getValue();
						
		storeCategoria.clearFilter();
		storeCategoria.filter({
			property: "idSindicato",
			  value: sindicatoId, 
			  exactMatch: true
			}		  
		);
	},
	
	BuscaEmpleado: function(btn){
		var searchPersonal = Ext.create('MetApp.view.RRHH.SearchGridPersonal');
		//searchPersonal.queryById('searchField').focus();
		var win = btn.up('window');
		var form = btn.up('form');	
		var formOtrosDatos = win.down('form [itemId=formOtrosDatos]');
		var grid = searchPersonal.down('grid');
		var store = grid.getStore();
		var btnInsert = searchPersonal.queryById('insertPersona');
		var storeDatosFijos = win.queryById('gridDatosFijos').getStore();
		var proxyDatosFijos = storeDatosFijos.getProxy();
		
		store.load();
		store.clearFilter();
		btnInsert.on('click', function(){			
			var record = grid.getSelectionModel().getSelection()[0];
			var idP = record.getData().idP;
			
			proxyDatosFijos.extraParams = {
				'idP': idP
			}
			
			proxyDatosFijos.setExtraParam('variable', 0);
			storeDatosFijos.load();
			
			var ajax = Ext.Ajax.request({
				url: Routing.generate('mbp_personal_Fulllist'),
				
				params: {
					idPersonal: idP
				},				
				success: function(resp){
					var jsonResp = Ext.decode(resp.responseText);
					console.log(jsonResp);
					
					/*CARGAMOS DEPARTAMENTO Y LOCALIDAD*/
					var comboDepto = win.queryById('comboPartido');
					var storeDepto = comboDepto.getStore();
					
					var comboLocalidad = win.queryById('comboLocalidad');
					var storeLocalidad = comboLocalidad.getStore();
					
					storeDepto.getProxy().setExtraParam('provinciaId', jsonResp.provincia);
					storeDepto.load({
						callback: function(records, operation, success) {
					        comboDepto.select(jsonResp.departamento);
					        
					    }
					});
					
					storeLocalidad.getProxy().setExtraParam('partidoId', jsonResp.departamento);
					storeLocalidad.load({
						callback: function(records, operation, success) {
					        comboLocalidad.select(jsonResp.localidad);
					        
					    }
					});
					
					var fullRecord = Ext.create('MetApp.model.Personal.PersonalModel');
					fullRecord.set(jsonResp);
					form.getForm().setValues(jsonResp);
					formOtrosDatos.getForm().setValues(jsonResp);
															
					//BOTONERA DATOS PERSONALES										
					var botonera = win.queryById('botonera'); 
					botonera.busquedaItem(botonera);
				}		
			});			
			searchPersonal.close();
		});
	},
	
	NuevoEmpleado: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		
		form.query('field').forEach(function(field){
			field.setReadOnly(false);		
		});
		form.queryById('fieldSetDir').expand();
		form.queryById('oficial').setReadOnly(true);
		form.queryById('buscaEmp').setDisabled(true);
						
		form.getForm().reset();
		
		win.queryById('nombre').focus();
		
		var botonera = win.queryById('botonera'); 
		botonera.editarItem(botonera);
	},
	
	EditarEmpleado: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var botonera = win.queryById('botonera');
		form.query('field').forEach(function(field){
			field.setReadOnly(false);		
		});
		
		form.queryById('oficial').setReadOnly(true);
		
		botonera.editarItem(botonera);
	},
	
	ResetForm: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var storeFijos = Ext.getStore('MetApp.store.Personal.PersonalDatosFijosStore');
		
		form.query('field').forEach(function(field){
			field.setReadOnly(true);		
		});
		
		storeFijos.loadData([]);
		
		form.getForm().reset();
		var  botonera = win.queryById('botonera');
		botonera.resetFn(botonera);		//RESETEA BOTONERA
		win.queryById('buscaEmp').setDisabled(false);
	},
	
	GuardarEmpleado: function(btn){
		var win = btn.up('window'); 
		var form = win.down('form');
		var botonera = win.queryById('botonera');
		var formOtrosDatos = win.down('form [itemId=formOtrosDatos]'); 
		formOtrosDatos.query('field').forEach(function(field){
			field.setReadOnly(false);		
		});	
		var valuesOtrosDatos = formOtrosDatos.getForm().getValues();
		formOtrosDatos.query('field').forEach(function(field){
			field.setReadOnly(true);		
		});	
		
		if(form.isValid()){
			var values = form.getValues();
			values.tallePantalon = valuesOtrosDatos.tallePantalon;
			values.talleCamisa = valuesOtrosDatos.talleCamisa;
			values.talleCalzado = valuesOtrosDatos.talleCalzado;
			var newEmpleado = Ext.create('MetApp.model.Personal.PersonalModel');
			var store = this.getStore('MetApp.store.Personal.PersonalStore');
			var proxy = store.getProxy();
			proxy.addListener('exception', function(st, response, operation, eOpts){
				jsonResp = Ext.JSON.decode(response.responseText);
				form.getForm().markInvalid(jsonResp.errors);
				store.rejectChanges();
			});
			
			proxy.afterRequest = function(request, success){		
				if(request.action == 'create' & success == true){			
					form.queryById('idP').setValue(request.records[0].data.idP);
					form.query('field').forEach(function(field){
						field.setReadOnly(true);		
					});									 
					botonera.busquedaItem(botonera);
					win.queryById('buscaEmp').setDisabled(false);
				}
				if(request.action == 'create' & success == false){
					store.rejectChanges();
				}
			};
			data = Ext.encode(values);
			if(values.idP > 0){
				Ext.Ajax.request({
					url: Routing.generate('mbp_personal_create'),
					params: {
						data: data
					},
					success: function(resp){
						respText = Ext.decode(resp.responseText);
						if(respText.success == true){
							form.query('field').forEach(function(field){
								field.setReadOnly(true);		
							});
							botonera.busquedaItem(botonera);
							win.queryById('buscaEmp').setDisabled(false);
						}						
					},
					failure: function(resp){
						jsonResp = Ext.JSON.decode(resp.responseText);
						form.getForm().markInvalid(jsonResp.errors);	
						store.rejectChanges();
					}
				});
			}else{
				newEmpleado.set(values);			
				store.add(newEmpleado);					
			}	
		}			
	},
	
	BorrarRegistro: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var botonera = win.queryById('botonera');
		Ext.Msg.show({
		     title:'Atencion',
		     msg: 'Desea eliminar el registro?',
		     buttons: Ext.Msg.YESNO,
		     icon: Ext.Msg.QUESTION,
		     fn:function(btn){
				if(btn == 'yes'){
					var idP = form.queryById('idP').getValue()
					var store = Ext.getStore('personalStore');
					var record = store.findRecord('idP', idP);
					store.remove(record);
					form.getForm().reset();
					botonera.resetFn(botonera);
				}
			}
		});
	},
	
	BuscaConcepto: function(btn){
		var winConceptos = btn.up('window');
		var win = Ext.create('MetApp.view.RRHH.ConceptosWinSearch');
		var grid = win.down('grid');
		var store = Ext.getStore('MetApp.store.Personal.ConceptosStore');
		store.load();
		
		win.queryById('insertConcepto').on('click', function(){
			var selection = grid.getSelectionModel().getSelection()[0];
			winConceptos.queryById('descripcionConcepto').setValue(selection.data.descripcion);
			winConceptos.queryById('cantDatosFijos').setValue(selection.data.importe);
			winConceptos.queryById('idDatosFijos').setValue(selection.data.id);
			win.close();			
		});
	},
	
	GuardarDatoFijo: function(btn){
		var win = btn.up('window');
		var form = win.down('form [itemId=formDatosFijos]');
		var cntBotonera = win.queryById('btnDatosFijos');
		var botonera = cntBotonera.queryById('botonera');
		var idP = win.queryById('idP').getValue();
		
		if(form.isValid()){
			var values = form.getValues();
			values.idP = idP;
			var model = Ext.create('MetApp.model.Personal.PersonalModel');
			model.set(values);
			var store = Ext.getStore('MetApp.store.Personal.PersonalDatosFijosStore');
			var proxy = store.getProxy;
			
						
			store.on('write', function(store, operation){
				if(operation.action == 'create' && operation.success == true){					
					form.getForm().reset();
				}
			});
			store.add(model);
		}
		form.getForm().markInvalid();
	},
	
	EliminarDatoFijo: function(grid, colIndex, rowIndex){
		var store = grid.getStore();
		var selection = grid.getStore().getAt(rowIndex);
		
		var proxy = store.getProxy();
		proxy.setExtraParam('idP', selection.data.datosFijos.idP);
		proxy.setExtraParam('idDatoFijo', selection.data.datosFijos.id);
		
		store.remove(selection);		
	},
	
	EditarDatosAdicionales: function(btn){
		var panel = btn.up('panel'); 
		var form = panel.down('form');
		var botonera = panel.queryById('botonera');
		botonera.queryById('btnSave').setDisabled(false);
		form.query('field').forEach(function(field){
			field.setReadOnly(false);		
		});
	},
	
	GuardarDatosAdicionales: function(btn){	
		//REFERENCIAS NECESARIAS DE LA VISTA
		var me = this;
		var win = btn.up('window');
		var panel = btn.up('panel'); 
		var form = panel.down('form');
		var formPersonal = win.down('form [itemId=formPersonal]');
		//HABILITAMOS CAMPOS DEL FORM PARA PODER TRAER SUS VALORES TEMPORALMENTE
		formPersonal.query('field').forEach(function(field){
			field.setReadOnly(false);		
		});		
		var valuesPersonal = formPersonal.getForm().getValues();
		//DESHABILITAMOS NUEVAMENTE CAMPOS DEL FORM
		formPersonal.query('field').forEach(function(field){
			field.setReadOnly(true);		
		});
		var store = me.getStore('personalStore');
		var proxy = store.getProxy();
		var idEmpleado = formPersonal.queryById('idP').getValue();
		var values = form.getForm().getValues();
		var record = store.findRecord('idP', idEmpleado);
		
		//HABILITAMOS UN LISTENER PARA ESCUCHAR EXCEPCIONES DEL SERVIDOR		
		proxy.addListener('exception', function(st, response, operation, eOpts){
			jsonResp = Ext.JSON.decode(response.responseText);
			form.getForm().markInvalid(jsonResp.errors);
			store.rejectChanges();
		});
		
		
		//HABILITAMOS LISTENER PARA RESPUESTAS DEL SERVIDOR
		proxy.afterRequest = function(request, success){
			if(request.action == 'create' ||  request.action == 'update' & success == true){			
				form.query('field').forEach(function(field){
					field.setReadOnly(true);		
				});								
			}
			if(request.action == 'create' & success == false){
				store.rejectChanges();
			}
		};
		
		//UNIMOS VALORES DE AMBOS FORMS
		valuesPersonal.talleCamisa = values.talleCamisa;
		valuesPersonal.tallePantalon = values.tallePantalon;
		valuesPersonal.talleCalzado = values.talleCalzado;
		
				
		record.set(valuesPersonal);
	}
});


















