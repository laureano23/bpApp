Ext.define('MetApp.view.Proveedores.FormasPagoView',{
	extend: 'Ext.window.Window',
	width: 490,
	modal: true,
	layout: 'anchor',
	alias: 'widget.FormasPagoView',
	itemId: 'FormasPagoView',
	autoShow: true,
	title: 'Tabla de Formas de Pago',
	
	initComponent: function(){		
		var me = this;
		var botonera = Ext.create('MetApp.resources.ux.BtnABMC');
		botonera.queryById('btnReset').hide();
		
		Ext.applyIf(me, {
			items: [
				{
					xtype: 'form',
					anchorSize: '100%',	
					border: false,
					margin: '5 5 5 5',
					defaults: {
						readOnly: true,
						disabledCls: 'myDisabledClass'
					},
					items: [
						{
							xtype: 'numberfield',
							itemId: 'id',
							fieldLabel: 'Id',
							name: 'id',
							hidden: true
						},						
						{
							xtype: 'textfield',
							name: 'descripcion',
							itemId: 'formaPago',
							fieldLabel: 'Forma de Pago',
							width: 450
						},
						{
							xtype: 'container',
							layout: 'hbox',
							items: [
								{
									xtype: 'container',
									layout: 'vbox',
									defaults: {
										readOnly: true,
										disabledCls: 'myDisabledClass',	
									},
									items: [
										{
											xtype: 'checkbox',
											name: 'retencionIIBB',
											itemId: 'retencionIIBB',
											fieldLabel: 'Retención IIBB',
											labelWidth: 120,
											uncheckedValue: 0
										},
										{
											xtype: 'checkbox',
											name: 'retencionIVA21',
											itemId: 'retencionIVA',
											fieldLabel: 'Retención IVA',
											labelWidth: 120,
											uncheckedValue: 0
										},
										{
											xtype: 'checkbox',
											name: 'chequeTerceros',
											itemId: 'chequeTerceros',
											fieldLabel: 'Cheque de Terceros',
											labelWidth: 120,
											uncheckedValue: 0
										},
										{
											xtype: 'checkbox',
											name: 'esChequePropio',
											itemId: 'esChequePropio',
											fieldLabel: 'Cheque Propio',
											labelWidth: 120,
											uncheckedValue: 0
										},
										{
											xtype: 'checkbox',
											name: 'depositaEnCuenta',
											itemId: 'depositaEnCuenta',
											fieldLabel: 'Deposita en cuenta',
											labelWidth: 120,
											uncheckedValue: 0
										},
									]
								},
								{
									xtype: 'combobox',
									margin: '0 0 0 5',
									store: 'MetApp.store.Bancos.ConceptosBancoStore',
									name: 'conceptoMov',
									itemId: 'conceptoMov',
									fieldLabel: 'Movimiento bancario',
									displayField: 'concepto',
									labelWidth: 130,									
									valueField: 'id',
									allowBlank: false,
									readOnly: true,
									disabledCls: 'myDisabledClass',
								},										
							]
						},																
						{
							xtype: 'grid',
							height: 200,
							margin: '0 0 5 0',
							store: 'MetApp.store.Finanzas.TiposPagoStore',
							disabled: false,
							columns: [
								{ text: 'Id', dataIndex: 'id' },
								{ text: 'Concepto', dataIndex: 'descripcion', flex: 1 },
								{ text: 'Concepto bancario', dataIndex: 'conceptoBancario', flex: 1, hidden: true }
							],
							width: 450
						},
						botonera
					]
				}
			]
		});
	this.callParent();
	},
});


