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
				}
		});		
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
	 * Grid para buscar articulos
	 */
	SearchArt: function(button){
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
				}
			});
			
			
				
			button.up('window').close();
		});				
	},
	
	
	/*
	 * Escuchamos el boton de busqueda para la formulacion de articulos
	 */
	SearhForm: function(){
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
			field.setDisabled(true);
		});
		
		var fldCodigo = form.queryById('codigo');
		fldCodigo.setDisabled(false);
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
			field.setDisabled(true);
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
						fields.setDisabled(false);
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
		var form = button.up('form');		
		var values = form.getValues();
		var store = Ext.StoreManager.lookup('Articulos.Articulos');
		var proxy = store.getProxy();
		var record;
		proxy.addListener('exception', function(st, response, operation, eOpts){	//CAPTURA ERRORES DEL SERVER
			jsonResp = Ext.JSON.decode(response.responseText);
			form.getForm().markInvalid(jsonResp.errors);
			store.removeAll();
			me.EditArt(button);
		});
		
		store.on('write', function(st, op){
			var resp = Ext.JSON.decode(op.response.responseText);
			form.queryById('idCodigo').setValue(resp.data.id);
			me.ResetForm(button);
		});
		
		if(values.id > 0){
			var rec = store.findRecord('id', values.id);
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
			field.setDisabled(false);		
		});
		form.queryById('codigo').setDisabled('true');
		form.query('button').forEach(function(btn){
			btn.setDisabled(true);
		});
		form.queryById('btnSave').setDisabled(false);
		form.queryById('btnReset').setDisabled(false);
	},
	
	DeleteArt: function(button){
		var form = button.up('form');
		var record = form.getRecord();
		var store = this.getArticulosArticulosStore();
		if(record===undefined){
			Ext.Msg.show({
				title: 'Atencion',
				msg: 'Debe seleccionar un Articulo',
				buttons: Ext.Msg.OK,
				icon: Ext.Msg.WARNING
			});
		}else{
			Ext.Msg.show({
				title: 'Atencion',
				msg: 'Desea eliminar el articulo?',
				buttons: Ext.Msg.YESNO,
				icon: Ext.Msg.WARNING,
				fn:function(btn){
					if(btn == 'yes'){
						store.remove(record);
						form.getForm().reset();
					}
				}
			});			
		}
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
	}	
});










