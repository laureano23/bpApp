Ext.define('MetApp.view.Calidad.NumCorrelativaForm',{
	extend: 'Ext.window.Window',
	alias: 'widget.correlativoForm',
	id: 'correlativoForm',
	height: 600,
	width: 1000,
	autoShow: true,
	title: 'Numeracion correlativa',
	modal: true,
	widget: 'correlativoForm',	
	frame: true,
	layout: 'border',
	
	initComponent: function(){
		var me = this;		
		var botonera = Ext.create('MetApp.resources.ux.BtnABMC');
		botonera.queryById('btnNew').hide();
		botonera.queryById('btnSave').setDisabled(false);
		Ext.applyIf(me,{
			items: [
				{
					xtype: 'form',
					border: false,
					region: 'north',
					layout: 'column',			
					frame: true,							
					bodyPadding: 10,					
					fieldDefaults: {						
						msgTarget: 'side',
						blankText: 'Debe completar este campo',
						allowBlank: false
					},
					items: [
						{
							xtype: 'container',
							columnWidth: 0.5,
							items: [
								{
									xtype: 'textfield',
									name: 'idCorrelativos',
									hidden: true
								},
								{
									xtype: 'numberfield',							
									fieldLabel: 'Num. desde',
									itemId: 'numDesde',						
									name: 'numCorrelativo'									
								},
								{
									xtype: 'numberfield',							
									fieldLabel: 'Num. hasta',
									itemId: 'numHasta',
									name: 'num_hasta'
								},
								{
									xtype: 'numberfield',
									fieldLabel: 'Cantidad',
									itemId: 'cant',
									fieldStyle: 'background: #c8f9fd',
									name: 'cant',
									readOnly: true
								},
								{
									xtype: 'datefield',
									fieldLabel: 'Fecha',
									emptyText: 'dd/mm/yy',       								
									format: 'd-m-y',
									name: 'fecha.date'
								},
								{
									xtype: 'numberfield',
									fieldLabel: 'OT enfriador',
									name: 'otEnf'
								}
							]
						},
						{
							xtype: 'container',
							columnWidth: 0.5,
							items: [
								{
									xtype: 'fieldset',
									title: 'Paneles',
									collapsible: true,
									items: [
										{
											xtype: 'numberfield',
											fieldLabel: 'OT 1',
											name: 'ot1panel'
										},
										{
											xtype: 'numberfield',
											fieldLabel: 'OT 2',
											name: 'ot2panel',
											allowBlank: true
										},
										{
											xtype: 'numberfield',
											fieldLabel: 'OT 3',
											name: 'ot3panel',
											allowBlank: true
										},
										{
											xtype: 'numberfield',
											fieldLabel: 'OT 4',
											name: 'ot4panel',
											allowBlank: true
										},
																			
									]
								},
								{
									xtype: 'container',
									layout: 'fit',
									//width: 600,
									items: [
										{
											xtype: 'textfield',
											width: 600,
											fieldLabel: 'Observaciones',
											name: 'obs',
											allowBlank: true
										}
									]							
								}								
							]
						},						
					]
				},
				{
					xtype: 'container',
					height: 20,
					region: 'center',
					layout: 'hbox',
					items:[
						botonera
					]
				},
				{
					xtype: 'gridCorrelativos',
					region: 'south',
				}
			]			
		});		
	this.callParent();	
	}
	
});















