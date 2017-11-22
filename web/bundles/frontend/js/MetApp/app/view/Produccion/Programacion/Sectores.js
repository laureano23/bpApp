Ext.define('MetApp.view.Produccion.Programacion.Sectores',{
	extend: 'Ext.window.Window',
	width: 700,
	modal: true,
	layout: 'anchor',
	alias: 'widget.sectores',
	itemId: 'tablaSectores',
	autoShow: true,
	title: 'Tabla de sectores',
	
	initComponent: function(){
		
		var me = this;
		Ext.applyIf(me, {
			items: [
				{
					xtype: 'form',
					anchorSize: '100%',	
					border: false,
					margin: '5 5 5 5',
					items: [
						{
							xtype: 'combobox',
							store: 'Produccion.Programacion.SectoresStore',
							displayField: 'descripcion',
							name: 'sector',
							itemId: 'sector',
							fieldLabel: 'Sector',
							width: 450
						},
						{
							xtype: 'numberfield',
							readOnly: true,
							name: 'costoSector',
							itemId: 'costoSector',
							fieldLabel: 'Costo minuto'
						},
						{
							xtype: 'numberfield',
							readOnly: true,
							name: 'hsDisponibles',
							itemId: 'hsDisponibles',
							fieldLabel: 'Horas Disponibles:'
						},
						{
							xtype: 'grid',
							title: 'Maquinas',
							itemId: 'gridMaquinas',
							alias: 'widget.gridMaquinas',
							anchorSize: '100%',
							height: 150,	
							autoScroll: true,				
							//store: 'Produccion.Programacion.MaquinasStore',
							multiSelect: true,
							idProperty: 'id',
							columns: [
								{text: 'Id', dataIndex: 'id', hidden: false},
								{text: 'Descripcion', dataIndex: 'descripcion', flex: 1 }
							]
						},
						{
							xtype: 'grid',							
							title: 'Personal',
							itemId: 'gridPersonal',
							alias: 'widget.gridPersonal',
							anchorSize: '100%',
							height: 150,	
							autoScroll: true,				
							//store: 'Personal.Personal',
							multiSelect: true,
							idProperty: 'id',
							columns: [
								{text: 'Id', dataIndex: 'idP', hidden: false},		
								{text: 'Nombre', dataIndex: 'nombre', flex: 1 }
							]
						}
					]
				}
			]
		});
	this.callParent();
	},
});


