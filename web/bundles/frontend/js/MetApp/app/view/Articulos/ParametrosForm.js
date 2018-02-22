Ext.define('MetApp.view.Articulos.ParametrosForm', {
	extend: 'Ext.window.Window',
	alias: 'widget.ParametrosForm',
	itemId: 'ParametrosForm',
	width: 800,
	height: 250,
	layout: 'fit',
	autoShow: true,
	title: 'Tabla de Parámetros',
	modal: true,
	
	initComponent: function(){
		var me = this;
		Ext.applyIf(me,{
			items: [
				
				{
					xtype: 'form',
					itemId: 'paramForm',
					margins: '5 0 0 5',	
					border: false,						
					fieldDefaults: {						
						msgTarget: 'side',
						blankText: 'Debe completar este campo',
						allowBlank: false,	
						labelWidth: 130						
					},
					items: [
						{
							xtype: 'container',
							layout: 'hbox',
							items: [
								{
									xtype: 'textfield',
									margin: '0 0 5 0',
									name: 'iva',
									itemId: 'iva',
									fieldLabel: 'Iva'
								},
								{
									xtype: 'label',
									margin: '5 0 0 5',	
									forId: 'iva',
									text: 'Parámetro para utilizar en las ordenes de compra',
									style: {
										'font-weight': 'bold' 
									}
								}
							]
						},								
						{
							xtype: 'textfield',
							name: 'dolarOficial',
							itemId: 'dolarOficial',
							fieldLabel: 'Dolar Oficial'
						},
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
							width: 400
						},
						{
							xtype: 'textfield',
							name: 'remitoNum',
							itemId: 'remitoNum',
							fieldLabel: 'Remito Número'
						},
						{
							xtype: 'textfield',
							name: 'topeRetencionIIBB',
							itemId: 'topeRetencionIIBB',
							fieldLabel: 'Tope Retención IIBB'
						},
						{
							xtype: 'textfield',
							name: 'topePercepcionIIBB',
							itemId: 'topePercepcionIIBB',
							fieldLabel: 'Tope Percepción IIBB'
						},
						{
							xtype: 'button',
							text: 'Guardar',
							itemId: 'guardar',
							name: 'guardar'
						}
					]
				}				
			]
		});
		this.callParent();
	}
});
