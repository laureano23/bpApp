Ext.define('MetApp.view.RRHH.TablaSindicatos', {
	extend: 'Ext.window.Window',
	modal: true,	
	width: 580,
	height: 150,
	layout: 'border',
	border: false,
	itemId: 'sindicatosTabla',
	alias: 'widget.sindicatosTabla',	
	autoShow: true,
	title: 'Tabla de Sindicatos',
	defaults: {
		border: false,
		frame: false
	},
	
	initComponent: function(){
		var me = this;
		
		var botonera = Ext.create('MetApp.resources.ux.BtnABMC');
		
		Ext.applyIf(me,{
			items: [
				{
					xtype: 'form',
					store: '',
					region: 'center',
					frame: false,
					border: false,					
					layout: 'hbox',
					items: [
						{							
							xtype: 'numberfield',
							itemId: 'idSindicato',
							width: 20,
							name: 'id',
							margins: '5 5 5 5',
							disabled: true,
							hidden: true	
						},
						{							
							xtype: 'textfield',
							disabledCls: 'myDisabledClass',
							name: 'sindicato',
							allowBlank: false,
							itemId: 'sindicato',
							margins: '5 5 5 5',
							fieldLabel: 'Sindicato',
							disabled: true	
						},
						{
							xtype: 'button',
							itemId: 'prevRecord',
							margins: '5 5 5 5',
							height: 25,
							width: 25,
							iconCls: 'arrow_up'
						},
						{
							xtype: 'button',
							itemId: 'nextRecord',							
							margins: '5 5 5 0',
							height: 25,
							width: 25,
							iconCls: 'arrow_down'
						}
					]
				},						
				{
					xtype: 'container',
					padding: '0 5 0 5',
					region: 'south',
					layout: 'vbox',		
					items: [botonera]
				}			
			]
		})
		
		me.callParent();
	}
});
