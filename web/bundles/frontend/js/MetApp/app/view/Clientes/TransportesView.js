Ext.define('MetApp.view.Clientes.TransportesView' ,{
	extend: 'Ext.window.Window',
	height: 350,
	width: 900,
	modal: true,
	autoShow: true,
	alias: 'widget.TransportesView',
	itemId: 'TransportesView',
	title: 'Tabla de Transportes',
	layout: 'border',
	bodyStyle: {
		background: 'white'
	},

	
	initComponent: function(){
		var me = this;
		Ext.applyIf(me,{
			items: [
				{
					xtype: 'grid',
					region: 'center',
					store: 'MetApp.store.Clientes.TransportesStore',
					columns: [
						{ header: 'Nombre', dataIndex: 'nombre', hidden: true },
						{ header: 'Nombre', dataIndex: 'nombre' },
						{ header: 'Direccion', dataIndex: 'direccion' },
						{ header: 'Teléfono', dataIndex: 'telefono' },
						{ header: 'Contacto', dataIndex: 'contacto' },
						{ header: 'Horarios', dataIndex: 'horarios' },
						{ header: 'Provincia', dataIndex: 'provincia' },
						{ header: 'Partido', dataIndex: 'departamento' },						
						{ 
							header: 'Editar',
							itemId: 'editar',
							xtype: 'actioncolumn',
							iconCls: 'edit'							
						},
						{ 
							header: 'Eliminar',
							itemId: 'eliminar',
							xtype: 'actioncolumn',
							iconCls: 'delete'							
						},
					]
				},
				{
					xtype: 'form',
					margins: '5 5 5 5',
					border: false,
					region: 'south',
					defaults: {
						margins: '5 5 0 0'
					},
					layout: 'vbox',
					items: [
						{
							xtype: 'container',
							defaults: {
								margins: '0 5 0 0',
								labelWidth: 60
							},
							layout: 'hbox',
							items: [
								{
									xtype: 'textfield',
									itemId: 'id',
									name: 'id',
									fieldLabel: 'id',
									hidden: true
								},
								{
									xtype: 'textfield',
									itemId: 'nombre',
									name: 'nombre',
									fieldLabel: 'Nombre'
								},
								{
									xtype: 'textfield',
									itemId: 'direccion',
									name: 'direccion',
									fieldLabel: 'Dirección'
								}
							]
						},
						{
							xtype: 'container',
							layout: 'hbox',
							defaults: {
								margins: '0 5 0 0',
								labelWidth: 60
							},
							items: [
								{
									xtype: 'combobox',
									name: 'provincia',
									itemId: 'comboProv',
									store: 'MetApp.store.Personal.ProvinciasStore',
									displayField: 'nombre',
									forceSelection: true,
									queryMode: 'local',
									valueField: 'idProvincia',
									typeAhead: true,							
									minChars: 1,
									fieldLabel: 'Provincia',
								},
								{
									xtype: 'combobox',
									forceSelection: true,
									queryMode: 'local',
									name: 'departamento',
									itemId: 'comboPartido',
									store: 'MetApp.store.Personal.PartidosStore',
									displayField: 'nombre',
									valueField: 'idPartido',
									typeAhead: true,
									minChars: 1,
									fieldLabel: 'Partido',
								},
							]
						},
						{
							xtype: 'container',
							layout: 'hbox',
							defaults: {
								margins: '0 5 0 0',
								labelWidth: 60
							},
							items: [
								{
									xtype: 'textfield',
									itemId: 'contacto',
									name: 'contacto',
									fieldLabel: 'Contacto'
								},
								{
									xtype: 'textfield',
									itemId: 'horarios',
									name: 'horarios',
									fieldLabel: 'Horarios'
								}
							]
						},
						{
							xtype: 'textfield',
							itemId: 'telefono',
							name: 'telefono',
							labelWidth: 60,
							width: 350,
							fieldLabel: 'Teléfono'
						},
						{
							xtype: 'button',
							text: 'Insertar',
							itemId: 'insert'
						}
					]
				},
								
			]
		});
		this.callParent();
	}
});
