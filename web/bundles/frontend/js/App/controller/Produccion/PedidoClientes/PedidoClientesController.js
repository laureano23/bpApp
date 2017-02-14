Ext.define('MetApp.controller.Produccion.PedidoClientes.PedidoClientesController', {
	extend: 'Ext.app.Controller',
	views: [
		'Produccion.Pedidos.NuevoPedidoForm',
		'Produccion.Pedidos.Reportes.ReportePedidos'
	],
	stores: [
		'Produccion.PedidoClientes.PedidoClientesStore',
	],
	
	
	init: function(){
		var me = this;
		me.control({
			'#nuevoPedido': {
				click: this.AddWin 
			},
			'nuevoPedidoForm button[action=buscaCliente]': {
				click: this.BuscaCliente 
			},
			'nuevoPedidoForm button[action=buscaArt]': {
				click: this.BuscaArticulo
			},
			'nuevoPedidoForm button[action=insertPedido]': {
				click: this.InsertarArticulo
			},
			'nuevoPedidoForm button[action=editPedido]': {
				click: this.EditarArticulo
			},
			'nuevoPedidoForm button[action=deletePedido]': {
				click: this.BorrarArticulo
			},
			'nuevoPedidoForm button[action=savePedido]': {
				click: this.GuardarPedido
			},
			'#articulosPedidos': {
				click: this.NewFormReportePedidos
			},
			'repoPedidos button[action=buscaClienteDesde]': {
				click: this.BuscaClienteDesde
			},
			'repoPedidos button[action=buscaClienteHasta]': {
				click: this.BuscaClienteHasta
			},
			'repoPedidos button[action=buscaArtDesde]': {
				click: this.BuscaArtDesde
			},
			'repoPedidos button[action=buscaArtHasta]': {
				click: this.BuscaArtHasta
			}
		});
	},
	
	AddWin: function(btn){
		win = Ext.widget('nuevoPedidoForm');
		searchBtn = win.queryById('buscaCliente');
		searchBtn.focus('',true); //FOCUS SOBRE EL BOTON DE BUSQUEDA
		
	},
	
	BuscaCliente: function(btn){		
		var win = Ext.widget('clientesSearchGrid'); //VENTANA DE BUSQUEDA DE CLIENTES
		var form = btn.up('form'); //FORMULARIO DE PEDIDOS
		var store = win.down('grid').getStore();
		var artBtn = btn.up('window').queryById('buscaArt');
		 
		
		store.clearFilter(true);
		store.load();
		var input = win.down('textfield');
		input.focus('', 10); // Le damos un tiempo al metodo focus para que el form termine de renderizar
		var button = win.down('button');
		
		button.on('click', function(){
			var grid = button.up('grid');
			var selection = grid.getSelectionModel().getSelection()[0];	
			var cliente = form.queryById('cliente');
			var idCliente = form.queryById('idCliente');		
		
			cliente.setValue(selection.data.rsocial);
			idCliente.setValue(selection.data.id); 
			win.close();
			artBtn.setDisabled(false);
			artBtn.focus('', 10);
		});
	},
	
	BuscaArticulo: function(btn){
		var searchForm = Ext.widget('winarticulosearch');
		var store = searchForm.down('grid').getStore();
		store.clearFilter(true);
		var button = searchForm.queryById('insertArt');
		var inputCantidad = btn.up('form').queryById('cantidad');
		/*
		 * Escuchamos al boton de la ventana de busqueda
		 */
		button.on('click',function(){
			var grid = button.up('grid');
			var selection = grid.getSelectionModel().getSelection()[0];
			var formPedido = btn.up('form');
						
			formPedido.loadRecord(selection);				
			button.up('window').close();
			inputCantidad.focus('', true);
		});	
	},
	
	//INSERTA ARTICULO EN LA GRILLA, NO GUARDA EN LA BD
	InsertarArticulo: function(btn){
		var win = btn.up('window');
		var store = win.down('grid').getStore();
		var form = btn.up('form');
		var cliente = form.queryById('cliente');
		var clienteValue = cliente.getValue();
		var idCliente = form.queryById('idCliente');
		var idClienteValue = idCliente.getValue();
		var oc = form.queryById('oc');
		var ocValue = oc.getValue();
		
		if(form.isValid()){
			values = form.getValues();
			
			record = Ext.create('MetApp.model.Produccion.PedidoClientes.PedidosClientesModel');
			record.set(values);
			
			store.add(record);
			
			form.getForm().reset();	
			
			cliente.setValue(clienteValue); //VOLVEMOS A SETEAR EL CLIENTE
			idCliente.setValue(idClienteValue); //VOLVEMOS A SETEAR EL CLIENTE
			oc.setValue(ocValue); //VOLVEMOS A SETEAR EL VALOR DE LA OC
			form.queryById('buscaArt').focus();
		}
	},
	
	EditarArticulo: function(btn){
		grid1 = btn.up('form').down('grid');
		records = grid1.getSelectionModel().getSelection()[0];
		
		if(records){
			form = btn.up('form'); 
		    form.loadRecord(records); //Cargamos el modelo seleccionado de la grilla al form
		    store = grid1.getStore();
		    store.remove(records);		    
		}else{
			Ext.MessageBox.show({
				title: 'Atencion',
				msg: 'Debe seleccionar un registro de la grilla',
				buttons : Ext.MessageBox.OK
			});
		}	
	},
	
	BorrarArticulo: function(btn){
		grid1 = btn.up('form').down('grid');
		records = grid1.getSelectionModel().getSelection()[0];
		
		if(records){
			store = grid1.getStore();
		    store.remove(records);		    
		}else{
			Ext.MessageBox.show({
				title: 'Atencion',
				msg: 'Debe seleccionar un registro de la grilla',
				buttons : Ext.MessageBox.YESNO
			});
		}	
	},
	
	GuardarPedido: function(btn){
		var win = btn.up('window');
		var form = btn.up('form');
		var grid1 = form.down('grid');
		var store = grid1.getStore();
		var values = [];
		
		store.each(function (rec){
			values.push(rec.data);
		});
		
		values = Ext.JSON.encode(values);
		
		numRecords = store.count();
		
		if(numRecords == 0){
			Ext.MessageBox.show({
				title: 'Atencion',
				msg: 'Debe ingresar algun articulo',
				buttons : Ext.MessageBox.OK
			});
		}else{
			Ext.Ajax.request({
				url: Routing.generate('mbp_produccion_nuevo_pedido'),
				
				params: {
					data: values,
					cliente: win.queryById('idCliente').getValue(),
					oc: win.queryById('oc').getValue()
				},
				
				success: function(resp, opt){
					resp = Ext.JSON.decode(resp.responseText);
					Ext.MessageBox.show({
						title: 'Atencion',
						msg: resp.msg,
						buttons : Ext.MessageBox.OK
					});
					form.getForm().reset();
					store.removeAll();
				}
			});	
		}
	},

	//FORMULARIO REPORTE DE PEDIDOS PENDIENTES
	NewFormReportePedidos: function(button){
		var repo = Ext.widget('repoPedidos');
		var btnSubmit = repo.queryById('newReportePedidos');
		
		btnSubmit.on('click', function(){
			var form = btnSubmit.up('form');
			var values = form.getForm().getValues();
			
			var jsonVal = Ext.JSON.encode(values);
			
			Ext.Ajax.request({
				url: Routing.generate('mbp_produccion_reporte_pedido'),
				
				params: {
					data: jsonVal
				},
				
				success: function(resp, opt){
					var jsonReporte = Ext.JSON.decode(resp.responseText);
					Reporte=jsonReporte.reporte;
					
					var ruta = Routing.generate('mbp_produccion_reporte_pedidoPdf');
									
					var WinReporte=Ext.create('Ext.Window', {
					  title: 'Recibos de sueldo',
					  width: 900,
					  height: 600,
					  layout: 'fit',
					  modal:true,										
					  html: '<iframe src='+ruta+' width="100%" height="100%"></iframe>'						  
				  });
				  WinReporte.show();
				}
			});
		});
	},

	//BUSCADOR DE CLIENTES
	BuscaClienteDesde: function(btn){
		clientes = Ext.widget('clientesSearchGrid');
		store = clientes.down('grid').getStore();
		form = btn.up('form');
		btnAceptar = clientes.queryById('insertCliente');
		
		store.clearFilter(true);
		store.load();
		btnAceptar.on('click', function(){
			var grid = btnAceptar.up('grid');
			var selection = grid.getSelectionModel().getSelection()[0];	
			var cliente = form.queryById('clienteDesde');		
		
			cliente.setValue(selection.data.id); 
			clientes.close();
		});
	},
	
	//BUSCADOR DE CLIENTES
	BuscaClienteHasta: function(btn){
		clientes = Ext.widget('clientesSearchGrid');
		store = clientes.down('grid').getStore();
		form = btn.up('form');
		btnAceptar = clientes.queryById('insertCliente');
		
		store.clearFilter(true);
		btnAceptar.on('click', function(){
			var grid = btnAceptar.up('grid');
			var selection = grid.getSelectionModel().getSelection()[0];	
			var cliente = form.queryById('clienteHasta');		
		
			cliente.setValue(selection.data.id); 
			clientes.close();
		});
	},
	
	//BUSCADOR DE ARTICULOS
	BuscaArtDesde: function(btn){
		var searchForm = Ext.widget('winarticulosearch');
		var store = searchForm.down('grid').getStore();
		store.clearFilter(true);
		var button = searchForm.queryById('insertArt');
		
		button.on('click',function(){
			var grid = button.up('grid');
			var selection = grid.getSelectionModel().getSelection()[0];
			var artDesde = btn.up('form').queryById('articuloDesde');
						
			artDesde.setValue(selection.data.codigo);				
			button.up('window').close();
		});	
	},
	
	//BUSCADOR DE ARTICULOS
	BuscaArtHasta: function(btn){
		var searchForm = Ext.widget('winarticulosearch');
		var store = searchForm.down('grid').getStore();
		store.clearFilter(true);
		var button = searchForm.queryById('insertArt');
		
		button.on('click',function(){
			var grid = button.up('grid');
			var selection = grid.getSelectionModel().getSelection()[0];
			var artHasta = btn.up('form').queryById('articuloHasta');
						
			artHasta.setValue(selection.data.codigo);				
			button.up('window').close();
		});	
	},
	
});



























