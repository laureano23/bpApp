Ext.define('MetApp.controller.Calidad.CalidadController',{
	extend: 'Ext.app.Controller',
	views: [
		'Calidad.NumCorrelativaForm',
		'Calidad.GridNumCorrelativa',
		'Calidad.FormEstanqueidadRG010',
		],
	stores: ['Calidad.Correlativos', 'Personal.PersonalStore', 'Calidad.Estanqueidad'],
	refs: [
		{
			ref:'gridCorrelativos',
			selector: 'gridCorrelativos'
		}
	],
	
	init: function(){
		var me = this;
		me.control({
			'#numCorrelativa': {
				click: this.AddCorrelativoForm
			},
			'correlativoForm numberfield[itemId=numHasta]': {
				blur: this.OutOfFocus
			},			
			'correlativoForm button[action=actSave]': {
				click: this.GuardarCorrelativos
			},
			'correlativoForm button[action=actEdit]':{
				click: this.EditarCorrelativos
			},
			'correlativoForm button[action=actDelete]':{
				click: this.EliminarCorrelativos
			},
			'gridCorrelativos dataview': {
				itemdblclick: this.EditarCorrelativos
			},
			'correlativoForm button[action=actResetForm]':{
				click: this.ResetForm
			},
			/*
			 * Formulario estanqueidad RG-010
			 */
			'#controlEstanqueidad': {
				click: this.AddEstanqueidadForm
			},
			'#rg010Estanqueidad': {
				click: this.AddRg010Reporte
			},
			'estanqueidadForm button[action=actNew]': {
				click: this.AddReg
			},
			'estanqueidadForm button[action=actEdit]': {
				click: this.EditEstanqueidadReg
			},
			'estanqueidadForm button[action=actDelete]': {
				click: this.DeleteEstanqueidadReg
			},
		})		
	},
	
	/*
	 * Iniciamos el formulario de numeracion correlativa
	 */
	
	AddCorrelativoForm: function(){
		var form = Ext.ComponentQuery.query('#correlativoForm form');
		if(form == ''){
			var addFormCorrelativo = Ext.widget('correlativoForm'); 
			var store = this.getCalidadCorrelativosStore().load();			
		}else{}
			
	},
	
	/*
	 * Calculamos automaticamente la cantidad
	 */
	OutOfFocus: function(){
		var desde = Ext.ComponentQuery.query('#correlativoForm numberfield[itemId=numDesde]')[0];
		var hasta = Ext.ComponentQuery.query('#correlativoForm numberfield[itemId=numHasta]')[0];
		var cant = hasta.value-desde.value + 1;
		var field = Ext.ComponentQuery.query('#correlativoForm numberfield[itemId=cant]')[0];
		field.setValue(cant);
	},
	
	/*
	 * Guardamos la numeracion correlativa pueden ser 1 o N registros simultaneos
	 */
	
	GuardarCorrelativos: function(button){
		var win = button.up('window');
		var grid = win.down('grid');
		var	form = win.down('form');
		var record = form.getRecord();
		var	values = form.getValues();
		var	store = this.getCalidadCorrelativosStore();	
			
			if(values.idCorrelativos > 0){
				record.set(values);
			}else{
				var rango = values.num_hasta - values.numCorrelativo;
				var data = [];	
				store.suspendEvents();				
				for(var i=0; i<= rango; i++){			
					data[i] = Ext.create('MetApp.model.Calidad.Correlativos');							
					data[i].beginEdit();
						data[i].set('idCorrelativos', data.internalId),
						data[i].set('numCorrelativo', parseInt(values.numCorrelativo) + i),	
						data[i].set('cant', values.cant),
						data[i].set('fecha.date', values['fecha.date']),
						data[i].set('otEnf', values.otEnf),						
						data[i].set('ot1panel', values.ot1panel),
						data[i].set('ot2panel', values.ot2panel),
						data[i].set('ot3panel', values.ot3panel),
						data[i].set('ot4panel', values.ot4panel),
						data[i].set('id_articulos', values.id_articulos),
						data[i].set('id_cliente', values.id_cliente),
						data[i].set('obs', values.obs),	
					data[i].endEdit();
					/*
					 * Usamos la funcion batch para insertar todos los registros primero y luego cargar el store
					 */										 						
					store.proxy.batch({
						create: [data[i]]											
					});
					store.resumeEvents();															
				}	
			}			
			/*
			 * Recargamos el store con las modificacion
			 */				
			store.load({
				callback: function(record, operation, success){
					grid.view.refresh();												
				}
			});
			form.getForm().reset();									
	},
	
	/*
	 * Editar los registros del grid, salvo los numeros correlativos, de estanqueidad, denomicacion y cliente,
	 * dichos campos deben volver a llenarse manualmente.
	 */
	EditarCorrelativos: function(button, record){;
		grid1 = this.getGridCorrelativos();			
		records = grid1.getSelectionModel().getSelection()[0];
		date = records.get('fecha.date');	
		/*
		 * Funcion para formatear fecha
		 */
	    function render_date(date){
	    	val = Ext.util.Format.date(date, 'd-m-Y');
	    	return val;
	    }
	    var form = Ext.getCmp('correlativoForm');	    
	    var editForm = form.down('form'); 
	    editForm.loadRecord(records); //Cargamos el modelo seleccionado de la grilla al form
	 },
	 
	 /*
	  * Seleccionamos el registro del grid y lo eliminamos
	  */
	 EliminarCorrelativos: function(grid, record){
	 	record = this.getGridCorrelativos().getSelectionModel().getSelection()[0];
	 	var	store = this.getCalidadCorrelativosStore();
	 	Ext.MessageBox.show({
	       title : 'Eliminar Registro',
		   buttons : Ext.MessageBox.YESNO,
		   msg : 'Desea Eliminar este registro?',
		   icon : Ext.Msg.WARNING,
	       fn : function(btn)
			{
				if (btn == 'yes')
				{			
	               store.remove(record);
	      		}				
			}	
	    });
	 },
	
	
	/*
	 * Resetea el formulario
	 */
	ResetForm: function(button){
		var win = button.up('window');
		var form = win.down('form');
		form.getForm().reset();
	},
	
	/*
	 * Reporte RG-010 Estanqueidad
	 */
	AddRg010Reporte: function(btn){
		Ext.Ajax.request({
			url: Routing.generate('mbp_calidad_generateFormRg010'),
			success: function(response,options){
				var jsonReporte = Ext.JSON.decode(response.responseText);
				Reporte=jsonReporte.reporte;
				var ruta = Routing.generate('mbp_calidad_showFormRg010');
												
				window.open(ruta, 'location=yes,height=800,width=1200,scrollbars=yes,status=yes');								
			}
		});
		
	},
	
	
	/*
	 * Formulario de estanqueidad de radiadores RG-010
	 */
	AddEstanqueidadForm: function(){
		var win = Ext.widget('estanqueidadForm');		
		var fieldSet = win.down('fieldset').collapse(); //Se colapsa del controlador porque en la vista tiene un BUG
		var grid = win.down('grid');
		var combo = win.down('combobox');
		grid.getStore().load(); //Cargamos el store para rellenar la grilla
		combo.store.load(); //Cargamos el store para los combos de personal		
	},
	
	//Inserta un registro al control de estanqueidad
	AddReg: function(btn){
		var form = btn.up('form');	
		var combo = form.queryById('idSoldador');	
		var win = btn.up('window');
		var grid = win.down('grid');
		var	store = this.getCalidadEstanqueidadStore();		
		var proxy = store.getProxy();
		
		proxy.addListener('exception', function(st, response, operation, eOpts){
			jsonResp = Ext.JSON.decode(response.responseText);
			form.getForm().markInvalid(jsonResp.errors);
			store.rejectChanges();
		});
		
		var values = form.getValues();
		var valid = form.isValid();
		if(valid == true){
			if(values.id == 0){
				var model = Ext.create('MetApp.model.Calidad.Estanqueidad');
				model.set('id', values['id']);
				model.set('fechaPrueba', values.fechaPrueba);
				model.set('ot', values.ot);
				model.set('presion', values.presion);
				model.set('pruebaNum', values.pruebaNum);
				model.set('estado', values.estado);
				model.set('reparado', values.reparado);
				model.set('mChapa', values.mChapa);
				model.set('mBagueta', values.mBagueta);
				model.set('mPerfil', values.mPerfil);
				model.set('mPisoDesp', values.mPisoDesp);
				model.set('mAnulado', values.mAnulado);
				model.set('mCiba', values.mCiba);
				model.set('mChapaColectora', values.mChapaColectora);
				model.set('tRosca', values.tRosca);
				model.set('tPoros', values.tPoros);
				model.set('tFijacion', values.tFijacion);
				model.set('sConector', values.sConector);
				model.set('sTapaPanel', values.sTapaPanel);
				model.set('sPuntera', values.sPuntera);
				model.set('sPlanchuelas', values.sPlanchuelas);
				model.set('idSoldador', values['idSoldador']);
				model.set('idProbador', values['idProbador']);
				store.add(model);	
				
			}else{
				var record = form.getRecord();
				record.beginEdit();
				record.set(values);
				record.endEdit();
			}							
		}		
	},
	
	//Edita los registros de control de estanqueidad
	EditEstanqueidadReg: function(btn){
		var win = btn.up('window');
		var form = btn.up('form');
		var grid1 = win.down('grid');	
		var	store = this.getCalidadEstanqueidadStore();
		var proxy = store.getProxy();
		
		records = grid1.getSelectionModel().getSelection()[0];
		
		
		//Transformamos objecto fecha a string
		var date = Ext.Date.parse(records.data.fechaPrueba, 'd-m-Y');
		var data = records.data;		
		
		form.loadRecord(records);
		//Seteamos cada campo del formulario manualmente
		form.queryById('id').setValue(data.id);
		form.queryById('fecha').setValue(data.fechaPrueba);
		form.queryById('ot').setValue(data.ot);
		form.queryById('presion').setValue(data.presion);
		form.queryById('prueba').setValue(data.pruebaNum);
		form.queryById('estado').setValue({estado: data.estado});
		form.queryById('perdidaPanel').setValue({
			mPerfil: data.mPerfil,
			mChapa: data.mChapa,
			mPisoDesp: data.mPisoDesp,
			mBagueta: data.mBagueta,
			mAnulado: data.mAnulado,
			mCiba: data.mCiba,
			mChapaColectora: data.mChapaColectora
			});
		form.queryById('perdidaTapas').setValue({			
			tPoros: data.tPoros,
			tRosca: data.tRosca,
			tFijacion: data.tFijacion
			});
		form.queryById('perdidaSoldadura').setValue({			
			sTapaPanel: data.sTapaPanel,
			sConector: data.sConector,
			sPlanchuelas: data.sPlanchuelas,
			sPuntera: data.sPuntera
			});		
		form.queryById('soldador').setValue(data['idSoldador']);
		form.queryById('probador').setValue(data['idProbador']);
		
		//Seteamos el como extraParam el id del registro
		proxy.extraParams.idReg = data.id;
	},
	
	//Elimina registro de control Estanqueidad
	DeleteEstanqueidadReg: function(btn){
		var win = btn.up('window');
		var form = btn.up('form');
		var grid1 = win.down('grid');	
		var	store = this.getCalidadEstanqueidadStore();
		var proxy = store.getProxy();
		
		record = grid1.getSelectionModel().getSelection()[0];
		//Seteamos el como extraParam el id del registro
		proxy.extraParams.idReg = record.data.id;		
		
		Ext.MessageBox.show({
	       title : 'Eliminar Registro',
		   buttons : Ext.MessageBox.YESNO,
		   msg : 'Desea Eliminar este registro?',
		   icon : Ext.Msg.WARNING,
	       fn : function(btn)
			{
				if (btn == 'yes')
				{			
	               store.remove(record);
	      		}				
			}	
	    });
	},	
});














