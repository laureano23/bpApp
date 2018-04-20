Ext.define('MetApp.view.Reportes.ReporteIntResarcitorios', {
	extend: 'Ext.window.Window',
	layout: 'fit',
	width: 470,
	autoShow: true,
	modal: true,
	alias: 'widget.ReporteIntResarcitorios', 
	layout: 'vbox',
	title: 'Reporte Intereses por Pago Fuera de Término',
	
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
							title: 'Cliente',
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
									itemId: 'cliente1',
									name: 'cliente1',
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
									itemId: 'btnCliente1',
									iconCls: 'search',
									margin: '0 0 0 2'
								},
								{
									xtype: 'textfield',
									margins: '0 0 0 5',
									itemId: 'cliente2',
									name: 'cliente2',
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
									itemId: 'btnCliente2',
									iconCls: 'search',
									margin: '0 0 0 2'
								},									
							]
						},								
						{
							xtype: 'button',
							itemId: 'printDateReport',
							action: 'printDateReport',
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
