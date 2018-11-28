
Ext.define('MetApp.view.Calidad.AsociarTrazabilidadView', {
	extend: 'Ext.window.Window',
	alias: 'widget.AsociarTrazabilidadView',
	itemId: 'AsociarTrazabilidadView',
	width: 700,
	layout: 'fit',
	autoShow: true,
	title: 'Asociar N° Correlativo',
	modal: true,	
	bodyStyle:{"background-color":"white"}, 
	
	initComponent: function(){
		var me = this;
		Ext.applyIf(me,{
			items: [
				{
					xtype: 'form',
					itemId: 'formGral',
					region: 'center',					
					border: false,
					layout: 'vbox',
					defaults: {
						disabledCls: 'myDisabledClass',
						margins: '5 5 5 5',
					},
					padding: '5 5 5 5',
					items: [	
						{
							xtype: 'container',
							layout: 'hbox',
							items: [
								{
									xtype: 'textfield',
									name: 'numCorrelativo',
									width:500,
									itemId: 'numCorrelativo',
									fieldLabel: 'N° Correlativo'
								},
							]
						},
						{
							xtype: 'numberfield',
							name: 'remitoNum',
							itemId: 'remitoNum',
							fieldLabel: 'Remito N°'
						},
						{
							xtype: 'button',
							text: 'Guardar',
							itemId: 'guardar'
						}																									
					]
				},	
			]
		});
		this.callParent();
	}
});
