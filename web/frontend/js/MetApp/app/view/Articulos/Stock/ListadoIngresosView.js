Ext.define('MetApp.view.Articulos.Stock.ListadoIngresosView', {
	extend: 'Ext.window.Window',
	alias: 'widget.ListadoIngresosView',
	itemId: 'ListadoIngresosView',
	layout: 'fit',
	autoShow: true,
	title: 'Listado de Ingresos',
	modal: true,	
	//width: 600,
	bodyStyle:{"background-color":"white"}, 
	
	initComponent: function(){
		var me = this;
		Ext.applyIf(me,{
			items: [
				{
					xtype: 'form',
					border: false,
					margins: '5 5 5 5',
					//layout: 'fit',
					itemId: 'form',
					items: [
						{
							xtype: 'container',
							layout: 'hbox',
							items: [
								{
									xtype: 'fieldset',
									title: 'Orígen',
									margins: '0 0 0 5',
									items: [
										{
											xtype: 'radiogroup',
											itemId: 'origen',
											name: 'origen',
											labelWidth: 50,
											margins: '0 0 0 5',
											columns: 2,
		        							vertical: true,
											width: 270,
								            items: [
								                { boxLabel: 'Proveedor', name: 'origen', inputValue: 'proveedor', width: 80, checked: true },
								                { boxLabel: 'Cliente', name: 'origen', inputValue: 'cliente', width: 120 }
								            ]
										},											
									]
								},
								{
									xtype: 'numberfield',
									name: 'idOrigen',
									itemId: 'idOrigen',
									hidden: true
								},
								{
									xtype: 'textfield',
									name: 'origen',
									margins: '5 0 0 5',
									itemId: 'origen',
									labelWidth: 120,
									width: 400
								},
								{
									xtype: 'button',
									itemId: 'buscarOrigen',
									iconCls: 'search',
									margins: '0 0 0 7',
								}
							]
						},
						{
							xtype: 'grid',
							marigns: '5 0 0 0',
							width: 800,
							height: 400,
							style: {
								background: 'white'
							},
							store: 'MetApp.store.Compras.HistoricoCompraStore',
							columns: [
								{ text: 'Id detalle', dataIndex: 'id', width: 150, hidden: true },
								{ text: 'Código', dataIndex: 'codigo', width: 150 },
								{ text: 'Descripción', dataIndex: 'descripcion', width: 300 },
								{ text: 'Cantidad', dataIndex: 'cant', flex: 1 },
								{ text: 'Lote', dataIndex: 'lote', flex: 1 },
								{ text: 'OC N°', dataIndex: 'idOc', flex: 1 },
								{ 
									header: 'Imprimir',
									itemId: 'imprimir',
									xtype: 'actioncolumn',
									items: [
										{ iconCls: 'reportes2' }										
									],
									flex: 1
								},
							]
						},											
					]
				}
			]
		});
		this.callParent();
	}
});