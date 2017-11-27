Ext.define('MetApp.view.RRHH.Personal.TabOtrosDatos', {
	extend: 'Ext.form.Panel',
	modal: true,	
	width: 900,
	height: 350,
	layout: 'border',
	border: false,
	itemId: 'TabOtrosDatos',
	alias: 'widget.TabOtrosDatos',	
	autoShow: true,
	defaults: {
		border: false,
		frame: false
	},
	items: [
		{
			xtype: 'form',
			itemId: 'formOtrosDatos',
			region: 'center',
			margins: '5 5 5 5',
			frame: false,
			border: false,
			layout: 'vbox',
			defaults: {
				readOnly: true,
				disabledCls: 'myDisabledClass',
			},
			items: [
				{
					xtype: 'numberfield',
					fieldLabel: 'Talle pantalon:',
					name: 'tallePantalon',
					itemId: 'tallePantalon',
				},
				{
					xtype: 'numberfield',
					fieldLabel: 'Talle camisa:',
					name: 'talleCamisa',
					itemId: 'talleCamisa',
				},
				{
					xtype: 'numberfield',
					fieldLabel: 'Talle calzado:',
					name: 'talleCalzado',
					itemId: 'talleCalzado',
				}													
			]
		}	
	]
		
	
});
