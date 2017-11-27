Ext.define('MetApp.view.Bancos.FormBancosView' ,{
	extend: 'Ext.window.Window',
	height: 520,
	width: 810,
	modal: true,
	autoShow: true,
	alias: 'widget.FormBancosView',
	itemId: 'FormBancosView',
	title: 'Tabla de Bancos',
	layout: 'border',
	
	initComponent: function(){
		var me = this;
		var botonera = Ext.create('MetApp.resources.ux.BtnABMC');
		Ext.applyIf(me,{
			items: [
				{
					xtype: 'form',
					region: 'center',
					margin: '5 5 5 5',
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
							xtype: 'fieldset',
							title: 'Cuentas',
							defaultType: 'textfield',
        					layout: 'hbox',
        					defaults: {
        						margin: '0 0 5 0',
        						readOnly: true,
        					},
							items: [
								{
									fieldLabel: 'Cuenta tipo',
									name: 'cuentaTipoNuevo',
									labelWidth:80,
								},
								{
									fieldLabel: 'Cuenta N°',
									name: 'cuentaNumeroNuevo',
									labelWidth:80,
								},
								{
									fieldLabel: 'CBU',
									name: 'cbuNuevo',
									labelWidth:40,
								}
							]
						},
						{
							xtype: 'grid',
							width: 700,
							store: {
								fields: ['cuentaNro', 'cuentaTipo', 'cbu'],
								proxy: {
									type: 'memory'
								}
							},
							columns: [
								{ text: 'Tipo', dataIndex: 'cuentaTipo', flex: 1 },
								{ text: 'N°', dataIndex: 'cuentaNro', flex: 1 },
								{ text: 'CBU', dataIndex: 'cbu', flex: 1 },
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
					region: 'south',
					items: [
						botonera
					]
				}
			]
		});
		this.callParent();
	}
});
