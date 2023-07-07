<?php

function precioCalculadoParaFacturacion($enlaceCon,$codMaterial,$codigoCiudadGlobal){
	//require("conexionmysqli.php");
	$fechaActual=date("Y-m-d");

	$globalAdmin=$_COOKIE["global_admin_cargo"];

	$fechaCompleta=date("Y-m-d");
	$horaCompleta=date("H:m:i");

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

	/**************** Iniciamos la revision de las oferats *******************/
	/*************************************************************************/
	$codigoOferta=0;
	$nombreOferta=0;
	$descuentoOferta=0;

	$sqlOferta="SELECT t.codigo, t.nombre, IFNULL((select tp.porcentaje_material from tipos_precio_productos tp where tp.cod_tipoprecio=t.codigo and tp.cod_material='$codMaterial'), t.abreviatura) AS abreviatura from tipos_precio t where '$fechaCompleta $horaCompleta' between t.desde and t.hasta
	 and (SELECT td.cod_dia from tipos_precio_dias td where td.cod_tipoprecio=t.codigo and td.cod_dia=DAYOFWEEK('$fechaCompleta'))
	and t.estado=1 and t.cod_estadodescuento=3 and $codigoCiudadGlobal in (SELECT tc.cod_ciudad from tipos_precio_ciudad tc where tc.cod_tipoprecio=t.codigo) and $codMaterial in (SELECT tpp.cod_material from tipos_precio_productos tpp where tpp.cod_tipoprecio=t.codigo);";
	
	//echo $sqlOferta;
	
	$respOferta=mysqli_query($enlaceCon, $sqlOferta);
	while($datOferta=mysqli_fetch_array($respOferta)){
		$codigoOferta=$datOferta[0];
		$nombreOferta=$datOferta[1];
		$descuentoOfertaPorcentaje=$datOferta[2];
		$descuentoOfertaPorcentaje=round($descuentoOfertaPorcentaje,2);

		$descuentoOfertaBs=$precioProducto*($descuentoOfertaPorcentaje/100);
	}
	/*************************************************************************/
	/*********************** Fin Revision de las ofertas ********************/
	$txtValidacionPrecioCero="";
	if($cadRespuesta>0){
		$txtValidacionPrecioCero="readonly='true'";
	}else{
		$txtValidacionPrecioCero="onkeyup='return false;' onkeydown='return false;' onkeypress='return false;' required";
	}

	$precioNumero=$cadRespuesta;
	$descuentoBs=0;
	$descuentoPorcentaje=0;
	$nombrePrecioAplicar="";
	if($codigoOferta>0 && $descuentoOfertaPorcentaje>0){
		$descuentoBs=$descuentoOfertaBs;
		$descuentoPorcentaje=$descuentoOfertaPorcentaje;
		$nombrePrecioAplicar=$nombreOferta;
	}else{
		$descuentoBs=$descuentoPrecioMonto;
		$descuentoPorcentaje=$descuentoAplicarCasoX;
		$nombrePrecioAplicar="";
	}
	$arrayPrecios=[$precioNumero,$txtValidacionPrecioCero,$descuentoBs,$descuentoPorcentaje,$nombrePrecioAplicar];

	return $arrayPrecios;

}

?>