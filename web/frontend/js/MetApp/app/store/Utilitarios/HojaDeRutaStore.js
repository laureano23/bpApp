Ext.define('MetApp.store.Utilitarios.HojaDeRutaStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Utilitarios.HojaDeRutaModel',
	alias: 'CCProveedoresStore',
	autoSync: true,
	autoLoad: false,	
	
	proxy: {
		type: 'ajax',
		api: {
			read: Routing.generate('mbp_hojaDeRuta_listarDestinos'),
			create: Routing.generate('mbp_hojaDeRuta_nuevoDestino'),
			destroy: Routing.generate('mbp_hojaDeRuta_anularDestino'),
			update: Routing.generate('mbp_hojaDeRuta_nuevoDestino')
		},
		
		reader: {
			type: 'json',
			root: 'data'
		},
		
		writer: {
			type: 'json',
			root: 'data',
			encode: true
		}
	}
});
