Ext.define('MetApp.view.Articulos.EnfriadoresFormView', {
	extend: 'Ext.window.Window',
	alias: 'widget.EnfriadoresFormView',
	itemId: 'EnfriadoresFormView',
	width: 800,
	layout: 'fit',
	autoShow: true,
	title: 'Tabla de enfriadores',
	modal: true,
	
	initComponent: function(){
		
		var me = this;
		Ext.applyIf(me,{
			items: [
				{
					xtype: 'form',
					margins: '5 5 5 5',
					border: false,
					frame: false,					
					items: [
						{
							xtype: 'container',
							layout: 'hbox',
							items: [
								{
									xtype: 'numberfield',
									name: 'id',
									itemId: 'idCodigo',
									fieldLabel: 'Id',				
									width: 250,
									text: 'id',
									hidden: true				
								},
								{
									xtype: 'textfield',				
									name: 'codigo',
									readOnly: true,
									itemId: 'codigo',
									fieldLabel: 'Codigo',				
									width: 250,
									text: 'codigo'				
								},
								{
									xtype: 'button',	
									margins: '0 0 0 5',			
									height: 25,				
									width: 30,
									iconCls: 'search',
									itemId: 'buscaArt'
								}
							]
						},
						{
							xtype: 'grid',
							store: 'MetApp.store.Articulos.FormulaEnfriadoresStore',
							columns: [
								{header: 'Id', dataIndex: 'id', flex: 1, hidden: false},
								{header: 'Codigo', dataIndex: 'codigo', flex: 1},
								{header: 'Descripcion', dataIndex: 'descripcion', flex: 1},
								{ 
									header: 'Imagen',
									itemId: 'nombreImagen',
									dataIndex: 'nombreImagen',
									xtype: 'actioncolumn',
									items: [
										{ iconCls: 'search' }										
									],
									flex: 1
								},								
							]
						}								
					]
				}						
			]
		})
		this.callParent();
	}
});
