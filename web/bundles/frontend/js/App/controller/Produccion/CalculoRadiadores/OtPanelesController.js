Ext.define('MetApp.controller.Produccion.CalculoRadiadores.OtPanelesController',{
	extend: 'Ext.app.Controller',
	
	views: [
		'Produccion.CalculoRadiadores.OtPaneles',
		'Produccion.CalculoRadiadores.OtPanelesSearch'
	],
	
	refs: [{
	    ref: 'OtPaneles',
	    selector: 'otpaneles' // your xtype
	}],
	
	stores: [
		'Produccion.CalculoRadiadores.Ot'
	],
	
	//stores: ['Produccion.CalculoRadiadores.Calculo'],
	
	init: function(){
		var me = this;
		me.control({
			'#otPaneles': {
				click: this.AddWin 
			},
			'otpaneles button[action=buscaOt]': {
				click: this.BuscaOt
			},
			'otpaneles textfield[itemId=ot]': {
				blur: this.ValidaOt
			},
			'otpaneles button[action=btnNew]': {
				click: this.NewOt
			},
			'otpaneles button[action=btnReporte]': {
				click: this.ReporteCalculo
			},
			'otpaneles button[action=buscaPanel]': {
				click: this.BuscaArticulo
			},
			'otpaneles button[action=buscaCliente]': {
				click: this.BuscaCliente
			},
			'otpaneles button[action=btnSave]': {
				click: this.SaveOt
			},
			'otpaneles button[action=btnReporteArmadoPanel]': {
				click: this.ReporteArmadoPanel
			},
			'otpaneles button[action=btnReset]': {
				click: this.ResetForm
			}		
		});
	},
	
	/*
	 * Insertamos la ventana
	 */
	AddWin: function(btn){
		Ext.widget('otpaneles');
	},
	
	NewOt: function(btn){		
		win = btn.up('window');
		win.down('form').getForm().reset();
		win.queryById('btnSave').setDisabled(false);		
		win.queryById('btnReset').setDisabled(false);
		win.queryById('ot').setDisabled(false);
		win.queryById('buscaPanel').setDisabled(false);
		win.queryById('buscaOt').setDisabled(true);
		win.queryById('ot').focus();
		
		win.queryById('btnReporte').setDisabled(true);
		win.queryById('btnArmado').setDisabled(true);
	},
	
	//BUSCA OT EN LA BD Y TRAE LOS DATOS
	BuscaOt: function(btn){
		var me = this;
		win = Ext.widget('otpanelessearch');
		grid = win.down('grid');		
		
		btn = grid.queryById('insertOt');
		store = this.getStore('Produccion.CalculoRadiadores.Ot');
		store.load();
		
		btn.on({
			click: function(){
				view = me.getOtPaneles();
				form = view.queryById('otPanelesForm');
				selection = grid.getSelectionModel().getSelection()[0].data;
				console.log(selection);
				form.queryById('idCodigo').setValue(selection['idCodigo']);
				form.queryById('ot').setValue(selection.ot);
				form.queryById('codigo').setValue(selection['codigo']);
				form.queryById('idCodigo').setValue(selection['idCodigo']);
				form.queryById('cliente').setValue(selection['rsocial']);
				form.queryById('cantidad').setValue(selection.cantidad);
				
				form.queryById('btnArmado').setDisabled(false);
				form.queryById('btnReporte').setDisabled(false);
				
				win.close();
			}
		});
		
	},
	
	ValidaOt: function(btn){
		win = btn.up('window');
		ot = win.queryById('ot');
		
		Ext.Ajax.request({
				url: Routing.generate('mbp_produccion_verificaot'),
				writer: {
					type: 'json',
				},
				params: {
					ot: ot.value
				},
				success: function(response, opt){
					jsonResp = Ext.JSON.decode(response.responseText);
					if(jsonResp.success === false){
						Ext.MessageBox.show({
							title: 'ERROR',
							msg: jsonResp.msg,
							icon: Ext.MessageBox.ERROR,
							buttons: Ext.Msg.OK
						});
						win.queryById('codigo').setDisabled(true);						
					}else{
						win.queryById('codigo').setDisabled(false);
					}
				}
			});
	},
		
	BuscaArticulo: function(btn){
		var me = this;
		var searchForm = Ext.widget('winarticulosearch');
		searchForm.down('grid').getStore().clearFilter(true);
		var button = Ext.ComponentQuery.query('#insertArt')[0];
		var form = Ext.ComponentQuery.query('#otWinPaneles')[0].down('form');
		var idCodigo = form.queryById('idCodigo');
		var ot = form.queryById('ot');
		/*
		 * Escuchamos al boton de la ventana de busqueda
		 */
		button.on('click',function(){
			
			var grid = button.up('grid');
			var selection = grid.getSelectionModel().getSelection()[0];
			idCodigo.setValue(selection.data.id);
			codigo = form.queryById('codigo').setValue(selection.data.codigo);
									
			searchForm.close();
		});
		button.on('click', function(){
			Ext.Ajax.request({
				url: Routing.generate('mbp_produccion_verificapanelcalculado'),
				writer: {
					type: 'json',
				},
				params: {
					idCodigo: idCodigo.value
				},
				success: function(response, opt){
					jsonResp = Ext.JSON.decode(response.responseText);
					if(jsonResp.success === false){
						Ext.MessageBox.show({
							title: 'ERROR',
							msg: jsonResp.msg,
							icon: Ext.MessageBox.ERROR,
							buttons: Ext.Msg.OK
						});
						codigo.reset();
						idCodigo.reset();
					}else{			
						form.queryById('buscaCliente').setDisabled(false);
						form.queryById('buscaCliente').focus();
					}
				}
			});
		});
	},
	
	BuscaCliente: function(btn){
		win = Ext.widget('clientesSearchGrid'); //VENTANA DE BUSQUEDA DE CLIENTES
		form = btn.up('form'); //FORMULARIO DE OT
		store = win.down('grid').getStore();
		cantField = form.queryById('cantidad');		 
		
		store.clearFilter(true);
		var input = win.down('textfield');
		input.focus('', 10); // Le damos un tiempo al metodo focus para que el form termine de renderizar
		var button = win.down('button');
		
		button.on('click', function(){
			var grid = button.up('grid');
			var selection = grid.getSelectionModel().getSelection()[0];	
			var cliente = form.queryById('cliente');
			var idCliente = form.queryById('idCliente');		
		
			cliente.setValue(selection.data.rsocial);
			idCliente.setValue(selection.data.id); 
			win.close();
			cantField.setDisabled(false);
			cantField.focus();
		});
	},
	
	ReporteCalculo: function(btn){
		var form = btn.up('form');	
		idCodigo = form.queryById('idCodigo').value;
		ot = form.queryById('ot').value;
		cantidad = form.queryById('cantidad').value;
		cliente = form.queryById('cliente').value;
		
		Ext.Ajax.request({
			url: Routing.generate('mbp_produccion_reportecalculo'),
			writer: {
				type: 'json',
				root: 'data',
				encode: 'true'
			},
			params: {
				ot: ot,
				cantidad: cantidad,
				codigo: idCodigo,
				cliente: cliente				
			},
			success: function(response,options){
				var jsonReporte = Ext.JSON.decode(response.responseText);
				Reporte=jsonReporte.reporte;
				
				var ruta = Routing.generate('mbp_produccion_reportecalculopdf');
			
				var WinReporte=Ext.create('Ext.Window', {
										  title: 'Reporte Calculo de panel',
										  width: 900,
										  height: 700,
										  layout: 'fit',
										  modal:true,										
										  html: '<iframe src='+ruta+' width="100%" height="100%"></iframe>'										
									  });
									  WinReporte.show();
			}
		});	
	},
	
	ReporteArmadoPanel: function(btn){
		var form = btn.up('form');	
		idCodigo = form.queryById('idCodigo').value;
		ot = form.queryById('ot').value;
		cantidad = form.queryById('cantidad').value;
		cliente = form.queryById('cliente').value;
		var values = form.getValues();
		
		Ext.Ajax.request({
			url: Routing.generate('mbp_produccion_reporteArmadoPanel'),
			params: {
				ot: ot,
				cantidad: cantidad,
				codigo: idCodigo,
				cliente: cliente
			},			
			timeout: 60000,						
			success: function(resp, opt){
				var jsonReporte = Ext.JSON.decode(resp.responseText);
				Reporte=jsonReporte.reporte;
				
				var ruta = Routing.generate('mbp_produccion_reporteArmadoPanelPDF');
								
				var WinReporte=Ext.create('Ext.Window', {
										  title: 'Reporte Calculo de panel',
										  width: 900,
										  height: 700,
										  layout: 'fit',
										  modal:true,										
										  html: '<iframe src='+ruta+' width="100%" height="100%"></iframe>'						  
									  });
									  WinReporte.show();
			}
		});
	},
	
	SaveOt: function(btn){
		var win = btn.up('window');
		var form = btn.up('form');
		values = form.getForm().getValues();
		var store = this.getStore('Produccion.CalculoRadiadores.Ot');
		console.log(win);	
		idCodigo = win.queryById('idCodigo').value;
		idCliente = win.queryById('idCliente').value;
		
		if(form.isValid() === true){
			var model = Ext.create('MetApp.model.Produccion.CalculoRadiadores.OtModel');
			model.set('cantidad', values.cantidad);
			model.set('idCodigo', idCodigo);
			model.set('idCliente', idCliente);
			model.set('id', 0);
			model.set('ot', values.ot);
			store.add(model);
			
			form.query('.button').forEach(function(btn){btn.setDisabled(false)});
			form.query('.textfield').forEach(function(btn){btn.setDisabled(true)});
			form.queryById('btnSave').setDisabled(true);
			form.queryById('buscaPanel').setDisabled(true);
		}
	},
	
	ResetForm: function(btn){
		form = btn.up('form');
		form.query('.button').forEach(function(btn){btn.setDisabled(true)});
		form.query('.textfield').forEach(function(btn){btn.setDisabled(true)});
		
		form.getForm().reset();
		form.queryById('btnNew').setDisabled(false);
		form.queryById('buscaOt').setDisabled(false);
		form.queryById('btnReset').setDisabled(false);
	}
});










