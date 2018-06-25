//©Xara Ltd
if(typeof(loc)=="undefined"||loc==""){var loc="";if(document.body&&document.body.innerHTML){var tt=document.body.innerHTML;var ml=tt.match(/["']([^'"]*)menu_almacenes_central.js["']/i);if(ml && ml.length > 1) loc=ml[1];}}

var bd=0
document.write("<style type=\"text/css\">");
document.write("\n<!--\n");
document.write(".menu_almacenes_central_menu {z-index:999;border-color:#000000;border-style:solid;border-width:"+bd+"px 0px "+bd+"px 0px;background-color:#ffffff;position:absolute;left:0px;top:0px;visibility:hidden;}");
document.write(".menu_almacenes_central_plain, a.menu_almacenes_central_plain:link, a.menu_almacenes_central_plain:visited{text-align:left;background-color:#ffffff;color:#0b0b0b;text-decoration:none;border-color:#000000;border-style:solid;border-width:0px "+bd+"px 0px "+bd+"px;padding:2px 0px 2px 0px;cursor:hand;display:block;font-size:8pt;font-family:Verdana, Arial, Helvetica, sans-serif;font-weight:bold;}");
document.write("a.menu_almacenes_central_plain:hover, a.menu_almacenes_central_plain:active{background-color:#000000;color:#ffffff;text-decoration:none;border-color:#000000;border-style:solid;border-width:0px "+bd+"px 0px "+bd+"px;padding:2px 0px 2px 0px;cursor:hand;display:block;font-size:8pt;font-family:Verdana, Arial, Helvetica, sans-serif;font-weight:bold;}");
document.write("a.menu_almacenes_central_l:link, a.menu_almacenes_central_l:visited{text-align:left;background:#ffffff url("+loc+"menu_almacenes_central_l.gif) no-repeat right;color:#0b0b0b;text-decoration:none;border-color:#000000;border-style:solid;border-width:0px "+bd+"px 0px "+bd+"px;padding:2px 0px 2px 0px;cursor:hand;display:block;font-size:8pt;font-family:Verdana, Arial, Helvetica, sans-serif;font-weight:bold;}");
document.write("a.menu_almacenes_central_l:hover, a.menu_almacenes_central_l:active{background:#000000 url("+loc+"menu_almacenes_central_l2.gif) no-repeat right;color: #ffffff;text-decoration:none;border-color:#000000;border-style:solid;border-width:0px "+bd+"px 0px "+bd+"px;padding:2px 0px 2px 0px;cursor:hand;display:block;font-size:8pt;font-family:Verdana, Arial, Helvetica, sans-serif;font-weight:bold;}");
document.write("\n-->\n");
document.write("</style>");

var fc=0xffffff;
var bc=0x000000;
if(typeof(frames)=="undefined"){var frames=0;}

startMainMenu("",0,0,2,0,0)
mainMenuItem("menu_almacenes_central_b1",".gif",21,127,"javascript:;","","Ingresos",2,2,"menu_almacenes_central_plain");
mainMenuItem("menu_almacenes_central_b2",".gif",21,127,"javascript:;","","Salidas",2,2,"menu_almacenes_central_plain");
mainMenuItem("menu_almacenes_central_b3",".gif",21,127,"javascript:;","","Reportes",2,2,"menu_almacenes_central_plain");
endMainMenu("",0,0);

startSubmenu("menu_almacenes_central_b3","menu_almacenes_central_menu",250);
submenuItem("Kardex",loc+"../"+"rpt_op_inv_kardex.php","","menu_almacenes_central_plain");
submenuItem("Existencias",loc+"../"+"rpt_op_inv_existencias.php","","menu_almacenes_central_plain");
submenuItem("Ingresos",loc+"../"+"rpt_op_inv_ingresos.php","","menu_almacenes_central_plain");
submenuItem("Salidas",loc+"../"+"rpt_op_inv_salidas.php","","menu_almacenes_central_plain");
submenuItem("Observaciones Kardex y Existencias",loc+"../"+"rpt_op_inv_obs_kardexexistencias.php","","menu_almacenes_central_plain");
endSubmenu("menu_almacenes_central_b3");

startSubmenu("menu_almacenes_central_b2_2","menu_almacenes_central_menu",116);
submenuItem("Búsqueda",loc+"../"+"navegador_salidabusqueda.php?grupo_salida=2","","menu_almacenes_central_plain");
submenuItem("Listado General",loc+"../"+"navegador_salidamateriales.php","","menu_almacenes_central_plain");
endSubmenu("menu_almacenes_central_b2_2");

startSubmenu("menu_almacenes_central_b2_1","menu_almacenes_central_menu",116);
submenuItem("Búsqueda",loc+"../"+"navegador_salidabusqueda.php?grupo_salida=1","","menu_almacenes_central_plain");
submenuItem("Listado General",loc+"../"+"navegador_salidamuestras.php","","menu_almacenes_central_plain");
endSubmenu("menu_almacenes_central_b2_1");

startSubmenu("menu_almacenes_central_b2","menu_almacenes_central_menu",216);
mainMenuItem("menu_almacenes_central_b2_1","Salida de Muestras",0,0,"javascript:;","","",1,1,"menu_almacenes_central_l");
mainMenuItem("menu_almacenes_central_b2_2","Salida de Material de Apoyo",0,0,"javascript:;","","",1,1,"menu_almacenes_central_l");
submenuItem("Salidas para ciclos enteros",loc+"../"+"navegador_salidaciclosenteros.php","","menu_almacenes_central_plain");
endSubmenu("menu_almacenes_central_b2");

startSubmenu("menu_almacenes_central_b1_2","menu_almacenes_central_menu",116);
submenuItem("Búsqueda",loc+"../"+"navegador_ingresobusqueda.php?grupo_ingreso=2","","menu_almacenes_central_plain");
submenuItem("Listado General",loc+"../"+"navegador_ingresomateriales.php","","menu_almacenes_central_plain");
endSubmenu("menu_almacenes_central_b1_2");

startSubmenu("menu_almacenes_central_b1_1","menu_almacenes_central_menu",116);
submenuItem("Búsqueda",loc+"../"+"navegador_ingresobusqueda.php?grupo_ingreso=1","","menu_almacenes_central_plain");
submenuItem("Listado General",loc+"../"+"navegador_ingresomuestras.php","","menu_almacenes_central_plain");
endSubmenu("menu_almacenes_central_b1_1");

startSubmenu("menu_almacenes_central_b1","menu_almacenes_central_menu",305);
mainMenuItem("menu_almacenes_central_b1_1","Ingreso de Muestras",0,0,"javascript:;","","",1,1,"menu_almacenes_central_l");
mainMenuItem("menu_almacenes_central_b1_2","Ingreso de Material de Apoyo",0,0,"javascript:;","","",1,1,"menu_almacenes_central_l");
submenuItem("Ingreso de Material de Apoyo en Tránsito",loc+"../"+"navegador_ingresomaterialapoyotransito.php","","menu_almacenes_central_plain");
endSubmenu("menu_almacenes_central_b1");

loc="";
