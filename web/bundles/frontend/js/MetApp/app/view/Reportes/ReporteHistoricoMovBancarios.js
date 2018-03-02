Ext.define('MetApp.view.Reportes.ReporteHistoricoMovBancarios', {
	extend: 'Ext.window.Window',
	layout: 'fit',
	autoShow: true,
	modal: true,
	alias: 'widget.ReporteHistoricoMovBancarios', 
	layout: 'vbox',
	title: 'Reporte de Movimientos Bancarios',
	
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
									xtype: 'fieldset',
									title: 'Desde',
									items: [
										{
											xtype: 'datefield',
											fieldLabel: 'Fecha',
											itemId: 'fecha1',
											name: 'fecha1'
										},
										{
											xtype: 'combobox',
											store: 'MetApp.store.Bancos.ConceptosBancoStore',
											fieldLabel: 'Concepto',
											itemId: 'concepto1',
											name: 'concepto1',
											displayField: 'concepto',
											valueField: 'id',
											forceSelection: true,
											width: 350
										},
										{
											xtype: 'combobox',
											store: 'MetApp.store.Bancos.CuentasBancoStore',
											fieldLabel: 'Cuenta',
											displayField: 'cuenta',
											itemId: 'cuenta1',
											name: 'cuenta1',
											valueField: 'id',
											forceSelection: true
										}
									]
								},
								{
									xtype: 'fieldset',
									title: 'Hasta',
									items: [
										{
											xtype: 'datefield',
											fieldLabel: 'Fecha',
											itemId: 'fecha2',
											name: 'fecha2'
										},
										{
											xtype: 'combobox',
											store: 'MetApp.store.Bancos.ConceptosBancoStore',
											fieldLabel: 'Concepto',
											itemId: 'concepto2',
											name: 'concepto2',
											displayField: 'concepto',
											valueField: 'id',
											forceSelection: true,
											width: 350
										},
										{
											xtype: 'combobox',
											store: 'MetApp.store.Bancos.CuentasBancoStore',
											fieldLabel: 'Cuenta',
											displayField: 'cuenta',
											itemId: 'cuenta2',
											name: 'cuenta2',
											valueField: 'id',
											forceSelection: true
										}
									]
								},								
							]
						},
						{
							xtype: 'button',
							itemId: 'printDateReport',
							action: 'printDateReport',
							iconCls: 'reportes',
							height: 50,
							width: 50
						}								
					]
				}		
			]
		});
		this.callParent();
	}
});
