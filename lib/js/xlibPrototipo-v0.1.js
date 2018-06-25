/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/*----------------- Pruebas JQuery ----------------*/

var venReDim=true;
var venDrag =true;
if($.browser.msie){
    //venReDim=false; venDrag=false;
    //alert("ggg:_"+$.browser.version+"_");
} else {
    venReDim=true; venDrag=true;
    //alert("ggg:_"+$.browser.version+"_");
}

function xtrim(c) {return c.replace(/^\s*|\s*$/g,"");}

RespRtrn = function (resp,msgerr){this.resp=resp;this.msgerr=msgerr;}

DlgEsperar = function(){
  this.x=10;this.y=10;this.w=150;this.h=40;this.msg="<img src='lib/iconos/progreso16rojo.gif' alt='En espera ...' />&nbsp;&nbsp;Cargando ...";
  this.setVisible = function(visible){
    cad="<div class='ui-widget-overlay'></div><div class='pnldlgsombra' style='position: absolute; left: "+this.x+"px; top: "+this.y+"px;'>"+
      "<div class='pnlenespera' style='width: "+(this.w-10)+"px;'>"+this.msg+"</div></div>";
    if(visible) $("#pnldlgenespera").html(cad); else $("#pnldlgenespera").html("");
  };
}
dlgEsp=new DlgEsperar();//dlgEsp.setVisible(true);

function cargarPnl(sel,prog,params) {cargarPnlLjn(sel,sel,prog,params,"","",function(){},function(){});}
function cargarPnlLjn(selEsp,selDest,prog,params,prefijo,sufijo,funTrgAnt,funTrgDes) {
  if(params!="")params=params+"&";params=params+"rnd="+(Math.random()*999999999999999999);
  $(selEsp).html(function() {funTrgAnt();
    $.get(prog, params, function(informacion) {
      $(selEsp).html("");$(selDest).html(""+prefijo+""+informacion+""+sufijo+"");funTrgDes();
    });
    return "<span class='pnlenespera'><img src='lib/iconos/progreso16rojo.gif' alt='En espera ...' />&nbsp;&nbsp;Cargando ...</span>";
  });
}
function cargarPnl2(sel,prog,params) {cargarPnlLjn2(sel,sel,prog,params,"","",function(){},function(){});}
function cargarPnlLjn2(selEsp,selDest,prog,params,prefijo,sufijo,funTrgAnt,funTrgDes) {
  if(params!="")params=params+"&";params=params+"rnd="+(Math.random()*999999999999999999);
  $(selEsp).html(function() {funTrgAnt();
    dlgEsp.setVisible(true);
    $.get(prog, params, function(informacion) {
      dlgEsp.setVisible(false);
      $(selEsp).html("");$(selDest).html(""+prefijo+""+informacion+""+sufijo+"");funTrgDes();
    });
    return "";
  });
}

function dlgSN(idSel,msg,funAnt,funDes) {
  $(idSel).dialog( {
    buttons: {
      'SI': function() {funAnt();$(this).html("");$(this).dialog('close');funDes();},
      'NO': function() {$(this).html("");$(this).dialog('close');}
    },
    title: 'Confirmaci&#243;n', modal: true, height: 200, width: 320, resizable: venReDim, draggable: venDrag
  });
  $(idSel).html(msg);
}
function dlgAC(idSel,tituto,msg,funAnt,funDes) {
  $(idSel).dialog( {
    buttons: {
      'Aceptar': function()  {funAnt();$(this).html("");$(this).dialog('close');funDes();},
      'Cancelar': function() {$(this).html("");$(this).dialog('close');}
    },
    title: tituto, modal: true, height: 250, width: 380, resizable: venReDim, draggable: venDrag
  });
  $(idSel).html(msg);
}
function dlgA(idSel,tituto,msg,funAnt,funDes) {
  $(idSel).dialog( {
    buttons: {
      'Aceptar': function() {funAnt();$(this).html("");$(this).dialog('close');funDes();}
    },
    title: tituto, modal: true, height: 250, width: 380, resizable: venReDim, draggable: venDrag
  });
  $(idSel).html(msg);
}

var dlgHeight02=350;
var dlgWidth02 =500;
function dlgFrmFun(titulo,prgFrm,parsPrgFrm,funValidar,funEjecutar,msgCnfr) {
  $("#pnldlgfrm").dialog( {
    buttons: {
      'Aceptar': function() {
        var resp=funValidar();
        if(resp.resp) {dlgSN(msgCnfr,function(){$("#pnldlgfrm").dialog('close');},funEjecutar);}
        else dlgA("Error",resp.msgerr,function(){},function(){});
      },
      'Cancelar': function() {
        $(this).dialog('close');
      }
    },
    title: titulo, height: dlgHeight02, width: dlgWidth02, modal: true, resizable: venReDim, draggable: venDrag
    //dragStart: function(event,ui) {$("#pnldlgfrm").dialog({height: dlgHeight02});},//necesario para eliminar un BUG del Internet Explorer
    //dragStop: function(event,ui) {$("#pnldlgfrm").dialog({height: dlgWidth02});}  //necesario para eliminar un BUG del Internet Explorer
  });
  cargarPnl("#pnldlgfrm",prgFrm,parsPrgFrm);
}
function dlgArespSvr(tituto,msgErr,prgRespSvr,parsPrgRespSvr,funValidar,funEjecutar) {
  $("#pnldlgArespSvr").dialog( {
    buttons: {
      'Aceptar': function()  {
        var resp=funValidar();
        if(resp.resp) {
          $.get(prgRespSvr, parsPrgRespSvr, function(informacion) {
            informacion=xtrim(informacion);
            if(informacion=="" || informacion=="OK") {
              $("#pnldlgfrm").dialog('close');
              funEjecutar();
            } else dlgA("Error",informacion,function(){},function(){});
          });
        } else dlgA("Error",resp.msgerr,function(){},function(){});
      },
      'Cancelar': function() {$(this).html("");$(this).dialog('close');}
    },
    title: tituto, modal: true, height: 250, width: 380, resizable: venReDim, draggable: venDrag
  });
  $("#pnldlgArespSvr").html(msgErr);
}

function estTblDetalle() {
  $('.tbldetalle tr').removeClass('tblfilaimpar tblfilapar');
  $('.tbldetalle tr:odd').addClass('tblfilaimpar txtpeque');
  $('.tbldetalle tr:even').addClass('tblfilapar txtpeque');
  $(".tbldetalle tr").hover(function(){$(this).addClass("tbldetallefilahover");}, function(){$(this).removeClass("tbldetallefilahover");});
}
function estTblApoyo() {
  $('.tblapoyo tr').removeClass('tblfilaimparapoyo tblfilaparapoyo');
  $('.tblapoyo tr:odd').addClass('tblfilaimparapoyo txtpeque');
  $('.tblapoyo tr:even').addClass('tblfilaparapoyo txtpeque');
}
function estTblTxtfield() {
  $("input:text").hover( function() { $(this).addClass("txtfieldseleccionable"); });
  $("input:text").mouseout( function() { $(this).removeClass("txtfieldseleccionable"); });
  $("input:text").keypress( function() { $(this).addClass("txtfieldmodificado"); });
}

function recargarPagina() {
  $('.pnltmp00').html(function() {
    $.get("prgRegistroVisitaMedica.php", "rnd="+(Math.random()*999999999999999999),
      function(informacion){
        $('.pnltmp00').html(informacion);
      }
    );
    return "<div class='pnlenespera'><img src='../../lib/iconos/progreso16rojo.gif' alt='En espera ...' />&nbsp;&nbsp;Cargando ...</div>";
  });
  $('.tbldetalle tr:odd').addClass('tblfilaimpar txtpeque');
  $('.tbldetalle tr:even').addClass('tblfilapar txtpeque');
  $('.tbldetalle').addClass('tblcabecera');
}

/*proceso inicial*/
$(document).ready(function() {
  //
  //cargarPnl2("#pnltmp00men","prgRecorrerMenuArbol.php","");
  //
});
/*proceso inicial*/
