Ext.define('MetApp.view.Utilitarios.VerificacionComprobanteView', {
	extend: 'Ext.window.Window',
	layout: 'fit',
	width: 1000,
	height: 400,
	autoShow: true,
	modal: true,
	alias: 'widget.VerificacionComprobanteView',
	layout: 'vbox',
	title: 'Verificación de comprobante',
	itemId: 'VerificacionComprobanteView',

	initComponent: function(){
		var me = this;
		Ext.applyIf(me, {
			items: [
				{
					xtype: 'form',
					itemId: 'fechaForm',
					border: false,
					layout: {
						type: 'hbox',
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
							xtype: 'container',
							layout: 'vbox',
							items: [
								{
									xtype: 'numberfield',
									itemId: 'puntoVta',
									name: 'puntoVta',
									fieldLabel: 'Punto de Venta'
								},
								{
									xtype: 'numberfield',
									itemId: 'numero',
									name: 'numero',
									fieldLabel: 'N° de Comprobante'
								},
								{
									xtype: 'combo',
									fieldLabel: 'Tipo',
									store: 'MetApp.store.Finanzas.TiposComprobantesStore',
									displayField: 'descripcion',
									valueField: 'id',
									name: 'tipo',
									itemId: 'tipo',
									forceSelection: true
								},
								{
									xtype: 'button',
									itemId: 'btnSubmit',
									text: 'Buscar'
								}
							]
						},
						{
							xtype: 'textarea',
							grow: true,
							allowBlank: true,
							name: 'res',
							itemId: 'res',
							anchor: '100%',
							width: 600,
							height:350
						}
					]
				}		
			]
		});
		this.callParent();
	}
});
