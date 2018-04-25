Ext.define('MetApp.view.Reportes.ReporteCCCliente', {
	extend: 'Ext.window.Window',
	layout: 'fit',
	autoShow: true,
	modal: true,
	alias: 'widget.ReporteCCCliente', 
	layout: 'fit',
	title: 'Reporte Resumen Cuenta Corriente Cliente',
	
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
							xtype: 'datefield',
							itemId: 'desde',
							name: 'desde',
							submitFormat: 'd/m/Y',
							format: 'd/m/Y',
							fieldLabel: 'Desde',
							labelWidth: 40
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
