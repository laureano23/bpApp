Ext.define('MetApp.view.Produccion.Programacion.Operaciones',{
	extend: 'Ext.window.Window',
	width: 500,
	modal: true,
	layout: 'anchor',
	alias: 'widget.operaciones',
	itemId: 'tablaOperaciones',
	autoShow: true,
	title: 'Tabla de operaciones',
	
	initComponent: function(){
		
		//BARRA DE OPERACIONES
		var btnABMC = Ext.create('MetApp.resources.ux.BtnABMC');
		
		var me = this;
		Ext.applyIf(me, {
			items: [
				{
					xtype: 'form',
					store: 'MetApp.Produccion.Programacion.OperacionesStore',
					border: false,
					anchorSize: '100%',
					margin: '5 5 5 5',
					defaults:{ readOnly: true },
					items: [
						{
							xtype: 'container',
							layout: 'hbox',
							margin: '0 0 5 0',
							items: [
								{
									xtype: 'numberfield',
									readOnly: true,
									name: 'id',
									itemId: 'id',
									fieldLabel: 'N°'
								},
								{
									xtype: 'button',
									action: 'buscaOpe',
									itemId: 'buscaOpe',
									iconCls: 'search',
									margin: '0 0 0 5',
									height: 25,				
									width: 30
								}
							]
						},						
						{
							xtype: 'textfield',
							allowBlank: false,
							name:'descripcion',
							itemId: 'descripcion',
							width: 350,
							fieldLabel: 'Descripcion'
						},
						{
							xtype: 'container',
							layout: 'hbox',
							items: [
								{
									xtype: 'textfield',
									allowBlank: false,
									name:'centroCosto',
									itemId: 'centroCosto',
									readOnly: true,
									width: 350,
									fieldLabel: 'Sector'
								},
								{
									xtype: 'button',
									disabled: true,
									action: 'buscaCentroCostos',
									itemId: 'buscaCentroCostos',
									iconCls: 'search',
									margin: '0 0 0 5',
									height: 25,				
									width: 30
								}
							]
						},
						{
							xtype: 'container',	
							height: 55,
							margin: '5, 0 0 0',
							padding: '1 0 0 1',
							layout: 'hbox',
							cls: 'panelBtn',
							anchorSize: '100%',
							items: btnABMC
						}
					]
				}
			]
		});
	this.callParent();
	},
	
	 
});


