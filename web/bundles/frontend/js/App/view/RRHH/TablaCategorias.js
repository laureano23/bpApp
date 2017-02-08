Ext.define('MetApp.view.RRHH.TablaCategorias', {
	extend: 'Ext.window.Window',
	modal: true,	
	width: 580,
	height: 250,
	layout: 'border',
	border: false,
	itemId: 'categoriasTabla',
	alias: 'widget.categoriasTabla',	
	autoShow: true,
	title: 'Tabla de Categorias',
	defaults: {
		border: false,
		frame: false
	},
	
	initComponent: function(){
		var me = this;
		
		var botonera = Ext.create('MetApp.resources.ux.BtnABMC');
		
		
		Ext.applyIf(me,{
			items: [
				{
					xtype: 'form',
					region: 'center',
					frame: false,
					border: false,					
					layout: 'vbox',					
					items: [
						{
							xtype: 'container',
							layout: 'hbox',
							defaults: {
								disabled: true,
								disabledCls: 'myDisabledClass',
								margins: '5 5 5 5',
							},
							items: [
								{							
								xtype: 'numberfield',
								itemId: 'idCategoria',
								name: 'id',
								width: 20,
								name: 'idCategoria',								
								disabled: true,
								hidden: true	
								},
								{							
									xtype: 'textfield',	
									allowBlank: 'false',						
									name: 'categoria',
									allowBlank: false,
									itemId: 'categoria',
									margins: '5 5 5 5',
									fieldLabel: 'Categoria',
								},
								{
									xtype: 'button',
									disabled: false,
									itemId: 'searchCategoria',
									margins: '5 5 5 5',
									height: 25,
									width: 25,
									iconCls: 'search'
								},
							]
						},
						{
							xtype: 'numberfield',
							allowBlank: false,
							decimalSeparator: '.',
							name: 'salario',
							itemId: 'salario',
							fieldLabel: 'Salario',		
							disabled: true,
							disabledCls: 'myDisabledClass',
							margins: '5 5 5 5',							
						},
						{
							xtype: 'combobox',
							itemId: 'comboSindicato',
							store: 'MetApp.store.Personal.SindicatosStore',
							displayField: 'sindicato',
							valueField: 'idSindicato',
							allowBlank: false,
							name: 'idSindicato',
							fieldLabel: 'Sindicato',
							disabled: true,
							disabledCls: 'myDisabledClass',
							margins: '5 5 5 5',	
						}						
					]
				},						
				{
					xtype: 'container',
					padding: '0 5 0 5',
					region: 'south',
					layout: 'vbox',		
					items: [botonera]
				}			
			]
		})
		
		me.callParent();
	}
});
