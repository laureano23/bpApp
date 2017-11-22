Ext.define('MetApp.view.Articulos.ArticulosFormulas', {
	extend: 'Ext.window.Window',
	alias: 'widget.articulosformulas',
	itemId: 'articulosFormulas',
	autoShow: true,
	title: 'Fórmulas',
	modal: true,
	height: 510,
	width: 1100,
	defaultFocus: 1,
	requires: [
		'MetApp.resources.ux.ParametersSingleton'
	],
	
	initComponent: function(){
		var botonera = Ext.create('MetApp.resources.ux.BtnABMC');
		botonera.queryById('btnSave').setDisabled(false);
		botonera.queryById('btnNew').hide();
		botonera.queryById('btnReset').setText('Recargar');
		
		Ext.applyIf(this,{
			items: [
				{
					xtype: 'form',
					margin: '0 0 5 0',
					border: false,
					itemId: 'srchFormulasForm',
					layout: 'anchor',
					width: 1050,
					items: [
						{
							xtype: 'container',							
							layout: {
						        type: 'hbox',
						        align: 'middle'
						    },
							height: 50,
							defaults: {
								disabled: false,
								disabledCls: 'myDisabledClass',
								allowBlank: false,
							},	
							width: 'auto',
							items: [
								{
									xtype: 'textfield',
									itemId: 'idFormula',
									name: 'idFormula',
									fieldLabel: 'Id formula',	
									labelWidth: 120,			
									width: 300,				
									readOnly: true,
									allowBlank: true,
									hidden: true
								},
								{
									xtype: 'textfield',
									itemId: 'id',
									name: 'id',
									fieldLabel: 'Id a formular',	
									labelWidth: 120,			
									width: 300,				
									readOnly: true,
									hidden: true
								},
								{
									xtype: 'textfield',
									name: 'codigo',
									fieldLabel: 'Articulo a formular',	
									labelWidth: 120,			
									width: 300,				
									readOnly: true,
									text: 'codigo'
								},
								{
									xtype: 'button',		
									height: 25,
									width: 30,
									iconCls: 'search',
									action: 'actBuscaArt',
									itemId: 'buscarArt'
								},
								{
									xtype: 'textfield',
									name: 'descripcion',
									width: 520,
									fieldLabel: 'Descripcion',
									readOnly: true
								},
								{
									xtype: 'button',
									iconCls: 'search',
									text: 'Estructura',
									itemId: 'estructura',
									width: 150,
									height: 25,
								}
							]
						}						
					]
				},
				{
					xtype: 'container',
					itemId: 'cnt2',
					layout: 'fit',
					width: 'auto',
    				height: 300,
					items: [
						{
							xtype:'grid',
							viewConfig: {
						    	enableTextSelection: true
						    },
							anchor: '100%',
							itemId: 'gridFormulas',
							alias: 'widget.gridFormulas',
							store: 'Articulos.Formula',
							loadMask: true,
							features: [
								{
									ftype: 'summary'
								}
							],
							columns: [
								{ header: 'Codigo', dataIndex: 'codigo', width: 170 },
								{ header: 'Descrpcion', dataIndex: 'descripcion', width: 350 },
								{ header: 'Cant.', dataIndex: 'cant',flex: 1 },
								{ header: 'Unidad.', dataIndex: 'unidad',flex: 1 },								
								{ 
									header: 'Costo',
									dataIndex: 'costo',
									flex: 1,
									renderer: function(value, meta){
										//var dolarOficial = MetApp.resources.ux.ParametersSingleton.getIva();
										var cant = meta.record.data.cant;
										var subTotal = meta.record.data.costo;
										var moneda = meta.record.data.moneda;
										var total;
										var dolarOficial = MetApp.resources.ux.ParametersSingleton.getDolarOficial();
										
										if(moneda == "U$D"){
											total  = subTotal / cant / dolarOficial;
										}else{
											total  = subTotal / cant;
										}
										return total.toFixed(2);
									}
								},
								{ header: 'Moneda', dataIndex: 'moneda',flex: 1 },
								{ 
									header: 'Link',
									flex: 1,
									xtype: 'actioncolumn',
									items: [
										{
											handler: function(view, rowIndex, colIndex, item, e, record, row){											
												var cn = MetApp.app.getController('Articulos.ArticulosController');
												var win = cn.addArticulosTb();
												var id = record.data['codigo'];
												cn.LinkArt(record);
																								
											},
											iconCls: 'search'										
										}										
									] 
								},
								{
									header: 'Sub Total',
									flex: 1,
									width: 150,
									xtype: 'numbercolumn',
									dataIndex: 'costo',
									summaryType: function (records, values) {
			                            var i = 0,
			                                length = records.length,
			                                total = 0,
			                                record;			                            
			                            
			                            for (i; i < length; ++i) {			                            	
			                                record = records[i];                             
			                               
			                                total += record.get('costo');                   
			                            }
			                            return (total);
			                            
		                      		},	
		                      		summaryRenderer: function (value, summaryData, field) {                           
			                            return Ext.util.Format.usMoney(value);
			                        },								
									renderer: function(value, meta, record){								
										
		                            	return Ext.util.Format.usMoney(value);	
		                                										
									}
								}
							]
						},
						{
							xtype: 'form',
							margins: '0 0 5 5',
							store: 'Articulos.Formula',
							itemId: 'formulaFill',
							border: false,
							items: [
								{
									xtype: 'container',
									height: 50,
									defaults: {
										readOnly: true,
										allowBlank: false,
									},
									layout: {
								        type: 'hbox',
								        align: 'middle'
								    },
									width: 'auto',
									items: [
										{
											xtype: 'textfield',
											fieldLabel: 'Id',
											name: 'id',
											itemId: 'id',
											readOnly: true,
											hidden: true								
										},
										{
											xtype: 'textfield',
											fieldLabel: 'Codigo',
											name: 'codigo',
											itemId: 'codigo',
											readOnly: true									
										},
										{
											xtype: 'button',				
											height: 25,
											width: 30,
											iconCls: 'search',
											action: 'actBuscaArtFormulas',
											itemId: 'buscarArtForm'
										},
										{
											xtype: 'textfield',
											name: 'descripcion',
											width: 450,
											fieldLabel: 'Descripcion',
											readOnly: true
										}
									]
								},
								{
									xtype: 'container',
									layout: 'hbox',
									width: 'auto',
									defaults: {
										allowBlank: false,
									},
									items: [
										{
											xtype: 'numberfield',
											decimalSeparator: '.',
											name: 'cant',
											fieldLabel: 'Cantidad:',
											itemId: 'cantidadForm'
										},
										{
											xtype: 'textfield',
											width: 100,
											labelWidth: 25,
											fieldLabel: 'Un.',
											readOnly: true,
											allowBlank: true,
											name: 'unidad'
										}
									]
								},
								{
									xtype: 'container',
									margins: '5 0 5 5',
									layout: {
								        type: 'hbox',
								        align: 'bottom'
								    },								
									items: [
										botonera,																				
									]
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
