Ext.define('MetApp.view.Principal.MyViewport', {
	extend: 'Ext.container.Viewport',
    id: 'MyViewport',
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
    	
        var dragZone = function(v){
            this.dragZone = Ext.create("Ext.dd.DragZone",v.getEl(),{
                getDragData : function(e){
                   var sourceEl = e.getTarget();
                   var clonedSourceEl = sourceEl.cloneNode(true);
                   return this.dragData = {
                           ddel: clonedSourceEl,//mandatory
                           sourceEl: sourceEl,
                           el: v
                   }
                }
            });
        };

    	var menuTablas =  [
			{
				text: 'Articulos',
                listeners : {
                    render : dragZone,
                },    
				itemId: 'tbArticulos',
				require: {role2: true},
				menu: [
					{
						text: 'Formulas',
                        listeners: {
                            render: dragZone
                        },
                        cls: 'button-draggable',
						itemId: 'btnFormulas',
                        listeners : {
                            render : dragZone
                        },
                    },
					{
						text: 'Familia',
						itemId: 'tabFamilia',
                        listeners : {
                            render : dragZone
                        },
					},
					{
						text: 'Sub Familia',
						itemId: 'tabSubFamilia',
                        listeners : {
                            render : dragZone
                        },
					}
				]
			},
			{                						
				text: 'Clientes',
				itemId: 'tbClientes', 
				require: {role1: true},
                listeners : {
                    render : dragZone
                },               						
			},
			{
				text: 'Proveedores',
				itemId: 'tbProveedores', 
				require: {role1: true},
                listeners : {
                    render : dragZone
                },
			},
			{
				text: 'Centro de Costos',
				itemId: 'tbCentroCostos', 
				require: {role1: true},
                listeners : {
                    render : dragZone
                },
			},
			{
				text: 'Lista de precios',				 
				require: {role1: true},
				menu: [
					{
						text: 'Lista Maestra',
						itemId: 'tbListaMaestra',
                        listeners : {
                            render : dragZone
                        },
					}
				]
			}
		]
		
        var menuCalidad = [
			{
				text: 'Num. Correlativa',
				itemId: 'numCorrelativa',
                listeners : {
                    render : dragZone
                },
			},
			{
				text: 'RG-010 Estanqueidad',
				menu: [
					{
						text: 'RG-010',
						itemId: 'rg010Estanqueidad',
                        listeners : {
                            render : dragZone
                        },					
					},
					{
						text: 'Formulario',
						itemId: 'controlEstanqueidad',
                        listeners : {
                            render : dragZone
                        },
					},
					{
						text: 'Reporte',
						menu: [
							{
								text: 'Estanqueidad entre fechas y por OT',
								itemId: 'repo1RG010',
                                listeners : {
                                    render : dragZone
                                },
							},
							{
								text: 'Fallas de soldadura',
								itemId: 'RG010FallasSoldadura',
                                listeners : {
                                    render : dragZone
                                },
							},
							{
								text: 'Estanqueidad Grafico 3D',
								itemId: 'repo2RG010',
                                listeners : {
                                    render : dragZone
                                },
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
		    			itemId: 'calculoRad',
                        listeners : {
                            render : dragZone,
                        }, 
					},
					{
						text: 'Parametros Brazing',
						itemId: 'parametrosBrazing',
						require: {role1: true},
                        listeners : {
                            render : dragZone,
                        }, 
					}
        		]
        	},
        	{
        		text: 'Agua',
        		itemId: 'radiadorAgua',
        		menu: [
        			{
        				text: 'Calculo y Seleccion',
        				itemId: 'calculoSeleccionAgua',
                        listeners : {
                            render : dragZone,
                        }, 
        			}
        		]
        	}
        	
        ]
        
        var subMenuPedidos = [
        	{
        		text: 'Nuevo pedido',
        		itemId: 'nuevoPedido',
                listeners : {
                    render : dragZone,
                }, 
        	},
        	{
        		text: 'Reportes',
        		menu: [
        			{
        				text: 'Art. pedido por cliente y periodo',
        				itemId: 'articulosPedidos',
                        listeners : {
                            render : dragZone,
                        }, 
        			}
        		]
        	}
        ]
        
        var subMenuProgramacion = [        	
        	{
        		text: 'Maquinas',
        		itemId: 'maquinas',
                listeners : {
                    render : dragZone,
                }, 
        	},
        	{
        		text: 'Operaciones',
        		itemId: 'operaciones',
                listeners : {
                    render : dragZone,
                }, 
        	},
        	{
        		text: 'Formulas M.O.',
        		itemId: 'formulasMo',
                listeners : {
                    render : dragZone,
                }, 
        	},
        	{
        		text: 'Procesos',
        		menu:[
	        		{
	        			text:'Programar pedidos',
	        			itemId: 'programarPedidos',
                        listeners : {
                            render : dragZone,
                        }, 	
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
    					text: 'Nueva OT',
    					itemId: 'nuevaOt',
    					listeners : {
                            render : dragZone,
                        }
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
    			itemId: 'tablaPersonal',
                listeners : {
                    render : dragZone,
                }, 
    		},
    		{
    			text: 'Sindicatos',
    			itemId: 'tablaSindicatos',
                listeners : {
                    render : dragZone,
                }, 
    		},
    		{
    			text: 'Categorias',
    			itemId: 'tablaCategorias',
                listeners : {
                    render : dragZone,
                }, 
    		},
    		{
    			text: 'Conceptos',
    			itemId: 'tablaConceptos',
                listeners : {
                    render : dragZone,
                }, 
    		},
    		{
    			text: 'Liquidaciones',
    			menu: [
    				{	
    					text: 'Nuevo pago',
    					itemId: 'tablaNuevoPago',
                        listeners : {
                            render : dragZone,
                        }, 	
    				},
    				{	
    					text: 'Reliquidar periodo',
    					itemId: 'tablaReliquidarPeriodo',
                        listeners : {
                            render : dragZone,
                        }, 
    				},
    				{	
    					text: 'Imprimir',
    					itemId: 'winImprimeRecibos'	,
                        listeners : {
                            render : dragZone,
                        }, 
    				}
    			]   			
    		},
    		{
    			text: 'Cuentas',
    			itemId: 'tablaCuentaEmpleados',
                listeners : {
                    render : dragZone,
                }, 
    		},
    		{
    			text: 'Transferencias',
    			itemId: 'tablaTransferencias',
                listeners : {
                    render : dragZone,
                }, 
    		},
    		{
    			text: 'Reportes',
    			menu: [
    				{
    					text: 'Libro Sueldos',
    					itemId: 'reporteLibroSueldos',
                        listeners : {
                            render : dragZone,
                        }, 
    				},
    				{
    					text: 'Resumen aguinaldo',
    					itemId: 'reporteResumenAguinaldo',
                        listeners : {
                            render : dragZone,
                        }, 
    				},
                    {
                        text: 'Resumen liquidaciones',
                        itemId: 'reporteResumenLiquidaciones',
                        listeners : {
                            render : dragZone,
                        }, 
                    }
    			]
    		},
    	]
    	
    	var menuMantenimiento = [
    		{
        		text: 'Sector',
        		itemId: 'sector',
                listeners : {
                    render : dragZone,
                }, 
        	},
    	]
    	
    	var menuCompras = [
    		{
    			text: 'Orden de compra',
    			itemId: 'ordenDeCompra',
                listeners : {
                    render : dragZone,
                }, 
    		},
            {
                text: 'Ver orden de compra',
                itemId: 'verOrdenDeCompra',
                listeners : {
                    render : dragZone,
                }, 
            }
    	]

        var menuRemitos = [
            {
                text: 'Cliente',
                itemId: 'remitoCliente',
                listeners : {
                    render : dragZone,
                }, 
            },
            {
                text: 'Proveedor',
                itemId: 'remitoProveedor',
                listeners : {
                    render : dragZone,
                }, 
            },
        ]
        
        var menuReportes = [
        	{
        		text: 'Ventas',
        		itemId: 'reportesVentas',
        		menu: [
        			{
        				text: 'Libro IVA ventas',
        				itemId: 'reporteIVAVentas',
        				listeners : {
		                    render : dragZone,
		                }, 
        			}
        		]        		
        	}
        	
        ]
        
    		    	
    	var me = this;    	

        var dropZone = function(v){
            this.dropZone = Ext.create("Ext.dd.DropZone",v.getEl(),{
                getTargetFromEvent: function(e) {
                    return e.getTarget();
                },
                onNodeDrop : function(target,dd,e,data){
                    var store = Ext.getStore('UserParamsStore');   
                    var favsNumer = store.getCount();
                    if(favsNumer >= 10){
                        return Ext.Msg.show({
                             title:'Atencion',
                             msg: 'No se pueden agregar mas de 10 favoritos',
                             buttons: Ext.Msg.OK,
                             icon: Ext.Msg.INFO
                        });
                    }
                    var btn = Ext.create('Ext.Button', {
                        text: data.el.text,
                        itemId: data.el.itemId,
                        margin: '5 5 5 5',
                        width: 200,
                        height: 50,
                        iconCls: 'favoritos'
                    });
                    var panelFav = me.queryById('panelFavoritos');
                    targetEl = Ext.get(target);
                    panelFav.insert(0, btn);

                                     
                    var idRec = favsNumer + 1;
                    store.add({nombre: data.el.text, itemId: data.el.itemId});
                    store.sync();
                },
            });
        };

    	me.items = [
	    	{
	    		xtype: 'panel',
                listeners : {
                    render : dropZone,
                },
	    		title: 'Favoritos',
	    		itemId: 'panelFavoritos',
                id: 'panelFavoritos',
		        region: 'center',        
		        autoHeight: true,
		        border: true,
		        margins: '0 0 5 0',
                items: me.params,
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
                    {
                        text: 'Reportes',
                        menu: autz.getAuthorizedElements(menuReportes)
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
