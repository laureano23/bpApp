Ext.define('MetApp.view.Produccion.CalculoRadiadores.WinCalculo',{
	extend: 'Ext.window.Window',
	alias: 'widget.wincalculo',
	itemId: 'calculoRadWin',
	title: 'Cálculo panel de Aire/Aceite',
	height: 675,
	width: 1200,
	modal: true,
	autoShow: true,	
	layout: 'fit',
	
	initComponent: function(){
		var me = this;
		Ext.applyIf(me,{
			items: [
				{
					xtype: 'tabpanel',
					tabBarHeaderPosition: 1,
	                tabBar: {
	                    flex: 1
	                },
					items:[
						{
							title: 'Calculo',
							items: [
								{
									xtype: 'calculoradaceiteform'
								}
							]
						},
						{
							title: 'Brazado',
							html: 'hola'
						}					
					] 						
				},
				
			]
		});
		this.callParent();
	}
});
