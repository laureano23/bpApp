	Ext.define('MetApp.view.Calidad.GridNumCorrelativa',{
	itemId: 'correlativosGrid',
	extend: 'Ext.grid.Panel',
	alias: 'widget.gridCorrelativos',
	store: 'Calidad.Correlativos',
	multiSelect: true,
	idProperty: 'idCorrelativos',
	height: 200,
    //width: 1240,
    plugins: {
	    ptype: 'bufferedrenderer',
	    trailingBufferZone: 20,  // Keep 20 rows rendered in the table behind scroll
	    leadingBufferZone: 50   // Keep 50 rows rendered in the table ahead of scroll
    },
	columns: [	
		{text: 'Id', dataIndex: 'idCorrelativos', hidden: true},		
		{text: 'N° Corr.', dataIndex: 'numCorrelativo', flex: 1,
			 renderer: function(val, metadata){
			 	if(val >= 88706 && val <= 88760){
			 		metadata.style += 'color:red;';
			 	}	
			 	return val;
			 }
		},		
		{text: 'Cant.', dataIndex: 'cant', flex: 1},
		{text: 'Fecha', dataIndex: 'fecha.date', type: 'datecolumn', renderer: Ext.util.Format.dateRenderer('d-m-Y'), flex: 1},			
		{text: 'OT enf.', dataIndex: 'otEnf', flex: 1},					
		{text: 'OT1', dataIndex: 'ot1panel', flex: 1},
		{text: 'OT2', dataIndex: 'ot2panel', flex: 1},
		{text: 'OT3', dataIndex: 'ot3panel', flex: 1},
		{text: 'OT4', dataIndex: 'ot4panel', flex: 1},
		{text: 'Obs.', dataIndex: 'obs', flex: 1},
	],
		
});
