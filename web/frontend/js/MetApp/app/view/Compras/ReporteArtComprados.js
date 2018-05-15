Ext.define('MetApp.view.Compras.ReporteArtComprados', {
	extend: 'Ext.window.Window',
	layout: 'fit',
	width: 470,
	autoShow: true,
	modal: true,
	alias: 'widget.ReporteArtComprados', 
	layout: 'vbox',
	title: 'Reporte Artículos Comprados',
	
	initComponent: function(){
		var me = this;
		Ext.applyIf(me, {
			items: [
				{
					xtype: 'form',
					itemId: 'fechaForm',
					border: false,
					layout: {
						type: 'vbox',
						align: 'center'	 
					},
					defaults: {
						margin: '2 2 2 2',	
						allowBlank: false
					},
					fieldDefaults: {
						allowBlank: false
					},
					items: [
						{
							xtype: 'container',
							margins: '5 0 0 0',
							layout: 'hbox',
							items: [
								{
									xtype: 'datefield',
									itemId: 'desde',
									name: 'desde',
									submitFormat: 'd/m/Y',
									format: 'd/m/Y',
									fieldLabel: 'Desde',
									labelWidth: 40
								},
								{
									xtype: 'datefield',
									itemId: 'hasta',
									submitFormat: 'd/m/Y',
									format: 'd/m/Y',
									name: 'hasta',
									fieldLabel: 'Hasta',
									margins: '0 0 0 5',
									labelWidth: 40
								},
							]
						},								
						{
							xtype: 'fieldset',
							title: 'Código',
							layout: 'hbox',
							style: {
								'text-align': 'center'
							},
							defaults: {
								margins: '0 0 5 0'
							},
							items: [								
								{
									xtype: 'textfield',
									itemId: 'codigo1',
									name: 'codigo1',
									labelWidth: 40,
									listeners: {
										blur: {
											fn: function(txt){
												if(txt.getValue() == ""){
													txt.setValue(1);
												}
											}, 
										}	
									},		
								},
								{
									xtype: 'button',
									itemId: 'btnCodigo1',
									iconCls: 'search',
									margin: '0 0 0 2'
								},
								{
									xtype: 'textfield',
									margins: '0 0 0 5',
									itemId: 'codigo2',
									name: 'codigo2',
									labelWidth: 40,
									listeners: {
										blur: {
											fn: function(txt){
												if(txt.getValue() == ""){
													txt.setValue("ZZZ");
												}
											}, 
										}	
									},		
								},
								{
									xtype: 'button',
									itemId: 'btnCodigo2',
									iconCls: 'search',
									margin: '0 0 0 2'
								},									
							]
						},
						{
							xtype: 'fieldset',
							title: 'Proveedor',
							layout: 'hbox',
							style: {
								'text-align': 'center'
							},
							defaults: {
								margins: '0 0 5 0'
							},
							items: [								
								{
									xtype: 'textfield',
									itemId: 'proveedor1',
									name: 'proveedor1',
									labelWidth: 40,
									listeners: {
										blur: {
											fn: function(txt){
												if(txt.getValue() == ""){
													txt.setValue(1);
												}
											}, 
										}	
									},		
								},
								{
									xtype: 'button',
									itemId: 'btnProveedor1',
									iconCls: 'search',
									margin: '0 0 0 2'
								},
								{
									xtype: 'textfield',
									margins: '0 0 0 5',
									itemId: 'proveedor2',
									name: 'proveedor2',
									labelWidth: 40,
									listeners: {
										blur: {
											fn: function(txt){
												if(txt.getValue() == ""){
													txt.setValue(999999);
												}
											}, 
										}	
									},		
								},
								{
									xtype: 'button',
									itemId: 'btnProveedor2',
									iconCls: 'search',
									margin: '0 0 0 2'
								},									
							]
						},								
						{
							xtype: 'button',
							itemId: 'printReport',
							action: 'printReport',
							height: 50,
							width: 50,
							iconCls: 'reportes'
						}
					]
				}		
			]
		});
		this.callParent();
	}
});
