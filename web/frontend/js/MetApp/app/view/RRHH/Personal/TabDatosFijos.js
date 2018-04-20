Ext.define('MetApp.view.RRHH.Personal.TabDatosFijos', {
	extend: 'Ext.form.Panel',
	modal: true,	
	width: 900,
	height: 350,
	layout: 'fit',
	border: false,
	itemId: 'personalDatosFijos',
	alias: 'widget.personalDatosFijos',	
	autoShow: true,
	defaults: {
		border: false,
		frame: false
	},
	items: [
		{
			xtype: 'form',
			itemId: 'formDatosFijos',
			region: 'center',
			margins: '5 5 5 5',
			frame: false,
			border: false,
			layout: 'vbox',			
			items: [
				{
					xtype: 'container',
					frame: false,
					border: false,
					defaults: {
						allowBlank: false,
						readOnlyCls: 'myDisabledClass',
					},
					layout: 'hbox',
					items: [
						{
							xtype: 'numberfield',
							itemId: 'idDatosFijos',
							name: 'idDatosFijos',
							fieldLabel: 'Id',
							hidden: true
						},
						{
							xtype: 'textfield',
							readOnly: true,
							name: 'descripcionConcepto',
							itemId: 'descripcionConcepto',
							fieldLabel: 'Concepto',
							width: 300
						},
						{
							xtype: 'button',
							itemId: 'btnSearchConcepto',
							iconCls: 'search',
							margins: '0 0 5 5'
						}	
					]
				},
				{
					xtype: 'numberfield',
					name: 'cantDatosFijos',
					itemId: 'cantDatosFijos',
					fieldLabel: 'Importe/Cant',
					readOnly: true,
					readOnlyCls: 'myDisabledClass'
				},
				{
					xtype: 'button',
					text: 'Insertar',
					action: 'actSave'
				},
				{
					xtype: 'grid',
					border: false,
					frame: false,
					itemId: 'gridDatosFijos',
					store: 'MetApp.store.Personal.PersonalDatosFijosStore',
					width: 550,
					layout: 'fit',
					columns: [
						{ 
							text: 'Codigo',
							dataIndex: 'datosFijos',
							width: 75,
							renderer: function(val){ 
								return val.codigo;
							}
						},
						{ 
							text: 'Concepto',
							dataIndex: 'datosFijos',
							width: 300,
							renderer: function(val){
								return val.descripcion;
							}
						},
						{ 
							text: 'Importe',
							xtype: 'numbercolumn',
							format: '0.000,00',
							dataIndex: 'datosFijos',
							width: 75,
							renderer: function(val){
								return val.importe;
							}
						},
						{							
							header: 'Borrar',
							itemId: 'eliminarDatoFijo',
							xtype: 'actioncolumn',
							items: [
								{ iconCls: 'delete' }										
							],	
							flex: 1
						}
					]
				},							
			]
		}	
	]
		
	
});
