Ext.define('MetApp.resources.ux.MailerWindow',{
	//singleton: true,	
	extend: 'Ext.window.Window',
	alias: 'widget.MailerWindow',
	itemId: 'MailerWindow',
	title: 'Correo',
	autoShow: true,
	modal: true,
	layout: 'fit',
	height: 450,
	width: 600,
	initComponent: function(){
		var me = this;
		me.callParent();
	},
	defaults: {
		margin: '5 5 5 5',
	},
	items:[
		{
			xtype: 'form',
			border: false,
			layout: 'anchor',
			items: [
				{
					xtype: 'textfield',
					name: 'destinatario',
					anchor    : '100%',
					fieldLabel: 'Destinatario',		
					allowBlank: false	
				},
				{
					xtype: 'textfield',
					name: 'asunto',
					anchor    : '100%',
					fieldLabel: 'Asunto',
					allowBlank: false
				},
				{
					xtype: 'textfield',
			        name: 'adjunto',
			        itemId: 'adjunto',
			        fieldLabel: 'Adjunto',
			        msgTarget: 'side',
			        allowBlank: true,
			        anchor: '100%',
			        readOnly: true
				},
				{
					xtype: 'textareafield',
					name: 'mensaje',
			        grow: true,
			        name: 'mensaje',
			        fieldLabel: 'Mensaje',
			        anchor: '100%',
			        height: 250
				},
				{
					xtype: 'container',
					layout: 'anchor',
					items: [
						{
							xtype: 'button',
							margin: '10 5 5 0',
							text: 'Enviar',
							itemId: 'enviarMail'
						},
						{
							xtype: 'button',
							margin: '10 5 5 0',
							text: 'Cancelar',
							itemId: 'cancelarMail',
							listeners: {
								click: {
									fn: function(btn){
										var win = btn.up('window');
										win.close();
									}
								}
							}
						}
					]
				}
			]
		}
		
	]
});
