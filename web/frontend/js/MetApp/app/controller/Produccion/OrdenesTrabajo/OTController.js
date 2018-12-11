Ext.define('MetApp.controller.Produccion.OrdenesTrabajo.OTController', {
	extend: 'Ext.app.Controller',
	
	views: [
		'MetApp.view.Produccion.OrdenesTrabajo.NuevaOTView',
		'MetApp.view.Clientes.SearchGridClientes',
		'MetApp.view.Articulos.WinArticuloSearch',
		'MetApp.view.Produccion.OrdenesTrabajo.CierreOTView',
		'MetApp.view.Produccion.OrdenesTrabajo.VerOTView',
		'MetApp.view.Produccion.Pedidos.PedidosPendientesView',
		'MetApp.view.Produccion.OrdenesTrabajo.AsociarOTView'
	],
	
	stores: [
		'Produccion.OrdenesTrabajo.CierreOTGridStore',
		'MetApp.store.Produccion.OrdenesTrabajo.OTStore',
		'MetApp.store.Produccion.OrdenesTrabajo.OTStoreEmisor',
		'Produccion.PedidoClientes.PedidoClientesStore'
	],
	
	refs:[
		{
			ref:'NuevaOTView',
			selector: 'NuevaOTView'
		},
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
			'NuevaOTView button[itemId=pedidos]': {
				click: this.AsociarPedidos
			},
			'PedidosPendientesView button[itemId=insert]': {
				click: this.InsertarPedidos
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
			'#asociarOt': {
				click: this.AsociarOTView
			},
			'AsociarOTView button[itemId=buscarPedido]': {
				click: this.BuscarPedidos
			},
			'AsociarOTView button[itemId=insert]': {
				click: this.AsociarPedidoOT
			},
			'AsociarOTView button[itemId=borrarAsociacion]': {
				click: this.BorrarAsociacionPedido
			},
		});
	},

	BorrarAsociacionPedido: function(btn){
		var win=btn.up('window');
		var form=win.down('form').getForm();

		form.submit({
			url: Routing.generate('mbp_produccion_asociarOTConPedido'),
			params: {
				borrar: true
			},
			success: function(form, action){
				var jsonResp=Ext.JSON.decode(action.response.responseText);
				
				if(jsonResp.success){
					Ext.Msg.show({
						title: 'Info',
						msg: 'Proceso exitoso'
					})
				}
			}
		})
	},

	AsociarPedidoOT: function(btn){
		var win=btn.up('window');
		var form=win.down('form').getForm();

		form.submit({
			url: Routing.generate('mbp_produccion_asociarOTConPedido'),

			success: function(form, action){
				var jsonResp=Ext.JSON.decode(action.response.responseText);
				
				if(jsonResp.success){
					Ext.Msg.show({
						title: 'Info',
						msg: 'Proceso exitoso'
					})
				}
			}
		})
	},

	BuscarPedidos: function(btn){
		var win=Ext.widget('ModificacionPedidosView');
		win.down('grid').getStore().load();
	},
	
	AsociarOTView: function(btn){
		var win=Ext.widget('AsociarOTView');
	},

	InsertarPedidos: function(btn){
		var grid = btn.up('window').down('grid');
		var store = grid.getStore();
		var sel = [];
		
		store.each(function(rec){
			if(rec.data.marcar == "true"){
				sel.push(rec);
			}
		});
		
		var viewProgramacion = this.getNuevaOTView();
		
		var pedidosTxt = viewProgramacion.queryById('pedidosAsociados');
		pedidosTxt.reset();
		console.log(sel);
		for (i = 0; i < sel.length; i++) {
		    if(pedidosTxt.getValue() == ""){
				pedidosTxt.setValue(String(sel[i].data.idDetalle));
			}else{
				pedidosTxt.setValue(pedidosTxt.getValue() + ',' + sel[i].data.idDetalle);	
			}		    
		}
		
		btn.up('window').close();
	},
	
	AsociarPedidos: function(btn){
		var viewOT = btn.up('window');
		var view = Ext.create('MetApp.view.Produccion.Pedidos.PedidosPendientesView');
		var gridPedidos = view.down('grid');
				
		//agregamos a la vista una columna checkbox
		var column = Ext.create('Ext.grid.column.CheckColumn', {
	        text: 'Marcar',
	        dataIndex: 'marcar',
	    });
	    
	    gridPedidos.headerCt.insert(
	      gridPedidos.columns.length, // that's index column
	      column);
		
		
		var store = gridPedidos.getStore();
		
		var codigo = viewOT.queryById('codigo').getValue();
		store.getProxy().setExtraParam('codigo', codigo);
		store.load({
			callback: function(){
				store.suspendEvent('update');		
			}
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
			win.queryById('sector').focus('', 20);
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
		
		
		var values = form.getValues();
		var otAsociadas = grid.getSelectionModel().getSelection();
					
		var jsonArray = [];
		var i=0;
		otAsociadas.forEach(function(e){
			jsonArray[i] = e.data;
			i++;
		});

		jsonArray = Ext.JSON.encode(jsonArray);		
		
		form.getForm().submit({
			clientValidation: true,
			target: '_blank',
			params: {
				data: Ext.JSON.encode(values),
				otAsociada: jsonArray
			},
			url: Routing.generate('mbp_produccion_nuevaot'),
			success: function(form, action){
				var jsonResp=Ext.JSON.decode(action.response.responseText);
				if(jsonResp.success){
					form.submit({
						standardSubmit: true,
						target: '_blank',
						params: {
							ot: jsonResp.ot,
						},
						url: Routing.generate('mbp_produccion_generarOt'),
					})					
				}
				form.reset();
			}
		})
				
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

		var form=btn.up('form');
		form.getForm().submit({
			clientValidation: false,
			standardSubmit: true,
			target: '_blank',
			params: {
				ot: selection.data.otNum
			},
			url: Routing.generate('mbp_produccion_generarOt')
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


















