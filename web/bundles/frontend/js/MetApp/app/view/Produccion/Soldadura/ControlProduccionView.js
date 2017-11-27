Ext.define('MetApp.view.Produccion.Soldadura.ControlProduccionView', {
	extend: 'Ext.window.Window',
	alias: 'widget.ControlProduccionView',
	itemId: 'ControlProduccionView',
	layout: 'border',
	modal: true,
	autoShow: true,
	frame: true,
	width: 920,
	height: 500,
	title: 'Control de producción soldadura',
	
	initComponent: function(){
		var me = this;
		Ext.applyIf(me, {
			items: [
				{
					xtype: 'form',
					region: 'north',					
					border: false,
					layout: 'vbox',
					items: [
						{
							xtype: 'container',
							margins: '5 5 5 5',
							//layout: 'fit',
							items: [
								{
									xtype: 'datefield',
									name: 'fechaCarga',
									fieldLabel: 'Fecha',
									value: new Date(),
									hidden: true
								},
								{
									xtype: 'container',
									layout: 'hbox',
									border: false,
									items: [
										{
											xtype: 'textfield',
											hidden: true,
											name: 'id',
											itemId: 'id'
										},
										{
											xtype: 'textfield',
											hidden: true,
											name: 'idP',
											itemId: 'idSoldador'
										},
										{
											xtype: 'textfield',
											readOnly: 'true',
											disableCls: 'myDisableCls',
											margins: '0 0 5 0',
											fieldLabel: 'Soldador',
											name: 'nombre',
											width: 500
										},
										{
											xtype: 'button',
											iconCls: 'search',
											itemId: 'buscaPersonal'
										},
										{
											xtype: 'datefield',
											validate: function(){
												var me = this;
												if(me.getValue() > new Date){
													me.markInvalid("La fecha no puede ser mayor a hoy");
													return false;
												}else{
													me.removeCls(me.invalidCls);
													return true;
												}
											},
											margins: '0 0 0 5',
											name: 'fecha',
											labelWidth: 50,
											itemId: 'fecha',
											fieldLabel: 'Fecha'
										},
									]
								},								
								{
									xtype: 'container',
									margins: '5 0 0 0',
									border: false,
									layout: 'hbox',
									defaults: {
										allowBlank: false,
									},
									items: [
										
										{
											xtype: 'numberfield',
											margins: '0 5 0 0',
											width: 120,
											labelWidth: 40,
											name: 'ot',
											fieldLabel: 'O.T'
										},
										{
											xtype: 'combo',
											width: 350,
											matchFieldWidth: false,
											labelWidth: 80,
											itemId: 'operaciones',
											name: 'operacionId',
											displayField: 'descripcion',
											valueField: 'operacionId',
											store: 'MetApp.store.Produccion.Programacion.OperacionesStore',
											fieldLabel: 'Operación'	
										},
										{
											xtype: 'timefield',
											minValue: '6:00 AM',
		        							maxValue: '8:00 PM',
											format: 'H:i',
											increment: 1,
											margins: '0 0 0 5',
											width: 150,
											labelWidth: 40,
											name: 'hsInicio',
											itemId: 'hsInicio',
											fieldLabel: 'Inicio',
											validate: function(){
												var me = this;
												var hsFin = me.up('form').queryById('hsFin');
												if(hsFin.getValue() == null || me.getValue() == null) return true;
												if(me.getValue().getTime() > hsFin.getValue().getTime()){
													me.markInvalid("La hora fin no puede ser menor a la de inicio");
													return false;
												}else{
													me.removeCls(me.invalidCls);
													return true;
												}
											},
										},
										{
											xtype: 'timefield',
											minValue: '6:00 AM',
		        							maxValue: '8:00 PM',
											format: 'H:i',
											increment: 1,
											margins: '0 0 0 5',
											width: 150,
											labelWidth: 40,
											name: 'hsFin',
											itemId: 'hsFin',
											fieldLabel: 'Fin',
											validate: function(){
												var me = this;
												var hsInicio = me.up('form').queryById('hsInicio');
												if(hsInicio.getValue() === null || me.getValue() == null) return true;
												if(hsInicio.getValue().getTime() > me.getValue().getTime()){
													me.markInvalid("La hora fin no puede ser menor a la de inicio");
													return false;
												}else{
													me.removeCls(me.invalidCls);
													return true;
												}
											},
										}
									]
								},
								{
									xtype: 'container',
									layout: 'hbox',
									margin: '5 0 0 0',
									items: [
										{
											xtype: 'numberfield',
											name: 'cantidad',
											itemId: 'cantidad',
											labelWidth: 70,
											width: 200,
											fieldLabel: 'Cantidad'
										},
										{
											xtype: 'textarea',											
											width: 500,						
											name: 'observaciones',
											fieldLabel: 'Observaciones'
										},
									]
								},										
								{
									xtype: 'button',
									text: 'Insertar',
									itemId: 'insertar'
								}
							]
						}
									
					]	
				},
				{
					xtype: 'grid',
					store: 'MetApp.store.Produccion.ProduccionSoldadoStore',
					region: 'center',
					columns: [	
						{
							text: 'Fecha',
							dataIndex: 'fecha',
							xtype: 'datecolumn',	
							format: 'd/m/Y',
							width: 80,
						},
						{text: 'idEmp', dataIndex: 'idP', hidden: true},
						{text: 'id', dataIndex: 'id', hidden: true},
						{text: 'Soldador', dataIndex: 'nombre', width: 250},
						{text: 'O.T', dataIndex: 'ot', flex: 1},
						{text: 'Operación', dataIndex: 'operacion', width: 200},
						{text: 'opId', dataIndex: 'operacionId', hidden: true},
						{text: 'Cant', dataIndex: 'cantidad', flex: 1},
						{text: 'Inicio', dataIndex: 'hsInicio', flex: 1},
						{text: 'Fin', dataIndex: 'hsFin', flex: 1},
						{text: 'observaciones', dataIndex: 'observaciones', hidden: true},
						{ 
							header: 'Editar',
							itemId: 'editar',
							xtype: 'actioncolumn',
							items: [
								{ iconCls: 'edit' }										
							],
							flex: 1
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
					],
				}
							
			]
		});
		this.callParent();
	}		
});
