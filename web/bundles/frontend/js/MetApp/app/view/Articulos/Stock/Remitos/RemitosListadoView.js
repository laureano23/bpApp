Ext.define('MetApp.view.Articulos.Stock.Remitos.RemitosListadoView' ,{
	extend: 'Ext.window.Window',	
	modal: true,
	autoShow: true,
	alias: 'widget.RemitosListadoView',
	itemId: 'RemitosListadoView',
	title: 'Remitos',
	layout: 'fit',
	
	initComponent: function(){
		
		var me = this;
		
		Ext.applyIf(me,{
			items: [
				{
					xtype: 'grid',
					height: 400,
					width: 900,
					itemId: 'gridRemitoPendiente',
					store: 'MetApp.store.Articulos.RemitosPendientesStore',
					columns: [
						{header: 'id remito', dataIndex: 'id', flex: 1, hidden: true},	
						{
							header: 'Emision',
							dataIndex: 'fecha',
							width: 100,
							format: 'd/m/Y',
							submitFormat: 'd/m/Y',
							xtype:'datecolumn',
						},
						{header: 'Cliente', dataIndex: 'cliente', width: 300},
						{header: 'Proveedor', dataIndex: 'proveedor', width: 300},
						{header: 'Remito Num.', dataIndex: 'remitoNum', flex: 1},
						{xtype: 'actioncolumn', header: 'Ver', itemId: 'verRemito', iconCls: 'reportes'}
					
					]
				}
			]			
		});
		
		this.callParent();
	}
});
