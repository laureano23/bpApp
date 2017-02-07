Ext.define('MetApp.view.Principal.MyViewport', {
	extend: 'Ext.container.Viewport',
	alias: 'widget.mainViewport',
	layout: 'border',
    renderTo: Ext.getBody(),
	requires: [
		'Ext.util.Point'
	],
    initComponent: function(){
    
    
    	/*
    	 * Defino el objeto que manejara la seguridad de las vistas
    	 */  		
    	var autz = Ext.create('MetApp.controller.Security.Autorizaciones');    	
    	autz.authorization(MetApp.User);
    	
    	
    	var menuTablas =  [
			{
				text: 'Articulos',
				itemId: 'tbArticulos',
				require: {role2: true},
				menu: [
					{
						text: 'Formulas',
						itemId: 'btnFormulas',
						listeners: {
							render: function(v){
								v.dragZone = dragZone = Ext.create("Ext.dd.DragZone",v.getEl(),{
									getDragData : function(e){								
                                       var sourceEl = e.getTarget();
                                       var clonedSourceEl = sourceEl.cloneNode(true);
                                       clonedSourceEl.obj = v;
                                       return v.dragData = {
                                               ddel: clonedSourceEl,//mandatory
                                               sourceEl: sourceEl,
                                               btn: Ext.create('Ext.Button',{
                                               		text: 'hola',
                                               		itemId: 'btnFormulas'
                                               })        
                                       }
                                    }
								});
							}
						}						
					},
					{
						text: 'Familia',
						itemId: 'tabFamilia'
					},
					{
						text: 'Sub Familia',
						itemId: 'tabSubFamilia'
					}
				]
			},
			{                						
				text: 'Clientes',
				itemId: 'tbClientes', 
				require: {role1: true}               						
			},
			{
				text: 'Proveedores',
				itemId: 'tbProveedores', 
				require: {role1: true}
			},
			{
				text: 'Centro de Costos',
				itemId: 'tbCentroCostos', 
				require: {role1: true}
			},
			{
				text: 'Lista de precios',				 
				require: {role1: true},
				menu: [
					{
						text: 'Lista Maestra',
						itemId: 'tbListaMaestra'
					}
				]
			}
		]
		
        var menuCalidad = [
			{
				text: 'Num. Correlativa',
				itemId: 'numCorrelativa'
			},
			{
				text: 'RG-010 Estanqueidad',
				menu: [
					{
						text: 'RG-010',
						itemId: 'rg010Estanqueidad'						
					},
					{
						text: 'Formulario',
						itemId: 'controlEstanqueidad'
					},
					{
						text: 'Reporte',
						menu: [
							{
								text: 'Estanqueidad entre fechas y por OT',
								itemId: 'repo1RG010'
							},
							{
								text: 'Fallas de soldadura',
								itemId: 'RG010FallasSoldadura'
							},
							{
								text: 'Estanqueidad Grafico 3D',
								itemId: 'repo2RG010'
							},
						]												
					},
				]				
			}
        ]
        
        var subMenuRadiadores = [
        	{
        		text: 'Placa Barra',
        		itemId: 'placaBarra',
        		menu: [
	        		{					
		    			text: 'Calculo de Radiadores',
		    			itemId: 'calculoRad' 
					},
					{
						text: 'O.T Paneles',
						itemId: 'otPaneles'
					},
					{
						text: 'Parametros Brazing',
						itemId: 'parametrosBrazing',
						require: {role1: true}
					}
        		]
        	},
        	{
        		text: 'Agua',
        		itemId: 'radiadorAgua',
        		menu: [
        			{
        				text: 'Calculo y Seleccion',
        				itemId: 'calculoSeleccionAgua'
        			}
        		]
        	}
        	
        ]
        
        var subMenuPedidos = [
        	{
        		text: 'Nuevo pedido',
        		itemId: 'nuevoPedido'
        	},
        	{
        		text: 'Reportes',
        		menu: [
        			{
        				text: 'Art. pedido por cliente y periodo',
        				itemId: 'articulosPedidos'
        			}
        		]
        	}
        ]
        
        var subMenuProgramacion = [        	
        	{
        		text: 'Maquinas',
        		itemId: 'maquinas'
        	},
        	{
        		text: 'Operaciones',
        		itemId: 'operaciones'
        	},
        	{
        		text: 'Formulas M.O.',
        		itemId: 'formulasMo'
        	},
        	{
        		text: 'Procesos',
        		menu:[
	        		{
	        			text:'Programar pedidos',
	        			itemId: 'programarPedidos'	
	        		}
        		]        		
        	}
        ]
        
    	var menuProduccion = [
    		{
    			text: 'Radiadores',
    			menu: autz.getAuthorizedElements(subMenuRadiadores)
    		},
    		{
    			text: 'Ordenes de Trabajo',
    			menu: [
    				{
    					text: 'Nueva OT'
    				}
    			]
    		},
    		{
    			text: 'Pedidos',
    			menu: autz.getAuthorizedElements(subMenuPedidos)
    		},
    		{
    			text: 'Programacion',
    			menu: autz.getAuthorizedElements(subMenuProgramacion)
    		}
    	]
    	
    	var menuRRHH = [
    		{
    			text: 'Personal',
    			itemId: 'tablaPersonal'
    		},
    		{
    			text: 'Sindicatos',
    			itemId: 'tablaSindicatos'
    		},
    		{
    			text: 'Categorias',
    			itemId: 'tablaCategorias'
    		},
    		{
    			text: 'Conceptos',
    			itemId: 'tablaConceptos'
    		},
    		{
    			text: 'Liquidaciones',
    			menu: [
    				{	
    					text: 'Nuevo pago',
    					itemId: 'tablaNuevoPago'	
    				},
    				{	
    					text: 'Reliquidar periodo',
    					itemId: 'tablaReliquidarPeriodo'	
    				},
    				{	
    					text: 'Imprimir',
    					itemId: 'winImprimeRecibos'	
    				}
    			]   			
    		},
    		{
    			text: 'Cuentas',
    			itemId: 'tablaCuentaEmpleados'
    		},
    		{
    			text: 'Transferencias',
    			itemId: 'tablaTransferencias'
    		},
    		{
    			text: 'Reportes',
    			menu: [
    				{
    					text: 'Libro Sueldos',
    					itemId: 'reporteLibroSueldos'
    				},
    				{
    					text: 'Resumen aguinaldo',
    					itemId: 'reporteResumenAguinaldo'
    				}
    			]
    		},
    	]
    	
    	var menuMantenimiento = [
    		{
        		text: 'Sector',
        		itemId: 'sector'
        	},
    	]
    	
    	var menuCompras = [
    		{
    			text: 'Orden de compra',
    			itemId: 'ordenDeCompra'
    		}
    	]

        var menuRemitos = [
            {
                text: 'Cliente',
                itemId: 'remitoCliente'
            },
            {
                text: 'Proveedor',
                itemId: 'remitoProveedor'
            },
        ]
    		    	
    	var me = this;    	
    	me.items = [
	    	{
	    		xtype: 'panel',
	    		title: 'Favoritos',
	    		itemId: 'panelFavoritos',
		        region: 'center',        
		        autoHeight: true,
		        border: true,
		        margins: '0 0 5 0',
		        listeners: {
		        	render: function(v){
	        			v.dropZone = Ext.create("Ext.dd.DropZone",v.getEl(),{
                        	getTargetFromEvent: function(e) {
                            return e.getTarget();
                        },
                        onNodeDrop : function(target,dd,e,data){
                            targetEl = Ext.get(target);
                            console.log(targetEl);  
                            console.log(data); 
                            //targetEl.add(data.btn);
                            v.add(data.btn);
                            
                   			}
                    	});
		        	}
		    	}
	    	},
	    	{
	    		id:'PnlNorte',
				xtype: 'toolbar',  
				vertical: true,
				style: {
			      background: 'blue'
			    },              
                region: 'west',
                frame: true,
                height: 40,
                margins: '2,1,0,0',                
                activeItem: 0,                
                shadowOffset: 10,
                          
              	items: [
                	
        			{
        				text: 'Tabla',
        				menu: autz.getAuthorizedElements(menuTablas) //Analizo los elementos a desplegar segun el rol
        			},
        			{
        				text: 'Calidad',
        				menu: autz.getAuthorizedElements(menuCalidad)
        			},
        			{
        				text: 'Produccion',
        				menu: autz.getAuthorizedElements(menuProduccion)
        			},
        			{
        				text: 'RRHH',
        				menu: autz.getAuthorizedElements(menuRRHH)
        			},
        			{
        				text: 'Ventas',  
        				itemId: 'tbCCClientes'
        			},
        			{
        				text: 'Proveedores',
        				itemId: 'tbCCProveedores'
        			},
        			{
        				text: 'Mantenimiento',
        				menu: autz.getAuthorizedElements(menuMantenimiento)
        			},
        			{
        				text: 'Compras',
        				menu: autz.getAuthorizedElements(menuCompras)
        			},
                    {
                        text: 'Remitos',
                        menu: autz.getAuthorizedElements(menuRemitos)
                    },
        			'->',   
        			{
        				xtype: 'button',
        				text: 'Ayuda',                				
        				handler: function(){
        					location.href = Routing.generate('mbp_manual_index')
        				}
        			},        				
        			{
        				xtype: 'button',
        				text: 'Cerrar Sesion',                				
        				handler: function(){
        					location.href = Routing.generate('logout')
        				}
        			}
        		]
        	}   
	    	   	
    	];
    	me.callParent(arguments);    		
    }     
});
