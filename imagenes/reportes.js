//©Xara Ltd
if(typeof(loc)=="undefined"||loc==""){var loc="";if(document.body&&document.body.innerHTML){var tt=document.body.innerHTML;var ml=tt.match(/["']([^'"]*)reportes.js["']/i);if(ml && ml.length > 1) loc=ml[1];}}

var bd=0
document.write("<style type=\"text/css\">");
document.write("\n<!--\n");
document.write(".reportes_menu {z-index:999;border-color:#000000;border-style:solid;border-width:"+bd+"px 0px "+bd+"px 0px;background-color:#404d5f;position:absolute;left:0px;top:0px;visibility:hidden;}");
document.write(".reportes_plain, a.reportes_plain:link, a.reportes_plain:visited{text-align:left;background-color:#404d5f;color:#ffffff;text-decoration:none;border-color:#000000;border-style:solid;border-width:0px "+bd+"px 0px "+bd+"px;padding:2px 0px 2px 0px;cursor:hand;display:block;font-size:9pt;font-family:Verdana, Arial, Helvetica, sans-serif;font-weight:bold;}");
document.write("a.reportes_plain:hover, a.reportes_plain:active{background-color:#f3f39e;color:#000000;text-decoration:none;border-color:#000000;border-style:solid;border-width:0px "+bd+"px 0px "+bd+"px;padding:2px 0px 2px 0px;cursor:hand;display:block;font-size:9pt;font-family:Verdana, Arial, Helvetica, sans-serif;font-weight:bold;}");
document.write("a.reportes_l:link, a.reportes_l:visited{text-align:left;background:#404d5f url("+loc+"reportes_l.gif) no-repeat right;color:#ffffff;text-decoration:none;border-color:#000000;border-style:solid;border-width:0px "+bd+"px 0px "+bd+"px;padding:2px 0px 2px 0px;cursor:hand;display:block;font-size:9pt;font-family:Verdana, Arial, Helvetica, sans-serif;font-weight:bold;}");
document.write("a.reportes_l:hover, a.reportes_l:active{background:#f3f39e url("+loc+"reportes_l2.gif) no-repeat right;color: #000000;text-decoration:none;border-color:#000000;border-style:solid;border-width:0px "+bd+"px 0px "+bd+"px;padding:2px 0px 2px 0px;cursor:hand;display:block;font-size:9pt;font-family:Verdana, Arial, Helvetica, sans-serif;font-weight:bold;}");
document.write("\n-->\n");
document.write("</style>");

var fc=0x000000;
var bc=0xf3f39e;
if(typeof(frames)=="undefined"){var frames=6;if(frames>0)animate();}

startMainMenu("reportes_left.gif",21,17,2,0,0)
mainMenuItem("reportes_b1",".gif",21,136,loc+"../"+"navegador_gestiones.php","","Gestiones",2,2,"reportes_plain");
mainMenuItem("reportes_b2",".gif",21,136,loc+"../"+"navegador_ciclos.php","","Ciclos",2,2,"reportes_plain");
mainMenuItem("reportes_b3",".gif",21,136,loc+"../"+"grilla_ciudades.php","","Grilla",2,2,"reportes_plain");
mainMenuItem("reportes_b4",".gif",21,136,loc+"../"+"navegador_lineas_visita.php","","Líneas de Visita",2,2,"reportes_plain");
mainMenuItem("reportes_b5",".gif",21,150,loc+"../"+"navegador_prod_especialidad.php","","Productos x Especialidad",2,2,"reportes_plain");
mainMenuItem("reportes_b6",".gif",21,136,"javascript:;","","Parrillas",2,2,"reportes_plain");
mainMenuItem("reportes_b7",".gif",21,136,"javascript:;","","Reportes",2,2,"reportes_plain");
endMainMenu("reportes_right.gif",21,17)

startSubmenu("reportes_b6_2","reportes_menu",149);
submenuItem("Parrilla Promocional",loc+"../"+"navegador_parrillas_ciclos.php","","reportes_plain");
submenuItem("Parrilla Especial",loc+"../"+"navegador_parrillas_especial_ciclos.php","","reportes_plain");
endSubmenu("reportes_b6_2");

startSubmenu("reportes_b6_1","reportes_menu",149);
submenuItem("Parrilla Promocional",loc+"../"+"navegador_parrillas_espe.php","","reportes_plain");
submenuItem("Parrilla Especial",loc+"../"+"navegador_parrilla_especial.php","","reportes_plain");
endSubmenu("reportes_b6_1");

startSubmenu("reportes_b6","reportes_menu",165);
mainMenuItem("reportes_b6_1","Ciclo en Curso",0,0,"javascript:;","","",1,1,"reportes_l");
mainMenuItem("reportes_b6_2","Ciclos Programados",0,0,"javascript:;","","",1,1,"reportes_l");
endSubmenu("reportes_b6");

loc="";
