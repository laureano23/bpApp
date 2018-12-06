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
		var form=btn.up('form');
		form.getForm().submit({
			standardSubmit: true,
			target: '_blank',
			url: Routing.generate('mbp_calidad_generateReporteRg010')
		})			
	},
	
	PrintDateRepoRG010: function(btn){
		var form=btn.up('form');
		form.getForm().submit({
			standardSubmit: true,
			target: '_blank',
			url: Routing.generate('mbp_calidad_generateReporteRg010Fechas')
		})
	},
	
	NewRepoFallasSoldadura: function(){
		Ext.create('MetApp.view.Calidad.Reportes.RepoFallasSoldadura');
	},
	
	PrintReportFallasSoldadura: function(btn){
		var form=btn.up('form');
		form.getForm().submit({
			standardSubmit: true,
			target: '_blank',
			url: Routing.generate('mbp_calidad_generateRepoFallasSoldadura')
		})
	},
	
	NewRepoEstanqueidad3D: function(btn){
		Ext.widget('RepoEstanqueidad3D');
	},
	
	PrintRepoEstanqueidad3D: function(btn){
		var form=btn.up('form');
		form.getForm().submit({
			standardSubmit: true,
			target: '_blank',
			url: Routing.generate('mbp_calidad_generateRepoEstanqueidad3D')
		})
	},
	
	NewRepoAcumuladoOpe: function(btn){
		Ext.widget('RepoAcumuladoOpe');
	},
	
	PrintRepoAcumuladoOpe: function(btn){
		var form=btn.up('form');
		form.getForm().submit({
			standardSubmit: true,
			target: '_blank',
			url: Routing.generate('mbp_calidad_generateRepoAcumuladoOpe')
		})
	}
});