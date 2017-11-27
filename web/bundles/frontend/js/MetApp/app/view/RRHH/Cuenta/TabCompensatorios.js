Ext.define('MetApp.view.RRHH.Cuenta.TabCompensatorios', {
	extend: 'Ext.form.Panel',
	modal: true,	
	width: 700,
	height: 400,
	layout: 'border',
	border: false,
	id: 'tabCompensatorios',
	itemId: 'tabCompensatorios',
	alias: 'widget.tabCompensatorios',	
	autoShow: true,
	defaults: {
		border: false,
		frame: false
	},
	
	initComponent: function(){
		var me = this;
		
		Ext.applyIf(me,{
			items: [	
				{
					xtype: 'container',
					hidden: true,
					region: 'north',
					margin: '5 5 5 5',
					layout: 'hbox',
					items: [
						{
							xtype: 'numberfield',
							fieldLabel: 'Id',
							name: 'idP',
							itemId: 'idP',
							hidden: true
						},
					]
				},			
				{
					xtype: 'grid',
					itemId: 'compGrid',
					region: 'center',
					store: 'MetApp.store.Personal.CuentaEmpleadosCompStore',
					columns: [
						{
							text: 'Concepto',
							dataIndex: 'periodo',
							width: 280,											
						},
						{
							text: 'Debe',
							xtype: 'numbercolumn',
							dataIndex: 'pagado',
							width: 80,		
						},
						{
							text: 'Haber',
							xtype: 'numbercolumn',
							dataIndex: 'neto',
							width: 80,		
						},
						{
							text: 'Saldo',
							dataIndex: 'saldo',
							xtype: 'numbercolumn',
							width: 80,	
						}
					]
				},
				{
					xtype: 'container',
					margin: '5 5 5 5',
					region: 'south',
					items: [
						{
							xtype: 'form',
							itemId: 'compForm',
							border: false,
							layout: 'vbox',
							items: [
								{
									xtype: 'container',
									layout: 'hbox',
									items: [
										{
											xtype: 'numberfield',
											hidden: true,
											fieldLabel: 'Id',
											name: 'id',
											itemId: 'id',
											disabled: true,
											disabledCls: 'myDisabledClass'
										},
										{
											xtype: 'textfield',
											fieldLabel: 'Concepto',
											name: 'periodo',
											itemId: 'concepto',
											disabled: true,
											disabledCls: 'myDisabledClass'
										},
										{
											xtype: 'numberfield',
											name: 'neto',
											itemId: 'neto',
											width: 130,
											labelWidth: 50,
											disabled: true,
											disabledCls: 'myDisabledClass',
											margin: '0 0 0 5',
											decimalSeparator: '.',
											fieldLabel: 'Importe'
										},
										{
											xtype: 'button',
											itemId: 'insertar',
											comp: 1,
											margin: '0 0 0 5',
											text: 'Insertar',
											disabled: true
										}
									]
								},
								{
									xtype: 'container',
									layout: 'hbox',
									defaults: {
										height: 30,
										width: 75,
										margin: '5 0 0 5',
									},
									items: [
										{
											xtype: 'button',
											comp: 1,
											text: 'Pago',
											iconCls: 'costos',
											itemId: 'pago',
										},
										{
											xtype: 'button',
											comp: 1,
											text: 'Editar',
											iconCls: 'edit',
											itemId: 'editar',
											disabled: true
										},
										{
											xtype: 'button',
											comp: 1,
											text: 'Eliminar',
											iconCls: 'delete',
											itemId: 'eliminar',
											disabled: true
										}
									]
								}	
							]
						}
						
					]
				}
			]
		})		
		me.callParent();
	}
});
