Ext.define('MetApp.model.Personal.PersonalModel',{
	extend: 'Ext.data.Model',
	idProperty: 'idPer',
	fields: [
		{ name: 'idP', type: 'int' },
		{ name: 'nombre', type: 'string' },
		{ name: 'cPostal', type: 'string' },
		{ name: 'categoria', type: 'string' },
		{ name: 'sindicato', type: 'string' },
		{ name: 'salario', type: 'auto' },
		{ name: 'compensatorio', type: 'float' },
		{ name: 'departamento', type: 'string' },
		{ name: 'direccion', type: 'string' },
		{ name: 'documentoNum', type: 'string' },
		{ name: 'documentoTipo', type: 'string' },
		{ name: 'estado', type: 'string' },
		{ name: 'fechaEgreso', type: 'date', dateFormat: 'd/m/Y' },
		{ name: 'fechaIngreso', type: 'date', dateFormat: 'd/m/Y' },
		{ name: 'fechaNacimiento', type: 'date', dateFormat: 'd/m/Y' },
		{ name: 'localidad', type: 'string' },
		{ name: 'periodo', type: 'int' },
		{ name: 'provincia', type: 'string' },
		{ name: 'tarea', type: 'string' },
		{ name: 'sector', type: 'string' },
		{ name: 'telefonos', type: 'string' },
		{ name: 'datosFijos', type: 'auto' },
		{ name: 'idDatosFijos', type: 'auto' },
		{ name: 'cantDatosFijos', type: 'auto' },
		{ name: 'cuil', type: 'int' },
		{ name: 'tipoContratacion', type: 'string' },
		{ name: 'antiguedad', type: 'bool' },
		{ name: 'antPorcentaje', type: 'int' },
		{ name: 'nacionalidad', type: 'string' },
		{ name: 'obraSocial', type: 'string' },
		{ name: 'talleCamisa', type: 'int' },
		{ name: 'tallePantalon', type: 'int' },
		{ name: 'talleCalzado', type: 'int' },
	]
});
