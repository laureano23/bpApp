Ext.define('MetApp.view.Produccion.Pedidos.Reportes.ReportePedidos', {
	extend: 'Ext.window.Window',
	height: 250,
	width: 300,
	autoShow: true,
	modal: true,
	idProperty: 'id',
	alias: 'widget.repoPedidos',
	itemId: 'repoPedidos',
	layout: 'vbox',
	title: 'Reporte articulos pedidos',
	
	initComponent: function(){
		var me = this;
		Ext.applyIf(me, {
			items: [				
				{
					xtype: 'form',
					border: false,
					defaults: {
						margin: '2 2 2 2',	
					},
					height: 250,
					layout: {
						type: 'vbox',
						align: 'center'
					},
					items: [							
						{
							xtype: 'container',
							layout: 'hbox',
							items: [
								{
									xtype: 'textfield',
									name: 'clienteDesde',
									itemId: 'clienteDesde',
									width: 100,
									fieldLabel: 'Cliente',
									labelAlign: 'top',
									listeners: {
										blur: {
											fn: function(txt){
												if(txt.getValue() == ""){
													txt.setValue(1);
												}
											}
										}
									}
								},
								{
									xtype: 'button',
									iconCls: 'search',									
									itemId: 'buscaClienteDesde',
									action: 'buscaClienteDesde',
									margin: '22 5 0 2'
								},
								{
									xtype: 'textfield',
									name: 'clienteHasta',
									itemId: 'clienteHasta',
									width: 100,
									fieldLabel: 'Cliente',
									labelAlign: 'top',
									listeners: {
										blur: {
											fn: function(txt){
												if(txt.getValue() == ""){
													txt.setValue(99999);
												}
											}
										}
									}
								},
								{
									xtype: 'button',
									iconCls: 'search',
									itemId: 'buscaClienteHasta',
									action: 'buscaClienteHasta',
									margin: '22 0 0 2'
								}
							]
						},
						{
							xtype: 'container',
							layout: 'hbox',
							items: [
								{
									xtype: 'datefield',
									margin: '0 2 0 0',
									name: 'fechaDesde',
									itemId: 'fechaDesde',
									width: 140,
									fieldLabel: 'Desde',
									labelAlign: 'top'
								},
								{
									xtype: 'datefield',
									name: 'fechaHasta',
									itemId: 'fechaHasta',
									width: 140,
									fieldLabel: 'Hasta',
									labelAlign: 'top'
								}
							]
						},
						{
							xtype: 'container',
							layout: 'hbox',
							items: [
								{
									xtype: 'textfield',
									name: 'articuloDesde',
									itemId: 'articuloDesde',
									width: 100,
									fieldLabel: 'Articulo',
									labelAlign: 'top',
									listeners: {
										blur: {
											fn: function(txt){
												if(txt.getValue() == ""){
													txt.setValue(1);
												}
											}
										}
									}
								},
								{
									xtype: 'button',
									iconCls: 'search',									
									itemId: 'buscaArtDesde',
									action: 'buscaArtDesde',
									margin: '22 5 0 2'
								},
								{
									xtype: 'textfield',
									name: 'articuloHasta',
									itemId: 'articuloHasta',
									width: 100,
									fieldLabel: 'Articulo',
									labelAlign: 'top',
									listeners: {
										blur: {
											fn: function(txt){
												if(txt.getValue() == ""){
													txt.setValue("ZZZ");
												}
											}
										}
									}
								},
								{
									xtype: 'button',
									iconCls: 'search',									
									itemId: 'buscaArtHasta',
									action: 'buscaArtHasta',
									margin: '22 5 0 2'
								}
							]
						},
						{
							xtype: 'button',
							itemId: 'newReportePedidos',
							action: 'newReportePedidos',
							height: 50,
							width: 50,
							iconCls: 'reportes'
						}
					]
				}		
			]
		});
		this.callParent();
	}
});