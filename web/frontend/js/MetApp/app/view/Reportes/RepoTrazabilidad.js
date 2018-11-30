Ext.define('MetApp.view.Reportes.RepoTrazabilidad', {
	extend: 'Ext.window.Window',
	layout: 'fit',
	width: 310,
	autoShow: true,
	modal: true,
	alias: 'widget.RepoTrazabilidad',
	layout: 'vbox',
	title: 'Reporte de Trazabilidad',
	itemId: 'RepoTrazabilidad',
	listeners: {
		render: {
			fn: function(el){
				el.queryById('correlativo').focus('', 50);
			}
		}
	},
	
	initComponent: function(){
		var me = this;
		Ext.applyIf(me, {
			items: [
				{
					xtype: 'form',
					itemId: 'form',
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
							allowBlank: false,
							itemId: 'correlativo',
							name: 'correlativo',
							fieldLabel: 'Correlativo'
						},
						{
							xtype: 'button',
							itemId: 'printReport',
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
