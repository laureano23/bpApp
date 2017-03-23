Ext.define('MetApp.view.Produccion.OrdenesTrabajo.NuevaOTView', {
	extend: 'Ext.window.Window',
	alias: 'widget.NuevaOTView',
	itemdId: 'NuevaOTView',
	title: 'Formulario OT',
	layout: 'fit',
	modal: true,
	autoShow: true,
	
	
	initComponent: function(){		
				
		var me = this;
		Ext.applyIf(me, {
			items: [
				{
					xtype: 'form',	
					border: false,
					frame: false,				
					fieldDefaults: {
						margins: '5 5 5 5',	
						allowBlank: false,
						readOnly: true
					},
					defaults: {
						margin: '0 5 0 0'
					},
					layout: 'vbox',					
					items: [
						{
							xtype: 'container',
							layout: 'hbox',
							items: [
								{
									xtype: 'datefield',
									fieldLabel: 'Emisión',
									itemId: 'emision',
									name: 'fechaEmision',
									width: 165,
									labelWidth: 55,
									value: new Date
								},
								{
									xtype: 'textfield',
									fieldLabel: 'Id',
									width: 450,
									labelWidth: 55,		
									name: 'id',
									hidden: true		
								},
								{
									xtype: 'textfield',
									fieldLabel: 'Cliente',
									width: 450,
									labelWidth: 55,		
									name: 'rsocial'							
								},
								{
									xtype: 'button',
									iconCls: 'search',
									margin: '2 0 0 0',
									itemId: 'btnCliente'
								},
							]
						},
						{
							xtype: 'container',
							layout: 'hbox',
							items: [
								{
									xtype: 'textfield',
									fieldLabel: 'Código',
									name: 'codigo',									
									labelWidth: 55
								},
								{
									xtype: 'button',
									itemId: 'btnCodigo',
									iconCls: 'search',
									margin: '2 0 0 0'
								},
								{
									xtype: 'textfield',
									name: 'descripcion',
									fieldLabel: 'Descripción',
									width: 550
								}
							]
						},
						{
							xtype: 'container',
							layout: 'hbox',
							items: [
								{
									xtype: 'numberfield',
									fieldLabel: 'Cantidad',
									name: 'cantidad',
									itemId: 'cantidad',
									width: 200,
									labelWidth: 55
									
								},
								{
									xtype: 'datefield',
									labelWidth: 150,
									itemId: 'fechaProg',
									name: 'fechaProg',
									fieldLabel: 'Fecha de programación',
									width: 270
								}
							]
						},
						{
							xtype: 'container',
							layout: 'hbox',
							items: [
								{
									xtype     : 'textareafield',
									name: 'observaciones',
		        					fieldLabel: 'Observaciones',
		        					width: 500,
		        					allowBlank: true
								},
								{
									xtype: 'fieldset',
									itemId: 'fieldSetDir',
									title: 'Tipo',
									collapsible: false,
									collapsed: false,
									items: [
										{
											xtype: 'radiogroup',
									        //fieldLabel: 'Tipo',
									        labelWidth: 40,
									        width: 350,
									        columns: 2,
									        vertical: true,
									        items: [
									            { boxLabel: 'Mecanizado', name: 'tipo', inputValue: '1', checked: true },
									            { boxLabel: 'Panel', name: 'tipo', inputValue: '2'},
									            { boxLabel: 'Fundicion', name: 'tipo', inputValue: '3' },
									            { boxLabel: 'General', name: 'tipo', inputValue: '4' }
									        ]
										}
									]
								}								
							]
						}		
					],
					buttons: [
						{
							text: 'Nueva',
							itemId: 'nueva',
							iconCls: 'add',
							height: 30
						},
						{
							text: 'Guardar',
							itemId: 'guardar',
							iconCls: 'save',
							height: 30
						},
						{
							text: 'Reset',
							iconCls: 'reset',
							height: 30,							
							handler: function(){
								this.up('form').getForm().reset();
								this.up('form').query('field').forEach(function(field){
									field.setReadOnly(true);		
								});
							}
						},						
					]
				}
			]
		});
		this.callParent();
	}
});
