Ext.define('MetApp.view.Produccion.CalculoRadiadores.OtPanelesSearch', {
	extend: 'Ext.window.Window',
	alias: 'widget.otpanelessearch',
	//height: 700,
	width: 1100,
	modal: true,
	autoShow: true,	
	layout: 'fit',
	title: 'Buscador OT',
	
	
	initComponent: function(){
		var me = this;
		Ext.applyIf(me,{
			items: [
				Ext.create('MetApp.view.Produccion.CalculoRadiadores.OtPanelesGrilla'),
			]
		});
		this.callParent();
	}
});
