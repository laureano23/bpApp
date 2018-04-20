Ext.define('MetApp.controller.Reportes.ReportesController',{
	extend: 'Ext.app.Controller',
	stores: [
	
	],
	views: [
		'MetApp.view.Reportes.RepoIVAVentas',
		'MetApp.view.Reportes.RepoIVACompras',
		'MetApp.view.Reportes.ReporteArtVendidos',
		'MetApp.view.Reportes.ReporteIntResarcitorios',
		'MetApp.view.Reportes.ReporteHistoricoMov',
		'MetApp.view.Reportes.RepoSaldoDeudor',
		'MetApp.view.Reportes.ReporteCbteNoPagodos',
		'MetApp.view.Reportes.RepoChequeTerceros',
		'MetApp.view.Reportes.RepoChequesEntregados'
	],
	refs:[
	],
	
	init: function(){
		var me = this;
		me.control({
			'#reporteIVAVentas': {
				click: this.AddReporteIVAVentas
			},
			'RepoIVAVentas button[itemId=printDateReport]': {
				click: this.ImprimirIvaVentas
			},
			'#reporteIVACompras': {
				click: this.AddReporteIVACompras
			},
			'RepoIVACompras button[itemId=printReport]': {
				click: this.ImprimirIvaCompras
			},
			'viewport menuitem[itemId=reporteArtVendidos]': {
				click: this.AddReportesArtVendidos
			},
			'ReporteArtVendidos button[itemId=btnCodigo1]': {
				click: this.BuscarArt
			},
			'ReporteArtVendidos button[itemId=btnCodigo2]': {
				click: this.BuscarArt
			},
			'ReporteArtVendidos button[itemId=btnCliente1]': {
				click: this.ArtVendidosCliente
			},
			'ReporteArtVendidos button[itemId=btnCliente2]': {
				click: this.ArtVendidosCliente
			},
			'ReporteArtVendidos button[itemId=printDateReport]': {
				click: this.ImprimirArtVendidos
			},
			'viewport menuitem[itemId=reporteIntResarcitorios]': {
				click: this.AddReportesIntResarcitorios
			},
			'ReporteIntResarcitorios button[itemId=printDateReport]': {
				click: this.ImprimirArtVendidos
			},
			'ReporteIntResarcitorios button[itemId=btnCliente1]': {
				click: this.ArtVendidosCliente
			},
			'ReporteIntResarcitorios button[itemId=btnCliente2]': {
				click: this.ArtVendidosCliente
			},
			'ReporteIntResarcitorios button[itemId=printDateReport]': {
				click: this.PrintInteresesRes
			},
			'viewport menuitem[itemId=reporteHistMov]': {
				click: this.AddFormHistoricoMov
			},
			'ReporteHistoricoMov button[itemId=printDateReport]': {
				click: this.PrintHistoricoMovReporte
			},
			'viewport menuitem[itemId=rg014]': {
				click: this.ReporteRG014
			},
			'viewport menuitem[itemId=reporteSaldoDeudor]': {
				click: this.reporteSaldoDeudor
			},
			'RepoSaldoDeudor button[itemId=printDateReport]': {
				click: this.imprimirSaldoDeudor
			},
			'viewport menuitem[itemId=reporteCbteNoPagado]': {
				click: this.AddReporteCbteNoPagado
			},
			'ReporteCbteNoPagodos button[itemId=printDateReport]': {
				click: this.ImprimirCbteNoPagados
			},
			'viewport menuitem[itemId=reporteChequeTerceros]': {
				click: this.AddReporteChequeTerceros
			},
			'RepoChequeTerceros button[itemId=printReport]': {
				click: this.ImprimirRepoChequeTerceros
			},
			'viewport menuitem[itemId=reporteChequeTercerosEntregados]': {
				click: this.AddChequeTercerosEntregados
			},
			'RepoChequesEntregados button[itemId=printReport]': {
				click: this.ImprimirChequeTercerosEntregados
			},
		});
	},
	
	AddChequeTercerosEntregados: function(btn){
		Ext.widget('RepoChequesEntregados');
	},
	
	ImprimirChequeTercerosEntregados: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var myMask = new Ext.LoadMask(form, {msg:"Cargando..."});
		myMask.show();
		
		if(form.isValid()){
			var values = form.getForm().getValues();
			
			Ext.Ajax.request({
				url: Routing.generate('mbp_proveedores_ChequesTercerosEntregados'),
				
				params: {
					desde: values.desde,
					hasta: values.hasta,
				},
				
				success: function(resp){					
					var jsonResp = Ext.JSON.decode(resp.responseText);
					if(jsonResp.success == true){
						var ruta = Routing.generate('mbp_proveedores_VerChequesTercerosEntregados');						
						window.open(ruta, 'location=yes,height=800,width=1200,scrollbars=yes,status=yes');
					}
					myMask.hide();
				},
				
				failure: function(resp){
					myMask.hide();
				}
			});	 
		}
	},
	
	ImprimirRepoChequeTerceros: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var myMask = new Ext.LoadMask(form, {msg:"Cargando..."});
		myMask.show();
		
		if(form.isValid()){
			var values = form.getForm().getValues();
			
			Ext.Ajax.request({
				url: Routing.generate('mbp_Reportes_InventarioCheques'),
				
				params: {
					desde: values.desde,
					hasta: values.hasta,
				},
				
				success: function(resp){					
					var jsonResp = Ext.JSON.decode(resp.responseText);
					if(jsonResp.success == true){
						var ruta = Routing.generate('mbp_Reportes_VerInventarioCheques');						
						window.open(ruta, 'location=yes,height=800,width=1200,scrollbars=yes,status=yes');
					}
					myMask.hide();
				},
				
				failure: function(resp){
					myMask.hide();
				}
			});	 
		}
	},
	
	AddReporteChequeTerceros: function(btn){
		Ext.widget('RepoChequeTerceros');
	},
	
	AddReporteCbteNoPagado: function(btn){
		Ext.widget('ReporteCbteNoPagodos');
	},
	
	ImprimirCbteNoPagados: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var myMask = new Ext.LoadMask(form, {msg:"Cargando..."});
		myMask.show();
		
		if(form.isValid()){
			var values = form.getForm().getValues();
			
			Ext.Ajax.request({
				url: Routing.generate('mbp_Reportes_CbtesNoPagados'),
				
				params: {
					desde: values.desde,
					hasta: values.hasta,
					cliente1: values.cliente1,
					cliente2: values.cliente2
				},
				
				success: function(resp){					
					var jsonResp = Ext.JSON.decode(resp.responseText);
					if(jsonResp.success == true){
						var ruta = Routing.generate('mbp_Reportes_VerCbptesNoPagados');						
						window.open(ruta, 'location=yes,height=800,width=1200,scrollbars=yes,status=yes');
					}
					myMask.hide();
				},
				
				failure: function(resp){
					myMask.hide();
				}
			});	
		}
	},
	
	imprimirSaldoDeudor: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var myMask = new Ext.LoadMask(win, {msg:"Cargando..."});
		myMask.show();
		
		if(form.isValid()){
			var values = form.getForm().getValues();
			
			Ext.Ajax.request({
				url: Routing.generate('mbp_Reportes_SaldoDeudor'),
				
				params: {
					vencimiento: values.vencimiento
				},
				
				success: function(resp){					
					var jsonResp = Ext.JSON.decode(resp.responseText);
					if(jsonResp.success == true){
						var ruta = Routing.generate('mbp_Reportes_VerReporteSaldoDeudor');						
						window.open(ruta, 'location=yes,height=800,width=1200,scrollbars=yes,status=yes');
					}
					myMask.hide();
				},
				
				failure: function(resp){
					myMask.hide();
				}
			});	
		}
	},
	
	reporteSaldoDeudor: function(btn){
		Ext.widget('RepoSaldoDeudor');
	},
	
	ReporteRG014: function(btn){
		Ext.Ajax.request({
			url: Routing.generate('mbp_calidad_generateRepoRG014'),
			
			success: function(resp){
				var ruta = Routing.generate('mbp_calidad_showRepoRG014');
						
				window.open(ruta, 'location=yes,height=800,width=1200,scrollbars=yes,status=yes');
			}
		})
	},
	
	PrintHistoricoMovReporte: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var myMask = new Ext.LoadMask(win, {msg:"Cargando..."});
		myMask.show();
		
		if(form.isValid()){
			var values = form.getForm().getValues();
			
			Ext.Ajax.request({
				url: Routing.generate('mbp_reportes_historicoMov'),
				
				params: {
					desde: values.desde,
					hasta: values.hasta,
					codigo1: values.codigo1,
					codigo2: values.codigo2
				},
				
				success: function(resp){					
					var jsonResp = Ext.JSON.decode(resp.responseText);
					if(jsonResp.success == true){
						var ruta = Routing.generate('mbp_reportes_historicoMov_PDF');
						
						window.open(ruta, 'location=yes,height=800,width=1200,scrollbars=yes,status=yes');
					}
					myMask.hide();
				},
				
				failure: function(resp){
					myMask.hide();
				}
			});	
		}
	},
	
	AddFormHistoricoMov: function(btn){
		Ext.widget('ReporteHistoricoMov');
	},
	
	PrintInteresesRes: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		
		var myMask = new Ext.LoadMask(win, {msg:"Cargando..."});
		
		if(form.isValid()){
			myMask.show();
			var values = form.getForm().getValues();
			Ext.Ajax.request({
				url: Routing.generate('mbp_Reportes_InteresesResarcitorios'),
				
				params: {
					cliente1: values.cliente1,
					cliente2: values.cliente2,
					desde: values.desde,
					hasta: values.hasta,
				},
				
				success: function(resp){
					var jsonResp = Ext.JSON.decode(resp.responseText);
					if(jsonResp.success == true){
						var ruta = Routing.generate('mbp_Reportes_VerReporteIntResarcitorios');
						
						window.open(ruta, 'location=yes,height=800,width=1200,scrollbars=yes,status=yes');
					}
					myMask.hide();
				},
				
				failure: function(resp){
					myMask.hide();
				}
			});
		}
	},
	
	AddReportesIntResarcitorios: function(btn){
		var view = Ext.widget('ReporteIntResarcitorios');
	},
	
	ArtVendidosCliente: function(btn){
		console.log(btn);
		var view = Ext.widget('clientesSearchGrid');
		view.down('grid').getStore().load();
		var viewReportes = btn.up('window');
		var btn2 = view.down('button');
		
		btn2.on('click', function(){			
			var sel = view.down('grid').getSelectionModel().getSelection()[0];
			
			if(btn.itemId == 'btnCliente1'){
				console.log("Es el boton 1");
				viewReportes.queryById('cliente1').setValue(sel.data.id);
			}else{
				viewReportes.queryById('cliente2').setValue(sel.data.id);
			}
			view.close();
		});
		
		
	},
	
	ImprimirArtVendidos: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		
		var myMask = new Ext.LoadMask(win, {msg:"Cargando..."});
		
		if(form.isValid()){
			myMask.show();
			var values = form.getForm().getValues();
			Ext.Ajax.request({
				url: Routing.generate('mbp_Reportes_ArtVendidos'),
				
				params: {
					codigo1: values.codigo1,
					codigo2: values.codigo2,
					cliente1: values.cliente1,
					cliente2: values.cliente2,
					desde: values.desde,
					hasta: values.hasta,
				},
				
				success: function(resp){
					var jsonResp = Ext.JSON.decode(resp.responseText);
					if(jsonResp.success == true){
						var ruta = Routing.generate('mbp_Reportes_VerArtVendidos');
						
						window.open(ruta, 'location=yes,height=800,width=1200,scrollbars=yes,status=yes');
					}
					myMask.hide();
				},
				
				failure: function(resp){
					myMask.hide();
				}
			});
		}
	},
	
	BuscarArt: function(btn){
		var view = Ext.widget('winarticulosearch');
		var viewReportes = btn.up('window');
		var btn = view.down('button');
		
		btn.on('click', function(){			
			var sel = view.down('grid').getSelectionModel().getSelection()[0];
			console.log(sel);
			
			if(btn.itemId == 'btnCodigo1'){
				viewReportes.queryById('codigo1').setValue(sel.data.codigo);
			}else{
				viewReportes.queryById('codigo2').setValue(sel.data.codigo);
			}
		});
	},
	
	AddReportesArtVendidos: function(btn){
		Ext.widget('ReporteArtVendidos');
	},
	
	AddReporteIVAVentas: function(btn){
		Ext.widget('RepoIVAVentas');
	},
	
	ImprimirIvaVentas: function(btn){
		var win = btn.up('window');
		var form = btn.up('form');
		var values = form.getValues();
		var myMask = new Ext.LoadMask(win, {msg:"Cargando..."});
				
		if(form.isValid()){
			myMask.show();
			Ext.Ajax.request({
				url: Routing.generate('mbp_CCClientes_LibroIVAVentas'),
				
				params: {
					desde: values.desde,
					hasta: values.hasta
				},
				
				success: function(resp){
					var jsonResp = Ext.JSON.decode(resp.responseText);
					if(jsonResp.success == true){
						var ruta = Routing.generate('mbp_CCClientes_VerLibroIVAVentas');
						
						window.open(ruta, 'location=yes,height=800,width=1200,scrollbars=yes,status=yes');
					}
					myMask.hide();
				},
				
				failure: function(resp){
					myMask.hide();
				}
			});
		}
	},
	
	AddReporteIVACompras: function(btn){
		Ext.widget('RepoIVACompras');
	},
	
	ImprimirIvaCompras: function(btn){
		var win = btn.up('window');
		var form = btn.up('form');
		var values = form.getValues();
		var myMask = new Ext.LoadMask(win, {msg:"Cargando..."});
				
		if(form.isValid()){
			myMask.show();
			Ext.Ajax.request({
				url: Routing.generate('mbp_proveedores_ReporteLibroIVACompras'),
				
				params: {
					desde: values.desde,
					hasta: values.hasta
				},
				
				success: function(resp){
					var jsonResp = Ext.JSON.decode(resp.responseText);
					if(jsonResp.success == true){
						var ruta = Routing.generate('mbp_proveedores_VerReporteLibroIVACompras');
						
						window.open(ruta, 'location=yes,height=800,width=1200,scrollbars=yes,status=yes');
					}
					myMask.hide();
				},
				
				failure: function(resp){
					myMask.hide();
				}
			});
		}
	}
})





































