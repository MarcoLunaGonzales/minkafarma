<?php

function precioCalculadoParaFacturacion($enlaceCon,$codMaterial,$codigoCiudadGlobal,$codCliente){
	require_once("funciones.php");
	
	$fechaActual=date("Y-m-d");

	$globalAdmin=$_COOKIE["global_admin_cargo"];
	$globalAlmacen=$_COOKIE["global_almacen"];

	$fechaCompleta=date("Y-m-d");
	$horaCompleta=date("H:m:i");

	$sqlMedicamento="SELECT m.cod_tipo_material from material_apoyo m where m.codigo_material='$codMaterial'";
	$respMedicamento=mysqli_query($enlaceCon, $sqlMedicamento);
	$medicamentoProducto=2;
	if($datMedicamento=mysqli_fetch_array($respMedicamento)){
		$medicamentoProducto=$datMedicamento[0];
	}

	$cadRespuesta="";
	$consulta="select p.`precio`, p.descuento_unitario from precios p where p.`codigo_material`='$codMaterial' and p.`cod_precio`=1 and 
	    p.cod_ciudad='$codigoCiudadGlobal' ";
	$rs=mysqli_query($enlaceCon,$consulta);
	$registro=mysqli_fetch_array($rs);
	$cadRespuesta=$registro[0];
	$descuentoUnitarioProducto=$registro[1];

	if($cadRespuesta==""){   
		$cadRespuesta=0;
	}
	$precioProducto=$cadRespuesta;
	$cadRespuesta=redondear2($cadRespuesta);
	if($descuentoUnitarioProducto==""){
		$descuentoUnitarioProducto=0;
	}

	$descuentoAplicarCasoX=0;
	/*Esto se aplica en los casos tipo no Farmacoss, no se permite el cambio manual del descuento*/
	$descuentoPrecio=0;
	/*FIN CASO 1 NO TIPO FARMACOSS*/

	/***********************************************************/
	/***********************************************************/
	/*Aqui sacamos la configuracion si estan habilitados los descuentos en precio y de Mayoristas caso tipo Farmacoss*/
	/***************** Si la Bandera es 1 procedemos *****************/
	$banderaPreciosDescuento=obtenerValorConfiguracion($enlaceCon,52);
	$maximoDescuentoPrecio=0;
	$descuentoMayorista=0;
	if($banderaPreciosDescuento==1){
		$maximoDescuentoPrecio=obtenerValorConfiguracion($enlaceCon,53);
		if($maximoDescuentoPrecio < 0 || $maximoDescuentoPrecio==""){
			$maximoDescuentoPrecio=0;
		}
		$descuentoProductoGeneral=$descuentoUnitarioProducto*($maximoDescuentoPrecio/100);

		$descuentoMayorista=precioMayoristaSucursal($enlaceCon, $codigoCiudadGlobal);
		$descuentoAplicarCasoX=$descuentoProductoGeneral+$descuentoMayorista;
		$descuentoAplicarCasoX=redondear2($descuentoAplicarCasoX);

		$descuentoPrecio=$descuentoAplicarCasoX;
	}
	/****************** Fin caso tipo Farmacoss ****************/
	/***********************************************************/
	/***********************************************************/
	/***********************************************************/
	$indiceConversion=0;
	$descuentoPrecioMonto=0;
	if($descuentoPrecio>0){
		$indiceConversion=($descuentoPrecio/100);
		$descuentoPrecioMonto=round($cadRespuesta*($indiceConversion),2);
		//$cadRespuesta=$cadRespuesta-($cadRespuesta*($indiceConversion));
	}


	/**************** Iniciamos la revision de las ofertas *******************/
	/*************************************************************************/
	$codigoOferta=0;
	$nombreOferta="";
	$descuentoOferta=0;
	
	$sqlOferta="";
	if($medicamentoProducto==1){
		//Verificamos la oferta general CUANDO ES MEDICAMENTO
		$sqlOfertaPre="SELECT t.codigo, t.nombre, t.abreviatura, t.oferta_stock_limitado, DATE_FORMAT(t.desde, '%Y-%m-%d'), DATE_FORMAT(t.hasta, '%Y-%m-%d') from tipos_precio t where '$fechaCompleta $horaCompleta' between t.desde and t.hasta and (SELECT td.cod_dia from tipos_precio_dias td where td.cod_tipoprecio=t.codigo and td.cod_dia=DAYOFWEEK('$fechaCompleta')) and t.estado=1 and t.cod_estadodescuento=3 and $codigoCiudadGlobal in (SELECT tc.cod_ciudad from tipos_precio_ciudad tc where tc.cod_tipoprecio=t.codigo) and t.por_linea=2;";
		$respOfertaPre=mysqli_query($enlaceCon, $sqlOfertaPre);
		$banderaOfertaGeneralMedicamento=mysqli_num_rows($respOfertaPre);
		if($banderaOfertaGeneralMedicamento==0){
			/************  Cuando es Medicamento pero no hay OFERTA GENERAL  *************/
			$sqlOferta="SELECT t.codigo, t.nombre, IFNULL(tp.porcentaje_material, t.abreviatura) AS abreviatura, t.oferta_stock_limitado, DATE_FORMAT(t.desde, '%Y-%m-%d'), DATE_FORMAT(t.hasta, '%Y-%m-%d'), IFNULL(tp.stock_oferta, 0) AS stockoferta from tipos_precio t, tipos_precio_productos tp where t.codigo=tp.cod_tipoprecio and '$fechaCompleta $horaCompleta' between t.desde and t.hasta and (SELECT td.cod_dia from tipos_precio_dias td where td.cod_tipoprecio=t.codigo and td.cod_dia=DAYOFWEEK('$fechaCompleta')) and t.estado=1 and t.cod_estadodescuento=3 and $codigoCiudadGlobal in (SELECT tc.cod_ciudad from tipos_precio_ciudad tc where tc.cod_tipoprecio=t.codigo) and tp.cod_material='$codMaterial';";
		}elseif ($banderaOfertaGeneralMedicamento>0) {
			/************  Cuando es Medicamento CON OFERTA GENERAL  *************/
			$sqlOferta="SELECT t.codigo, t.nombre, t.abreviatura, t.oferta_stock_limitado, DATE_FORMAT(t.desde, '%Y-%m-%d'), DATE_FORMAT(t.hasta, '%Y-%m-%d') from tipos_precio t where '$fechaCompleta $horaCompleta' between t.desde and t.hasta and (SELECT td.cod_dia from tipos_precio_dias td where td.cod_tipoprecio=t.codigo and td.cod_dia=DAYOFWEEK('$fechaCompleta')) and t.estado=1 and t.cod_estadodescuento=3 and $codigoCiudadGlobal in (SELECT tc.cod_ciudad from tipos_precio_ciudad tc where tc.cod_tipoprecio=t.codigo) and t.por_linea=2;";
		}
	}else{		
		//Verificamos la oferta general CUANDO NO ES MEDICAMENTO *** PRODUCTO MARKET
		$sqlOfertaPre="SELECT t.codigo, t.nombre, t.abreviatura, t.oferta_stock_limitado, DATE_FORMAT(t.desde, '%Y-%m-%d'), DATE_FORMAT(t.hasta, '%Y-%m-%d') from tipos_precio t where '$fechaCompleta $horaCompleta' between t.desde and t.hasta and (SELECT td.cod_dia from tipos_precio_dias td where td.cod_tipoprecio=t.codigo and td.cod_dia=DAYOFWEEK('$fechaCompleta')) and t.estado=1 and t.cod_estadodescuento=3 and $codigoCiudadGlobal in (SELECT tc.cod_ciudad from tipos_precio_ciudad tc where tc.cod_tipoprecio=t.codigo) and t.por_linea=3;";
		$respOfertaPre=mysqli_query($enlaceCon, $sqlOfertaPre);
		$banderaOfertaGeneralNOMedicamento=mysqli_num_rows($respOfertaPre);
		if($banderaOfertaGeneralNOMedicamento==0){
			/************  Cuando NO es Medicamento pero no hay OFERTA GENERAL  *************/
			$sqlOferta="SELECT t.codigo, t.nombre, IFNULL(tp.porcentaje_material, t.abreviatura) AS abreviatura, t.oferta_stock_limitado, DATE_FORMAT(t.desde, '%Y-%m-%d'), DATE_FORMAT(t.hasta, '%Y-%m-%d'), IFNULL(tp.stock_oferta, 0) AS stockoferta from tipos_precio t, tipos_precio_productos tp where t.codigo=tp.cod_tipoprecio and '$fechaCompleta $horaCompleta' between t.desde and t.hasta and (SELECT td.cod_dia from tipos_precio_dias td where td.cod_tipoprecio=t.codigo and td.cod_dia=DAYOFWEEK('$fechaCompleta')) and t.estado=1 and t.cod_estadodescuento=3 and $codigoCiudadGlobal in (SELECT tc.cod_ciudad from tipos_precio_ciudad tc where tc.cod_tipoprecio=t.codigo) and tp.cod_material='$codMaterial';";
		}elseif ($banderaOfertaGeneralNOMedicamento>0) {
			/************  Cuando NO es Medicamento CON OFERTA GENERAL  *************/
			$sqlOferta="SELECT t.codigo, t.nombre, t.abreviatura, t.oferta_stock_limitado, DATE_FORMAT(t.desde, '%Y-%m-%d'), DATE_FORMAT(t.hasta, '%Y-%m-%d') from tipos_precio t where '$fechaCompleta $horaCompleta' between t.desde and t.hasta and (SELECT td.cod_dia from tipos_precio_dias td where td.cod_tipoprecio=t.codigo and td.cod_dia=DAYOFWEEK('$fechaCompleta')) and t.estado=1 and t.cod_estadodescuento=3 and $codigoCiudadGlobal in (SELECT tc.cod_ciudad from tipos_precio_ciudad tc where tc.cod_tipoprecio=t.codigo) and t.por_linea=3;";
		}		

		/************  Cuando NO ES MEDICAMENTO  *************/
		/*$sqlOferta="SELECT t.codigo, t.nombre, IFNULL(tp.porcentaje_material, t.abreviatura) AS abreviatura, t.oferta_stock_limitado, DATE_FORMAT(t.desde, '%Y-%m-%d'), DATE_FORMAT(t.hasta, '%Y-%m-%d'), IFNULL(tp.stock_oferta, 0) AS stockoferta from tipos_precio t, tipos_precio_productos tp where t.codigo=tp.cod_tipoprecio and '$fechaCompleta $horaCompleta' between t.desde and t.hasta and (SELECT td.cod_dia from tipos_precio_dias td where td.cod_tipoprecio=t.codigo and td.cod_dia=DAYOFWEEK('$fechaCompleta')) and t.estado=1 and t.cod_estadodescuento=3 and $codigoCiudadGlobal in (SELECT tc.cod_ciudad from tipos_precio_ciudad tc where tc.cod_tipoprecio=t.codigo) and tp.cod_material='$codMaterial';";*/
	}	
	//echo $sqlOferta;
	$respOferta=mysqli_query($enlaceCon, $sqlOferta);
	while($datOferta=mysqli_fetch_array($respOferta)){
		//echo "entro oferta";
		$codigoOferta=$datOferta[0];
		$nombreOferta=$datOferta[1];
		$descuentoOfertaPorcentaje=$datOferta[2];
		$descuentoOfertaPorcentaje=round($descuentoOfertaPorcentaje,2);
		$descuentoOfertaBs=$precioProducto*($descuentoOfertaPorcentaje/100);
		$ofertaStockLimitado=$datOferta[3];
		$fechaInicioOferta=$datOferta[4];
		$fechaFinalOferta=$datOferta[5];
		$stockProductoOferta=$datOferta[6];

		/*Si la oferta es de stock limitado validamos el stock y las salidas*/
		$salidasProductoOferta=0;
		if($ofertaStockLimitado==1){
			if($stockProductoOferta>0){
				$salidasProductoOferta=salidasItemPeriodo($enlaceCon, $globalAlmacen, $codMaterial, $fechaInicioOferta, $fechaFinalOferta);
				$ingresosProductoOferta=ingresosItemPeriodoxCompra($enlaceCon, $globalAlmacen, $codMaterial, $fechaInicioOferta, $fechaFinalOferta);

				if(  ($salidasProductoOferta>=$stockProductoOferta) || $ingresosProductoOferta>0 ){
					$descuentoOfertaPorcentaje=0;
					$descuentoOfertaBs=0;
					$nombreOferta=$nombreOferta."(expirada)";
				}else{
					$nombreOferta=$nombreOferta."(vigente)";
				}
			}elseif($stockProductoOferta<=0) {
				$descuentoOfertaPorcentaje=0;
				$descuentoOfertaBs=0;
				$nombreOferta=$nombreOferta."(error en config.)";
			}
		}
	}
	/*************************************************************************/
	/*********************** Fin Revision de las ofertas ********************/
	$txtValidacionPrecioCero="";
	if($cadRespuesta>0){
		$txtValidacionPrecioCero="readonly='true'";
	}else{
		$txtValidacionPrecioCero="onkeyup='return false;' onkeydown='return false;' onkeypress='return false;' required";
	}

	
	/*********  VERIFICAMOS PRECIOS CLIENTE  **********/
	$sqlPreciosCliente="SELECT cpd.precio_base, cpd.porcentaje_aplicado, cpd.precio_aplicado, cpd.precio_producto FROM clientes_precios cp LEFT JOIN clientes_preciosdetalle cpd ON cpd.cod_clienteprecio = cp.codigo 
		WHERE cpd.cod_producto = '$codMaterial' AND cp.cod_cliente = '$codCliente' LIMIT 1";
	$respPreciosCliente=mysqli_query($enlaceCon, $sqlPreciosCliente);
	//echo $sqlPreciosCliente;
	$banderaPrecioCliente=0;
	if($respPreciosCliente) {
    	$datosCliente = mysqli_fetch_assoc($respPreciosCliente);  
    	if ($datosCliente) {
        	//echo "entro cliente";
        	$porcentajeAplicadoCliente = $datosCliente['porcentaje_aplicado'];
        	$precioAplicadoCliente= $datosCliente['precio_aplicado'];
        	$precio_base=$datosCliente['precio_base'];
        	$precio_producto=$datosCliente['precio_producto'];
        	//Aplicamos el porcentaje al precio Base del Producto
        	$indiceConversionCliente=($porcentajeAplicadoCliente/100);
			$descuentoAplicadoClienteBs=round($precioProducto*($indiceConversionCliente),2);
			$nombrePrecioAplicarCliente="Precio Cliente";
			$banderaPrecioCliente=1;
        }
    } 
	/*********  FIN VERIFICAMOS PRECIOS CLIENTE  **********/

	
	$precioNumero=$cadRespuesta;
	$descuentoBs=0;
	$descuentoPorcentaje=0;
	$nombrePrecioAplicar="";
	if($codigoOferta>0 && $descuentoOfertaPorcentaje>0){
		$descuentoBs=$descuentoOfertaBs;
		$descuentoPorcentaje=$descuentoOfertaPorcentaje;
		$nombrePrecioAplicar=$nombreOferta;
	}elseif ($banderaPrecioCliente==1) {
		$descuentoBs=$descuentoAplicadoClienteBs;
		$descuentoPorcentaje=$porcentajeAplicadoCliente;
		$nombrePrecioAplicar=$nombrePrecioAplicarCliente;
	}else{
		$descuentoBs=$descuentoPrecioMonto;
		$descuentoPorcentaje=$descuentoAplicarCasoX;
		$nombrePrecioAplicar="";
	}
	$arrayPrecios=[$precioNumero,$txtValidacionPrecioCero,$descuentoBs,$descuentoPorcentaje,$nombrePrecioAplicar];

	return $arrayPrecios;

}

?>