Ext.define('MetApp.model.Calidad.Estanqueidad',{
	extend: 'Ext.data.Model',
	idProperty: 'id',
	
	fields: [
		{name: 'id', type: 'int'},
		{name: 'fechaPrueba', type: 'date',
			dateFormat: 'd/m/Y',
			dateReadFormat: 'd/m/Y',
			dateWriteFormat: 'd/m/Y',
		},
		{name: 'ot', type: 'int'},
		{name: 'pruebaNum', type: 'int'},
		{name: 'presion', type: 'int'},
		{name: 'estado', type: 'int', }, // 0 OK, 1 NO OK, 2 REPARADO
		{name: 'mChapa', type: 'boolean', },
		{name: 'mBagueta', type: 'boolean', },
		{name: 'mAnulado', type: 'boolean', },
		{name: 'mCiba', type: 'boolean', },
		{name: 'mPerfil', type: 'boolean', },
		{name: 'mChapaColectora', type: 'boolean', },
		{name: 'mPisoDesp', type: 'boolean', },
		{name: 'tRosca', type: 'boolean', },
		{name: 'tPoros', type: 'boolean', },
		{name: 'tFijacion', type: 'boolean', },
		{name: 'sConector', type: 'boolean', },
		{name: 'sTapaPanel', type: 'boolean', },
		{name: 'sPlanchuelas', type: 'boolean', },
		{name: 'sPuntera', type: 'boolean', },
		{name: 'idSoldador', type: 'int', },
		{name: 'idProbador', type: 'int', },
	]
});

