Ext.define('MetApp.view.Utilitarios.TxtRetencionesView', {
	extend: 'Ext.window.Window',
	layout: 'fit',
	width: 300,
	autoShow: true,
	modal: true,
	alias: 'widget.TxtRetencionesView',
	layout: 'vbox',
	title: 'TXT Retenciones',
	itemId: 'TxtRetencionesView',
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
				            xtype      : 'fieldcontainer',
				            border: 1,
				            fieldLabel : 'Periodo',
				            defaultType: 'radiofield',
				            defaults: {
				                flex: 1
				            },
				            layout: 'vbox',
				            items: [
				                {
				                    boxLabel  : '1er Quincena',
				                    name      : 'periodo',
				                    inputValue: '1',
				                    id        : 'radio1'
				                },
				                {
				                    boxLabel  : '2da Quincena',
				                    name      : 'periodo',
				                    inputValue: '2',
				                    id        : 'radio2'
				                }
				            ]
				        },						
						{
							xtype: 'button',
							text: 'Descargar',
							itemId: 'descargar',
							action: 'descargar',
						}
					]
				}		
			]
		});
		this.callParent();
	}
});
