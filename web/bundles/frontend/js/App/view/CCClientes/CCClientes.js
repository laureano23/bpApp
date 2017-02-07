Ext.define('MetApp.view.CCClientes.CCClientes' ,{
	extend: 'Ext.window.Window',
	height: 450,
	width: 900,
	modal: true,
	autoShow: true,
	alias: 'widget.CCClientes',
	itemId: 'CCClientes',
	title: 'Cuenta corriente clientes',
	layout: 'border',
	
	initComponent: function(){
		var me = this;
		Ext.applyIf(me,{
			items: [
				{
					xtype: 'form',
					store: 'MetApp.store.Finanzas.CCClientesStore',
					border: false,
					region: 'north',
					items: [
						{
							xtype: 'container',
							margins: '5 0 0 5',
							defaults: {
								margins: '5 0 0 5',
								disabled: true,
								disabledCls: 'myDisabledClass'
							},
							layout: 'hbox',
							items: [
								{
									xtype: 'numberfield',
									width: 200,
									name: 'id',
									itemId: 'id',
									fieldLabel: 'Cliente',						
								},
								{
									xtype: 'button',
									disabled: false,
									iconCls: 'search',
									itemId: 'buscaCliente'
								},
								{
									xtype: 'textfield',
									name: 'rsocial',
									itemId: 'rsocial',
									width: 450
								}
							]
						},
						{
							xtype: 'container',
							margins: '5 0 0 5',
							defaults: {
								margins: '5 0 0 5',
								disabled: true,
								disabledCls: 'myDisabledClass'
							},
							layout: 'hbox',
							items: [
								{
									xtype: 'textfield',
									fieldLabel: 'Direccion',
									name: 'direccion',
									width: 450
								},
								{
									xtype: 'textfield',
									name: 'cuit',
									labelWidth: 50,
									fieldLabel: 'CUIT'
								}
							]
						}					
					]
				},
				{
					xtype: 'grid',
					region: 'center',
					itemId: 'gridCC',	
					columns: {
						defaults: {
							sortable: false
						},
						items: [
							{ text: 'Id', dataIndex: 'id', hidden: true },
							{ 
								text: 'Emision',
								dataIndex: 'emision',
								renderer: function(val){
									var date = Ext.Date.parse(val, 'd-m-Y');
									return Ext.Date.format(date, 'd-m-Y');
									
								} 
							},
							{ text: 'Concepto', dataIndex: 'concepto' },
							{ text: 'Vencimiento', dataIndex: 'vencimiento' },
							{ text: 'Debe', dataIndex: 'debe' },
							{ text: 'Haber', dataIndex: 'haber' },
							{
								text: 'Saldo',
								dataIndex: 'saldo'
							},
							{ 
								header: 'Detalle',
								itemId: 'detalle',
								xtype: 'actioncolumn',
								items: [
									{ iconCls: 'search' }										
								] 
							},
							{ 
								header: 'Mail',
								itemId: 'mail',
								xtype: 'actioncolumn',
								items: [
									{ iconCls: 'mail' }										
								] 
							},
							{ 
								header: 'Eliminar',
								itemId: 'eliminar',
								xtype: 'actioncolumn',
								items: [
									{ iconCls: 'delete' }										
								] 
							},
							{ text: 'tipo', dataIndex: 'tipo', hidden: true }
						]
					},
					store: 'MetApp.store.Finanzas.CCClientesStore',
				},
				{
					xtype: 'container',
					disabled: true,
					itemId: 'btnCnt',
					region: 'south',
					layout: 'hbox',
					defaults: {
						margins: '5 0 5 5'
					},
					items: [
						{
							xtype: 'button',
							itemId: 'nuevaFc',
							height: 80,
							width: 60,
							text: 'Comp.',
						},
						{
							xtype: 'button',
							itemId: 'nuevoCobro',
							height: 80,
							width: 60,
							text: 'Cobro',
						},
						{
							xtype: 'button',
							height: 80,
							width: 60,
							text: 'Notas',
						}
					]
				}
			]
		});
		this.callParent();
	}
});
