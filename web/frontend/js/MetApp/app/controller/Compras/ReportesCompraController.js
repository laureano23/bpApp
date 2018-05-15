Ext.define('MetApp.controller.Compras.ReportesCompraController',{
	extend: 'Ext.app.Controller',	
	views: [
		'MetApp.view.Compras.ReporteArtComprados'
	],
	
	stores: [
	],
	
	refs:[
	],
	
	init: function(){
		var me = this;
		me.control({
			'viewport menuitem[itemId=repoArticulosComprados]': {
				click: this.RepoArticulosComprados
			},	
			'ReporteArtComprados button[itemId=btnCodigo1]': {
				click: this.BuscarArt
			},
			'ReporteArtComprados button[itemId=btnCodigo2]': {
				click: this.BuscarArt
			},
			'ReporteArtComprados button[itemId=btnProveedor1]': {
				click: this.ArtVendidosCliente
			},
			'ReporteArtComprados button[itemId=btnProveedor2]': {
				click: this.ArtVendidosCliente
			},
			'ReporteArtComprados button[itemId=printReport]': {
				click: this.ImprimirArtComprados
			},
		});
	},
	
	RepoArticulosComprados: function(btn){
		Ext.widget('ReporteArtComprados');
	},
	
	BuscarArt: function(btnSearch){
		var view = Ext.widget('winarticulosearch');
		var viewReportes = btnSearch.up('window');
		var btn = view.down('button');
		
		btn.on('click', function(){		
			console.log(btnSearch);	
			var sel = view.down('grid').getSelectionModel().getSelection()[0];
			
			if(btnSearch.itemId == 'btnCodigo1'){
				viewReportes.queryById('codigo1').setValue(sel.data.codigo);
			}else{
				viewReportes.queryById('codigo2').setValue(sel.data.codigo);
			}			
			view.close();
		});
	},
	
	ArtVendidosCliente: function(btn){
		var view = Ext.widget('clientesSearchGrid');		
		var viewReportes = btn.up('window');
		var btn2 = view.down('button');
		
		btn2.on('click', function(){			
			var sel = view.down('grid').getSelectionModel().getSelection()[0];
			
			if(btn.itemId == 'btnProveedor1'){
				viewReportes.queryById('proveedor1').setValue(sel.data.id);
			}else{
				viewReportes.queryById('proveedor2').setValue(sel.data.id);
			}
			view.close();
		});
	},
	
	ImprimirArtComprados: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		
		form.getForm().submit({
			clientValidation: true,
			url: Routing.generate('mbp_compras_articulosComprados'),
			success: function(resp){
				var ruta = Routing.generate('mbp_personal_verArticulosComprados');
						
				window.open(ruta, 'location=yes,height=800,width=1200,scrollbars=yes,status=yes');
			},
			failure: function(resp){
				
			}
		})
		
		/*if(form.isValid()){
		
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
		}*/
	},
});










