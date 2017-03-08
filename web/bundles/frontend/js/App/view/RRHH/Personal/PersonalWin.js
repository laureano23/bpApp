Ext.define('MetApp.view.RRHH.Personal.PersonalWin',{
	extend: 'Ext.window.Window',
	alias: 'widget.personalWin',
	itemId: 'personalWin',
	title: 'Tabla de Personal',
	height: 550,
	width: 900,
	modal: true,
	autoShow: true,	
	layout: 'border',
	
	initComponent: function(){
		var me = this;
				
		var btnDatosPersonales = Ext.create('MetApp.resources.ux.BtnABMC');
		btnDatosPersonales.queryById('btnEdit').setDisabled(true);
		btnDatosPersonales.queryById('btnDelete').setDisabled(true);
		
		var btnDatosFijos = Ext.create('MetApp.resources.ux.BtnABMC');
		btnDatosFijos.queryById('btnEdit').hide();
		btnDatosFijos.queryById('btnReset').hide();
		btnDatosFijos.queryById('btnDelete').setDisabled(true);
		
		var btnOtrosDatos = Ext.create('MetApp.resources.ux.BtnABMC');
		btnOtrosDatos.queryById('btnNew').hide();
		btnOtrosDatos.queryById('btnReset').hide();
		btnOtrosDatos.queryById('btnDelete').hide();
		
		Ext.applyIf(me,{
			items: [
				{
					xtype: 'tabpanel',
					region: 'center',
					tabBarHeaderPosition: 1,
					layout: 'fit',
	                tabBar: {
	                    flex: 1
	                },
					items:[
						{
							title: 'Datos personales',
							iconCls: 'contact',
							items: [
								{
									xtype: 'personalTabla'
								},									
								{
									xtype: 'container',
									border: false,
									itemId: 'btnDatosPersonales',
									padding: '0 5 0 5',
									region: 'south',
									layout: 'vbox',		
									items: [btnDatosPersonales]
								}		
							]
						},
						{
							title: 'Datos Fijos',
							iconCls: 'infoAct',
							items: [
								{
									xtype: 'personalDatosFijos',
									border: false,
								},
								{
									xtype: 'container',
									border: false,
									itemId: 'btnDatosFijos',
									defaults: {
										margins: '0 0 0 5',	
									},									
									layout: 'vbox',		
									items: [btnDatosFijos]
								}		
							]
						},
						{
							title: 'Otros datos',
							iconCls: 'infoAct',
							items: [
								{
									xtype: 'TabOtrosDatos',
									border: false,
								},
								{
									xtype: 'container',
									border: false,
									itemId: 'btnOtrosDatos',
									//padding: '0 5 0 5',
									layout: 'vbox',		
									items: [btnOtrosDatos]
								}		
							]
						},
						{
							title: 'Contribuciones',
							iconCls: 'costos',
							html: 'hola'
						}										
					] 						
				},
				
			]
		});
		this.callParent();
	}
});