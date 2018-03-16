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
	listeners: {
		afterrender: {
			fn: function(win){				
				var map = new Ext.util.KeyMap({
				    target: this.getId(),	
				   	binding: [
				   		{
				   			key: Ext.EventObject.F3,
				   			defaultEventAction: 'preventDefault',
				   			fn: function(){ 
				   				win.queryById('nuevaFc').fireEvent('click');
				   			}
				   		},
				   		{
				   			key: Ext.EventObject.F4,
				   			defaultEventAction: 'preventDefault',
				   			fn: function(){ 
				   				win.queryById('nuevoCobro').fireEvent('click');
				   			}
				   		},
				   		{
				   			key: Ext.EventObject.F5,
				   			defaultEventAction: 'preventDefault',
				   			fn: function(){ 
				   				win.queryById('notas').fireEvent('click');
				   			}
				   		},
				   	]
				});	
			}
		}
	},
	
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
								readOnly: true,
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
								readOnly: true,
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
							{ text: 'Debe', dataIndex: 'debe', xtype: 'numbercolumn' },
							{ text: 'Haber', dataIndex: 'haber', xtype: 'numbercolumn' },
							{
								text: 'Saldo',
								dataIndex: 'saldo',
								xtype: 'numbercolumn'
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
								renderer: function(val, met, rec){
									if(!rec.data.tipo){
										this.items[0].iconCls = 'delete';
									}else{
										this.disabledAction(rec.index);
									}
								}
							},
							{ text: 'tipo', dataIndex: 'tipo', hidden: true }
						]
					},
					store: 'MetApp.store.Finanzas.CCClientesStore',
				},
				{
					xtype: 'container',
					style: {
						background: 'white'
					},
					readOnly: true,
					border: false,
					itemId: 'btnCnt',
					region: 'south',
					layout: 'hbox',
					defaults: {
						margins: '5 0 5 5',
						width: 80,
						height: 80
					},
					items: [
						{
							xtype: 'button',
							itemId: 'nuevaFc',
							text: 'Comp. (F3)',
						},
						{
							xtype: 'button',
							itemId: 'nuevoCobro',
							text: 'Cobro (F4)',
						},
						{
							xtype: 'button',
							itemId: 'notas',
							text: 'Notas (F5)',
						}
					]
				}
			]
		});
		this.callParent();
	}
});
