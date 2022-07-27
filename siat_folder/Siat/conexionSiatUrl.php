<?php
namespace SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat;
class conexionSiatUrl 
{
	//pruebas
	const endpoint 	= 'https://pilotosiatservicios.impuestos.gob.bo/v2/ServicioFacturacionComputarizada';
	const wsdl 		= 'https://pilotosiatservicios.impuestos.gob.bo/v2/ServicioFacturacionComputarizada?wsdl';
	const wsdlSincronizacion = 'https://pilotosiatservicios.impuestos.gob.bo/v2/FacturacionSincronizacion?wsdl';
	const wsdlCodigo='https://pilotosiatservicios.impuestos.gob.bo/v2/FacturacionCodigos?wsdl';
	const wsdlFacturacionElectronica='https://pilotosiatservicios.impuestos.gob.bo/v2/ServicioFacturacionElectronica?wsdl';
	const wsdlOperaciones = 'https://pilotosiatservicios.impuestos.gob.bo/v2/FacturacionOperaciones?wsdl';
	const wsdlCompraVenta='https://pilotosiatservicios.impuestos.gob.bo/v2/ServicioFacturacionCompraVenta?wsdl';

	//Oficial
	// const endpoint 	= 'https://siatrest.impuestos.gob.bo/v2/ServicioFacturacionComputarizada';
	// const wsdl 		= 'https://siatrest.impuestos.gob.bo/v2/ServicioFacturacionComputarizada?wsdl';
	// const wsdlSincronizacion = 'https://siatrest.impuestos.gob.bo/v2/FacturacionSincronizacion?wsdl';
	// const wsdlCodigo='https://siatrest.impuestos.gob.bo/v2/FacturacionCodigos?wsdl';
	// const wsdlFacturacionElectronica='https://siatrest.impuestos.gob.bo/v2/ServicioFacturacionElectronica?wsdl';
	// const wsdlOperaciones = 'https://siatrest.impuestos.gob.bo/v2/FacturacionOperaciones?wsdl';
	// const wsdlCompraVenta='https://siatrest.impuestos.gob.bo/v2/ServicioFacturacionCompraVenta?wsdl';

	// const AMBIENTE_PRODUCCION = 1;
	// const AMBIENTE_PRUEBAS = 2;
	const AMBIENTE_ACTUAL=2;//VARIABLE QUE SE TOMA EN CUENTA PARA EL AMBIENTE DEL SISTEMA
}