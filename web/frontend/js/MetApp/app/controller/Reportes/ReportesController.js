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
		'MetApp.view.Reportes.RepoChequesEntregados',
		'MetApp.view.Reportes.ReporteCCCliente',
		'MetApp.view.Reportes.RepoComisiones',
		'MetApp.view.Reportes.RepoRetenciones',
		'MetApp.view.Reportes.RepoOrdenesDePago',
		'MetApp.view.Reportes.RepoCobranzasEntreFechas',
		'MetApp.view.Reportes.ReporteCCProveedores',
		'MetApp.view.Reportes.RepoSaldoAcreedor',
		'MetApp.view.Reportes.RepoChequesPropiosEntregados',
		'MetApp.view.Reportes.RepoTrazabilidad'
	],
	refs:[
	],
	
	init: function(){
		var me = this;
		me.control({
			'RepoChequesPropiosEntregados button[itemId=printReport]': {
				click: this.PrintChequesPropiosEntregados
			},	
			'viewport menuitem[itemId=reporteChequePropiosEntregados]': {
				click: this.FormChequePropioEntregado
			},
			'viewport menuitem[itemId=reporteResumenSaldoAcreedor]': {
				click: this.FormResumenSaldoAcreedor
			},
			'RepoSaldoAcreedor button[itemId=printReport]': {
				click: this.PrintResumenSaldoAcreedor
			},		
			'#reporteRetenciones': {
				click: this.AddReporteRetenciones
			},
			'RepoRetenciones button[itemId=printReport]': {
				click: this.ImprimirReporteRetenciones
			},
			'#reporteComisiones': {
				click: this.AddReporteComisiones
			},
			'RepoComisiones button[itemId=printDateReport]': {
				click: this.ImprimirComisiones
			},
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
			'ReporteHistoricoMov button[itemId=btnCodigo1]': {
				click: this.BuscarArtDesdeReporteHistMov
			},
			'ReporteHistoricoMov button[itemId=btnCodigo2]': {
				click: this.BuscarArtHastaReporteHistMov
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
			'viewport menuitem[itemId=reporteResumenCCCliente]': {
				click: this.AddResumenCCCliente
			},
			'ReporteCCCliente button[itemId=btnCliente1]': {
				click: this.BuscarClienteCC
			},
			'ReporteCCCliente button[itemId=printDateReport]': {
				click: this.SubmitCCForm
			},
			'viewport menuitem[itemId=reporteOrdenesDePago]': {
				click: this.AddRepoOrdenesDePago
			},
			'RepoOrdenesDePago button[itemId=printReport]': {
				click: this.SubmitOrdenPagoReport
			},
			'viewport menuitem[itemId=reporteCobranzasEntreFechas]': {
				click: this.AddReporteCobranzasEntreFechas
			},
			'RepoCobranzasEntreFechas button[itemId=printReport]': {
				click: this.SubmitCobranzasReport
			},
			'viewport menuitem[itemId=reporteCCProveedores]': {
				click: this.AddReporteCCProveedores
			},
			'ReporteCCProveedores button[itemId=btnProveedor]': {
				click: this.BuscarProveedorCC
			},
			'ReporteCCProveedores button[itemId=printDateReport]': {
				click: this.SubmitCCProveedoresReport
			},
			'viewport menuitem[itemId=trazabilidadRepo]': {
				click: this.AddReporteTrazabilidad
			},
			'RepoTrazabilidad button[itemId=printReport]': {
				click: this.SubmitTrazabilidadReport
			},
		});
	},

	SubmitTrazabilidadReport: function(btn){
		var form=btn.up('form');
		form.getForm().submit({
			url: Routing.generate('mbp_calidad_repoTrazabilidad'),

			success: function(form, atcion){
				var ruta=Routing.generate('mbp_calidad_showRepoTrazabilidad');
				window.open(ruta, '_blank, location=yes,height=800,width=1200,scrollbars=yes,status=yes');
			}
		})
	},

	AddReporteTrazabilidad: function(btn){
		Ext.widget('RepoTrazabilidad');
	},

	PrintChequesPropiosEntregados: function(btn){
		var form=btn.up('form');

		form.getForm().submit({
			url: Routing.generate('mbp_proveedores_reporteChequePropioEntregado'),
			success: function(resp){
				var ruta=Routing.generate('mbp_proveedores_verReporteChequePropioEntregado');
				window.open(ruta, '_blank, location=yes,height=800,width=1200,scrollbars=yes,status=yes');
			}
		})
	},

	FormChequePropioEntregado: function(bnt){
		Ext.widget('RepoChequesPropiosEntregados');
	},

	FormResumenSaldoAcreedor: function(){
		Ext.widget('RepoSaldoAcreedor');
	},

	PrintResumenSaldoAcreedor: function(btn){
		var form=btn.up('form');
		form.submit({
			url: Routing.generate('mbp_proveedores_reporteSaldoAcreedor'),

			success: function(resp){
				var ruta=Routing.generate('mbp_proveedores_verReporteSaldoAcreedor');
				window.open(ruta, '_blank, location=yes,height=800,width=1200,scrollbars=yes,status=yes');
			}
		})
	},

	SubmitCCProveedoresReport: function(btn){
		var form=btn.up('form');

		/*Ext.Ajax.request({
			url: Routing.generate('mbp_proveedores_reporteCC'),
		})*/
		form.getForm().suspendEvent('actionfailed');
		form.submit({
			url: Routing.generate('mbp_proveedores_reporteCC'),
			success: function(){
				var ruta=Routing.generate('mbp_proveedores_verReporteCC');
				window.open(ruta, '_blank, location=yes,height=800,width=1200,scrollbars=yes,status=yes');
			},
			failure: function(){

			}
		});
	},

	BuscarProveedorCC: function(btn){
		var winReporte=btn.up('window');
		var win=Ext.widget('ProveedoresSearchGrid');
		win.down('button').on('click', function(){
			var sel=win.down('grid').getSelectionModel().getSelection()[0];
			winReporte.queryById('proveedor').setValue(sel.data.id);
			win.close();
			winReporte.queryById('printDateReport').focus('', 10);
		});
	},

	AddReporteCCProveedores: function(btn){
		var win=Ext.widget('ReporteCCProveedores');
	},

	AddReporteCobranzasEntreFechas: function(btn){
		var win=Ext.widget('RepoCobranzasEntreFechas');
		win.queryById('desde').focus('', 10);
	},

	SubmitCobranzasReport: function(btn){
		var form=btn.up('form').getForm();

		form.submit({
			url: Routing.generate('mbp_finanzas_CobranzasEntreFechas'),
			waitMsg: 'Cargando...',
			success: function(){
				var ruta=Routing.generate('mbp_finanzas_VerCobranzasEntreFechas');
				window.open(ruta, '_blank, location=yes,height=800,width=1200,scrollbars=yes,status=yes');
			}
		})
	},

	SubmitOrdenPagoReport: function(btn){
		var form=btn.up('form').getForm();

		form.submit({
			url: Routing.generate('mbp_proveedores_OrdenesDePago'),
			waitMsg: 'Cargando...',
			success: function(){
				var ruta=Routing.generate('mbp_proveedores_VerOrdendesDePago');
				window.open(ruta, '_blank, location=yes,height=800,width=1200,scrollbars=yes,status=yes');
			}
		})
	},

	AddRepoOrdenesDePago: function(btn){
		Ext.widget('RepoOrdenesDePago');
	},
	
	ImprimirReporteRetenciones: function(btn){
		var win=btn.up('window');
		var form=win.down('form').getForm();

		form.submit({
			url: Routing.generate('mbp_proveedores_reporteRetencion'),

			success: function(resp){
				var ruta=Routing.generate('mbp_proveedores_verReporteRetencion');
				window.open(ruta, '_blank, location=yes,height=800,width=1200,scrollbars=yes,status=yes');
			}
		})
	},

	AddReporteRetenciones: function(btn){
		Ext.widget('RepoRetenciones');
	},

	ImprimirComisiones: function(btn){
		var win=btn.up('window');
		var form=win.down('form').getForm();

		form.submit({
			url: Routing.generate('mbp_Reportes_generarComisiones'),

			success: function(resp){
				var ruta=Routing.generate('mbp_Reportes_servirReporteComisiones');
				window.open(ruta, '_blank, location=yes,height=800,width=1200,scrollbars=yes,status=yes');
			}
		})
	},

	AddReporteComisiones: function(btn){
		Ext.widget('RepoComisiones');
	},
	
	SubmitCCForm: function(btn){
		var win=btn.up('window');
		var form=win.down('form');
		
		form.submit({
			clientValidation: true,
			url: Routing.generate('mbp_CCClientes_reporteCC'),
			success: function(resp){
				var ruta=Routing.generate('mbp_CCClientes_verCCCliente');
				window.open(ruta, '_blank, location=yes,height=800,width=1200,scrollbars=yes,status=yes');
			}
		});
	},
	
	BuscarClienteCC: function(btn){
		var winReporte=btn.up('window');
		var win=Ext.widget('clientesSearchGrid');
		var grid=win.down('grid');
		grid.getStore().load();
		var btnOk=win.queryById('insertCliente');
		
		btnOk.on('click', function(btn){
			var sel=grid.getSelectionModel().getSelection()[0];
			console.log(sel);
			winReporte.queryById('cliente1').setValue(sel.data.id);
			
			win.close();
		})
		
	},
	
	AddResumenCCCliente: function(btn){
		var win=Ext.widget('ReporteCCCliente');
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
		var win=Ext.widget('ReporteCbteNoPagodos');
		var btn1=win.queryById('btnCliente1');
		var btn2=win.queryById('btnCliente2');
		
		btn1.on('click', function(){
			var viewCliente=Ext.widget('clientesSearchGrid');
			var btnInsert=viewCliente.down('button');
			btnInsert.on('click', function(){
				var sel=viewCliente.down('grid').getSelectionModel().getSelection();
				win.queryById('cliente1').setValue(sel[0].data.id);
				viewCliente.close();
			})
		})
		
		btn2.on('click', function(){
			var viewCliente=Ext.widget('clientesSearchGrid');
			var btnInsert=viewCliente.down('button');
			btnInsert.on('click', function(){
				var sel=viewCliente.down('grid').getSelectionModel().getSelection();
				win.queryById('cliente2').setValue(sel[0].data.id);
				viewCliente.close();
			})
		})
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

	BuscarArtDesdeReporteHistMov: function(btn){
		var win=Ext.widget('winarticulosearch');
		win.queryById('insertArt').on('click', function(){			
			var sel=win.down('grid').getSelectionModel().getSelection()[0];
			var artDesde=btn.up('window').queryById('codigo1');
			artDesde.setValue(sel.data.codigo);
			win.close();
		});
	},

	BuscarArtHastaReporteHistMov: function(btn){
		var win=Ext.widget('winarticulosearch');
		win.queryById('insertArt').on('click', function(){			
			var sel=win.down('grid').getSelectionModel().getSelection()[0];
			var artDesde=btn.up('window').queryById('codigo2');
			artDesde.setValue(sel.data.codigo);
			win.close();
		});
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
		var view = Ext.widget('clientesSearchGrid');
		view.down('grid').getStore().load();
		var viewReportes = btn.up('window');
		var btn2 = view.down('button');
		
		btn2.on('click', function(){			
			var sel = view.down('grid').getSelectionModel().getSelection()[0];
			
			if(btn.itemId == 'btnCliente1'){
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
	
	BuscarArt: function(btnSearch){
		var view = Ext.widget('winarticulosearch');
		var viewReportes = btnSearch.up('window');
		var btn = view.down('button');
		
		btn.on('click', function(){			
			var sel = view.down('grid').getSelectionModel().getSelection()[0];
			
			if(btnSearch.itemId == 'btnCodigo1'){
				viewReportes.queryById('codigo1').setValue(sel.data.codigo);
			}else{
				viewReportes.queryById('codigo2').setValue(sel.data.codigo);
			}
			view.close();
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





































