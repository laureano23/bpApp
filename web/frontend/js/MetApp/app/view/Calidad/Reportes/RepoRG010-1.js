Ext.define('MetApp.view.Calidad.Reportes.RepoRG010-1', {
	extend: 'Ext.window.Window',
	height: 450,
	width: 300,
	autoShow: true,
	modal: true,
	alias: 'widget.repo1RG010',
	layout: 'vbox',
	title: 'Reporte controle de estanqueidad RG-010',
	
	initComponent: function(){
		var me = this;
		Ext.applyIf(me, {
			items: [
				{
					xtype: 'container',
					height: 100,
					layout: 'vbox',
					items: [
						{
							xtype: 'radiofield',
							boxLabel: 'Por OT',
							name: 'tipo',
							checked: true,
							inputValue: 'ot',
							itemId: 'tipoOt'							
						},
						{
							xtype: 'radiofield',
							boxLabel: 'Por Fechas',
							name: 'tipo',
							inputValue: 'fecha',
							itemId: 'tipoFecha'						
						}
					]
				},
				{
					xtype: 'form',
					itemId: 'otForm',
					title: 'Por OT',
					collapsible: true,
					width: 295,
					border: false,
					layout: {
						type: 'vbox',
						align: 'center'	
					},
					defaults: {
						margin: '2 2 2 2',	
					},
					fieldDefaults: {
						allowBlank: false
					},
					items: [
						{
							xtype: 'numberfield',
							itemId: 'ot',
							fieldLabel: 'O.T',
							labelWidth: 30
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
				},
				{
					xtype: 'form',
					itemId: 'fechaForm',
					title: 'Por Fechas',
					collapsible: true,
					collapsed: true,
					disabled: true,
					width: 295,
					border: false,
					layout: {
						type: 'vbox',
						align: 'center'	
					},
					defaults: {
						margin: '2 2 2 2',	
					},
					fieldDefaults: {
						allowBlank: false
					},
					items: [
						{
							xtype: 'datefield',
							itemId: 'desde',
							name: 'desde',
							fieldLabel: 'Desde',
							labelWidth: 40
						},
						{
							xtype: 'datefield',
							itemId: 'hasta',
							name: 'hasta',
							fieldLabel: 'Hasta',
							labelWidth: 40
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
