Ext.define('MetApp.controller.Calidad.CalidadReportesController',{
	extend: 'Ext.app.Controller',
	views: [
		'Calidad.Reportes.RepoRG010-1',
		'MetApp.view.Calidad.Reportes.RepoEstanqueidad3D',
		'MetApp.view.Calidad.Reportes.RepoAcumuladoOpe'	
		],
	stores: ['Calidad.Estanqueidad'],
	
	init: function(){
		var me = this;
		me.control({
			'#repo1RG010': {
				click: this.NewRepo1RG010
			},
			'repo1RG010 radiofield[name=tipo]': {
				change: this.TipoReporteRG010
			},
			'repo1RG010 button[action=printReport]': {
				click: this.PrintRepoRG010
			},
			'repo1RG010 button[action=printDateReport]': {
				click: this.PrintDateRepoRG010
			},
			'#RG010FallasSoldadura': {
				click: this.NewRepoFallasSoldadura
			},
			'RepoFallasSoldadura button[action=printDateReport]': {
				click: this.PrintReportFallasSoldadura
			},
			'#repo2RG010': {
				click: this.NewRepoEstanqueidad3D
			},
			'RepoEstanqueidad3D button[action=printBtn]': {
				click: this.PrintRepoEstanqueidad3D
			},
			'#acumuladoOpe': {
				click: this.NewRepoAcumuladoOpe
			},
			'RepoAcumuladoOpe button[action=printDateReport]': {
				click: this.PrintRepoAcumuladoOpe
			},			
		});
	},
	
	NewRepo1RG010: function(){
		Ext.widget('repo1RG010');		
	},
	
	TipoReporteRG010: function(radio){
		otForm = radio.up('window').queryById('otForm');
		fechaForm = radio.up('window').queryById('fechaForm');
		
		if(radio.inputValue == 'fecha' && radio.checked == true){
			//DESABILITO Y COLAPSO EL PANEL DE OT
			otForm.setDisabled(true);
			otForm.collapse(true);
			//HABILITO Y EXPANDO EL PANEL DE REPORTE POR FECHAS
			fechaForm.setDisabled(false);
			fechaForm.expand(true);
		}else{
			if(radio.inputValue == 'ot' && radio.checked == true){
				//DESABILITO Y COLAPSO EL PANEL DE FECHAS
				fechaForm.setDisabled(true);
				fechaForm.collapse(true);
				//HABILITO Y EXPANDO EL PANEL DE REPORTE POR OT
				otForm.setDisabled(false);
				otForm.expand(true);
			}			
		}
	},
	
	PrintRepoRG010: function(btn){
		form = btn.up('form');
		ot = form.queryById('ot').getValue();
		console.log(ot);
		
		if(form.isValid() == true){
			Ext.Ajax.request({
				url: Routing.generate('mbp_calidad_generateReporteRg010'),
				
				params: {
					ot: ot
				},
				
				success: function(resp, opt){
				var jsonReporte = Ext.JSON.decode(resp.responseText);
				Reporte=jsonReporte.reporte;
				
				var ruta = Routing.generate('mbp_calidad_showReporteRg010');
								
				window.open(ruta, 'location=yes,height=800,width=1200,scrollbars=yes,status=yes');
				}	
				
			});
		}		
	},
	
	PrintDateRepoRG010: function(btn){
		var win = btn.up('window');
		var form = btn.up('form');
		var fechaDesde = form.queryById('desde').getValue();
		var fechaHasta = form.queryById('hasta').getValue();
		var myMask = new Ext.LoadMask(win, {msg:"Cargando..."});
		
		if(form.isValid() == true){
			myMask.show();
			Ext.Ajax.request({
				url: Routing.generate('mbp_calidad_generateReporteRg010Fechas'),
				
				params: {
					fechaDesde: fechaDesde,
					fechaHasta: fechaHasta
				},
				
				success: function(resp, opt){
					myMask.hide();
					var jsonReporte = Ext.JSON.decode(resp.responseText);
					Reporte=jsonReporte.reporte;
					
					var ruta = Routing.generate('mbp_calidad_showReporteRg010Fechas');
									
					window.open(ruta, 'location=yes,height=800,width=1200,scrollbars=yes,status=yes');
				}	
			});
		}
	},
	
	NewRepoFallasSoldadura: function(){
		Ext.create('MetApp.view.Calidad.Reportes.RepoFallasSoldadura');
	},
	
	PrintReportFallasSoldadura: function(btn){
		var win = btn.up('window');
		var form = btn.up('form');
		var values = form.getValues();
		
		Ext.Ajax.request({
			url: Routing.generate('mbp_calidad_generateRepoFallasSoldadura'),
			
			params: {
				desde: values.desde,
				hasta: values.hasta
			},
			
			success: function(resp){
				jsonResp = Ext.JSON.decode(resp.responseText);
				if(jsonResp.success == true){
					var ruta = Routing.generate('mbp_calidad_showRepoFallasSoldadura');
					
					var myMask = new Ext.LoadMask(win, {msg:"Cargando..."});
					myMask.show();			
					
					window.open(ruta, 'location=yes,height=800,width=1200,scrollbars=yes,status=yes');				
				}
				myMask.hide();	
			}
		});
	},
	
	NewRepoEstanqueidad3D: function(btn){
		Ext.widget('RepoEstanqueidad3D');
	},
	
	PrintRepoEstanqueidad3D: function(btn){
		var win = btn.up('window');
		var form = btn.up('form');
		var values = form.getValues();
		
		Ext.Ajax.request({
			url: Routing.generate('mbp_calidad_generateRepoEstanqueidad3D'),
			
			params: {
				desde: values.desde,
				hasta: values.hasta
			},
			
			success: function(resp){
				jsonResp = Ext.JSON.decode(resp.responseText);
				if(jsonResp.success == true){
					var ruta = Routing.generate('mbp_calidad_showRepoEstanqueidad3D');
					
					var myMask = new Ext.LoadMask(win, {msg:"Cargando..."});
					myMask.show();			
					
					window.open(ruta, 'location=yes,height=800,width=1200,scrollbars=yes,status=yes');			
				}
				myMask.hide();	
			}
		});
	},
	
	NewRepoAcumuladoOpe: function(btn){
		Ext.widget('RepoAcumuladoOpe');
	},
	
	PrintRepoAcumuladoOpe: function(btn){
		var win = btn.up('window');
		var form = btn.up('form');
		var values = form.getValues();
		
		Ext.Ajax.request({
			url: Routing.generate('mbp_calidad_generateRepoAcumuladoOpe'),
			
			params: {
				desde: values.desde,
				hasta: values.hasta
			},
			
			success: function(resp){
				jsonResp = Ext.JSON.decode(resp.responseText);
				if(jsonResp.success == true){
					var ruta = Routing.generate('mbp_calidad_showRepoAcumuladoOpe');
					
					var myMask = new Ext.LoadMask(win, {msg:"Cargando..."});
					myMask.show();			
					
					window.open(ruta, 'location=yes,height=800,width=1200,scrollbars=yes,status=yes');			
				}
				myMask.hide();	
			}
		});
	}
});