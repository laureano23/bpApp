Ext.define('MetApp.view.Produccion.Programacion.FormProgramacion', {
	extend: 'Ext.window.Window',
	alias: 'widget.formProgramacion',
	id: 'formProgramacion',
	itemId: 'formProgramacion',
	width: 400,
	height: 250,
	layout: 'fit',
	autoShow: true,
	title: 'Formulario programacion',
	modal: true,
	
	initComponent: function(){
		var me = this;
		
		
		Ext.applyIf(me, {
			items: [
				{
					xtype: 'form',
					itemId: 'formProg',
					store: 'Produccion.Programacion.ProgramacionStore',
					fieldDefaults: {
						margin: '5 5 5 5',	
					},					
					items: [
						{
							xtype: 'textfield',
							readOnly: true,
							fieldLabel: 'Operacion',
							name: 'operacion',
							itemId: 'operacion'						
						},
						{
							xtype: 'datefield',
							format: 'H:i:s',
							readOnly: true,
							fieldLabel: 'Tiempo/u',
							name: 'tiempoU',
							itemId: 'tiempoU',
						},
						{
							xtype: 'datefield',
							allowBlank: false,
							fieldLabel: 'Fecha Inicio',
							name: 'fechaI',
							itemId: 'fechaI',
						},
						{
							xtype: 'timefield',
							allowBlank: false,
							name: 'horaI',
							itemId: 'horaI',
							fieldLabel: 'Hora inicio'
						},
						{
							xtype: 'combobox',
							fieldLabel: 'Maquina',
							itemId: 'maquina',
							store: 'Produccion.Programacion.MaquinasStore',
							displayField: 'descripcion',
							valueField: 'id'
						},
						{
							xtype: 'button',
							margin: '10 0 0 170',
							text: 'Aceptar',
							itemId: 'progRec',
							action: 'progRec'
						}
					]
				}				
			]
		});
		this.callParent();
	}
});
