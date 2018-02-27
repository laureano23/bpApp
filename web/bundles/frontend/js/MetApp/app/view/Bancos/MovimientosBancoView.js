Ext.define('MetApp.view.Bancos.MovimientosBancoView',{
	extend: 'Ext.window.Window',
	width: 820,
	modal: true,
	layout: 'anchor',
	alias: 'widget.MovimientosBancoView',
	itemId: 'MovimientosBancoView',
	autoShow: true,
	title: 'Movimientos bancarios',
	
	initComponent: function(){		
		var me = this;
		var botonera = Ext.create('MetApp.resources.ux.BtnABMC');
		botonera.queryById('btnReset').hide();
		botonera.queryById('btnEdit').setDisabled(false);
		botonera.queryById('btnDelete').setDisabled(false);
		
		Ext.applyIf(me, {
			items: [
				{
					xtype: 'form',
					anchorSize: '100%',	
					border: false,
					margin: '5 5 5 5',
					layout: 'vbox',					
					items: [
						{
							xtype: 'container',
							layout: 'hbox',
							defaults: {
								readOnly: true,
								disabledCls: 'myDisabledClass'
							},
							items: [
								{
									xtype: 'numberfield',
									itemId: 'idBanco',
									fieldLabel: 'Id banco',
									name: 'idBanco',
									hidden: true
								},						
								{
									xtype: 'combo',
									name: 'cuenta',
									store: 'MetApp.store.Bancos.CuentasBancoStore',
									displayField: 'cuenta',					
									fieldLabel: 'Cuenta',
									itemId: 'cuenta',
									valueField: 'id',
									allowBlank: false,
									forceSelection: true,
									width: 400
								},
							]
						},	
						{
							xtype: 'container',
							layout: 'hbox',
							margins: '5 0 0 0',
							defaults: {
								readOnly: true,
								disabledCls: 'myDisabledClass'
							},
							items: [
								{
									xtype: 'numberfield',
									itemId: 'idMovimiento',
									fieldLabel: 'Id movimiento',
									name: 'idMovimiento',
									hidden: true
								},						
								{
									xtype: 'combobox',
									store: 'MetApp.store.Bancos.ConceptosBancoStore',
									name: 'conceptoMov',
									itemId: 'conceptoMov',
									fieldLabel: 'Concepto',
									displayField: 'concepto',
									valueField: 'id',
									width: 450,
									allowBlank: false
								},
								{
									xtype: 'button',
									text: 'Cheques de terceros',
									itemId: 'chequeTerceros'
								}
							]
						},	
						{
							xtype: 'container',
							margins: '5 0 5 0',
							layout: 'hbox',
							defaults: {
								readOnly: true,
								disabledCls: 'myDisabledClass'
							},
							items: [
								{
									xtype: 'datefield',
									width: 210,
									itemId: 'fecha',
									fieldLabel: 'Fecha',
									name: 'fecha',
									margins: '0 5 0 0',
									allowBlank: false
								},
								{
									xtype: 'textfield',
									width: 450,
									itemId: 'observaciones',
									fieldLabel: 'Observaciones',
									name: 'observaciones',
								},
							]
						},						
						{
							xtype: 'grid',
							height: 200,
							margin: '0 0 5 0',
							store: 'MetApp.store.Bancos.MovimientosBancoStore',						
							columns: [
								{ text: 'N° comprobante', dataIndex: 'numCbte' },
								{ text: 'Banco', dataIndex: 'banco', flex: 1 },
								{ text: 'Fecha diferida', dataIndex: 'fechaDiferida', flex: 1 },
								{ text: 'Importe', dataIndex: 'importe', flex: 1 },
								{ text: 'Observacion', dataIndex: 'obsCbte', flex: 1 },
								{ text: 'Cheque terceros', dataIndex: 'idChequeTerceros', flex: 1, hidden:true },
							],
							itemId: 'gridMov',
							width: 700						
						},
						{
							xtype: 'container',
							layout: 'hbox',
							itemId: 'contCbte',
							defaults: {
								readOnly: true,
								disabledCls: 'myDisabledClass',
								margins: '5 0 0 5'
							},
							items: [
								{
									xtype: 'textfield',
									name: 'numCbte',
									fieldLabel: 'Comprobante N°'	
								},
								{
									xtype: 'datefield',
									labelWidth: 60,
									name: 'fechaDiferida',
									fieldLabel: 'Diferido'
								},
								{
									xtype: 'numberfield',
									labelWidth: 60,
									name: 'importe',
									fieldLabel: 'Importe'
								},
								{
									xtype: 'textfield',
									labelWidth: 60,
									name: 'idChequeTerceros',
									fieldLabel: 'Cheque terceros',
									hidden: true
								}
							]
						},
						{
							xtype: 'container',
							layout: 'hbox',
							margins: '5 0 5 0',
							items: [
								{
									xtype: 'textfield',
									name: 'obsCbte',
									itemId: 'obsCbte',
									fieldLabel: 'Observaciones',
									readOnly: true,
									width: 600,
									disabledCls: 'myDisabledClass',
									margins: '0 5 0 5'
								},
								{
									xtype: 'button',
									text: 'Insertar',
									itemId: 'insertarCbte'
								}
							]
						},				
						botonera,
						
					]
				}
			]
		});
	this.callParent();
	},
});


