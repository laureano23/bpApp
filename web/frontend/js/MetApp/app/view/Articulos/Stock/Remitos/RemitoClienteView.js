Ext.define('MetApp.view.Articulos.Stock.Remitos.RemitoClienteView' ,{
	extend: 'Ext.window.Window',	
	modal: true,
	autoShow: true,
	alias: 'widget.RemitoClienteView',
	itemId: 'RemitoClienteView',
	title: 'Remito a clientes',
	layout: 'fit',
	
	initComponent: function(){
		var me = this;

		var line1 = [
			{
				xtype: 'fieldset',
				title: 'Orígen',
				margins: '0 0 0 5',
				items: [
					{
						xtype: 'radiogroup',
						itemId: 'origen',
						labelWidth: 50,
						margins: '0 0 0 5',
						columns: 2,
						vertical: true,
						width: 270,
			            items: [
			                { boxLabel: 'Proveedor', name: 'origen', inputValue: 'proveedor', width: 80 },
			                { boxLabel: 'Cliente', name: 'origen', inputValue: 'cliente', width: 120, checked: true }
			            ]
					},											
				]
			},
			{
				xtype: 'textfield',
				name: 'idOrigen',
				itemId: 'idOrigen',
				hidden: true
				
			},
			{
				xtype: 'textfield',
				margin: '10 0 0 5',
				width: 400,
				name: 'fuente',
				itemId: 'fuente'
			},
			{
				xtype: 'button',
				itemId: 'buscarOrigen',
				iconCls: 'search',
				margins: '10 0 0 5'
			}
		]
		
		var line2 = [
			{
				xtype: 'grid',
				itemId: 'gridRemito',
				anchorSize: '100%',
				height: 200,	
				autoScroll: true,	
				store: {
					xtype: 'store',
					fields: ['codigo', 'descripcion', 'cantidad', 'unidad', 'oc', 'pedidoNum'],
					proxy: {
						type: 'memory',		
								
						reader: {
							type: 'json',
							root: 'items'			
						},
								
						writer: {
							type: 'json',
							root: 'items',
							encode: true
						}
					}
				},
				columns: [
					{xtype: 'rownumberer', width: 50, sortable: false},						
					{header: 'Codigo', dataIndex: 'codigo', flex: 1},
					{header: 'Descripcion', dataIndex: 'descripcion', width: 400},
					{header: 'Cantidad', dataIndex: 'cantidad', flex: 1},
					{header: 'U', dataIndex: 'unidad', width: 50},
					{header: 'OC', dataIndex: 'oc', flex: 1},
					{header: 'Pedido N°', dataIndex: 'pedidoNum', flex: 1}
				]										
			}			
		]
		
				
		var line3 = [
			{
				xtype: 'textfield',
				name: 'codigo',
				allowBlank: false,
				itemId: 'codigo',
				readOnly: true,
				width: 200,
				labelWidth: 50,
				fieldLabel: 'Codigo:'
			},
			{
				xtype: 'button',	
				margins: '5 5 5 5',	
				height: 25,				
				width: 30,
				iconCls: 'search',
				action: 'buscaArt',
				itemId: 'buscaArt'
			},
			{
				xtype: 'textfield',
				name: 'descripcion',
				itemId: 'descripcion',
				readOnly: true,
				width: 600,
				fieldLabel: 'Descripcion:'
			}
		]
		
		var line4 = [
			{
				xtype: 'numberfield',
				name: 'cantidad',
				itemId: 'cantidad',
				allowBlank: false,
				labelWidth: 55,
				width: 150,
				decimalSeparator: '.',
				fieldLabel: 'Cantidad:'
			},
			{
				xtype: 'textfield',
				name: 'unidad',
				itemId: 'unidad',
				allowBlank: true,
				width: 100,
				labelWidth: 30,
				fieldLabel: 'Un:'
			},
			{
				xtype: 'textfield',
				itemId: 'oc',
				width: 150,
				labelWidth: 40,
				name: 'oc',
				itemId: 'oc',
				fieldLabel: 'O/C :'
			},
			{
				xtype: 'textfield',
				readOnly: true,
				name: 'pedidoNum',
				itemId: 'pedidoNum',
				fieldLabel: 'Pedido N°',
				emptyText: 'Click para ver pedidos'
			},					
		]
		
		var line5= [
			{
				xtype: 'button',
				margins: '5 5 5 5',
				itemId: 'insertarItem',
				action: 'insertarItem',
				text: 'Insertar'				
			},
			{
				xtype: 'button',
				margins: '5 5 5 5',
				itemId: 'editarItem',
				action: 'editarItem',
				text: 'Editar'				
			},
			{
				xtype: 'button',
				margins: '5 5 5 5',
				itemId: 'borrarItem',
				action: 'borrarItem',
				text: 'Borrar'				
			},
			{
				xtype: 'button',
				margins: '5 5 5 5',
				itemId: 'guardarRemito',
				action: 'guardarRemito',
				text: 'Guardar'				
			},
			{
				xtype: 'button',
				margins: '5 5 5 5',
				itemId: 'pedidosAutorizados',
				action: 'pedidosAutorizados',
				text: 'Pedidos Autorizados'				
			},
		]

		Ext.applyIf(me,{
			items: [
				{
					xtype: 'container',				
					fieldDefaults: {
						margins: '5 5 5 5',	
					},					
					items: [
						{
							xtype: 'container',
							fieldDefaults: {
								margins: '5 5 5 5',	
							},	
							border: false,
							items: line1,
							layout: 'hbox'
						},
						{
							xtype: 'container',
							height: 200,
							width: 850,
							border: false,
							items: line2,
							layout: 'anchor'
						},
						{
							xtype: 'form',
							fieldDefaults: {
								margins: '5 5 5 5'
							},	
							border: false,
							frame: false,
							items: [
								{
									xtype: 'container',
									border: false,
									items: line3,
									layout: 'hbox'
								},
								{
									xtype: 'container',
									border: false,
									items: line4,
									layout: 'hbox'
								},
								{
									xtype: 'container',
									border: false,
									items: line5,
									layout: 'hbox'
								}		
							]
						},
								
					]
				}
			]
		});
		this.callParent();
	}
});
