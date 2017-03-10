Ext.define('MetApp.view.CCClientes.Cobranza' ,{
	extend: 'Ext.window.Window',
	height: 550,
	width: 900,
	modal: true,
	autoShow: true,
	alias: 'widget.cobranza',
	itemId: 'cobranza',
	title: 'Cobranzas',
	layout: 'border',
	listeners: {
		afterrender: function(win){
			win.queryById('reciboNum').focus(true, 100);
		}
	},
	
	initComponent: function(){
		var me = this;
		var botonera = Ext.create('MetApp.resources.ux.BtnABMC');
		botonera.queryById('btnNew').setText('Insertar (F3)');
		botonera.queryById('btnSave').setDisabled(false);
		botonera.queryById('btnReset').hide();
		
		Ext.applyIf(me,{
			items: [
				{
					xtype: 'form',
					border: false,
					itemId: 'formDatosCob',
					region: 'north',
					layout: 'vbox',
					margins: '5 0 5 5',
					items: [
						{
							xtype: 'container',
							layout: 'hbox',
							items: [
								{
									xtype: 'label',
									text: 'N° recibo',
									margins: '5 5 0 0',
								},
								{
									xtype: 'numberfield',
									allowBlank: false,
									name: 'ptoVta',
									itemId: 'ptoVta',
									width: 60,
									value: 1	
								},
								{
									xtype: 'numberfield',
									allowBlank: false,
									itemId: 'reciboNum',
									name: 'reciboNum',
									margins: '0 5 0 0',							
								},
								{
									xtype: 'datefield',
									allowBlank: false,
									fieldWidth: 60,
									fieldLabel: 'Emision',
									itemId: 'emisionFecha',
									name: 'emisionFecha',
									readOnly: true,
									value: new Date()
								}
							]	
						},						
						{
							xtype: 'grid',
							height: 200,
							width: 900,
							border: false,
							frame: false,
							plugins: [
						        Ext.create('Ext.grid.plugin.CellEditing', {
						            clicksToEdit: 1
						        })
						    ],
							columns: [
								{ 
									text: 'Forma de pago',
									dataIndex: 'formaPago',
									flex: 1
								},
								{ text: 'Numero', dataIndex: 'numero', flex: 1 },
								{ text: 'Banco', dataIndex: 'banco', flex: 1 },
								{ 
									text: 'Importe',
									dataIndex: 'importe',
									flex: 1,							
								},
								{ 
									text: 'Diferido',
									dataIndex: 'diferido',
									flex: 1,
								}
							]
						},		
					]
				},
						
				{
					xtype: 'form',
					itemId: 'formItemCob',
					store: 'MetApp.store.Finanzas.GrillaPagosStore',
					region: 'center',
					padding: '5 5 5 5',
					border: false,
					items: [
						{
							xtype: 'container',
							defaults: {
								margins: '5 0 5 5',
							},							
							layout: 'hbox',
							items: [
								{
									xtype: 'combobox',
									allowBlank: false,
									displayField: 'descripcion',
									fieldLabel: 'Forma de pago',
									name: 'formaPago',
									itemId: 'formaPago',
									store: 'MetApp.store.Finanzas.TiposPagoStore'							
								},
								{
									xtype: 'textfield',
									labelWidth: 50,
									width: 200,
									fieldLabel: 'Numero',
									name: 'numero',
									itemId: 'numero'
								},
								{
									xtype: 'textfield',
									labelWidth: 50,
									width: 150,
									fieldLabel: 'Banco',
									itemId: 'banco',
									name: 'banco'
								},
								{
									xtype: 'numberfield',
									allowBlank: false,
									labelWidth: 50,
									width: 150,
									decimalSeparator: '.',
									name: 'importe',
									itemId: 'importe',
									fieldLabel: 'Importe'
								}
							]
						},
						{
							xtype: 'container',
							layout: 'hbox',
							items: [
								{
									xtype: 'datefield',
									format:'d/m/Y',
									allowBlank: false,
									fieldLabel: 'Diferido',
									name: 'diferido',
									itemId: 'diferido',
									margins: '5 5 5 5'
								},
								{
									xtype: 'button',
									margins: '0 5 0 0',
									text: 'Imputar fcs.',
									itemId: 'imputar',
									width: 80,
									height: 30
								},
								botonera								
							]
						},
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
									itemId: 'totalCobrar',
									readOnly: true,
									value: 0,
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
