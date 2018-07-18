Ext.define('MetApp.controller.Compras.OrdenDeCompraController',{
	extend: 'Ext.app.Controller',	
	views: [
		'MetApp.view.Compras.OrdenCompraView',
		'MetApp.view.Compras.ListaOcView',
		'MetApp.view.Compras.HistoricoArticuloCompras',
		'MetApp.view.Compras.ModificarOCView',
		],
	stores: [
		'Articulos.Articulos',
		'MetApp.store.Compras.OrdenCompraStore',
		'MetApp.store.Compras.HistoricoCompraStore'
	],
	
	refs:[
		{
			ref:'OrdenCompraView',
			selector: 'OrdenCompraView'
		},
	],
	
	init: function(){
		var me = this;
		
		var store = Ext.getStore('MetApp.store.Compras.OrdenCompraStore');
		store.on({
			'datachanged':function(st, opts){
				var view = me.getOrdenCompraView();
				if(!view){return;}
				var total=0;
				st.each(function(rec){
					var data = rec.getData();
					total += data.cant * data.costo;			
				});						
				var desc = view.queryById('desc').getValue();
				total = total - desc * total / 100;
				view.queryById('total').setValue(total);
			}
		});
		
		me.control({
				'#ordenDeCompra': {
					click: this.addOcWin
				},
				'OrdenCompraView button[itemId=buscaProveedor]': {
					click: this.BuscaProveedor
				},
				'OrdenCompraView button[itemId=buscarArt]': {
					click: this.BuscaArt
				},
				'OrdenCompraView button[itemId=btnNew]': {
					click: this.InsertarItem
				},
				'OrdenCompraView button[itemId=btnDelete]': {
					click: this.EliminarItem
				},
				'OrdenCompraView button[itemId=btnEdit]': {
					click: this.EditarItem
				},
				'OrdenCompraView button[itemId=btnSave]': {
					click: this.GenerarOc
				},	
				'OrdenCompraView combobox[itemId=monedaOC]': {
					change: this.ListenChangeTc
				},
				'OrdenCompraView button[itemId=historicoArt]': {
					click: this.HistoricoArticulo
				},	
				'#verOrdenDeCompra': {
					click: this.VerOrdenDeCompra
				},	
				'ListaOcView actioncolumn[itemId=eliminar]': {
					click: this.EliminarOc
				},	
				'ListaOcView actioncolumn[itemId=detalle]': {
					click: this.VerOrdenDetalle
				},
				'ListaOcView textfield[itemId=filtroProveedor]': {
					keyup: this.FiltrarLista
				},
				'#modificarOrdenDeCompra': {
					click: this.ModificarOrdenDeCompra
				},
				'ModificarOCView button[itemId=buscarOC]': {
					click: this.BuscarOC
				},					
		});		
	},

	BuscarOC: function(btn){
		var win=btn.up('window');
		var form=btn.up('form').getForm();
		var grid=win.down('grid');
		var store=grid.getStore();
		form.submit({
			url: Routing.generate('mbp_compras_buscarOrden'),
			success: function(form, resp){
				var jsonResp=Ext.JSON.decode(resp.response.responseText);				
				store.loadRawData(jsonResp.data);
			}
		});
	},

	ModificarOrdenDeCompra: function(btn){
		var win=Ext.widget('ModificarOCView');
		var store=win.down('grid').getStore();
		var form=win.down('form');
		store.removeAll();

		//listener del store
		store.on('update', function(st, rec, op){
			store.suspendEvents();
			var arrayRec=[];
			var i=0;
			st.each(function(rec){
				arrayRec[i]=rec.data;
				i++;
			})
			
			form.submit({
				url: Routing.generate('mbp_compras_modificarOrden'),
				success: function(form, resp){
					var jsonResp=Ext.JSON.decode(resp.response.responseText);
					store.resumeEvents();
				},
				params:{
					data: Ext.JSON.encode(arrayRec)
				},
				failure: function(form, resp){
					store.resumeEvents();
				}
			});
		});
	},
	
	callbackOrdenCompraStore: function(st, opts){
		var total=0;
		st.each(function(rec){
			var data = rec.getData();
			total += data.cant * data.costo;			
		});		
		//var view = MetApp.getApplication().getController('MetApp.controller.Compras.OrdenDeCompraController').getOrdenCompraView();
		var desc = view.queryById('desc').getValue();
		total = total - desc * total / 100;
		view.queryById('total').setValue(total);
	},
	
	//VENTANA DE ORDEND DE COMPRA
	addOcWin: function(){			
		var me = this;
		var win = Ext.widget('OrdenCompraView');
		var store = win.down('grid').getStore();
		var desc = win.queryById('desc');

		store.removeAll();
		
		win.queryById('buscaProveedor').focus('', 20);
		//HOT KEY DE LA TABLA ORDEN DE COMPRA
		var map = new Ext.util.KeyMap({
		    target: win.getId(),	
		   	binding: [
		   		{
		   			key: Ext.EventObject.F5,
		   			defaultEventAction: 'preventDefault',
		   			fn: function(){ 
		   				me.GenerarOc(win.queryById('btnSave'));
		   			}
		   		},
		   		{
		   			key: Ext.EventObject.F1,
		   			defaultEventAction: 'preventDefault',
		   			fn: function(){ 
		   				me.EditarItem(win.queryById('btnEdit'));
		   			}
		   		},
		   		{
		   			key: Ext.EventObject.F3,
		   			defaultEventAction: 'preventDefault',
		   			fn: function(){
		   				me.InsertarItem(win.queryById('btnNew'));
		   			}
		   		},
		   		{
		   			key: Ext.EventObject.F8,
		   			defaultEventAction: 'preventDefault',
		   			fn: function(){ 
		   				me.EliminarItem(win.queryById('btnDelete'));
		   			}
		   		},
		   	]
		});	
				
		desc.on('blur', function(){
			var total=0;
			store.each(function(rec){
				var data = rec.getData();
				total += data.cant * data.costo;			
			});
			var desc = win.queryById('desc').getValue();
			total = total - desc * total / 100;
			win.queryById('total').setValue(total);
		});
	},	
	
	BuscaProveedor: function(btn){
		var me = this;
		var winOrdenDeCompra = btn.up('window');
		var win = Ext.widget('ProveedoresSearchGrid');
		var grid = win.down('grid');
		var form = winOrdenDeCompra.queryById('formProveedor');
		grid.getStore().load();
		
		var btnInsert = win.queryById('insertProveedor');
		btnInsert.on('click', function(){
			var record = grid.getSelectionModel().getSelection()[0];
			win.close();
			form.loadRecord(record);	
			winOrdenDeCompra.queryById('buscarArt').focus('', 20);		
		});
	},
	
	BuscaArt: function(btn){
		var winOc = btn.up('window');
		var formArt = winOc.queryById('formArticulos');
		var winArticulos = Ext.widget('winarticulosearch');
		var btnAceptar = winArticulos.queryById('insertArt');
		
		btnAceptar.on('click', function(){
			var grid = winArticulos.down('grid');
			var selection = grid.getSelectionModel().getSelection()[0];
			
			formArt.loadRecord(selection);
			console.log(selection);
			winArticulos.close();
			
			if(selection.data.codigo == 'ZZZ'){
				var desc = winOc.queryById('descripcion');
				desc.setReadOnly(false);
				desc.focus('', 100);
			}else{
				winOc.queryById('cant').focus('', 100);	
			}
			
		});		
	},
	
	InsertarItem: function(btn){
		var win = btn.up('window');
		var grid = win.down('grid');
		var store = grid.getStore();
		var form = win.queryById('formArticulos');
		var values = form.getForm().getValues();
		var tc = MetApp.resources.ux.ParametersSingleton.dolarOficial;
		values.tcArticulo = tc;
		
		console.log(values);
		store.loadRawData(values, true);
		form.getForm().reset();
		form.queryById('descripcion').setReadOnly(true);
		win.focus();
	},
	
	EliminarItem: function(btn){
		var win = btn.up('window');
		var grid = win.down('grid');
		var store = grid.getStore();
		var selection = grid.getSelectionModel().getSelection()[0];
		
		store.remove(selection);
	},
	
	EditarItem: function(btn){
		var win = btn.up('window');
		var form = win.queryById('formArticulos');
		var grid = win.down('grid');
		var store = grid.getStore();
		var selection = grid.getSelectionModel().getSelection()[0];
		
		store.remove(selection);
		form.loadRecord(selection);
	},
	
	GenerarOc: function(btn){
		var win = btn.up('window');
		var store = win.down('grid').getStore();
		var formProv = win.queryById('formProveedor');
		var data = new Array(); 
		var i = 0;
		
		store.each(function(rec){
			data[i] = rec.getData();
			i++;	
		});
		
		var formValues = formProv.getForm().getValues();
		var descuentoGral = win.queryById('desc');
		formValues.descuentoGral = descuentoGral.getValue();
		var jsonDataDetalle = Ext.JSON.encode(data);
		var jsonOrdenData = Ext.JSON.encode(formValues);
		
		if(formProv.isValid() && store.getCount() != 0){
			var myMask = new Ext.LoadMask(win, {msg:"Cargando..."});
			myMask.show();
			Ext.Ajax.request({
				url: Routing.generate('mbp_compras_nuevaOrden'),
				params: {
					detalle: jsonDataDetalle,
					orden: jsonOrdenData
				},
				success: function(resp){
					var msg = Ext.JSON.decode(resp.responseText);
					if(msg.success == true){
						//DEPLEGAMOS LA OC
						Ext.Ajax.request({
							url: Routing.generate('mbp_compras_reporteOrden'),
							
							params: {
								idOC: msg.idOC
							},
							
							success: function(resp){								
								var ruta = Routing.generate('mbp_personal_verOC');
						    	window.open(ruta, '_blank, location=yes,height=800,width=1200,scrollbars=yes,status=yes');
								myMask.hide();	
							},

							failure: function(){
								myMask.hide();
							}
						});

						formProv.getForm().reset();
						store.removeAll();
						descuentoGral.setValue(0);
					}					
				},

				failure: function(){
					myMask.hide();
				}
			})
		}
	},
	
	ListenChangeTc: function(combo, newValue, oldValue, eOpts){
		var view = combo.up('window');
		var tc = view.queryById('tc');
		
		if(combo.value == "U$D"){
			tc.setValue(MetApp.resources.ux.ParametersSingleton.dolarOficial);
			tc.setReadOnly(false);
		}else{
			tc.setValue("");
		}
	},
	
	HistoricoArticulo: function(btn){
		var winOc = btn.up('window');
		var codigo = winOc.queryById('codigo').getValue();
		
		var winHist = Ext.widget('HistoricoArticuloCompras');
		
		Ext.Ajax.request({
			url: Routing.generate('mbp_compras_historicoCompra'),
			
			params: {
				codigo: codigo
			},
			
			success: function(resp){
				console.log(resp);
				var jsonResp = Ext.JSON.decode(resp.responseText);
				console.log(jsonResp);
				var store = winHist.down('grid').getStore();
				store.loadRawData(jsonResp.data);
				
			}
		});
	},

	VerOrdenDeCompra: function(btn){
		var win = Ext.widget('ListaOcView');
		var grid = win.down('grid');
		var store = grid.getStore();
		var model = grid.getView();
		var selectionModel = model.getSelectionModel();

		store.clearFilter(true);
		//EVENTO PARA SELECCIONAR UN ITEM AL POSIONAR EL MOUSE
		model.on('highlightitem', function(view, node){
			var domEl = new Ext.dom.Element(node);
			selectionModel.deselectAll();
			selectionModel.select(domEl.dom.rowIndex);			
		});

		//EVENTO PARA SACAR LA SELECCION DE LA GRILLA
		model.on('itemmouseleave', function(view, node){
			selectionModel.deselectAll();	
		});

		var myMask = new Ext.LoadMask(win, {msg:"Cargando..."});
		myMask.show();
		Ext.Ajax.request({
			url: Routing.generate('mbp_compras_listarOrdenes'),
			
			success: function(resp){
				myMask.hide();
				var jsonResp = Ext.JSON.decode(resp.responseText);
				store.loadRawData(jsonResp.data);		
				win.queryById('filtroProveedor').focus('', 20);		
			},

			failure: function(resp){
				myMask.hide();
			}
		});
	},

	EliminarOc: function(grid, colIndex, rowIndex){
		Ext.Msg.show({
		     title:'Atención',
		     msg: 'Desea eliminar la orden de compra?',
		     buttons: Ext.Msg.YESNOCANCEL,
		     icon: Ext.Msg.QUESTION,
		     fn:function(opt){
				if(opt == 'yes'){				
					var myMask = new Ext.LoadMask(grid, {msg:"Aguarde..."});
					var store = grid.getStore();
					var selection = store.getAt(rowIndex);
					Ext.Ajax.request({
						url: Routing.generate('mbp_compras_eliminarOrden'),
						params: {
							idOc: selection.data.idOc
						},
						success: function(resp){
							myMask.hide();
							store.remove(selection);
						},
						failure: function(resp){
							myMask.hide();
						}
					});
				}
			}
		});
	},

	VerOrdenDetalle: function(btn){
		var win = btn.up('window');
		var grid=win.down('grid');
		var selection = win.down('grid').getSelectionModel().getSelection()[0];

		var myMask = new Ext.LoadMask(grid, {msg:"Cargando..."});
		myMask.show();
		Ext.Ajax.request({
			url: Routing.generate('mbp_compras_reporteOrden'),

			params: {
				idOC: selection.data.idOc
			},

			success: function(resp){
				myMask.hide();	
				var jsonResp = Ext.JSON.decode(resp.responseText);
				var ruta = Routing.generate('mbp_personal_verOC');
				if(jsonResp.success == true){
					window.open(ruta, '_blank, location=yes,height=800,width=1200,scrollbars=yes,status=yes');				 
				}
			},

			failure: function(resp){
				myMask.hide();
			}
		});
	},

	FiltrarLista: function(field, ev){
		var store = field.up('window').down('grid').getStore();
		var val = field.getValue();
		store.clearFilter(true);
		store.filter(
			{property: 'proveedor',
			value: val,
			anyMatch: true}
		);
	}
});










