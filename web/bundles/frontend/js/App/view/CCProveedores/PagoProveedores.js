Ext.define('MetApp.view.CCProveedores.PagoProveedores' ,{
	extend: 'Ext.window.Window',
	height: 530,
	width: 950,
	modal: true,
	autoShow: true,
	alias: 'widget.PagoProveedores',
	itemId: 'PagoProveedores',
	title: 'Pago a proveedores',
	layout: 'border',
	
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
							//region: 'center',	
							columns: {
								defaults: {
									sortable: false
								},
								items: [
									{ text: 'Id', dataIndex: 'id', hidden: true, flex: 1 },
									{ text: 'Id Cheque', dataIndex: 'idCheque', hidden: true, flex: 1 },
									{ text: 'Forma de pago', dataIndex: 'formaPago', flex: 1 },
									{ text: 'Numero', dataIndex: 'numero', flex: 1 },
									{ text: 'Banco', dataIndex: 'banco', flex: 1 },
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
					margin: '5 5 5 5',
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
								},
								{
									xtype: 'textfield',
									width: 200,
									labelWidth: 50,
									itemId: 'numero',
									name: 'numero',
									fieldLabel: 'Numero'
								},
								{
									xtype: 'combobox',
									store: 'MetApp.store.Finanzas.BancosStore',
									displayField: 'nombre',
									width: 200,
									labelWidth: 50,
									itemId: 'banco',
									name: 'banco',
									fieldLabel: 'Banco'
								},
								{
									xtype: 'numberfield',
									allowBlank: false,
									width: 200,
									labelWidth: 50,
									itemId: 'importe',
									name: 'importe',
									fieldLabel: 'Importe'
								},
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
									xtype: 'datefield',									
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
									xtype: 'textfield',
									fieldLabel: 'Total imputado',
									itemId: 'totalImputado',
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
