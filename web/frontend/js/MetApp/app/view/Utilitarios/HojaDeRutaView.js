Ext.define('MetApp.view.Utilitarios.HojaDeRutaView' ,{
	extend: 'Ext.window.Window',
	height: 400,
	width: 1200,
	modal: true,
	autoShow: true,
	alias: 'widget.HojaDeRutaView',
	itemId: 'HojaDeRutaView',
	title: 'Hoja de Ruta',
	layout: 'border',
	bodyStyle:{"background-color":"white"}, 
	
	initComponent: function(){
		var me = this;
		var user = MetApp.User.name.name;
		
		Ext.applyIf(me,{
			items: [
				{
					xtype: 'container',
					bodyStyle:{"background-color":"red"}, 
					margin: '5 5 5 5',	
					region: 'north',
					items: [
						{
							xtype: 'datefield',
							fieldLabel: 'Emisión',
							name: 'fechaEmision',
							readOnly: true,
							value: new Date()							
						}
					]
				},				
				{
					xtype: 'grid',
					store: 'MetApp.store.Utilitarios.HojaDeRutaStore',
					plugins: [
						Ext.create('Ext.grid.plugin.CellEditing',{
							clicksToEdit: 1,
							pluginId: 'cellplugin',
						})
					],
					selType: 'cellmodel',
					viewConfig: {
						enableTextSelection: true,
						preserveScrollOnRefresh: true,
					},
					region: 'center',
					columns: {
						defaults: {
							sortable: false
						},
						items: [
							{ text: 'Id', dataIndex: 'idViaje', hidden: true },
							{ text: 'Desde', dataIndex: 'fechaDesde', flex:1 },
							{ text: 'Hasta', dataIndex: 'fechaHasta', flex:1 },
							{ text: 'Nombre', dataIndex: 'nombre', flex:1 },
							{ text: 'Domicilio', dataIndex: 'domicilio', width: 250 },
							{ text: 'Descripción', dataIndex: 'descripcion', width: 300 },
							{ text: 'Horarios', dataIndex: 'horarios', flex:1 },
							{ text: 'Emisor', dataIndex: 'emisor', width: 70 },
							{							
								header: 'Estado',
								width: 70,
								dataIndex: 'estado',
								editor: {
									xtype: 'combobox',
									width: 250,										
									typeAhead: true,
									displayField: 'estado',
									store: {
										fields: ['estado'],
										data : [
											{"estado":"Pendiente"},
											{"estado":"Terminado"}
										]
									}
								   },
								renderer: function(value, metaData, record, row, col, store, gridView){
									return value == "Pendiente" ? '<span style="color:red;">'+value+'</span>' :
										 value == "Terminado" ? '<span style="color:green;">'+value+'</span>' :
										 "";
								},									
							},
							{ 
								header: 'Editar',
								itemId: 'editar',
								xtype: 'actioncolumn',
								items: [
									{ iconCls: 'edit' }										
								],
								flex: 1
							},
							{ 
								header: 'Borrar',
								itemId: 'borrar',
								xtype: 'actioncolumn',
								items: [
									{ iconCls: 'delete' }										
								],
								flex: 1
							}
						]
					}
				},
				{
					xtype: 'form',
					store: 'MetApp.store.Utilitarios.HojaDeRutaStore',
					border: false,
					region: 'south',
					defaults: {
						margin: '5 5 5 5',
					},
					items: [
						{
							xtype: 'container',
							layout: 'vbox',
							items: [
								{
									xtype: 'container',
									layout: 'hbox',
									defaults: {
										margin: '5 5 5 5',
									},
									items: [
										{
											xtype: 'textfield',
											name: 'idViaje',
											itemId: 'idViaje',
											hidden: true
										},										
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
										{
											xtype: 'button',
											iconCls: 'search',
											itemId: 'buscar',
											height: 25,
											width: 30,
										},
										{
											xtype: 'textfield',
											name: 'nombre',
											width: 500,
											itemId: 'nombre'
										}
									]
								},
								{
									xtype: 'container',
									layout: 'hbox',
									defaults: {
										margin: '5 5 5 5',
									},
									items: [
										{
											xtype: 'textfield',
											name: 'domicilio',
											itemId: 'domicilio',
											fieldLabel: 'Domicilio',
											width: 600
										},
										{
											xtype: 'textfield',
											name: 'horarios',
											itemId: 'horarios',
											fieldLabel: 'Horarios'
										}
									]
								},
								{
									xtype: 'container',
									layout: 'hbox',
									defaults: {
										margin: '5 5 5 5',
									},
									items: [
										{
											xtype: 'textfield',
											name: 'descripcion',
											fieldLabel: 'Descripción',
											width: 600
										},
										{
											xtype: 'textfield',
											name: 'emisor',
											fieldLabel: 'Emisor',
											readOnly: true,
											value: user
										},
									]
								},
								{
									xtype: 'container',
									layout: 'hbox',
									defaults: {
										margin: '5 5 5 5',
									},
									items: [
										{
											xtype: 'datefield',
											name: 'fechaDesde',
											fieldLabel: 'Desde'
										},
										{
											xtype: 'datefield',
											name: 'fechaHasta',
											fieldLabel: 'Hasta'
										},
										{
											xtype: 'button',
											itemId: 'insert',
											text: 'Insertar'
										}
									]
								}
							]
						}
					]
				},
					
						
			]
		});
		this.callParent();
	}
});
