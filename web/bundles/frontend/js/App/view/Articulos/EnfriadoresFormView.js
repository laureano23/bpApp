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
							margin: '0 0 5 0',
							items: [
								{
									xtype: 'numberfield',
									name: 'id',
									itemId: 'idCodigo',
									fieldLabel: 'Id',				
									width: 250,
									hidden: true				
								},
								{
									xtype: 'numberfield',
									name: 'idFormula',
									itemId: 'idFormula',
									fieldLabel: 'Id formula',				
									width: 250,
									hidden: false				
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
								},
								{
									xtype: 'button',	
									margins: '0 0 0 5',			
									height: 25,
									itemId: 'estructura',
									text: 'Estructura'
								}
							]
						},
						{
							xtype: 'grid',
							store: 'MetApp.store.Articulos.FormulaEnfriadoresStore',
							columns: [
								{header: 'Id', dataIndex: 'idArt', flex: 1, hidden: false},
								{header: 'Codigo', dataIndex: 'codigo', width: 250},
								{header: 'Descripcion', dataIndex: 'descripcion', width: 450},
								{ 
									header: 'Imagen',
									itemId: 'nombreImagen',
									dataIndex: 'nombreImagen',
									xtype: 'actioncolumn',
									items: [
										{ 
											iconCls: 'search',
											getClass: function(value,metadata,record){
												var nombre = record.get('nombreImagen');
												console.log(nombreImagen);
												if (nombre == "" ) {
												    return 'x-hide-display'; 
												} else {
												    return 'search';               
												}
											},
										}										
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
