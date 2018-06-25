
var TREE_ITEMS_ADM = [
	 	['Administracion',0,
			 		/*['Funcionarios','navegador_funcionarios1.php'],
					['Clientes','programas/clientes/inicioClientes.php'],
					['Proveedores','programas/proveedores/inicioProveedores.php'],
					['Vehiculos','navegador_vehiculos.php'],*/
            
					['Inventarios',0,
						/*['Registro de Almacenes','navegador_almacenes.php'],
						['Registro de Materiales','navegador_material.php'],
						['Registro de Tipos de Materiales','navegador_tiposmaterial.php'],
						['Registro de Tipos de Ingreso','navegador_tiposingreso.php'],
						['Registro de Tipos de Salida','navegador_tipossalida.php'],
						['Registro de Precios','navegador_precios.php'],*/
						['Registro de Costos por Mes (Promedio Ponderado) y Almacen', 'navegadorCostosMesAlmacen.php'],
						['Cargado de Costos Iniciales PEPS/UEPS', 'navegadorCostosMesAlmacenPUEPS.php'],
						
						['Calcular Costos Promedio', 'navegadorCalcularCostos.php'],
						['Calcular Costos PEPS', 'navegadorCalcularCostosPEPS.php'],
						['Calcular Costos UEPS', 'navegadorCalcularCostosUEPS.php'],
						
					],
					['Territorios','navegador_territorios.php'],
		],
	 ];
	 

var TREE_ITEMS_ALMREG = [


	 	['Modulo de Almacenes',0,
			 		['Ordenes de Compra',0,
						['Registro de O.C.','navegador_ordenCompra.php'],
						['Registro de O.C. de Terceros','registrarOCTerceros.php'],
						['Generar OC a traves de Ingresos','navegadorIngresosOC.php'],
						['Registro de Pagos','navegador_pagos.php'],
					],
					['Cobranzas',0,
						['Listado de Cobranzas','navegadorCobranzas.php'],
					],

			 		['Ingresos',0,
						//['Ingreso de Materiales en Transito','navegador_ingresomaterialapoyotransito.php'],
						['Ingreso de Materiales','navegador_ingresomateriales.php'],
						['Liquidacion de Ingresos','navegadorLiquidacionIngresos.php'],
					],

			 		['Salidas',0,
						['Listado de Traspasos','navegador_salidamateriales.php'],
						['Listado de Ventas','navegadorVentas.php'],
					],
			 		['Configuracion',0,
						['Cambiar cotizacion Dolar','navegadorDolar.php'],
					],
			 		['Reportes',0,
						['Kardex de Movimiento','rpt_op_inv_kardex.php'],
						['Existencias','rpt_op_inv_existencias.php'],
						['Ingresos','rpt_op_inv_ingresos.php'],
						['Salidas','rpt_op_inv_salidas.php'],
						['Precios','rptPrecios.php'],
						['OC por Pagar','rptOCPagar.php'],
						
						['Costos',0,
							['Kardex de Movimiento Precio Promedio','rptOpKardexCostos.php'],
							['Kardex de Movimiento PEPS','rptOpKardexCostosPEPS.php'],
							['Kardex de Movimiento UEPS','rptOpKardexCostosUEPS.php'],
							['Existencias','rptOpExistenciasCostos.php'],
						],
						['Ventas',0,
							['Ventas x Documento','rptOpVentasDocumento.php'],
							['Kardex x Cliente','rptOpKardexCliente.php'],

						],
						['Cobranzas',0,
							['Cobranzas','rptOpCobranzas.php'],
							['Cuentas x Cobrar','rptOpCuentasCobrar.php'],
						],

					],
		],
	 ];