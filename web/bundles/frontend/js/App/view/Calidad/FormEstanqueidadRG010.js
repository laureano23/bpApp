Ext.define('MetApp.view.Calidad.FormEstanqueidadRG010', {
	extend: 'Ext.window.Window',
	alias: 'widget.estanqueidadForm',
	itemId: 'estanqueidadForm',
	height: 550,
	width: 800,
	autoShow: true,
	title: 'Formulario control estanqueidad',
	modal: true,
	widget: 'estanqueidadForm',	
	frame: true,
	layout: 'border',
	
	initComponent: function(){
		var me = this;
		var botonera = Ext.create('MetApp.resources.ux.BtnABMC');
		botonera.queryById('btnNew').setText('Insertar');
		botonera.queryById('btnSave').hide();
		botonera.queryById('btnReset').hide();
		Ext.applyIf(me, {
			items: [
				{
					xtype: 'form',
					itemId: 'formEstanqueidad',
					region: 'north',			
					fieldDefaults: {						
						msgTarget: 'side',
						blankText: 'Debe completar este campo',
						allowBlank: false
					},
					items: [
						{
							xtype: 'container',
							layout: 'vbox',
							margin: '5 0 0 5',
							items: [
								{
									xtype: 'numberfield',
									hidden: true,
									allowBlank: true,
									name: 'id',
									itemId: 'id',
									value: 0
								},
								{
									xtype: 'datefield',
									fieldLabel: 'Fecha',
									name: 'fechaPrueba',
									itemId: 'fecha',
									emptyText: 'dd/mm/yy',       								
									format: 'd/m/Y',
								},
								{
									xtype: 'numberfield',
									name: 'ot',
									itemId: 'ot',
									fieldLabel: 'O.T N°:'
								},
								{
									xtype: 'numberfield',
									name: 'pruebaNum',
									itemId: 'prueba',
									fieldLabel: 'Prueba N°:'
								},
								{
									xtype: 'numberfield',
									name: 'presion',
									itemId: 'presion',
									fieldLabel: 'Presion de prueba'
								},
								{
									xtype: 'radiogroup',
									width: 400,
									fieldLabel: 'Estado',
									columns: 3,
									itemId: 'estado',											
									vertical: true,
									items: [
										{ boxLabel: 'OK', name: 'estado', inputValue: '0', checked: true },
										{ boxLabel: 'NO OK', name: 'estado', inputValue: '1' },
										{ boxLabel: 'Reparado', name: 'estado', inputValue: '2' },										
									],									
									listeners: {
										//Muestra y oculta los motivos de perdida
										change: function(radiogroup, radio){											
											var win = radiogroup.up('window');											
											var fieldSet = win.queryById('motivos');
											if(radio.estado == '0'){												
												fieldSet.hide();
												fieldSet.query('.field').forEach(function(field){
													field.setValue('0');
												});
											}else{
												fieldSet.expand();
												fieldSet.show();	
											}											
										}
									}
								},
								{
									xtype: 'fieldset',
									title: 'Motivos',
									itemId: 'motivos',
									hidden: true,								
									collapsible: true,
									items: [
										{
											xtype: 'checkboxgroup',
											itemId: 'perdidaPanel',
											allowBlank: true,
											width: 800,
											fieldLabel: 'Perdida Panel',
											columns: 6,											
											vertical: true,
											items: [												
												{ boxLabel: 'Perfil', name: 'mPerfil', inputValue: '1', uncheckedValue: '0'}, 
												{ boxLabel: 'Chapa', name: 'mChapa', inputValue: '1', uncheckedValue: '0'}, 
												{ boxLabel: 'Pisos Desp.', name: 'mPisoDesp', inputValue: '1', uncheckedValue: '0' },
												{ boxLabel: 'Tubos', name: 'mBagueta', inputValue: '1', uncheckedValue: '0' },
												{ boxLabel: 'Anulado', name: 'mAnulado', inputValue: '1', uncheckedValue: '0' },
												{ boxLabel: 'Ciba', name: 'mCiba', inputValue: '1', uncheckedValue: '0' },
												{ boxLabel: 'Chapa colectora', name: 'mChapaColectora', inputValue: '1', uncheckedValue: '0' },								
											]
										},
										{
											xtype: 'checkboxgroup',
											allowBlank: true,
											width: 500,
											fieldLabel: 'Perdida Tapas',
											columns: 3,
											itemId: 'perdidaTapas',											
											vertical: true,
											items: [												
												{ boxLabel: 'Poros', name: 'tPoros', inputValue: '1', uncheckedValue: '0' },
												{ boxLabel: 'Rosca', name: 'tRosca', inputValue: '1', uncheckedValue: '0' },
												{ boxLabel: 'Fijacion', name: 'tFijacion', inputValue: '1', uncheckedValue: '0' },																														
											]
										},
										{
											xtype: 'checkboxgroup',
											allowBlank: true,
											width: 700,
											fieldLabel: 'Perdida Sold',
											columns: 3,
											itemId: 'perdidaSoldadura',											
											vertical: true,
											items: [												
												{ boxLabel: 'Tapa a panel', name: 'sTapaPanel', inputValue: '1', uncheckedValue: '0' },
												{ boxLabel: 'Conectores', name: 'sConector', inputValue: '1', uncheckedValue: '0' },
												{ boxLabel: 'Planchuelas', name: 'sPlanchuelas', inputValue: '1', uncheckedValue: '0' },
												{ boxLabel: 'Punteras', name: 'sPuntera', inputValue: '1', uncheckedValue: '0' },																
											]
										},
										{
											xtype: 'combobox',											
											allowBlank: true,
											value: 0,
											store: 'Personal.PersonalStore',
											queryMode: 'local',
											displayField: 'nombre',
											triggerAction: 'query',
											valueField: 'idP',
											typeAhead: true,											
											minChars: 1,
											fieldLabel: 'Soldador',
											width: 600,
											name: 'idSoldador',
											itemId: 'soldador'
										},
									],
								},
								{
									xtype: 'combobox',											
									allowBlank: false,
									store: 'Personal.PersonalStore',
									queryMode: 'local',
									displayField: 'nombre',
									triggerAction: 'query',
									valueField: 'idP',
									typeAhead: true,											
									minChars: 1,
									fieldLabel: 'Probador',
									width: 600,
									name: 'idProbador',
									itemId: 'probador',
									listeners: {
										focus: function(e, v, o){
												var store = e.store.clearFilter();
											}
									}
								},botonera
							]
						},
											
					],										
				},
				{
					xtype: 'grid',
					region: 'center',
					itemId: 'gridEstanqueidad',
					alias: 'widget.gridEstanqueidad',
					plugins: {
				        ptype: 'bufferedrenderer',
				        trailingBufferZone: 20,  // Keep 20 rows rendered in the table behind scroll
				        leadingBufferZone: 50   // Keep 50 rows rendered in the table ahead of scroll
				    },
					anchorSize: '100%',
					height: 200,	
					autoScroll: true,				
					store: 'Calidad.Estanqueidad',
					multiSelect: true,
					idProperty: 'id',
					columns: [
						{text: 'Id', dataIndex: 'id', hidden: false},		
						{
							text: 'Fecha',
							dataIndex: 'fechaPrueba',
							xtype: 'datecolumn',	
							flex: 1,
							format: 'd/m/Y'
						},
						{text: 'O.T', dataIndex: 'ot', flex: 1},
						{text: 'Prueba', dataIndex: 'pruebaNum', flex: 1},
						{
							text: 'Estado',
							dataIndex: 'estado',
							align: 'center',
							renderer: function(value, metaData, record, row, col, store, gridView){
								return this.renderStatus(value)
							},
							flex: 1									
						}
					],
					renderStatus: function(value){
						return value == 1 ? '<span style="color:red;">'+'No ok'+'</span>' : value == 0 ? '<span style="color:green;">'+'Ok'+'</span>' : '<span style="color:black;">'+'Reparado'+'</span>'; 
					}
				}									
			]
		});
		this.callParent();
	}
});


