Ext.define('MetApp.resources.ux.BtnABMC',{
	extend: 'Ext.container.Container',
	itemId: 'botonera',
	layout: 'fit',
	border: false,
	
	initComponent: function(){
		var me = this;
		me.callParent();	
	},
	resetFn: function(botonera){
		botonera.queryById('btnNew').setDisabled(false);
		botonera.queryById('btnSave').setDisabled(true);
		botonera.queryById('btnEdit').setDisabled(false);
		botonera.queryById('btnDelete').setDisabled(false);
	},
	nuevoItem: function(botonera){		
		botonera.queryById('btnSave').setDisabled(false);
		botonera.queryById('btnEdit').setDisabled(true);
		botonera.queryById('btnDelete').setDisabled(true);
	},
	editarItem: function(botonera){
		botonera.queryById('btnSave').setDisabled(false);
		botonera.queryById('btnEdit').setDisabled(true);
		botonera.queryById('btnDelete').setDisabled(true);
	},
	busquedaItem: function(botonera){
		botonera.queryById('btnSave').setDisabled(true);
		botonera.queryById('btnEdit').setDisabled(false);
		botonera.queryById('btnDelete').setDisabled(false);
	},
	guardarItem: function(botonera){
		botonera.queryById('btnSave').setDisabled(true);
		botonera.queryById('btnEdit').setDisabled(false);
		botonera.queryById('btnDelete').setDisabled(false);
	},
	items: [
		{
			xtype: 'fieldset',
			padding: '0 0 0 0',
			itemId: 'fieldSet',
			width: 600,	
			height: 32,
			border: false,
			defaults: {
				disabled: false,
				margin: '1 1 1 1',
				width: 105
			},
			items: [
				{
					xtype: 'button',
					margin: '1 1 1 0',
					iconCls: 'add',												
					height: 30,
					//width: 75,
					text: 'Nuevo (F3)',
					action: 'actNew',
					itemId: 'btnNew'					
				},
				{
					xtype: 'button',
					iconCls: 'save',
					disabled: true,
					height: 30,
					//width: 75,
					text: 'Guardar (F5)',
					action: 'actSave',
					itemId: 'btnSave',
				},
				{
					xtype: 'button',
					iconCls: 'edit',
					height: 30,
					//width: 75,
					text: 'Editar (F1)',
					action: 'actEdit',
					itemId: 'btnEdit'
				},
				{
					xtype: 'button',
					iconCls: 'delete',
					height: 30,
					//width: 75,
					text: 'Eliminar (F8)',
					action: 'actDelete',
					itemId: 'btnDelete'
				},
				{
					xtype: 'button',
					iconCls: 'reset',
					disabled: false,
					height: 30,
					//width: 75,
					text: 'Volver',
					action: 'actResetForm',
					itemId: 'btnReset'
				}	
			]
		}
	]
});
