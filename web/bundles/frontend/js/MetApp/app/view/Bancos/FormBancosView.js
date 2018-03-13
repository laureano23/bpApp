Ext.define('MetApp.view.Bancos.FormBancosView' ,{
	extend: 'Ext.window.Window',
	height: 535,
	width: 850,
	modal: true,
	autoShow: true,
	alias: 'widget.FormBancosView',
	itemId: 'FormBancosView',
	title: 'Tabla de Bancos',
	layout: 'border',
	bodyStyle: {
		background: 'white'
	},

	
	initComponent: function(){
		var me = this;
		var botonera = Ext.create('MetApp.resources.ux.BtnABMC');
		Ext.applyIf(me,{
			items: [
				{
					xtype: 'form',
					region: 'center',
					margin: '5 5 5 5',
					style: {
						background: 'white'
					},
					defaults: {
						margin: '2 0 2 0',						
					},
					layout: 'vbox',
					border: false,
					items: [					
						{
							xtype: 'combo',
							name: 'idBanco',
							store: 'MetApp.store.Personal.BancosStore',
							displayField: 'nombre',							
							fieldLabel: 'Banco',
							itemId: 'banco',
							valueField: 'id',
							allowBlank: false
						},
						{
							xtype: 'container',
							layout: 'hbox',
							defaults: {
								readOnly: true,
								labelWidth: 75,
								margins: '0 5 0 0'
							},
							items: [
								{
									xtype: 'textfield',
									width: 400,
									fieldLabel: 'Dirección',
									name: 'direccion'
								},
								{
									xtype: 'textfield',
									fieldLabel: 'Código postal',
									labelWidth: 90,
									name: 'cp'
								}
							] 
						},
						{
							xtype: 'container',
							layout: 'hbox',
							defaults: {
								readOnly: true,
								labelWidth: 75,
								margins: '0 5 0 0'
							},
							items: [
								{
									xtype: 'textfield',
									fieldLabel: 'Localidad',
									name: 'localidad'
								},
								{
									xtype: 'textfield',
									fieldLabel: 'Teléfonos',
									name: 'telefono'
								},
							] 
						},
						{
							xtype: 'container',
							layout: 'hbox',
							defaults: {
								readOnly: true,
								labelWidth: 75,
								margins: '0 5 0 0'
							},
							items: [
								{
									xtype: 'textfield',
									fieldLabel: 'Email',
									name: 'email',
									width: 310
								},
								{
									xtype: 'textfield',
									fieldLabel: 'CUIT',
									name: 'cuit'
								},
							] 
						},
						{
							xtype: 'form',
							border: false,
							itemId: 'formCuentas',
							items: [
								{
									xtype: 'fieldset',
									title: 'Cuentas',
									defaultType: 'textfield',
		        					layout: 'hbox',
		        					defaults: {
		        						margin: '0 0 5 5',
		        						readOnly: true,      						
		        					},
									items: [
										{
											fieldLabel: 'Cuenta tipo',
											name: 'cuentaTipo',
											labelWidth:80,
										},
										{
											fieldLabel: 'Cuenta N°',
											name: 'cuentaNro',
											labelWidth:80,
										},
										{
											fieldLabel: 'CBU',
											name: 'cbu',
											labelWidth:40,
										},
										{
											xtype: 'button',
											iconCls: 'add2',
											itemId: 'nuevaCuenta'
										},										
									]
								},
							]
						},								
						{
							xtype: 'grid',
							width: 700,							
        					height: 120,
							store: {
								fields: ['cuentaNro', 'cuentaTipo', 'cbu', 'idCuenta'],
								proxy: {
									type: 'memory'
								}
							},
							columns: [
								{ text: 'Id Cuenta', dataIndex: 'idCuenta', flex: 1, hidden: true },
								{ text: 'Tipo', dataIndex: 'cuentaTipo', flex: 1 },
								{ text: 'N°', dataIndex: 'cuentaNro', flex: 1 },
								{ text: 'CBU', dataIndex: 'cbu', flex: 1 },
								{ 
									header: 'Eliminar',
									itemId: 'eliminar',
									xtype: 'actioncolumn',
									iconCls: 'delete'
								},
							]
						},
						{
							xtype: 'container',
							layout: 'hbox',
							defaults: {
								margin: '0 5 5 5',
							},
							items: [
								{
									xtype: 'fieldset',
									title: 'Contacto',
									defaultType: 'textfield',
		        					layout: 'vbox',
		        					defaults: {
										readOnly: true
									},
									items: [
										{
											fieldLabel: 'Nombre',
											name: 'contacto',
										},
										{
											fieldLabel: 'Cargo',
											name: 'cargo',
										},
										{
											fieldLabel: 'Teléfono',
											name: 'telContacto',
										},
										{
											fieldLabel: 'Email',
											name: 'emailContacto',
										}
									]
								},
							]
						},							
					]
				},
				{
					xtype: 'container',
					margins: '0 0 0 5',
					region: 'south',
					style: {
						background: 'white'
					},
					items: [
						botonera
					]
				}
			]
		});
		this.callParent();
	}
});
