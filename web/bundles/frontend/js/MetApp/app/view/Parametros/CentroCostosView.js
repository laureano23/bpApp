Ext.define('MetApp.view.Parametros.CentroCostosView', {
	extend: 'Ext.window.Window',
	height: 150,
	width: 600,
	modal: true,
	autoShow: true,
	alias: 'widget.CentroCostosView',
	itemId: 'CentroCostosView',
	title: 'Centro de costos',
	layout: 'vbox',
	
	initComponent: function(){
		var me = this;
		var botonera = Ext.create('MetApp.resources.ux.BtnABMC');
		
		Ext.applyIf(me,{
			items:[
				{
					xtype: 'form',
					itemId: 'form',
					border: false,
					store: 'Parametros.CentroCostosStore',
					margins: '5 5 5 5',
					width: 580,
					height: 200,
					items: [
						{
							xtype: 'container',
							layout: 'hbox',
							defaults: {
								disabled: true,
								disabledCls: 'myDisabledClass',								
							},
							items: [
								{							
								xtype: 'numberfield',
								itemId: 'id',
								name: 'id',
								width: 70,							
								disabled: true,
								hidden: true	
								},
								{							
									xtype: 'textfield',	
									allowBlank: 'false',						
									name: 'nombre',
									allowBlank: false,
									itemId: 'centroCosto',
									margins: '5 5 5 0',
									fieldLabel: 'Centro de Costo',
								},
								{
									xtype: 'button',
									disabled: false,
									itemId: 'searchCentroCosto',
									margins: '5 5 5 5',
									height: 25,
									width: 25,
									iconCls: 'search'
								},
							]
						},	
						{
							xtype: 'container',
							margins: '0 0 5 0',
							layout: 'hbox',
							defaults: {
								disabled: true,
								disabledCls: 'myDisabledClass',
							},
							items: [
								{
									xtype: 'numberfield',
									decimalSeparator: '.',
									allowBlank: 'false',
									fieldLabel: 'Costo ($/min)',
									name: 'costo',
									itemId: 'costo',									
								},
								{
									xtype      : 'fieldcontainer',
						            defaultType: 'radiofield',
						            defaults: {
						                flex: 1
						            },
						            layout: 'hbox',
						            items: [
						            	{
						                    boxLabel  : 'Pesos',
						                    checked: true,
						                    name      : 'moneda',
						                    inputValue: 'p',
						                    itemId    : 'pesos'
						                },
						                {
						                    boxLabel  : 'Dolares',
						                    name      : 'moneda',
						                    inputValue: 'd',
						                    itemId 	  : 'dolares'
						                },
						            ]
								},
							]
						},						
						botonera	
					]
				}
			]			
		});		
		this.callParent();
	}
});

