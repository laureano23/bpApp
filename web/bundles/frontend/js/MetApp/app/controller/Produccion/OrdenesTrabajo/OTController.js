Ext.define('MetApp.controller.Produccion.OrdenesTrabajo.OTController', {
	extend: 'Ext.app.Controller',
	
	views: [
		'MetApp.view.Produccion.OrdenesTrabajo.NuevaOTView',
		'MetApp.view.Clientes.SearchGridClientes',
		'MetApp.view.Articulos.WinArticuloSearch',
		'MetApp.view.Produccion.OrdenesTrabajo.CierreOTView',
		'MetApp.view.Produccion.OrdenesTrabajo.VerOTView'
	],
	
	stores: [
		'Produccion.OrdenesTrabajo.CierreOTGridStore',
		'MetApp.store.Produccion.OrdenesTrabajo.OTStore',
		'MetApp.store.Produccion.OrdenesTrabajo.OTStoreEmisor'
	],
	
	
	init: function(){
		var me = this;
		
		
		
		me.control({
			'#nuevaOt': {
				click: this.NuevaOT
			},
			'NuevaOTView button[itemId=nueva]': {
				click: this.HabilitaForm
			},
			'NuevaOTView datefield[itemId=emision]': {
				blur: this.BlurEmision
			},
			'NuevaOTView button[itemId=guardar]': {
				click: this.GuardarOT
			},
			'NuevaOTView button[itemId=btnCliente]': {
				click: this.BuscarCliente
			},
			'NuevaOTView button[itemId=btnCodigo]': {
				click: this.BuscarCodigo
			},
			'NuevaOTView combo[itemId=sector]': {
				select: this.VerificarProgramacion
			},
			'#cierreOt': {
				click: this.CerrarOT
			},
			'CierreOTView button[itemId=guardar]': {
				click: this.GuardarCierreOt
			},
			'CierreOTView textfield[itemId=filtroCliente]': {
				keyup: this.FiltrarCliente
			},
			'#verOt': {
				click: this.VerOT
			},
			'VerOTView button[itemId=verOT]': {
				click: this.ReporteOT
			},
		});
	},
		
	NuevaOT: function(btn){
		Ext.widget('NuevaOTView');
	},
	
	HabilitaForm: function(btn){
		var form = btn.up('form');
		form.query('field').forEach(function(field){
			field.setReadOnly(false);		
		});
		form.queryById('emision').setReadOnly(true);
		form.queryById('btnCliente').focus('', 20);
	},
	
	BlurEmision: function(field){
		var win = field.up('window');
		win.queryById('btnCliente').focus('', 20);
	},
	
	BuscarCliente: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		
		var viewClientes = Ext.widget('clientesSearchGrid');
		viewClientes.down('grid').getStore().load();
		
		var btnInsert = viewClientes.queryById('insertCliente');
		btnInsert.on('click', function(){
			
			var selection = viewClientes.down('grid').getSelectionModel().getSelection()[0];			
			form.loadRecord(selection);
			form.queryById('idCliente').setValue(selection.data.id);
			viewClientes.close();
			win.queryById('btnCodigo').focus('', 20);
		});
	},
	
	BuscarCodigo: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var winArticulos = Ext.widget('winarticulosearch');
		
		var btnInsert = winArticulos.queryById('insertArt');
		btnInsert.on('click', function(){
			var selection = winArticulos.down('grid').getSelectionModel().getSelection()[0];
			form.loadRecord(selection);
			winArticulos.close();
			win.queryById('cantidad').focus('', 20);
		});
	},
	
	GuardarOT: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var grid = win.down('grid');
		
		if(form.isValid()){
			var myMask = new Ext.LoadMask(win, {msg:"Cargando..."});
			myMask.show();
			var values = form.getValues();
			var otAsociadas = grid.getSelectionModel().getSelection();
						
			var jsonArray = [];
			var i=0;
			otAsociadas.forEach(function(e){
				jsonArray[i] = e.data;
				i++;
			});
			
			jsonArray = Ext.JSON.encode(jsonArray);			
			
			Ext.Ajax.request({
				url: Routing.generate('mbp_produccion_nuevaot'),
				
				params: {
					data: Ext.JSON.encode(values),
					otAsociada: jsonArray
				},
				
				success: function(resp){
					var jsonResp = Ext.JSON.decode(resp.responseText);
					if(jsonResp.success == true){
						form.getForm().reset();
						form.query('field').forEach(function(field){
							field.setReadOnly(true);		
						});
						
						Ext.Ajax.request({
							url: Routing.generate('mbp_produccion_generarOt'),
							
							params: {
								ot: jsonResp.ot
							},
							
							success: function(resp){
								var jResp = Ext.JSON.decode(resp.responseText);
								if(jResp.success == true){
									var ruta = Routing.generate('mbp_produccion_verOt');
		    						window.open(ruta, 'location=yes,height=800,width=1200,scrollbars=yes,status=yes');		
								}
								myMask.hide();
							},
							
							failure: function(resp){
								myMask.hide();
							}
						})
					}
				},
				
				failure: function(resp){
					var jsonResp = Ext.JSON.decode(resp.responseText);
					form.getForm().markInvalid(jsonResp.errors);
					myMask.hide();
				}
			});
		}
		
	},
	
	CerrarOT: function(btn){
		var view = Ext.widget('CierreOTView');
		var grid = view.down('grid');
		var store = grid.getStore();
		
		var myMask = new Ext.LoadMask(view, {msg:"Cargando..."});
		myMask.show();
		Ext.Ajax.request({
			url: Routing.generate('mbp_produccion_CerrarOtListado'),
			
			success: function(resp){
				myMask.hide();
				jsonData = Ext.JSON.decode(resp.responseText);
				store.loadRawData(jsonData.items);
			},
			
			failure: function(resp){
				myMask.hide();
			}
		})
	},
	
	GuardarCierreOt: function(btn){
		var win = btn.up('window');
		var grid = win.down('grid');
		var store = grid.getStore();
		var modifiedRecords = store.getModifiedRecords();
		var arrayParam = [];
		var myMask = new Ext.LoadMask(win, {msg:"Cargando..."});
		myMask.show();
		
		for(var i=0; i<modifiedRecords.length; i++){
			arrayParam.push(modifiedRecords[i].getData());
		}
		
		Ext.Ajax.request({
			url: Routing.generate('mbp_produccion_ActualizarCerradoOt'),
			
			params: {
				items: Ext.JSON.encode(arrayParam)
			},
			
			success: function(resp){
				myMask.hide();
				jsonResp = Ext.JSON.decode(resp.responseText);
				if(jsonResp.success == true){
					store.sync();					
				}
			},
			
			failure: function(resp){
				myMask.hide();
			}
		})
	},
	
	FiltrarCliente: function(txt){
		var win = txt.up('window');
		var grid = win.down('grid');
		var store = grid.getStore();
		
		store.clearFilter(true);
		store.filter(
			{property: 'cliente',
			value: txt.getValue(),
			anyMatch: true}
		);
	},
	
	VerOT: function(btn){
		var view = Ext.widget('VerOTView');
		var grid = view.down('grid');
		var store = grid.getStore();
		var myMask = new Ext.LoadMask(view, {msg:"Cargando..."});
		myMask.show();
		
		Ext.Ajax.request({
			url: Routing.generate('mbp_produccion_ListarOrdenesCompletas'),
			
						
			success: function(resp){
				myMask.hide();
				var jsonResp = Ext.JSON.decode(resp.responseText);
				if(jsonResp.success == true){
					store.loadData(jsonResp.data);	
				}						
			},
			
			failure: function(resp){
				myMask.hide();
			}
		});
	},
	
	ReporteOT: function(btn){
		var win = btn.up('window');
		var grid = win.down('grid');
		var selection = grid.getSelectionModel().getSelection()[0];
		var myMask = new Ext.LoadMask(win, {msg:"Cargando..."});
		myMask.show();
		
		Ext.Ajax.request({
			url: Routing.generate('mbp_produccion_generarOt'),
			
			params: {
				ot: selection.data.otNum
			},
			
			success: function(resp){
				myMask.hide();
				var jResp = Ext.JSON.decode(resp.responseText);
				if(jResp.success == true){
					var ruta = Routing.generate('mbp_produccion_verOt');
					window.open(ruta, 'location=yes,height=800,width=1200,scrollbars=yes,status=yes');		
				}
			},
			
			failure: function(resp){
				myMask.hide();
			}
		})
	},
	
	VerificarProgramacion: function(combo){
		var win = combo.up('window');
		var sector = combo.getValue();
		var codigo = win.queryById('codigo').getValue();
		
		Ext.Ajax.request({
			url: Routing.generate('mbp_produccion_verificarOT'),
			
			params: {
				sector: sector,
				codigo: codigo
			},
			
			success: function(resp){
				var jResp = Ext.JSON.decode(resp.responseText);
				if(jResp.type == "info"){
					Ext.Msg.show({
						title: 'Atencion',
						msg: "Ya se encuentran emitidas y pendientes las ordenes: </br>"+jResp.data
					});		
				}
			},
			
			failure: function(resp){
				
			}
		});
	}
});


















