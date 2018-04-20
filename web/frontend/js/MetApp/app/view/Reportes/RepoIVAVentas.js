Ext.define('MetApp.view.Reportes.RepoIVAVentas', {
	extend: 'Ext.window.Window',
	layout: 'fit',
	width: 300,
	autoShow: true,
	modal: true,
	alias: 'widget.RepoIVAVentas',
	layout: 'vbox',
	title: 'Reporte IVA ventas',
	itemId: 'RepoIVAVentas',
	listeners: {
		render: {
			fn: function(el){
				el.queryById('desde').focus('', 50);
			}
		}
	},
	
	initComponent: function(){
		var me = this;
		Ext.applyIf(me, {
			items: [
				{
					xtype: 'form',
					itemId: 'fechaForm',
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
							allowBlank: false,
							itemId: 'desde',
							format: 'd/m/Y',
							submitFormat: 'd/m/Y',
							name: 'desde',
							fieldLabel: 'Desde',
							labelWidth: 40
						},
						{
							xtype: 'datefield',
							format: 'd/m/Y',
							submitFormat: 'd/m/Y',
							allowBlank: false,
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
