Ext.define('MetApp.view.Produccion.CalculoRadiadores.OtPanelesCierre', {
	extend: 'Ext.window.Window',
	alias: 'widget.otpanelesCierre',
	itemId: 'otWinPanelesCierre',
	title: 'O.T panel de Aire/Aceite',
	height: 450,
	width: 600,
	modal: true,
	autoShow: true,
	layout: 'fit',
	
	
	initComponent: function(){
		var me = this;
		Ext.applyIf(me,{
			items: [
			{
				xtype: 'form',
				items: [
				
				]
			}								
			]
		});
		this.callParent();
	}
});
