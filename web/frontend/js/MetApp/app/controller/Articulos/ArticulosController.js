Ext.define('MetApp.controller.Articulos.ArticulosController',{
	extend: 'Ext.app.Controller',	
	views: [
		'Articulos.ArticulosForm',
		'Articulos.WinArticuloSearch',
		'Articulos.ArticuloSearchGrd',
		'Articulos.ArticulosFormulas'
		],
	stores: ['Articulos.Articulos', 'Articulos.Formula'],
	
	init: function(){
		
		var me = this;
		me.control({
				'#tbArticulos': {
					click: this.addArticulosTb
				},
				'#actualizarBDArticulos': {
					click: this.actualizarBDArticulos
				},
				'articulosform button[itemId=estructuraProducto]': {
					click: this.EstructuraProducto
				},
				'articulosform button[action=actBuscaArt]': {
					click: this.SearchArt
				},				
				'articulosform button[itemId=btnNew]': {
					click: this.NewArt
				},
				'articulosform button[itemId=btnSave]': {
					click: this.SaveArt
				},
				'articulosform button[itemId=btnEdit]': {
					click: this.EditArt
				},
				'articulosform button[itemId=btnDelete]': {
					click: this.DeleteArt
				},
				'articulosform button[itemId=btnReset]': {
					click: this.ResetForm
				},
				'articulosform textfield[itemId=codigo]': {
					blur: this.OutOfFocus
				},
				'articulosform button[itemId=cargarImagen]': {
					click: this.CargarImagen
				},
				'articulosform button[action=actBuscaArt]': {
					click: this.SearchArt
				},
				'articulosform button[itemId=copiarRuta]': {
					click: this.CopiarRuta
				},
				'articulosform button[itemId=pedidoPendiente]': {
					click: this.PedidosPendientes
				},
				'articulosform button[itemId=enQueFormulas]': {
					click: this.EnQueFormulas
				},
		});		
	},

	EstructuraProducto: function(btn){
		console.log("hola");
		console.log(this.getController('Articulos.FormulasController'));
		this.getController('Articulos.FormulasController').Estructura(btn);
	},
	
	EnQueFormulas: function(btn){
		
	},
	
	PedidosPendientes: function(btn){
		var win=btn.up('window');
		var form=win.down('form');
		var myMask = new Ext.LoadMask(form, {msg:"Cargando..."});
		myMask.show();
		
		
		var codigo=form.queryById('codigo').getValue();
		var data={
			articuloDesde: codigo,
			articuloHasta:codigo,
			clienteDesde:1,
			clienteHasta:999999,
			fechaDesde:"01/01/2000",
			fechaHasta:"01/01/2500"
		};
		
		
		console.log(data);
		var values=Ext.JSON.encode(data);
		
		console.log(values);
		
		
		Ext.Ajax.request({
			url: Routing.generate('mbp_produccion_reporte_pedido'),
			
			params: {
				data: values
			},
			
			success: function(resp, opt){
			    var jsonResp = Ext.JSON.decode(resp.responseText);
				if(jsonResp.success == true){
					var ruta = Routing.generate('mbp_produccion_reporte_pedidoPdf');
					window.open(ruta, '_blank, location=yes,height=800,width=1200,scrollbars=yes,status=yes');
					myMask.hide();
				}
			},
			
			failure: function(res){
				myMask.hide();
			}
		});
	},
	
	CopiarRuta: function(btn){
		var win = btn.up('window');
		var ruta = win.queryById('rutaServer');
		
		ruta.setReadOnly(false);
		ruta.focus();
		ruta.selectText();		
		document.execCommand("Copy");
		ruta.setReadOnly(true);
	},
	
	/*
	 * Iniciamos el formulario para manejar articulos
	 */
	addArticulosTb: function(){		
		var form = Ext.ComponentQuery.query('#articulosForm form');	
		if(form == ''){			
			var addForm = Ext.widget('articulosform');
			var storeFamilia = addForm.queryById('familia').getStore();
			var storeSubFamilia = addForm.queryById('subFamilia').getStore();
			
			storeFamilia.load();
			storeSubFamilia.load();								
		}else{}
	},
	
	/*
	 * FUNCION PARA ACTUALIZAR LA BD DE ARTICULOS CON LA BD DE NMA
	 * */
	actualizarBDArticulos: function(btn){
		var win = btn.up('viewport');
		var myMask = new Ext.LoadMask(win, {msg:"Cargando..."});
		myMask.show();
		Ext.Ajax.request({
			url: Routing.generate('mbp_articulos_actualizar_base'),
			
			params: {
				
			},
			
			success: function(resp){
				var jsonResp = Ext.JSON.decode(resp.responseText);
				myMask.hide();
				Ext.Msg.show({
				     title:'Atencion',
				     msg: jsonResp.msg,
				     buttons: Ext.Msg.OK,
				     icon: Ext.Msg.INFO,
			    });
			    myMask.hide();
			},
			
			failure: function(resp){
				var jsonResp = Ext.JSON.decode(resp.responseText);
				Ext.Msg.show({
				     title:'Error',
				     msg: 'Error al actualizar la BD',
				     buttons: Ext.Msg.OK,
				     icon: Ext.Msg.ALERT,
			    });
			    myMask.hide();
			}
		});
	},
	
	/*
	 * Grid para buscar articulos
	 */
	SearchArt: function(button){
		var win = button.up('window');
		var searchForm = Ext.widget('winarticulosearch');
		var store = searchForm.down('grid').getStore();
		store.sync();
		store.clearFilter(true);
		var button = Ext.ComponentQuery.query('#insertArt')[0];
		/*
		 * Escuchamos al boton de la ventana de busqueda
		 */
		button.on('click',function(){
			var grid = button.up('grid');
			var selection = grid.getSelectionModel().getSelection()[0];
			Ext.Ajax.request({
				url: Routing.generate('mbp_articulos_conCosto'),
				
				params: selection.data,
				
				success: function(resp){
					jsonResp = Ext.JSON.decode(resp.responseText);
					var formArt = Ext.getCmp('articulosForm');
					
					formArt = Ext.getCmp('articulosForm').down('form');	
					formArt.getForm().setValues(jsonResp.data[0]);
					var nombreImagen = win.queryById('imagen');
					if(jsonResp.data[0].nombreImagen != null){
						nombreImagen.show();
						nombreImagen.initialConfig.autoEl.href = Routing.generate('mbp_articulos_servirImagen', {id: jsonResp.data[0].id});
						console.log(nombreImagen);
					}else{
						nombreImagen.hide();
					}					
				}
			});
			
			
				
			button.up('window').close();
		});				
	},
	
	
	/*
	 * Escuchamos el boton de busqueda para la formulacion de articulos
	 */
	SearhForm: function(btn){
		var searchForm = Ext.widget('winarticulosearch');
		searchForm.down('grid').getStore().clearFilter(true);
		var button = Ext.ComponentQuery.query('#insertArt')[0];
		/*
		 * Escuchamos al boton de la ventana de busqueda
		 */
		button.on('click',function(){
			var grid = button.up('grid');
			var selection = grid.getSelectionModel().getSelection()[0];
								
			var formArt = Ext.ComponentQuery.query('#formulasSearch')[0];	
			
			formArt.loadRecord(selection);
			button.up('window').close();
		});
	},
	
		
	/*
	 * Creamos un nuevo articulo
	 */
	NewArt: function(button){
		var form = button.up('form');
		form.getForm().reset();

		form.query('field').forEach(function(field){
			field.setReadOnly(true);
		});
		
		var fldCodigo = form.queryById('codigo');
		fldCodigo.setReadOnly(false);
		fldCodigo.focus();
		
		form.query('button').forEach(function(button){
			button.setDisabled(true);			
		});
		
		var btnSave = form.queryById('btnSave');
		var btnReset = form.queryById('btnReset');
		btnSave.setDisabled(false);
		btnReset.setDisabled(false);
		
		form.queryById('fieldSetCosto').expand();
	},
	
	/*
	 * Reset al formulario de articulo
	 */
	ResetForm: function(button){
		var form = button.up('form');
		
		form.query('button').forEach(function(button){			
			button.setDisabled(false);
		});				
		
		var btnSaveArt = form.queryById('btnSave');
		var btnResetForm = form.queryById('btnReset');
		btnSaveArt.setDisabled(true);
		btnResetForm.setDisabled(true);
		
		form.query('.field').forEach(function(field){
			field.setReadOnly(true);
		});		
	},
	
	/*
	 * Validamos el codigo
	 */
	OutOfFocus: function(field){		
		var field1 = field.getValue();
		var form = field.up('form');
		var btnReset = form.queryById('btnReset');
		var me = this;
		
		if(field1 == ''){
			Ext.Msg.show({
    			title: 'Atencion',
    			msg: 'Debe escribir un codigo para continuar',
    			buttons: Ext.Msg.OK,
    			icon: Ext.Msg.WARNING
    		});
		}else{
			var request = Ext.Ajax.request({
		    url: Routing.generate('mbp_articulos_validate'),
		   	params: {
		   		codigo: field1
		   	},
		    success: function(response){
		    	var validResp = Ext.JSON.decode(response.responseText);
		    	if(validResp.success == false){
		    		Ext.Msg.show({
		    			title: 'Error',
		    			msg: 'Este articulo ya existe',
		    			buttons: Ext.Msg.OK,
		    			icon: Ext.Msg.WARNING
		    		});
		    	}if(validResp.success == true){
		    		form.query('field').forEach(function(fields){
						fields.setReadOnly(false);
					});
		    	}
		    }
			});	
		}
	},
	
	/*
	 * Comienza el ABMC de articulos
	 */
	SaveArt: function(button){
		var me = this;
		var win=button.up('window');
		var form = win.queryById('artForm');		
		var values = form.getForm().getValues();
		var store = Ext.StoreManager.lookup('Articulos.Articulos');
		var proxy = store.getProxy();
		var record;
		
		//if(!form.isValid()) return;
		
		proxy.addListener('exception', function(st, response, operation, eOpts){	//CAPTURA ERRORES DEL SERVER
			jsonResp = Ext.JSON.decode(response.responseText);
			form.getForm().markInvalid(jsonResp.errors);
			me.EditArt(button);
			store.rejectChanges();
			
		});
		
		store.on('write', function(st, op){
			var resp = Ext.JSON.decode(op.response.responseText);
			form.queryById('id').setValue(resp.data.id);
			me.ResetForm(button);
		});
		
		console.log(values);
		
		if(values.id > 0){
			var rec = store.findRecord('id', values.id);
			console.log(rec);
			rec.set(values);			
		}
		else{
			record = Ext.create('MetApp.model.Articulos.Articulos');			
			record.set(values);			
			store.add(record);				
		}
	},
	
	EditArt: function(button){
		var form = button.up('form');
		var record = form.getRecord();
		var store = this.getArticulosArticulosStore();
		
		form.query('field').forEach(function(field){
			field.setReadOnly(false);		
		});
		form.queryById('codigo').setReadOnly('true');
		form.query('button').forEach(function(btn){
			btn.setDisabled(true);
		});
		form.queryById('btnSave').setDisabled(false);
		form.queryById('btnReset').setDisabled(false);
	},
	
	DeleteArt: function(button){
		var form = button.up('form');
		var record = form.getForm().getRecord();
		var store = this.getArticulosArticulosStore();
		var id = form.queryById('id').getValue();
		var model = store.findRecord('id', id);
		
		var proxy = store.getProxy();
		proxy.addListener('exception', function(st, response, operation, eOpts){	//CAPTURA ERRORES DEL SERVER
			me.EditArt(button);
			store.rejectChanges();			
		});
		
		
		Ext.Msg.show({
			title: 'Atencion',
			msg: 'Desea eliminar el articulo?',
			buttons: Ext.Msg.YESNO,
			icon: Ext.Msg.WARNING,
			fn:function(btn){
				if(btn == 'yes'){
					store.remove(model);
					form.getForm().reset();
				}
			}
		});	
		
	},
	/*
	 * Termina el ABMC de articulos
	 */
	
	/*
	 * Funcion para cargar un record pasando el ID, utilizado para link
	 */
	LinkArt: function(record){
		var store = this.getArticulosArticulosStore();
		var form = Ext.ComponentQuery.query('#artForm')[0];
		form.loadRecord(record);
		if(record.get('moneda') == "U$D"){
			form.queryById('pesos').setValue('d');
		}
	},
	
	CargarImagen: function(btn){
		var form = btn.up('form');
		form.submit({
			url: Routing.generate('mbp_articulos_cargarImagen'),
			
			waitMsg: 'Cargando foto...',
			
			success: function(resp, action){
				var jsonResp = Ext.JSON.decode(action.response.responseText);
				
				Ext.Msg.show({
					title: 'Atencion',
					msg: 'Imágen cargada con éxito',
					buttons: Ext.Msg.OK,
					icon: Ext.Msg.INFO,
				});
			},
			
			failure: function(resp, action){
				var jsonResp = Ext.JSON.decode(action.response.responseText);
				
				Ext.Msg.show({
					title: 'Atencion',
					msg: jsonResp.msg,
					buttons: Ext.Msg.OK,
					icon: Ext.Msg.WARNING,
				});	
			}
		});
	}	
});










