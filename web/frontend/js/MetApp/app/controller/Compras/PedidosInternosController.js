Ext.define('MetApp.controller.Compras.PedidosInternosController',{
	extend: 'Ext.app.Controller',	
	views: [
		'Produccion.Programacion.Programacion',
		'MetApp.view.Compras.PedidosInternosView',
		'MetApp.view.Articulos.ArticuloSearchGrd',
		'MetApp.view.Compras.ListaPedidosInternosView'
	],
	stores: [
		
	],
	
	refs:[
		{
			ref:'',
			selector: ''
		},
	],
	
	init: function(){
		var me = this;
		
		me.control({
			'programacion button[itemId=solicitarMat]': {
				click: this.PedidoInterno
			},
			'PedidosInternosView button[itemId=insertar]': {
				click: this.InsertarArticulo
			},
			'PedidosInternosView button[itemId=guardar]': {
				click: this.GuardarPedidoMat
			},
			'PedidosInternosView actioncolumn[itemId=editar]': {
				click: this.EditarItem
			},
			'PedidosInternosView actioncolumn[itemId=eliminar]': {
				click: this.EliminarItem
			},
			'viewport menuitem[itemId=verPedidosInternos]': {
				click: this.VerPedidosInternos
			},	
			'ListaPedidosInternosView grid': {
				edit: this.CambiarProveedor
			},	
		});		
	},
	
	CambiarProveedor: function(cell, grid){
	
		console.log(grid.record);
		console.log(cell);
		var win=Ext.widget('ProveedoresSearchGrid');

		win.down('button').on('click', function(){
			var model=grid.record;
			var gridCliente=win.down('grid');
			var sel=gridCliente.getSelectionModel().getSelection()[0];
			console.log(sel);
			model.set('proveedor', sel.data.rsocial);
			win.close();
		});
	},

	VerPedidosInternos: function(btn){
		var win = Ext.widget('ListaPedidosInternosView');
		var store = win.down('grid').getStore();
				
		Ext.Ajax.request({
			url: Routing.generate('mbp_pedidosInternos_listarPedidos'),
			
			success: function(resp){
				var data = Ext.JSON.decode(resp.responseText);				
				store.loadRawData(data.data);
			},
			
			failure: function(resp){
				
			}
		});
		
		//SI HAY ALGUN CAMBIO EN LA GRILLA HACEMOS LA LLAMADA AL SERVER
		store.on('update', function(st, rec, op){
			st.suspendEvents();
			Ext.Ajax.request({
				url: Routing.generate('mbp_pedidosInternos_actualizarPedido'),
				
				params: {
					data: Ext.JSON.encode(rec.data)
				},
						
				success: function(resp){
					st.resumeEvents();
					Ext.Ajax.request({
						url: Routing.generate('mbp_pedidosInternos_listarPedidos'),
						
						success: function(resp){
							var data = Ext.JSON.decode(resp.responseText);				
							store.loadRawData(data.data);
						},
						
						failure: function(resp){
							
						}
					});
				},
				
				failure: function(resp){
					
				}
			})	
		});
	},
	
	PedidoInterno: function(btn){
		var pedidoInterno = Ext.widget('PedidosInternosView');
		var btnSearch = pedidoInterno.queryById('btnCodigo');
		
		var store=pedidoInterno.down('grid').getStore();
		store.removeAll();
		btnSearch.focus('', 10); 
		
		btnSearch.on('click', function(){
			var winSearch = Ext.widget('winarticulosearch');
			
			winSearch.down('button').on('click', function(){
				var grid = winSearch.down('grid');
				var sel = grid.getSelectionModel().getSelection()[0];
				
				var formArt = pedidoInterno.queryById('formaArt');				
				formArt.loadRecord(sel);
				console.log(sel);
				
				winSearch.close();
				if(sel.data.codigo == 'ZZZ'){
					var descripcion = pedidoInterno.queryById('descripcion');
					descripcion.setReadOnly(false);
					descripcion.focus('', 10);					
				}else{
					pedidoInterno.queryById('cantidad').focus('', 10);	
				}
			});
		});
	},
	
	InsertarArticulo: function(btn){
		var win = btn.up('window');
		var form=win.queryById('formaArt');
		var values = form.getForm().getValues();
		var comboProv=win.queryById('provSug1');
		var storeProv=comboProv.getStore();

		if(!form.isValid()) return;
		
		var model = Ext.create('MetApp.model.Compras.OrdenCompraModel');

		model.set(values);
		model.set('proveedor', comboProv.rawValue);

		var store = win.down('grid').getStore();
		store.add(model);
		
		win.queryById('formaArt').getForm().reset();
		win.queryById('descripcion').setReadOnly(true);
		win.queryById('btnCodigo').focus('', 10);
	},
	
	GuardarPedidoMat: function(btn){
		var win = btn.up('window');
		
		var store = win.down('grid').getStore();
		
		var data = new Array();
		var i =0;
		
		store.each(function(rec){
			data[i] = rec.getData();
			i++;	
		});
		
		console.log(data);
		var jsonData = Ext.JSON.encode(data);
		Ext.Ajax.request({
			url: Routing.generate('mbp_pedidosInternos_nuevoPedido'),
			
			params: {
				articulos: jsonData,				
			},
			
			success: function(resp){
				var jsonResp = Ext.JSON.decode(resp.responseText);
				if(jsonResp.success == true){
					store.removeAll();
				}
			},
			
			failure: function(resp){
				
			}
		});
	},
	
	EditarItem: function(grid, colIndex, rowIndex){
		var selection = grid.getStore().getAt(rowIndex);
		var win = grid.up('window');
		var form = win.queryById('formaArt');
		
		console.log(selection);
		form.loadRecord(selection);
		form.down('combobox').setRawValue(selection.data.proveedor);
		var store = grid.getStore();
		store.remove(selection);
	},
	
	EliminarItem: function(grid, colIndex, rowIndex){
		var selection = grid.getStore().getAt(rowIndex);
		var win = grid.up('window');
		
		Ext.Msg.show({
			title: 'Atencion',
			msg: 'Desea remover el articulo?',
			buttons: Ext.Msg.YESNO,
			fn: function(btn){
				if(btn == "yes"){
					var store = grid.getStore();
					store.remove(selection);			
				}
			}
		});
	},
});










