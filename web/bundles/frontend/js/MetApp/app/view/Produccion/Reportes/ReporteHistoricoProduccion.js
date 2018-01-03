Ext.define('MetApp.view.Produccion.Reportes.ReporteHistoricoProduccion', {
	extend: 'Ext.window.Window',
	layout: 'fit',
	width: 470,
	autoShow: true,
	modal: true,
	alias: 'widget.ReporteHistoricoProduccion', 
	layout: 'vbox',
	title: 'Reporte Histórico de Produccón',
	
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
							xtype: 'container',
							layout: 'hbox',
							items: [
								{
									xtype: 'container',
									layout: 'hbox',
									items: [
										{
											xtype: 'textfield',
											itemId: 'codigo1',
											name: 'codigo1',
											labelWidth: 40,
										},
										{
											xtype: 'button',
											itemId: 'btnCodigo1',
											iconCls: 'search',
											margin: '2 0 0 2'
										},
									]
								},
								{
									xtype: 'container',
									layout: 'hbox',
									items: [
										{
											xtype: 'textfield',
											margins: '0 0 0 5',
											itemId: 'codigo2',
											name: 'codigo2',
											labelWidth: 40,
										},
										{
											xtype: 'button',
											itemId: 'btnCodigo2',
											iconCls: 'search',
											margin: '2 0 0 2'
										},
									]
								}	
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
