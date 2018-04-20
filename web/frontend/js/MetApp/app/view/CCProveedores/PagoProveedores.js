Ext.define('MetApp.view.CCProveedores.PagoProveedores' ,{
	extend: 'Ext.window.Window',
	height: 550,
	width: 950,
	modal: true,
	autoShow: true,
	alias: 'widget.PagoProveedores',
	itemId: 'PagoProveedores',
	title: 'Pago a proveedores',
	layout: 'border',
	bodyStyle: {
		'background-color': 'white'
	},
	
	initComponent: function(){
		var me = this;
		var botonera = Ext.create('MetApp.resources.ux.BtnABMC');
		botonera.queryById('btnNew').setText('Insertar (F3)');
		botonera.queryById('btnReset').hide();
		botonera.queryById('btnSave').setDisabled(false);
		Ext.applyIf(me,{
			items: [
				{
					xtype: 'container',
					style: {
						'background-color': 'white'
					},
					region: 'north',
					items: [
						{
							xtype:'numberfield',
							itemId: 'idOP',
							name: 'idOP',
							fieldLabel: 'idOP',
							value: 0,
							hidden: true
						},
						{
							xtype: 'datefield',
							allowBlank: false,
							fieldLabel: 'Emision',
							margin: '5 0 5 5',
							value: new Date()
						},
						{
							xtype: 'grid',
							store: 'MetApp.store.Proveedores.PagoProveedoresStore',
							height: 150,
							itemId: 'gridPP',
							columns: {
								defaults: {
									sortable: false
								},
								items: [
									{ text: 'Id', dataIndex: 'id', hidden: true, flex: 1 },
									{ text: 'forma de pago ID', dataIndex: 'fid', hidden: true, flex: 1 },
									{ text: 'Id Cheque', dataIndex: 'idCheque', hidden: true, flex: 1 },
									{ text: 'Forma de pago', dataIndex: 'formaPago', flex: 1 },
									{ text: 'Numero', dataIndex: 'numero', flex: 1 },
									{ text: 'Banco', dataIndex: 'banco', flex: 1 },
									{ text: 'Cuenta', dataIndex: 'cuenta', flex: 1 },
									{ text: 'Importe', dataIndex: 'importe', flex: 1 },
									{ text: 'Diferido', dataIndex: 'diferido', flex: 1 },							
								]
							},
						},						
					]
				},				
				
				{
					xtype: 'form',
					border: false,
					margin: '5 5 0 5',
					region: 'center',
					items: [
						{
							xtype: 'container',
							layout: 'hbox',
							defaults: {
								margin: '5 5 0 0'
							},
							items: [
								{
									xtype: 'combobox',
									store: 'MetApp.store.Finanzas.TiposPagoStore',
									displayField: 'descripcion',
									allowBlank: false,
									itemId: 'formaPago',
									name: 'formaPago',
									fieldLabel: 'Forma de pago',										
									listeners: {
										select: {
											fn: function(el, rec, eOpts){
												var win=el.up('window');
												var idF=win.queryById('fid');
												idF.setValue(rec[0].data.id);
											}
										}
									}								
								},						
								{
									xtype: 'combo',
									name: 'cuenta',
									store: 'MetApp.store.Bancos.CuentasBancoStore',
									displayField: 'cuenta',						
									fieldLabel: 'Cuenta',
									itemId: 'cuenta',
									valueField: 'id',
									allowBlank: true,
									forceSelection: true,
									width: 400
								},
								{
									xtype: 'textfield',
									name: 'fid',
									fieldLabel: 'id forma pago',
									itemId: 'fid',
									hidden: true
								}
							]
						},
						{
							xtype: 'container',
							layout: 'hbox',
							defaults: {
								margin: '5 5 5 0',
							},
							items: [
								{
									xtype: 'textfield',
									width: 200,
									labelWidth: 50,
									itemId: 'numero',
									name: 'numero',
									fieldLabel: 'Numero'
								},								
								{
									xtype: 'textfield',
									allowBlank: false,
									width: 200,
									labelWidth: 50,
									itemId: 'importe',
									name: 'importe',
									fieldLabel: 'Importe'
								},
								{
									xtype: 'datefield',		
									labelWidth: 60,							
									name: 'diferido',
									itemId: 'diferido',
									fieldLabel: 'Diferido'
								},								
								{
									xtype: 'button',
									text: 'Cheques de terceros',
									itemId: 'chequesTerceros',
									action: 'chequesTerceros',
								},
								{
									xtype: 'button',
									text: 'Imputar facturas',
									itemId: 'imputarFacturas',
									action: 'imputarFacturas',
								},								
							]
						},
						botonera,										
					]
				},
				{
					xtype: 'container',
					region: 'south',
					items: [
						{
							xtype: 'grid',
							height: 150,
							itemId: 'gridImputaFc',
							store: 'MetApp.store.Proveedores.GridImputaFcStore',
							plugins: [
						        Ext.create('Ext.grid.plugin.CellEditing', {
						            clicksToEdit: 1,
						            
						        })
						    ],
							columns: [
								{ text: 'Id', dataIndex: 'id' },
								{ text: 'Factura N°', dataIndex: 'numFc' },
								{ text: 'Importe', dataIndex: 'haber' },
								{ text: 'Vencimiento', dataIndex: 'vencimiento' },
								{ text: 'Aplicado', dataIndex: 'valorAplicado' },
								{ 
									text: 'Pendiente',
									dataIndex: 'pendiente',
									renderer: function(value, metaData, record, row, col, store, gridView){
										return record.data.haber - record.data.valorAplicado; 
									}
								},
								{ 
									text: 'Aplicar',
								  	editor: 'textfield',
								  	dataIndex:'aplicar',
								},
							]
						},
						{
							xtype: 'container',
							layout: 'hbox',
							margin: '5 0 5 5',
							items: [
								{
									xtype: 'numberfield',
									fieldLabel: 'Total a pagar',
									itemId: 'totalAPagar',
									readOnly: true,
									fieldStyle: {
										'font-weight'   : 'bold',										
									}
								},
								{
									xtype: 'numberfield',
									fieldLabel: 'Total imputado',
									itemId: 'totalImputado',
									readOnly: true,
									fieldStyle: {
										'font-weight'   : 'bold',										
									}
								},
								{
									xtype: 'numberfield',
									fieldLabel: 'Diferencia',
									itemId: 'diferencia',
									readOnly: true,
									fieldStyle: {
										'font-weight'   : 'bold',										
									}
								}
							]
						}
					]
				}							
			]
		});
		this.callParent();
	}
});
