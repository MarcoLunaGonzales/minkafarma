<?php
require("../../conexionmysqli.inc");
require("../../estilos_almacenes.inc");

$globalAgencia=$_COOKIE["global_agencia"];
$globalAlmacen=$_COOKIE["global_almacen"];
?>

<h1>Clientes</h1>

<div class='divBotones'>
  <input class='boton' type='button' value='Adicionar' onclick='javascript:frmAdicionar();'>
  <input class='boton' type='button' value='Editar' onclick='javascript:frmModificar();'>
  <input class='boton2' type='button' value='Eliminar' onclick='javascript:frmEliminar();'>
</div>

<center>
  <table class='texto'>
    <tr>
      <th>&nbsp;</th>
      <th>Cliente</th>
      <th>NIT</th>
      <th>CI</th>
      <th>Email</th>
      <th>Telefono</th>
      <th>Direccion</th>
      <th>Ciudad</th>
      <th>Acciones</th>
    </tr>
    <?php
    $consulta="
        SELECT c.cod_cliente, CONCAT(c.nombre_cliente, ' ', c.paterno) as nombre_cliente, c.nit_cliente, c.ci_cliente, c.email_cliente, c.telf1_cliente, c.dir_cliente, c.cod_area_empresa, a.descripcion
        FROM clientes AS c INNER JOIN ciudades AS a ON c.cod_area_empresa = a.cod_ciudad 
        WHERE c.cod_area_empresa='$globalAgencia' ORDER BY c.nombre_cliente ASC
    ";
    $rs=mysqli_query($enlaceCon,$consulta);
    $cont=0;
    while($reg=mysqli_fetch_array($rs))
    {
        $cont++;
        $codCliente = $reg["cod_cliente"];
        $nomCliente = $reg["nombre_cliente"];
        $nitCliente = $reg["nit_cliente"];
        $ciCliente = $reg["ci_cliente"];
        $emailCliente = $reg["email_cliente"];
        $telefonoCliente = $reg["telf1_cliente"];
        $dirCliente = $reg["dir_cliente"];
        $codArea = $reg["cod_area_empresa"];
        $nomArea = $reg["descripcion"];
        ?>
        <tr>
          <td><input type='checkbox' id='idchk<?php echo $cont; ?>' value='<?php echo $codCliente; ?>' ></td>
          <td><?php echo $nomCliente; ?></td>
          <td><?php echo $nitCliente; ?></td>
          <td><?php echo $ciCliente; ?></td>
          <td><?php echo $emailCliente; ?></td>
          <td><?php echo $telefonoCliente; ?></td>
          <td><?php echo $dirCliente; ?></td>
          <td><?php echo $nomArea; ?></td>
          <td>
            <a href='../../clientePrecio.php?cod_cliente=<?php echo $codCliente; ?>' title='Registrar Precios Clientes' class='text-dark'><i class='material-icons'>description</i></a>
            <a href='../../clienteDocumento.php?cod_cliente=<?php echo $codCliente; ?>' target='_blank' title='Carga de Documentos' class='text-dark'><i class='material-icons'>cloud_upload</i></a>
            <a href='#' title='Carga de Precio Clientes' class='text-primary modal_documento' data-cod_cliente="<?php echo $codCliente; ?>">
              <i class='material-icons'>description</i>
            </a>
            <a href="../../clientePrecioVer.php?cod_cliente=<?php echo $codCliente; ?>" title="Ver Precios Clientes" class="text-info"><i class="material-icons">visibility</i></a>
            <a href='#' title='Clonar Precio Clientes' class='text-warning modal_clonar' data-cod_cliente="<?php echo $codCliente; ?>">
              <i class='material-icons'>file_copy</i>
            </a>
          </td>
        </tr>
        <?php
    }
    ?>
  </table>
  <input type='hidden' id='idtotal' value='<?php echo $cont; ?>' >
</center>

<div class='divBotones'>
  <input class='boton' type='button' value='Adicionar' onclick='javascript:frmAdicionar();'>
  <input class='boton' type='button' value='Editar' onclick='javascript:frmModificar();'>
  <input class='boton2' type='button' value='Eliminar' onclick='javascript:frmEliminar();'>
</div>

<!-- Modal Carga de Datos -->
<div class="modal fade" id="cargarModal" tabindex="-1" aria-labelledby="cargarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cargarModalLabel">Cargar Archivo - Precio Cliente</h5>
            </div>
            <div class="modal-body">
                <input type="hidden" id="cargar_cod_cliente">
                <input type="file" id="cargar_doc" class="form-control">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onClick="$('#cargarModal').modal('hide');">Cancelar</button>
                <button id="cargar_save" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal Carga de Datos -->
<div class="modal fade" id="clonarModal" tabindex="-1" aria-labelledby="clonarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="clonarModalLabel">Copiar - Precio Cliente</h5>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input type="hidden" id="cliente_actual">
                    <label for="cliente_clonado">Clonar de cliente:</label>
                    <select id="cliente_clonado" class="form-control select2">
                        <?php
                        $consulta=" SELECT c.cod_cliente, CONCAT(c.nombre_cliente, ' ', c.paterno) as nombre_cliente
                              FROM clientes AS c INNER JOIN ciudades AS a ON c.cod_area_empresa = a.cod_ciudad 
                              WHERE c.cod_area_empresa='$globalAgencia' ORDER BY c.nombre_cliente ASC
                          ";
                          $rs = mysqli_query($enlaceCon,$consulta);
                          while($row=mysqli_fetch_array($rs))
                          {
                        ?>
                            <option value="<?=$row['cod_cliente']?>"><?=$row['nombre_cliente']?></option>
                        <?php
                          }
                        ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onClick="$('#clonarModal').modal('hide');">Cancelar</button>
                <button id="clonar_save" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</div>
