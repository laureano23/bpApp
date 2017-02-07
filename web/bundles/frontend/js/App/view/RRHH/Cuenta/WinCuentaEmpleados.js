Ext.define('MetApp.view.RRHH.Cuenta.WinCuentaEmpleados', {
	extend: 'Ext.window.Window',
	modal: true,	
	width: 700,
	height: 500,
	layout: 'border',
	border: false,
	itemId: 'winCuentaEmpleados',
	alias: 'widget.winCuentaEmpleados',	
	autoShow: true,
	title: 'Cuenta de empleados',
	defaults: {
		border: false,
		frame: false
	},
	
	initComponent: function(){
		var me = this;
		
		Ext.applyIf(me,{
			items: [
				{
					xtype: 'tabpanel',
					region: 'center',
					tabBarHeaderPosition: 1,
					layout: 'fit',
	                tabBar: {
	                    flex: 1
	                },
					items:[
						{
							title: 'Cuenta salarios',
							iconCls: 'contact',
							items: [
								{
									xtype: 'tabEmpleados'
								},	
							]
						},
						{
							title: 'Compensatorio',
							iconCls: 'infoAct',
							items: [
								{
									xtype: 'tabCompensatorios'
								},	
							]
						}							
					] 						
				},				
			]
		})
		
		me.callParent();
	}
});
