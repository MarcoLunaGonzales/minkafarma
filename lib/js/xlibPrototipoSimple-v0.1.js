/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/*----------------- Pruebas JQuery ----------------*/

function xtrim(c) {return c.replace(/^\s*|\s*$/g,"");}

/* procedimientos ajax */
function prvt_getXmlHttp()
   {var xmlhttp=false;
    try{xmlhttp=new ActiveXObject("Msxml2.XMLHTTP");
       }
    catch(e)
       {try{xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
           }
        catch(e)
           {xmlhttp=false;
           }
       }
    if (!xmlhttp && typeof XMLHttpRequest!="undefined")
       {xmlhttp=new XMLHttpRequest();
       }
    return xmlhttp;
   }
function pnlAjax(selDes,prog,params)//selDes='pnlRem', prog='programa.jsp', params='p1=v1&p2=v2&...'
   {var ajax=prvt_getXmlHttp();
    if(params!="") params=params+"&";
    params=params+"rnd="+(Math.random()*999999999999999999);
    var url=prog+"?"+params;
    ajax.open("GET",url,true);
    ajax.onreadystatechange=function()
       {if(ajax.readyState==1)
           {}
        else if(ajax.readyState==4)
           {if(ajax.status==200)
               {var tagDes=document.getElementById(selDes);
                tagDes.innerHTML=ajax.responseText;
               }
           }
       }
    ajax.send(null);
   }
function dlgAjax(selDes,prog,params)//selDes='pnldlg', prog='programa.jsp', params='p1=v1&p2=v2&...'
   {var ajax=prvt_getXmlHttp();
    if(params!="") params=params+"&";
    params=params+"rnd="+(Math.random()*999999999999999999);
    var url=prog+"?"+params;
    ajax.open("GET",url,true);
    ajax.onreadystatechange=function()
       {if(ajax.readyState==1)
           {}
        else if(ajax.readyState==4)
           {if(ajax.status==200)
               {var tagDes=document.getElementById(selDes);
                tagDes.innerHTML=ajax.responseText;
               }
           }
       }
    ajax.send(null);
   }
function alertAjax(prog,params,fun)//prog='programa.jsp', params='p1=v1&p2=v2&...'
   {var ajax=prvt_getXmlHttp();
    if(params!="") params=params+"&";
    params=params+"rnd="+(Math.random()*999999999999999999);
    var url=prog+"?"+params;
    ajax.open("GET",url,true);
    ajax.onreadystatechange=function()
       {if(ajax.readyState==1)
           {}
        else if(ajax.readyState==4)
           {if(ajax.status==200)
               {//var tagDes=document.getElementById(selDes); tagDes.innerHTML=ajax.responseText;
                fun();
                alert(ajax.responseText);
               }
           }
       }
    ajax.send(null);
   }


