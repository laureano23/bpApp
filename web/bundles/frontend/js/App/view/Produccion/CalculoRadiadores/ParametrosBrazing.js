Ext.define('MetApp.view.Produccion.CalculoRadiadores.ParametrosBrazing', {
	extend: 'Ext.window.Window',
	title: 'Parametros Brazing',
	height: 450,
	width: 500,
	alias: 'widget.parametrosbrazing',	
	modal: true,
	autoShow: true,
	id: 'brazingParamsForm',
	
	
	initComponent: function(){
		var me = this;
		Ext.applyIf(me,{
			items: [
				{
					xtype: 'form',
					border: false,
					fieldDefaults: {
						margin: '5 5 5 5',
						labelWidth: 200,						
					},					
					items: [
						{
							xtype: 'combobox',
							itemId: 'tipoCarga',
							fieldLabel: 'Tipo de Carga:',							
							store: ['Liviana', 'Pesada'],
							value: 'Liviana',
							disabled: false
						},
						{
							xtype: 'textfield',
							fieldLabel: 'Ciclos:',
							name: 'ciclos',
							disabled: true,
							disabledCls: 'myDisabledClass',
						},
						{
							xtype: 'timefield',
							format: 'H:i',
							name: 'intervalos',
							fieldLabel: 'Intervalos de giro:',
							disabled: true,
							disabledCls: 'myDisabledClass',
							
						},
						{
							xtype: 'timefield',
							format: 'H:i',
							name: 'tiempoCarga',
							fieldLabel: 'Tiempo a T°:',
							emptyText: 'hh:mm',			
							disabled: true,				
							disabledCls: 'myDisabledClass'							
						},
						{
							xtype: 'textfield',
							name: 'tclEnfSup',
							fieldLabel: 'T° enfriador sup:',
							disabled: true,
							disabledCls: 'myDisabledClass'
						},
						{
							xtype: 'textfield',
							name: 'tclPurgaInf',
							fieldLabel: 'T° purga inf:',
							disabled: true,
							disabledCls: 'myDisabledClass'
						},
						{
							xtype: 'textfield',
							name: 'tclEnfInf',
							fieldLabel: 'T° enfriador inf:',
							disabled: true,
							disabledCls: 'myDisabledClass'
						},
						{
							xtype: 'textfield',
							name: 'tclPurgaSup',
							fieldLabel: 'T° purga sup:',
							disabled: true,
							disabledCls: 'myDisabledClass'
						},
						{
							xtype: 'textfield',
							name: 'tPrecalentado',
							fieldLabel: 'T° precalentado:',
							disabled: true,
							disabledCls: 'myDisabledClass',
						},			
						{
							xtype: 'textfield',
							name: 'caudalPrecamara',
							fieldLabel: 'Nitrogeno precamara:',
							disabled: true,
							disabledCls: 'myDisabledClass',
						},
						{
							xtype: 'textfield',
							name: 'caudalHorno',
							fieldLabel: 'Nitrogeno horno:',
							disabled: true,
							disabledCls: 'myDisabledClass',
						}						
					]
				},
				{
					xtype: 'container',
					defaults: {
						margin: '5 5 5 5',
						height: 50
					},
					items: [
						{
							xtype: 'button',
							scale: 'large',
							text: 'Editar',
							iconCls: 'edit',
							itemId: 'edit',
							action: 'edit',
						},
						{
							xtype: 'button',
							scale: 'large',
							text: 'Guardar',
							iconCls: 'save',
							itemId: 'save',
							action: 'save',
							disabled: true
						}
					]
				}				
			]
		});
		this.callParent();
	}
});
