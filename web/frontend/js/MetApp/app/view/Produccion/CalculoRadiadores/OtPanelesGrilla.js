Ext.define('MetApp.view.Produccion.CalculoRadiadores.OtPanelesGrilla', {
	extend: 'Ext.grid.Panel',
	alias: 'widget.otpanelessearchgrid',
	itemId: 'otPanelesSearchGrid',
	requires: ['Ext.grid.plugin.BufferedRenderer'],
	idProperty: 'id',
	loadMask: true,
	//width: 725,
    height: 500,
    plugins: 'bufferedrenderer',    
    multiSelect: true,
    store: 'Produccion.CalculoRadiadores.Ot',
    
    
    dockedItems: [{
    xtype: 'toolbar',
    dock: 'top',    
    layout: 'hbox',
    items: [
	        {
				xtype: 'textfield',
				value: '',
				labelWidth: 50,
				width:150,			
				fieldLabel: 'OT',			
				name: 'otSearch',
				itemId: 'otSearch',					
				enableKeyEvents : true,
				listeners: {
					keyup: function(field, e){
						ot = field.value;
						codigo = field.up('window').down('grid').queryById('codigoSearch').value;
						descripcion = field.up('window').down('grid').queryById('descSearch').value;
						tapas = field.up('window').down('grid').queryById('tapasSearch').value;
						profundidad = field.up('window').down('grid').queryById('profSearch').value;
						ancho = field.up('window').down('grid').queryById('anchoSearch').value;
						
						store = field.up('grid').getStore();
						store.clearFilter(true);
												
						store.filter([
							{
								property: 'ot',
								value: ot,
								anyMatch: true
							},
							{
								property: 'codigo',
								value: codigo,
								anyMatch: false,
							},
							{
								property: 'descripcion',
								value: descripcion,
								anyMatch: true
							},
							{
								property: 'apoyoTapas',
								value: tapas,
								anyMatch: true
							},
							{
								property: 'prof',
								value: profundidad,
								anyMatch: true
							},
							{
								property: 'ancho',
								value: ancho,
								anyMatch: true
							}
						]);					
					}
				}
			},
			{
				xtype: 'textfield',
				value: '',
				labelWidth: 50,			
				fieldLabel: 'Codigo',			
				name: 'codigoSearch',
				itemId: 'codigoSearch',					
				enableKeyEvents : true,
				listeners: {
					keyup: function(field, e){
						ot = field.up('window').down('grid').queryById('otSearch').value;
						codigo = field.value;
						descripcion = field.up('window').down('grid').queryById('descSearch').value;
						tapas = field.up('window').down('grid').queryById('tapasSearch').value;
						profundidad = field.up('window').down('grid').queryById('profSearch').value;
						ancho = field.up('window').down('grid').queryById('anchoSearch').value;
						
						store = field.up('grid').getStore();
						store.clearFilter(true);
												
						store.filter([
							{
								property: 'ot',
								value: ot,
								anyMatch: true,								
							},
							{
								property: 'codigo',
								value: codigo,
								anyMatch: false,
							},
							{
								property: 'descripcion',
								value: descripcion,
								anyMatch: true
							},
							{
								property: 'apoyoTapas',
								value: tapas,
								anyMatch: true
							},
							{
								property: 'prof',
								value: profundidad,
								anyMatch: true
							},
							{
								property: 'ancho',
								value: ancho,
								anyMatch: true
							}
						]);
					}
				}
			},
			{
				xtype: 'textfield',	
				value: '',
				labelWidth: 70,		
				fieldLabel: 'Descripcion',			
				name: 'descSearch',
				itemId: 'descSearch',					
				enableKeyEvents : true,
				listeners: {
					keyup: function(field, e){
						ot = field.up('window').down('grid').queryById('otSearch').value;
						codigo = field.up('window').down('grid').queryById('codigoSearch').value;
						descripcion = field.value;
						tapas = field.up('window').down('grid').queryById('tapasSearch').value;
						profundidad = field.up('window').down('grid').queryById('profSearch').value;
						ancho = field.up('window').down('grid').queryById('anchoSearch').value;
						
						store = field.up('grid').getStore();
						store.clearFilter(true);
						
						store.filter([
							{
								property: 'ot',
								value: ot,
								anyMatch: true
							},
							{
								property: 'codigo',
								value: codigo,
								anyMatch: false,
							},
							{
								property: 'descripcion',
								value: descripcion,
								anyMatch: true
							},
							{
								property: 'apoyoTapas',
								value: tapas,
								anyMatch: true
							},
							{
								property: 'prof',
								value: profundidad,
								anyMatch: true
							},
							{
								property: 'ancho',
								value: ancho,
								anyMatch: true
							}
						]);
					}
				}
			},
			{
				xtype: 'textfield',	
				value: '',
				labelWidth: 40,	
				width: 100,	
				fieldLabel: 'Tapas',			
				name: 'tapasSearch',
				itemId: 'tapasSearch',					
				enableKeyEvents : true,
				listeners: {
					keyup: function(field, e){
						ot = field.up('window').down('grid').queryById('otSearch').value;
						codigo = field.up('window').down('grid').queryById('codigoSearch').value;
						descripcion = field.up('window').down('grid').queryById('descSearch').value;
						tapas = field.value;
						profundidad = field.up('window').down('grid').queryById('profSearch').value;
						ancho = field.up('window').down('grid').queryById('anchoSearch').value;
						
						store = field.up('grid').getStore();
						store.clearFilter(true);
						
						store.filter([
							{
								property: 'ot',
								value: ot,
								anyMatch: true
							},
							{
								property: 'codigo',
								value: codigo,
								anyMatch: false,
							},
							{
								property: 'descripcion',
								value: descripcion,
								anyMatch: true
							},
							{
								property: 'apoyoTapas',
								value: tapas,
								anyMatch: true
							},
							{
								property: 'prof',
								value: profundidad,
								anyMatch: true
							},
							{
								property: 'ancho',
								value: ancho,
								anyMatch: true
							}
						]);
					}
				}
			},
			{
				xtype: 'textfield',	
				value: '',
				labelWidth: 40,	
				width: 100,	
				fieldLabel: 'Prof.',			
				name: 'profSearch',
				itemId: 'profSearch',					
				enableKeyEvents : true,
				listeners: {
					keyup: function(field, e){
						ot = field.up('window').down('grid').queryById('otSearch').value;
						codigo = field.up('window').down('grid').queryById('codigoSearch').value;
						descripcion = field.up('window').down('grid').queryById('descSearch').value;
						tapas = field.up('window').down('grid').queryById('tapasSearch').value;
						profundidad = field.value;
						ancho = field.up('window').down('grid').queryById('anchoSearch').value;
						
						store = field.up('grid').getStore();
						store.clearFilter(true);
						
						store.filter([
							{
								property: 'ot',
								value: ot,
								anyMatch: true
							},
							{
								property: 'codigo',
								value: codigo,
								anyMatch: false,
							},
							{
								property: 'descripcion',
								value: descripcion,
								anyMatch: true
							},
							{
								property: 'apoyoTapas',
								value: tapas,
								anyMatch: true
							},
							{
								property: 'prof',
								value: profundidad,
								anyMatch: true
							},
							{
								property: 'ancho',
								value: ancho,
								anyMatch: true
							}
						]);
					}
				}
			},
			{
				xtype: 'textfield',	
				value: '',
				labelWidth: 40,	
				width: 100,	
				fieldLabel: 'Ancho',			
				name: 'anchoSearch',
				itemId: 'anchoSearch',					
				enableKeyEvents : true,
				listeners: {
					keyup: function(field, e){
						ot = field.up('window').down('grid').queryById('otSearch').value;
						codigo = field.up('window').down('grid').queryById('codigoSearch').value;
						descripcion = field.up('window').down('grid').queryById('descSearch').value;
						tapas = field.up('window').down('grid').queryById('tapasSearch').value;
						profundidad = field.up('window').down('grid').queryById('profSearch').value;
						ancho = field.value;
						store = field.up('grid').getStore();
						store.clearFilter(true);
						
						store.filter([
							{
								property: 'ot',
								value: ot,
								anyMatch: true
							},
							{
								property: 'codigo',
								value: codigo,
								anyMatch: false,
							},
							{
								property: 'descripcion',
								value: descripcion,
								anyMatch: true
							},
							{
								property: 'apoyoTapas',
								value: tapas,
								anyMatch: true
							},
							{
								property: 'prof',
								value: profundidad,
								anyMatch: true
							},
							{
								property: 'ancho',
								value: ancho,
								anyMatch: true
							}
						]);
					}
				}
			},
			{
				xtype: 'button',
				text: 'Ok',
				itemId: 'insertOt',
				action: 'insertOt'
			}
    	]
	}],
    
    columns: [
		{xtype: 'rownumberer', width: 50, sortable: false},
		{header: 'Id', dataIndex: 'id', flex: 1, hidden: true},
		{header: 'OT', dataIndex: 'ot', width: 60 },
		{header: 'idCodigo', dataIndex: 'idCodigo.id', hidden: true, flex: 1},
		{header: 'idCodigo', dataIndex: 'idCodigo', hidden: true },
		{header: 'Codigo', dataIndex: 'codigo', width: 170 },
		{header: 'Descripcion', dataIndex: 'descripcion', width: 350 },
		{header: 'Cliente', dataIndex: 'rsocial', flex: 1},
		{header: 'Cantidad', dataIndex: 'cantidad', width: 70 },
		{header: 'Tapas', dataIndex: 'apoyoTapas', width: 70 },
		{header: 'Prof.', dataIndex: 'prof', width: 70 },
		{header: 'Ancho', dataIndex: 'ancho', width: 70 }
	],
});
