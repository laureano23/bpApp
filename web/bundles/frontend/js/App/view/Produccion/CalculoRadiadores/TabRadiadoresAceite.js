Ext.define('MetApp.view.Produccion.CalculoRadiadores.TabRadiadoresAceite', {
	extend: 'Ext.tab.Panel',
	alias: 'widget.tabradiadoresaceite',
	width: 400,
    height: 400,
    tabPosition: 'bottom',
	
	initComponent: function(){
		var me = this;		
		Ext.applyIf(me,{
			items: [
				{
					xtype: 'wincalculo'
				}				
			]
		});
		this.callParent();
	},
	renderTo : Ext.getBody()		
});
