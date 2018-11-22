Ext.define('MetApp.view.Principal.MyViewport', {
	extend: 'Ext.container.Viewport',
    id: 'MyViewport',
	alias: 'widget.mainViewport',
	layout: 'border',
    renderTo: Ext.getBody(),
	requires: [
		'Ext.util.Point',		
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
        
        var env = MetApp.resources.ux.ParametersSingleton.env;
        var html='';
        if(env == 'dev'){
        	html = '<h1 style="color: red; font-size: 100px; text-align: center;">DEV</h1>'
        }
        
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
					},
					{
						text: 'Actualizar BD',
						itemId: 'actualizarBDArticulos',
                        listeners : {
                            render : dragZone
                        },
					},
				]
			},			
			{
				text: 'PosEnfriadores',
				itemId: 'tabPosEnfriadores',
                listeners : {
                    render : dragZone
                },
			},
			{                						
				text: 'Clientes',
				itemId: 'tbClientes', 
				require: {role2: true},
                listeners : {
                    render : dragZone
                },               						
			},
			{
				text: 'Proveedores',
				itemId: 'tbProveedores', 
				//require: {role1: true},
                listeners : {
                    render : dragZone
                },
			},
			{
				text: 'Bancos',
				itemId: 'tbBancos', 
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
			},
			{
				text: 'Transportes',
				itemId: 'tbTransportes',
                listeners : {
                    render : dragZone
                },
			},			
			{
				text: 'Parámetros',
				itemId: 'formParametros',
                listeners : {
                    render : dragZone
                },
			},
		]
		
        var menuCalidad = [
			{
				text: 'Num. Correlativa',
				itemId: 'numCorrelativa',
				require: {role2: true},
                listeners : {
                    render : dragZone
                },
			},
			{
				text: 'RG-010 Estanqueidad',
				menu: [
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
							{
								text: 'Acumulado por operaciones',
								itemId: 'acumuladoOpe',
                                listeners : {
                                    render : dragZone
                                },
							},
						]												
					}					
				]				
			},
			{
				text: 'Soldadura',
				menu: [
					{
						text: 'Control de producción',
						itemId: 'controlProduccionSoldado',
                        listeners : {
                            render : dragZone
                        },
					}
				]				
			},
			{
				text: 'Control de Recepción',
				itemId: 'controlRecepcion'
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
        		text: 'Modificar pedidos',
        		itemId: 'modificarPedido',
                listeners : {
                    render : dragZone,
                }, 
			},
			{
        		text: 'Autorizar Entregas',
        		itemId: 'autorizarEntregas',
                listeners : {
                    render : dragZone,
                }, 
			},
			{
        		text: 'Entregas Autorizadas',
        		itemId: 'entregasAutorizadas',
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
        	},
        	{
        		text: 'Seguimiento OT',
        		itemId: 'seguimientoOT',
                listeners : {
                    render : dragZone,
                }, 
        	},
        ]
        
    	var menuProduccion = [
    		{
    			text: 'Radiadores',
    			require: {role2: true},
    			menu: autz.getAuthorizedElements(subMenuRadiadores)
    		},
    		{
    			text: 'Ordenes de Trabajo',
    			require: {role2: true},
    			menu: [
    				{
    					text: 'Nueva OT',
    					itemId: 'nuevaOt',
    					listeners : {
                            render : dragZone,
                        }
    				},
    				{
    					text: 'Cierre OT',
    					itemId: 'cierreOt',
    					listeners : {
                            render : dragZone,
                        }
    				},
    				{
    					text: 'Ver OT',
    					itemId: 'verOt',
    					listeners : {
                            render : dragZone,
                        }
					},
					{
    					text: 'Asociar OT',
    					itemId: 'asociarOt',
    					listeners : {
                            render : dragZone,
                        }
    				}
    			]
    		},
    		{
    			text: 'Pedidos',
    			require: {role2: true},
    			menu: autz.getAuthorizedElements(subMenuPedidos)
    		},
    		{
    			text: 'Programacion',
    			require: {role2: true},
    			menu: autz.getAuthorizedElements(subMenuProgramacion)
    		},
    		{
    			text: 'Reportes',
    			menu: [
    				{
	    				text: 'Listado de ordenes entre fechas',
						itemId: 'ordenesPorSector',
						listeners : {
                       		render : dragZone,
                       }
                   },
                   {
	    				text: 'Listado de ordenes por cliente',
						itemId: 'ordenesPorCliente',
						listeners : {
                       		render : dragZone,
                       }
                   },
                   {
	    				text: 'Histórico de Producción',
						itemId: 'historicoProduccion',
						listeners : {
                       		render : dragZone,
                       }
                   },
    			]
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
    					text: 'En Lote',
    					itemId: 'tablaNuevoPagoEnLote',
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
            },
            {
                text: 'Modificar orden de compra',
                itemId: 'modificarOrdenDeCompra',
                listeners : {
                    render : dragZone,
                }, 
            },
            {
                text: 'Ver Pedidos Internos',
                itemId: 'verPedidosInternos',
                listeners : {
                    render : dragZone,
                }, 
            },
            {
                text: 'Articulos Comprados',
                itemId: 'repoArticulosComprados',
                listeners : {
                    render : dragZone,
                }, 
            }
    	]
    	
    	var menuStock = [
    		{
    			text: 'Entrada/Salida',
    			itemId: 'entradaSalidaStock',
                listeners : {
                    render : dragZone,
                }, 
    		},
    		{
    			text: 'Listado Ingresos',
    			itemId: 'listadoIngresos',
                listeners : {
                    render : dragZone,
                }, 
    		}
    	]

        var menuRemitos = [
            {
                text: 'Nuevo Remito',
                itemId: 'remitoCliente',
                listeners : {
                    render : dragZone,
                }, 
            },
            {
                text: 'Ver Remitos',
                itemId: 'verRemitos',
                listeners : {
                    render : dragZone,
                }, 
            },
            {
                text: 'Anular Remitos Pendientes de Facturación',
                itemId: 'verRemitosPendientesFc',
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
        			},
        			{
        				text: 'Art. Vendidos por Cliente y Período',
        				itemId: 'reporteArtVendidos',
        				listeners : {
		                    render : dragZone,
		                }, 
        			},
        			{
        				text: 'Intereses por Pago Fuera de Término',
        				itemId: 'reporteIntResarcitorios',
        				listeners : {
		                    render : dragZone,
		                }, 
        			},
        			{
        				text: 'Resumen Saldo Deudor',
        				itemId: 'reporteSaldoDeudor',
        				listeners : {
		                    render : dragZone,
		                }, 
        			},
        			{
        				text: 'Comprobantes No Pagados',
        				itemId: 'reporteCbteNoPagado',
        				listeners : {
		                    render : dragZone,
		                }, 
        			},
        			{
        				text: 'Resumen Cuenta Corriente',
        				itemId: 'reporteResumenCCCliente',
        				listeners : {
		                    render : dragZone,
		                }, 
        			},
                    {
                        text: 'Comisiones a Vendedores',
                        itemId: 'reporteComisiones',
                        listeners : {
                            render : dragZone,
                        }, 
					},
					{
                        text: 'Cobranzas Entre Fechas',
                        itemId: 'reporteCobranzasEntreFechas',
                        listeners : {
                            render : dragZone,
                        }, 
                    },
        		]        		
        	},
        	{
        		text: 'Proveedores',
        		menu: [
					{
        				text: 'Resumen Cuenta Corriente',
        				itemId: 'reporteCCProveedores'
        			},
        			{
        				text: 'Libro IVA compras',
        				itemId: 'reporteIVACompras',
        				listeners : {
		                    render : dragZone,
		                }, 
					},
					{
        				text: 'Resumen Saldo Acreedor',
        				itemId: 'reporteResumenSaldoAcreedor',
        				listeners : {
		                    render : dragZone,
		                }, 
        			},
        			{
        				text: 'Inventario Cheques de Terceros',
        				itemId: 'reporteChequeTerceros',
        				listeners : {
		                    render : dragZone,
		                }, 
        			},
        			{
        				text: 'Cheques de Terceros Ingresados y Entregados',
        				itemId: 'reporteChequeTercerosEntregados',
        				listeners : {
		                    render : dragZone,
		                }, 
					},
					{
        				text: 'Cheques Propios Emitidos y Entregados',
        				itemId: 'reporteChequePropiosEntregados',
        				listeners : {
		                    render : dragZone,
		                }, 
        			},
                    {
                        text: 'Retenciones',
                        itemId: 'reporteRetenciones',
                        listeners : {
                            render : dragZone,
                        }, 
					},
					{
                        text: 'Ordenes de Pago',
                        itemId: 'reporteOrdenesDePago',
                        listeners : {
                            render : dragZone,
                        }, 
                    }
        		]
        	},
        	{
        		text: 'Stock',
        		menu: [
        			{
        				text: 'Histórico de Movimientos Articulos',
        				itemId: 'reporteHistMov',
        				listeners : {
		                    render : dragZone,
		                }, 
        			}
        		]
        	},
        	{
        		text: 'Bancos',
        		menu: [
        			{
        				text: 'Movimientos Bancarios Histórico',
        				itemId: 'reporteHistMovBancarios',
        				listeners : {
		                    render : dragZone,
		                }, 
        			}
        		]
        	},
        	{
        		text: 'Calidad',
        		menu: [
        			{
        				text: 'RG',
        				menu: [
        					{
        						text: 'RG-010 Control Final de Estanqueidad',
        						itemId: 'rg010Estanqueidad',
		        				listeners : {
				                    render : dragZone,
				                }, 
        					},
        					{
        						text: 'RG-014 Numeración Correlativa de Radiadores',
        						itemId: 'rg014',
		        				listeners : {
				                    render : dragZone,
				                }, 
        					}
        				]		        				
        			}
        		]
        	}
        ]
        
        var menuBancos = [
        	{        		
				text: 'Movimientos de bancos',
				itemId: 'movBancos',
				listeners : {
                    render : dragZone,
                },         		
        	},
        	{        		
				text: 'Conceptos de bancos',
				itemId: 'conceptoBancos',
				listeners : {
                    render : dragZone,
                },       		
        	}
        ]
        
        var menuProveedores = [
        	{
        		text: 'CC Proveedores',
        		itemId: 'tbCCProveedores'
        	},
        	{
        		text: 'Formas de Pago',
        		itemId: 'tbFormasPago'
        	}
        ]
        
        var menuUtilitarios = [
        	{
        		text: 'TXT Retenciones/Percepciones',
        		itemId: 'tbRetenciones'
        	},
        	{
        		text: 'Cotizaciones',        		
        		menu: [
        			{
        				text: 'Nueva',
        				itemId: 'tbCotizaciones',
        			},
        			{
        				text: 'Listado',
        				itemId: 'tbListadoCoti',
					}
        		]
			},	
			{
				text: 'Hoja de Ruta',
				itemId: 'tbHojaDeRuta',
			},
			{
				text: 'CITI',
				menu: [
        			{
        				text: 'CITI Ventas',
        				itemId: 'tbCITIVentas',
        			},
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
        
        var panelLateral = [
        	{
				text: 'Tabla',
				menu: autz.getAuthorizedElements(menuTablas) //Analizo los elementos a desplegar segun el rol
			},
			{
				text: 'Calidad',
				require: {role2: true},
				menu: autz.getAuthorizedElements(menuCalidad)
			},
			{
				text: 'Produccion',
				require: {role2: true},
				menu: autz.getAuthorizedElements(menuProduccion)
			},
			{
				text: 'RRHH',
				require: {role2: true},
				menu: autz.getAuthorizedElements(menuRRHH)
			},
			{
				text: 'Ventas',  
				require: {role2: true},
				itemId: 'tbCCClientes'
			},
			{
				text: 'Proveedores',
				require: {role2: true},
				menu: autz.getAuthorizedElements(menuProveedores)        				
			},
			{
				text: 'Bancos',
				require: {role2: true},
				menu: autz.getAuthorizedElements(menuBancos)
			},
			{
				text: 'Mantenimiento',
				require: {role2: true},
				menu: autz.getAuthorizedElements(menuMantenimiento)
			},
			{
				text: 'Compras',
				require: {role2: true},
				menu: autz.getAuthorizedElements(menuCompras)
			},
			{
				text: 'Stock',
				require: {role2: true},
				menu: autz.getAuthorizedElements(menuStock)
			},
            {
                text: 'Remitos',
                require: {role2: true},
                menu: autz.getAuthorizedElements(menuRemitos)
            },
            {
                text: 'Reportes',
                require: {role2: true},
                menu: autz.getAuthorizedElements(menuReportes)
            },
            {
                text: 'Utilitarios',
                require: {role2: true},
                menu: autz.getAuthorizedElements(menuUtilitarios)
            },
            '->',   
           /* {
            	xtype: 'button',
				text: 'Notificaciones',
				itemId: 'notificacionesBtn',
				//cls: 'sinNotificaciones'
            },*/
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

    	me.items = [
	    	{
	    		xtype: 'panel',
                listeners : {
                    render : dropZone,
                },
	    		title: MetApp.User.name.name,
	    		itemId: 'panelFavoritos',
                id: 'panelFavoritos',
		        region: 'center',        
		        autoHeight: true,
		        border: true,
		        margins: '0 0 5 0',
                items: me.params,
                html: html
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
                          
              	items: autz.getAuthorizedElements(panelLateral)
        	}   
	    	   	
    	];
    	me.callParent(arguments);    		
    }     
});
