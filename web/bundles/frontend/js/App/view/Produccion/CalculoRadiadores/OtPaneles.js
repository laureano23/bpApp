Ext.define('MetApp.view.Produccion.CalculoRadiadores.OtPaneles', {
	extend: 'Ext.window.Window',
	alias: 'widget.otpaneles',
	itemId: 'otWinPaneles',
	title: 'O.T panel de Aire/Aceite',
	height: 450,
	width: 600,
	modal: true,
	autoShow: true,
	layout: 'fit',
	
	
	initComponent: function(){
		var me = this;
		Ext.applyIf(me,{
			items: [
			{
				xtype: 'form',
				itemId: 'otPanelesForm',
				store: 'Produccion.CalculoRadiadores.Ot',
				fieldDefaults: {
					msgTarget: 'side',
					blankText: 'Debe completar este campo',
					allowBlank: false,
					disabled: true,
				},
				items:[
					{
						xtype:'container',
						layout: 'hbox',
						defaults: {
							margin: '5, 5, 5, 5',					
						},					
						items: [
							{
								xtype: 'label',
								forId: 'ot',
								text: 'O.T:',
								style: 'font-weight:bold;font-size: 35px;text-align: justify;',
								margin: '10 5 5 5'
							},
							{
								xtype: 'numberfield',
								itemId: 'ot',
								name: 'ot',
								inputType: 'number',
								fieldStyle: 'font-size: 35px;',
								hideLabel: true,							
								height: 55,
								flex: 1,
								required: true
							},
							{
								xtype: 'button',
								scale: 'large',
								height: 55,							
								action: 'buscaOt',
								itemId: 'buscaOt',
								iconCls: 'search',
								iconAlign: 'center'
							}						
						]					
					},
					{
						xtype:'container',
						layout: 'hbox',
						defaults: {
							margin: '5, 5, 5, 5',					
						},					
						items: [
							{
								xtype: 'label',
								forId: 'codigo',								
								text: 'Código:',
								style: 'font-weight:bold;font-size: 35px;text-align: justify;',
								margin: '10 5 5 5'
							},
							{
								xtype: 'textfield',
								hidden: true,
								name: 'idCodigo',
								itemId:'idCodigo'
							},
							{
								xtype: 'textfield',
								readOnly: true,
								fieldStyle: 'font-size: 35px;',
								itemId: 'codigo',
								name: 'codigo',
								hideLabel: true,							
								height: 55,
								flex: 1,
								required: true
							},
							{
								xtype: 'button',
								scale: 'large',
								height: 55,							
								action: 'buscaPanel',
								itemId: 'buscaPanel',
								iconCls: 'search',
								iconAlign: 'center',
								disabled: true
							}
						]
					},
					{
						xtype:'container',
						layout: 'hbox',
						defaults: {
							margin: '5, 5, 5, 5',					
						},					
						items: [
							{
								xtype: 'label',
								forId: 'cliente',								
								text: 'Cliente:',
								style: 'font-weight:bold;font-size: 35px;text-align: justify;',
								margin: '10 5 5 5'
							},
							{
								xtype: 'textfield',
								readOnly: true,
								hidden: true,
								fieldStyle: 'font-size: 35px;',
								itemId: 'idCliente',
								name: 'idCliente',							
								height: 55,
								flex: 1,
								required: true
							},
							{
								xtype: 'textfield',
								readOnly: true,
								fieldStyle: 'font-size: 35px;',
								itemId: 'cliente',
								name: 'cliente',							
								height: 55,
								flex: 1,
								required: true
							},
							{
								xtype: 'button',
								scale: 'large',
								height: 55,							
								action: 'buscaCliente',
								itemId: 'buscaCliente',
								iconCls: 'search',
								iconAlign: 'center',
								disabled: true
							}
						]
					},
					{
						xtype:'container',
						layout: 'hbox',
						defaults: {
							margin: '5, 5, 5, 5',					
						},					
						items: [
							{
								xtype: 'label',
								forId: 'cantidad',
								text: 'Cantidad:',
								style: 'font-weight:bold;font-size: 35px;text-align: justify;',
								margin: '10 5 5 5'
							},
							{
								xtype: 'numberfield',
								inputType: 'number',
								fieldStyle: 'font-size: 35px;',
								itemId: 'cantidad',
								name: 'cantidad',
								hideLabel: true,							
								height: 55,
								flex: 1,
								required: true
							}
						]
					},
					{
						xtype:'container',
						layout: 'hbox',
						defaults: {
							margin: '5, 5, 5, 5',	
							disabled: true				
						},					
						items: [
							{
								xtype: 'button',
								scale: 'large',
								height: 50,																									
								text: 'Nuevo',
								iconCls: 'add',
								itemId: 'btnNew',
								action: 'btnNew',
								disabled: false
							},
							{
								xtype: 'button',
								scale: 'large',
								height: 50,																									
								text: 'Guardar',
								iconCls: 'save',
								itemId: 'btnSave',
								action: 'btnSave'
							},	
							{
								xtype: 'button',
								scale: 'large',
								height: 50,																	
								text: 'Armado Panel',
								iconCls: 'reportes',
								itemId: 'btnArmado',
								action: 'btnReporteArmadoPanel',
							},						
							{
								xtype: 'button',
								scale: 'large',
								height: 50,																		
								text: 'Hoja de ruta',
								iconCls: 'reportes',
								itemId: 'btnReporte',
								action: 'btnReporte',
							}
						]
					},
					{
						xtype:'container',
						layout: 'hbox',
						defaults: {
							margin: '5, 5, 5, 5',	
							disabled: true				
						},					
						items: [
							{
								xtype: 'button',
								scale: 'large',
								height: 50,																									
								text: 'Reset',
								iconCls: 'reset',
								itemId: 'btnReset',
								action: 'btnReset',
								disabled: false
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
