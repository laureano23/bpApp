Ext.define('MetApp.view.CCProveedores.ChequeTerceros' ,{
	extend: 'Ext.window.Window',
	height: 400,
	width: 950,
	modal: true,
	autoShow: true,
	alias: 'widget.ChequeTerceros',
	itemId: 'ChequeTerceros',
	title: 'Cheques de terceros',
	layout: 'anchor',
	
	initComponent: function(){
		var me = this;
		
		Ext.applyIf(me,{
			items: [
				{
					xtype: 'grid',
					store: 'MetApp.store.Proveedores.GridChequeTercerosStore',
					itemId: 'gridChequeTerceros',
					anchorSize: '100%',	
					scroll: true,
					height: 300,
					columns: {
						defaults: {
							sortable: false
						},
						items: [
							{ text: 'Id', dataIndex: 'id', hidden: true, flex: 1 },							
							{ text: 'forma de pago Id', dataIndex: 'fid', hidden: false, flex: 1 },
							{ text: 'Banco', dataIndex: 'banco', flex: 1 },
							{ text: 'Emision', dataIndex: 'emision', flex: 1 },
							{ text: 'Diferido', dataIndex: 'diferido', flex: 1 },
							{ text: 'N° de cheque', dataIndex: 'numero', flex: 1 },									
							{ text: 'Librador', dataIndex: 'librador', width: 300 },
							{ text: 'Importe', dataIndex: 'importe', flex: 1, sortable: true },
							{ 
								xtype : 'checkcolumn',
								text : 'Marcar',
								dataIndex: 'marca',
								listeners: {
									checkchange: function(col, rowIndex, checked, eOpts){
										var win = col.up('window');
										var grid = col.up('grid');
										var store = grid.getStore();
										var txtImporte = win.queryById('importe');
										
										txtImporte.setValue(0);
										store.each(function(rec){
											if(rec.get('marca') == true){
												txtImporte.setValue(rec.get('importe') + txtImporte.getValue());	
											}											
										});
									}
								}
							}
						]
					}					
				},
				{
					xtype: 'container',
					margin: '5 0 5 5',
					layout: 'hbox',
					items: [
						{
							xtype: 'numberfield',
							fieldLabel: 'Importe',
							itemId: 'importe',
							readOnly: true
						},
						{
							xtype: 'button',
							margin: '0 0 0 5',
							itemId: 'aceptar',
							text: 'Aceptar'
						}
					]
				}							
			]
		});
		this.callParent();
	}
});
