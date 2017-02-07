Ext.define('MetApp.view.CCProveedores.CCProveedores' ,{
	extend: 'Ext.window.Window',
	height: 450,
	width: 1000,
	modal: true,
	autoShow: true,
	alias: 'widget.CCProveedores',
	itemId: 'CCProveedores',
	title: 'Cuenta corriente proveedores',
	layout: 'border',
	
	initComponent: function(){
		var me = this;
		Ext.applyIf(me,{
			items: [
				{
					xtype: 'form',
					//store: 'MetApp.store.Finanzas.CCClientesStore',
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
									fieldLabel: 'Proveedor',						
								},
								{
									xtype: 'button',
									disabled: false,
									iconCls: 'search',
									itemId: 'buscaProveedor'
								},
								{
									xtype: 'textfield',
									name: 'rSocial',
									itemId: 'rSocial',
									width: 450
								},
								{
									xtype: 'numberfield',
									hidden: true,
									name: 'tipoGasto',
									itemId: 'tipoGasto',
									fieldLabel: 'Tipo gasto'
								},
								{
									xtype: 'numberfield',
									hidden: true,
									name: 'vencimientoFc',
									itemId: 'vencimientoFc',
									fieldLabel: 'Vencimiento Fc'
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
					store: 'MetApp.store.Proveedores.CCProveedoresStore',
					region: 'center',
					itemId: 'gridCC',	
					height: 50,
					columns: {
						defaults: {
							sortable: false
						},
						items: [
							{ text: 'IdF', dataIndex: 'idF', hidden: true, flex: 1 },
							{ text: 'IdOP', dataIndex: 'idOP', hidden: true, flex: 1 },
							{ text: 'Emision', dataIndex: 'fechaEmision', width:100,
								renderer: function(val){
									var dt = Ext.Date.parse(val, 'd-m-Y H:i:s');
									return Ext.Date.format(dt, 'd-m-Y');
								}
							 },
							{ 
								text: 'Concepto',
								dataIndex: 'concepto',
								width:300,
								renderer: function(value, metaData, record, row, col, store, gridView){
									if(record.get('valorImputado') != record.get('haber')){
										return '<span style="color:red;">'+value+'</span>';
									}else{
										return value;
									}
								},
							},
							{ text: 'Vencimiento', dataIndex: 'vencimiento', width:100 },
							{ text: 'Debe', dataIndex: 'debe', flex: 1 },
							{ text: 'Haber', dataIndex: 'haber', flex: 1 },
							{
								text: 'Saldo',
								dataIndex: 'saldo',
								flex: 1
							},
							{ 
								header: 'Detalle',
								renderer: function(val){
									if(val==false){
										this.removeCls('search');
									}
								},
								itemId: 'detalle',
								xtype: 'actioncolumn',								
								items: [
									{ 
										iconCls: 'search',
										getClass: function(value,metadata,record){
											var debe = record.get('debe');
											if (debe == 0 ) {
											    return 'x-hide-display'; 
											} else {
											    return 'search';               
											}
										},
									}										
								],
								flex: 1,
								dataIndex: 'detalle'
							},
							{ 
								header: 'Eliminar',
								itemId: 'eliminar',
								xtype: 'actioncolumn',
								items: [
									{ iconCls: 'delete' }										
								],
								flex: 1 
							},
							{ 
								header: 'Imputado',
								renderer: function(val){
									if(val==false){
										this.removeCls('search');
									}
								},
								itemId: 'imputado',
								xtype: 'actioncolumn',								
								items: [
									{ 
										iconCls: 'search',
										getClass: function(value,metadata,record){
											var haber = record.get('haber');
											if (haber == 0 ) {
											    return 'x-hide-display'; 
											} else {
											    return 'search';               
											}
										},
									}										
								],
								flex: 1,
								dataIndex: 'imputado'
							},
							{ 
								header: 'Editar',
								itemId: 'editarComp',
								xtype: 'actioncolumn',								
								items: [
									{ 
										iconCls: 'edit',
										/*getClass: function(value,metadata,record){
											var idOP = record.get('idOP');
											if (idOP == 0 ) {
											    return 'x-hide-display'; 
											} else {
											    return 'edit';               
											}
										},*/
									}										
								],
								flex: 1,
								dataIndex: 'imputado'
							},
							{ text: 'tipo', dataIndex: 'tipo', hidden: true },
							{ text: 'valorIm', dataIndex: 'valorImputado', hidden: true }
						]
					},
				},
				{
					xtype: 'container',
					height: 90,
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
							text: 'Fact.',
						},
						{
							xtype: 'button',
							itemId: 'nuevoPago',
							height: 80,
							width: 60,
							text: 'Pago',
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
