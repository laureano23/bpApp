Ext.define('MetApp.view.Articulos.ListaDePrecios.ReporteListaMaestra', {
	extend: 'Ext.window.Window',
	height: 190,
	width: 500,
	modal: true,
	autoShow: true,
	alias: 'widget.ReporteListaMaestra',
	itemId: 'ReporteListaMaestra',
	title: 'Lista Maestra',
	layout: 'fit',
	
	initComponent: function(){
		var me = this;
		Ext.applyIf(me,{
			items:[
				{
					xtype: 'form',
					//layout: 'vbox',
					defaults: {
						margin: '5 5 5 5',
					},
					itemId: 'formListaMaestra',
					items: [						
						{
							xtype: 'combobox',
							name: 'familia',
							itemId: 'familia',
							width: 350,
							fieldLabel: 'Familia',
							store: 'MetApp.store.Articulos.FamiliaStore',
							displayField: 'familia',
							valueField: 'idFamilia',
							margins: '0 5 0 0'
						},
						{
							xtype: 'combobox',
							itemId: 'subFamilia',
							name: 'subFamilia',
							width: 350,
							fieldLabel: 'Sub familia',
							store: 'MetApp.store.Articulos.SubFamiliaStore',
							displayField: 'subFamilia',
							valueField: 'idSubFamilia',
						},
						{
							xtype: 'button',
							text: 'Imprimir',
							height: 30,
							width: 80,
							itemId: 'imprimir',
							iconCls: 'reportes2'
						}
					]
				}						
			],			
		});		
		this.callParent();
	}
});
