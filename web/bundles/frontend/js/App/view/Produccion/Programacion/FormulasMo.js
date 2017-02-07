Ext.define('MetApp.view.Produccion.Programacion.FormulasMo', {
	extend: 'Ext.window.Window',
	alias: 'widget.formulasMo',
	layout: 'anchor',
	width: 900,
	height: 470,
	autoShow: true,
	modal: true,
	title: 'Formula de mano de obra',
	cls: 'window',
	
	
	initComponent: function(){
		var me = this;
		var btnABMC = Ext.create('MetApp.resources.ux.BtnABMC');
			
		Ext.applyIf(me,{
			items:[
				{
					xtype: 'form',
					name: 'formArt',
					itemId: 'formArt',
					border: false,					
					anchorSize: '100%',			
					margin: '5 5 5 5',
					layout: 'hbox',
					cls: 'panelBtn',
					fieldDefaults: {
						readOnly: true
					},
					items:[
						{
							xtype: 'numberfield',
							hidden: true,
							name: 'idArt',
							itemId: 'idArt',
							fieldLabel: 'Id',
							labelWidth: 50
						},
						{
							xtype: 'textfield',
							name: 'codigo',
							itemId: 'codigo',
							fieldLabel: 'Codigo',
							labelWidth: 50
						},
						{
							xtype: 'button',
							action: 'buscaArt',
							itemId: 'buscaArt',
							iconCls: 'search',
							height: 25,
							width: 30,
							margin: '0 5 0 5'
						},
						{
							xtype: 'textfield',
							name: 'descripcion',
							itemId: 'descripcion',
							fieldLabel: 'Descripcion',
							width: 600
						}
					]
				},				
				{
					xtype: 'grid',
					store: 'Produccion.Programacion.FormulaMoStore',
					margin: '5 5 5 5',
					height: 250,
					autoScroll: true,
					anchorSize: '100%',	
					features: { ftype: 'summary' },
					viewConfig: {
				        listeners: {
				            refresh: function(dataview) {
				                Ext.each(dataview.panel.columns, function(column) {
				                    if (column.autoSizeColumn === true)
				                        column.autoSize();
				                })
				            }
				        }
			    	},
					columns: [
						{ 
							header: 'Id',
							dataIndex: 'id',
							autoSizeColumn: true
						},
						{ 
							header: 'Operacion',
							dataIndex: 'idOperacion',
							renderer: function(val){
								return val.descripcion
							},
							autoSizeColumn: true
						},
						{ 
							header: 'Centro Costo',
							dataIndex: 'idOperacion',
							renderer: function(val){
								var value = val.centroCosto.nombre;
								return value;
							},
							flex: 1
						},
						{ 
							header: 'Costo ($)',
							dataIndex: 'idOperacion',
							renderer: function(val){
								return val.centroCosto.costo;
							},
							autoSizeColumn: true
						},
						{ 
							header: 'Tiempo',
							dataIndex: 'tiempo',
							renderer: function(val){						
								return Ext.util.Format.date(val.date,'H:i:s');								
							},
							autoSizeColumn: true
						},
						{ 
							header: 'Sub total',
							xtype: 'numbercolumn',
							flex: 1,
							renderer: function(value, meta){								
								var costo = meta.record.data.idOperacion.centroCosto.costo;
								var tiempo = meta.record.data.tiempo;
								var date = new Date(tiempo.date);
								var min = parseFloat(Ext.Date.format(date, 'i'));
								var hs = parseFloat(Ext.Date.format(date, 'H'));
								var seg = parseFloat(Ext.Date.format(date, 's'));
								
								if(seg!=0){
									seg = seg/60;
								}
								var res = (min + hs*60 + seg) * costo;
								
								return Ext.util.Format.usMoney(res);
								
							},
							summaryType: function (records, values) {
								
	                            var i = 0,
	                                length = records.length,
	                                total = 0,
	                                record,
	                                costo;
	                            
	                            for (; i < length; ++i) {
	                                record = records[i];  
	                                costo = record.data.idOperacion.centroCosto.costo;	                                
	                                var tiempo = new Date(record.data.tiempo.date);
	                                var min = parseFloat(Ext.Date.format(tiempo, 'i'));
									var hs = parseFloat(Ext.Date.format(tiempo, 'H'));
									var seg = parseFloat(Ext.Date.format(tiempo, 's'));                               
	                                total += (min + hs*60 + seg) * costo;                              
	                            }
	                            
	                            return Ext.util.Format.usMoney(total);
	                            
                      		},	
							summaryRenderer: function (value, summaryData, field) {
								                     			                             
	                            return Ext.String.format('Total: ' + value);
	                        }
						}
					]
				},	
				{
					xtype: 'form',
					border: false,
					name: 'formOperaciones',
					itemId: 'formOperaciones',
					margin: '5 5 5 5',
					anchorSize: '100%',	
					layout: 'vbox',
					cls: 'panelBtn',
					items: [
						{
							xtype: 'container',	
							defaults: {
								readOnly: true
							},				
							anchorSize: '100%',			
							margin: '5 5 5 5',
							layout: 'hbox',
							items:[
								{
									xtype: 'numberfield',
									name: 'id',
									itemId: 'id',
									fieldLabel: 'Id',
									width: 150,
									labelWidth: 90,
									hidden: true
								},
								{
									xtype: 'textfield',
									name: 'operacion',
									itemId: 'operacion',
									fieldLabel: 'Operacion N°',
									width: 150,
									labelWidth: 90
								},
								{
									xtype: 'button',
									action: 'buscaOperacion',
									itemId: 'buscaOperacion',
									iconCls: 'search',
									height: 25,
									width: 30,
									margin: '0 5 0 5'
								},
								{
									xtype: 'textfield',
									name: 'descripcionForm',
									itemId: 'descripcionForm',
									fieldLabel: 'Descripcion',
									width: 600
								}
							]
						},
						{
							xtype: 'container',
							labelWidth: 90,			
							anchorSize: '100%',			
							margin: '5 5 5 5',
							layout: 'hbox',
							items: [
								{
									xtype: 'timefield',
									itemId: 'tiempo',
									name: 'tiempo',
									format: 'H:i:s',
									fieldLabel: 'Tiempo'
								},
								{
									xtype: 'button',
									itemId: 'insert',
									action: 'actInsert',
									text: 'Insertar'
								}
							]
						}
					]
				},
								
				{
					xtype: 'container',	
					layout: 'fit',
					cls: 'panelBtn',
					anchorSize: '100%',				
					margin: '5 5 5 5',
					layout: 'hbox',
					items: [
						btnABMC
					]
				}
			]
		});
		this.callParent(arguments);		
	}
	
});
