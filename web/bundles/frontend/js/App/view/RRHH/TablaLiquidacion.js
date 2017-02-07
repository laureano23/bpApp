Ext.define('MetApp.view.RRHH.TablaLiquidacion', {
	extend: 'Ext.window.Window',
	modal: true,	
	width: 1000,
	height: 470,
	layout: 'border',
	border: false,
	itemId: 'liquidacionTabla',
	alias: 'widget.liquidacionTabla',	
	autoShow: true,
	title: 'Tabla de Liquidacion (Novedades)',
	listeners: {
		'afterrender': function(win){
			win.queryById('searchPersonal').focus('', 200);
		}
	},
		
	initComponent: function(){
		var me = this;
		var botonera = Ext.create('MetApp.resources.ux.BtnABMC');
		botonera.queryById('btnNew').setText('Insertar (F3)');
		botonera.queryById('btnSave').setDisabled(false);
		
		
		Ext.applyIf(me,{
			items: [
				{
					xtype: 'container',
					border: false,
					frame: false,
					region: 'north',
					height: 200,
					layout: 'vbox',
					items: [					
						{
							xtype: 'container',
							border: false,
							frame: false,
							layout: 'hbox',
							items: [
								{
									xtype: 'numberfield',
									itemId: 'idEmpleado',
									hidden: true,
									fieldLabel: 'id',
								},
								{
									xtype: 'textfield',
									disabled: true,
									disabledCls: 'myDisabledClass',
									itemId: 'empleado',
									width: 600,
									fieldLabel: 'Empleado',
								},
								{
									xtype: 'button',
									itemId: 'searchPersonal',
									margin: '0 0 0 5',
									iconCls: 'search'
								}	
							]
						},												
						{
							xtype: 'checkbox',
							fieldLabel: 'Compensatorio',
							name: 'compensatorio',
							itemId: 'compensatorio',
							hidden: false
						},
						{
							xtype: 'label',
							itemId: 'liquidacionFecha',
							margin: '5 0 0 0'
						},
						{
							xtype: 'label',
							itemId: 'periodo',
							margin: '5 0 0 0'
						},
						{
							xtype: 'numberfield',
							itemId: 'codigoPeriodo',
							hidden: true,
							margin: '5 0 0 0'
						},
						{
							xtype: 'numberfield',
							itemId: 'anioLiquidacion',
							//hidden: true,
							margin: '5 0 0 0'
						},
						{
							xtype: 'datefield',
							fieldLabel: 'Fecha pago',
							readOnly: true,
							itemId: 'fechaPago',
							margin: '5 0 0 0',
							format: 'd/m/Y'
						},	
						{
							xtype: 'textfield',
							fieldLabel: 'Descripcion',
							itemId: 'descripcion'
						},				
						{
							xtype: 'datefield',
							format: 'd/m/Y',
							hidden: true,
							itemId: 'fechaFin',
							margin: '5 0 0 0'
						}
					]
				},
				{
					xtype: 'grid',
					border: false,
					frame: false,
					region: 'center',
					features: [{ ftype: 'summary' }],
					height: 180,
					store: 'MetApp.store.Personal.LiquidacionStore',
					anchor: '100%',
					plugins: [
				        Ext.create('Ext.grid.plugin.CellEditing', {
				            clicksToEdit: 1
				        })
				    ],
					columns: [
						{
							text: 'IdRecibo',
							hidden: true,
							width: 80,									
							dataIndex: 'idRecibo',
						},
						{
							text: 'Codigo',
							width: 80,									
							dataIndex: 'idConcepto',
						},
						{
							text: 'Concepto',	
							width: 200,
							dataIndex: 'descripcionConcepto',						
						},
						{
							text: 'Cantidad',
							xtype: 'numbercolumn',
							dataIndex: 'cantidadConcepto',
						},
						{
							text: 'Importe',
							dataIndex: 'importe',
						},
						{
							header: 'Sub Total',
							name: 'subTotal',
							dataIndex: 'subTotal',
							itemId: 'subTotal',
							width: 200,
							xtype: 'numbercolumn',
							editor: {
				                xtype: 'numberfield',
				                allowBlank: false
				           	},							
							summaryType: function (records, values) {
	                            var i = 0,
	                                length = records.length,
	                                total = 0,
	                                record;
	                            
	                            for (i=0; i < length; ++i) {
	                                record = records[i];  			                                                                 
	                                total += record.get('subTotal');				                                
	                            }		                            
	                            return total
                      		},	
                      				                      		
                      		summaryRenderer: function (value, summaryData, field) {		                      			                             
	                            return Ext.String.format('Total: ' + value);
	                        },
	                        								
							renderer: function(value, meta){
								return Ext.util.Format.usMoney(meta.record.data['subTotal']);
							}
						},
						{
							text: 'E',
							width: 20,
							dataIndex: 'compensatorio',
							renderer: function(value, meta){
								if(value == 1){
									meta.style = "background-color:yellow;";	
								}else{
									meta.style = "background-color:green;";
								}									
							}
						},
					]
				},
				{
					xtype: 'form',
					height:80,
					border: false,
					frame: false,
					//margin: '5 5 5 5',
					layout: 'vbox',
					region: 'south',
					items: [
						{
							xtype: 'container',
							border: false,
							frame: false,
							layout: 'hbox',
							items: [
								{
									xtype: 'numberfield',
									fieldLabel: 'idRecibo',
									name: 'idRecibo',
									hidden: true,
									itemId: 'idRecibo',
								},
								{
									xtype: 'numberfield',
									fieldLabel: 'id',
									name: 'idConcepto',
									hidden: true,
									itemId: 'idConcepto',
								},
								{
									xtype: 'textfield',
									itemId: 'concepto',
									name: 'descripcionConcepto',
									disabled: true,
									disabledCls: 'myDisabledClass',
									width: 500,
									fieldLabel: 'Concepto'
								},
								{
									xtype: 'button',
									disabled: true,
									itemId: 'buscaConcepto',
									margin: '0 0 0 5',
									iconCls: 'search'
								}
							]
						},
						{
							xtype: 'container',
							border: false,
							frame: false,
							margin: '10 0 0 0',
							layout: 'hbox',
							items: [
								{
									xtype: 'numberfield',
									decimalSeparator: '.',
									name: 'cantidadConcepto',
									itemId: 'cantidad',
									required: true,
									width: 200,
									labelWidth: 100,
									fieldLabel: 'Cantidad'
								},
								{
									xtype: 'textfield',
									itemId: 'unidad',
									name: 'unidad',
									disabled: true,
									disabledCls: 'myDisabledClass',
									margin: '0 5 0 5',
									labelWidth: 60,
									width: 200,
									fieldLabel: 'Unidad'
								},
								botonera
							]
						}						
					]
				}				
			]
		})
		
		me.callParent();
	}
});
