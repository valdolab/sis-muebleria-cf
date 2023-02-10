<?php
# #eliminar usuario
if ($_POST['action'] == 'eliminarUsuario') {
include "accion/conexion.php";
  if (!empty($_POST['usuario'])) {
    $idusuario = $_POST['usuario'];

    $query_delete2 = mysqli_query($conexion, "DELETE FROM sucursal_usuario WHERE sucursal_idusuario = '$idusuario'");

    $query_select1 = mysqli_query($conexion,"SELECT *  from permiso_usuario WHERE permiso_idusuario = '$idusuario'");
    if (mysqli_num_rows($query_select1) > 0)
    {
      $query_delete3 = mysqli_query($conexion, "DELETE FROM permiso_usuario WHERE permiso_idusuario = '$idusuario'");
    }
    else
    {
      $query_delete3 = 1;
    }
    $query_delete = mysqli_query($conexion, "DELETE FROM usuario WHERE idusuario = '$idusuario'");

    mysqli_close($conexion);
    if ($query_delete and $query_delete2 and $query_delete3) {
      $elimino_usuario = 1;
    }else {
      $elimino_usuario = 0;
    }
    //$elimino_usuario = mysqli_error($conexion);
    echo json_encode($elimino_usuario,JSON_UNESCAPED_UNICODE);
  }
  exit;
}
  # #eliminar documento
  if ($_POST['action'] == 'eliminarDocumento') {
  include "accion/conexion.php";
  if (!empty($_POST['documento'])) {
    $id_documento = $_POST['documento'];

    $query_doc = mysqli_query($conexion, "DELETE FROM documento WHERE iddocumento = $id_documento");

    mysqli_close($conexion);
    if ($query_doc) {
      $elimino_doc = 1;
    }else {
      $elimino_doc = 0;
    }
    echo json_encode($elimino_doc,JSON_UNESCAPED_UNICODE);
  }
  exit;
}
  #eliminar sucursal
  if ($_POST['action'] == 'eliminarSucursal') {
  include "accion/conexion.php";
  if (!empty($_POST['sucursal'])) {
    $id_sucursal = $_POST['sucursal'];

    //primero consultar si tiene algun usuario o documento asignado para luego moverlo a  matriz
    //numbers of users
    $select_findusers = mysqli_query($conexion, "SELECT sucursal_idusuario from sucursal_usuario where sucursal_idsucursales = $id_sucursal");
    $numusers_assigned = mysqli_num_rows($select_findusers);

    //numbers of docs finded
    $select_finddocs = mysqli_query($conexion, "SELECT iddocumento from documento where idsucursal = $id_sucursal");
    $numdocs_assigned = mysqli_num_rows($select_finddocs);
    //then find the ID from matrix
    $select_findMatriz = mysqli_query($conexion, "SELECT idsucursales from sucursales where matriz = 1");
    $id_sucursalmatriz = mysqli_fetch_assoc($select_findMatriz)['idsucursales'];

    if($numdocs_assigned > 0)
    {
      $cambio_docs_ok = 1;
      while($row = mysqli_fetch_assoc($select_finddocs))
      {
        $insert_docs_matrix = mysqli_query($conexion, "UPDATE documento SET idsucursal = $id_sucursalmatriz where iddocumento = $row[iddocumento]");
        if(!$insert_docs_matrix)
        {
          $cambio_docs_ok = 0;
        } 
      }
    }
    else
    {
      $cambio_docs_ok = 1;
    }

    //set users to matrix if num_users > 0
    if($numusers_assigned > 0)
    {
      $flag_changeUsers = 1;
      //actualizar cada usuario de esta sucursal a la sucursal matriz
      while($row = mysqli_fetch_assoc($select_findusers))
      {
        //verificar si el usuario de esta sucursal a borrar no tiene asgnada paralelamente la sucursal matriz, si es así no hacer nada
        $finduserINmatriz = mysqli_query($conexion, "SELECT sucursales.idsucursales from sucursales INNER JOIN sucursal_usuario on sucursal_usuario.sucursal_idsucursales = sucursales.idsucursales where sucursal_usuario.sucursal_idusuario = '$row[sucursal_idusuario]' and sucursales.matriz = 1");
        if(mysqli_num_rows($finduserINmatriz) == 0)
        {
          $insert_users_matriz = mysqli_query($conexion, "INSERT into sucursal_usuario(sucursal_idusuario,sucursal_idsucursales) values ('$row[sucursal_idusuario]',$id_sucursalmatriz)");
          if(!$insert_users_matriz)
          {
            $flag_changeUsers = 0;
          }
        }
      }
      $delete_old_suc = mysqli_query($conexion, "DELETE FROM sucursal_usuario where sucursal_idsucursales = $id_sucursal");
      if(!$delete_old_suc)
          {
            $flag_changeUsers = 0;
          }
    }
    else
    {
      $flag_changeUsers = 1;
    }

    //ahora si borramos la sucursal
    $query_delete_sucursal = mysqli_query($conexion, "DELETE FROM sucursales WHERE idsucursales = $id_sucursal");
    //mysqli_close($conexion);
    //calcular si todo fue bien o no
    if ($query_delete_sucursal)
    {
      $elimino_sucursal = 1;
    }
    else
    {
      $elimino_sucursal = 0;
    }
    if ($elimino_sucursal == 1 and $cambio_docs_ok == 1 and $flag_changeUsers == 1)
    {
      $elimino_sucursal = 1;
    }
    else
    {
      $elimino_sucursal = 0;
    }

    //$elimino_sucursal = mysqli_error($conexion);
    echo json_encode($elimino_sucursal,JSON_UNESCAPED_UNICODE);
  }
  exit;
}

#eliminar cliente
if ($_POST['action'] == 'eliminarCliente') 
{
  include "accion/conexion.php";
  if (!empty($_POST['cliente'])) {
    $id_cliente = $_POST['cliente'];
    $query_delete_cliente = mysqli_query($conexion, "DELETE FROM cliente WHERE idcliente = '$id_cliente'");
    $query_delete_cliente_refs = mysqli_query($conexion, "DELETE FROM referencias_cliente WHERE idcliente = '$id_cliente'");

    mysqli_close($conexion);
    if ($query_delete_cliente and $query_delete_cliente_refs) 
    {
      $elimino_cliente = 1;
    }else {
      $elimino_cliente = 0;
    }
    echo json_encode($elimino_cliente,JSON_UNESCAPED_UNICODE);
  }
  exit;
}

# #eliminar puesto
  if ($_POST['action'] == 'eliminarPuesto') {
  include "accion/conexion.php";
  if (!empty($_POST['puesto'])) {
    $id_puesto = $_POST['puesto'];

    $query_puesto = mysqli_query($conexion, "DELETE FROM puesto WHERE idpuesto = '$id_puesto'");

    mysqli_close($conexion);
    if ($query_puesto) {
      $elimino_puesto = 1;
    }else {
      $elimino_puesto = 0;
    }
    echo json_encode($elimino_puesto,JSON_UNESCAPED_UNICODE);
  }
  exit;
}

# #eliminar zona
  if ($_POST['action'] == 'eliminarZona') {
  include "accion/conexion.php";
  if (!empty($_POST['zona'])) {
    $id_zona = $_POST['zona'];

    $query_zona = mysqli_query($conexion, "DELETE FROM zonas WHERE idzona = '$id_zona'");

    mysqli_close($conexion);
    if ($query_zona) {
      $elimino_zona = 1;
    }else {
      $elimino_zona = 0;
    }
    echo json_encode($elimino_zona,JSON_UNESCAPED_UNICODE);
  }
  exit;
}

# #eliminar zona
  if ($_POST['action'] == 'eliminarSubzona') {
  include "accion/conexion.php";
  if (!empty($_POST['subzona'])) {
    $id_subzona = $_POST['subzona'];

    $query_subzona = mysqli_query($conexion, "DELETE FROM subzonas WHERE idsubzona = '$id_subzona'");

    mysqli_close($conexion);
    if ($query_subzona) {
      $elimino_subzona = 1;
    }else {
      $elimino_subzona = 0;
    }
    echo json_encode($elimino_subzona,JSON_UNESCAPED_UNICODE);
  }
  exit;
}

# #eliminar tipo
if ($_POST['action'] == 'eliminarTipo') 
{
  include "accion/conexion.php";
  if (!empty($_POST['tipo'])) {
    $id_tipo = $_POST['tipo'];

    $query_tipo = mysqli_query($conexion, "DELETE FROM tipo WHERE idtipo = '$id_tipo'");

    mysqli_close($conexion);
    if ($query_tipo) {
      $elimino_tipo= 1;
    }else {
      $elimino_tipo = 0;
    }
    echo json_encode($elimino_tipo,JSON_UNESCAPED_UNICODE);
  }
  exit;
}

# #eliminar tipo venta
if ($_POST['action'] == 'eliminarTipoVenta') 
{
  include "accion/conexion.php";
  if (!empty($_POST['tipo_venta'])) {
    $id_tipo_venta = $_POST['tipo_venta'];

    $query_tipoV = mysqli_query($conexion, "DELETE FROM venta_tipo WHERE idtipo_venta = '$id_tipo_venta'");

    mysqli_close($conexion);
    if ($query_tipoV) {
      $elimino_tipoV= 1;
    }else {
      $elimino_tipoV = 0;
    }
    echo json_encode($elimino_tipoV,JSON_UNESCAPED_UNICODE);
  }
  exit;
}

# #eliminar modalidad de pago
if ($_POST['action'] == 'eliminarModalidadPago') 
{
  include "accion/conexion.php";
  if (!empty($_POST['modalidad_pago'])) {
    $id_modalidad_pago = $_POST['modalidad_pago'];

    $query_ModalidadP = mysqli_query($conexion, "DELETE FROM modalidad_pago WHERE idmodalidad_pago  = '$id_modalidad_pago'");

    mysqli_close($conexion);
    if ($query_ModalidadP) {
      $elimino_ModalidadP= 1;
    }else {
      $elimino_ModalidadP = 0;
    }
    echo json_encode($elimino_ModalidadP,JSON_UNESCAPED_UNICODE);
  }
  exit;
}

if ($_POST['action'] == 'eliminarTipoCompra') 
{
  include "accion/conexion.php";
  if (!empty($_POST['tipo_compra'])) {
    $id_tipo_compra = $_POST['tipo_compra'];

    $query_tipoC = mysqli_query($conexion, "DELETE FROM compra_tipo WHERE idtipo_compra = '$id_tipo_compra'");

    mysqli_close($conexion);
    if ($query_tipoC) {
      $elimino_tipoC= 1;
    }else {
      $elimino_tipoC = 0;
    }
    echo json_encode($elimino_tipoC,JSON_UNESCAPED_UNICODE);
  }
  exit;
}

if ($_POST['action'] == 'eliminarProveedor') 
{
  include "accion/conexion.php";
  if (!empty($_POST['proveedor'])) {
    $id_proveedor = $_POST['proveedor'];

    $query_proveedor = mysqli_query($conexion, "DELETE FROM proveedor WHERE idproveedor = '$id_proveedor'");

    mysqli_close($conexion);
    if ($query_proveedor) {
      $elimino_compra= 1;
    }else {
      $elimino_compra = 0;
    }
    echo json_encode($elimino_compra,JSON_UNESCAPED_UNICODE);
  }
  exit;
}

function deleteAll($dir) {
    foreach(glob($dir . '/*') as $file) {
        if(is_dir($file))
            deleteAll($file);
        else
            unlink($file);
    }
    rmdir($dir);
}

#eliminar cat
  if ($_POST['action'] == 'eliminarCategoria') {
  include "accion/conexion.php";
  if (!empty($_POST['categoria'])) 
  {
    $id_cat = $_POST['categoria'];

    $select_cat = mysqli_query($conexion, "SELECT nombre from categoria where idcategoria = '$id_cat'");
    $nom_cat = mysqli_fetch_assoc($select_cat)['nombre'];
    $estructura = "../img/catalogo_productos/".$nom_cat;
    deleteAll($estructura);

    $query_cat = mysqli_query($conexion, "DELETE FROM categoria WHERE idcategoria = '$id_cat'");
    //$elimino_cat = 1;

    mysqli_close($conexion);
    if ($query_cat) {
      $elimino_cat= 1;
    }else {
      $elimino_cat = 0;
    }
    echo json_encode($elimino_cat,JSON_UNESCAPED_UNICODE);
  }
  exit;
}

#eliminar subcat
  if ($_POST['action'] == 'eliminarSubCat') {
  include "accion/conexion.php";
  if (!empty($_POST['subcategoria'])) 
  {
    $id_subcat = $_POST['subcategoria'];

    $select_subcat = mysqli_query($conexion, "SELECT subcategoria.nombre as subcat, categoria.nombre as cat from subcategoria inner join categoria on categoria.idcategoria = subcategoria.categoria where idsubcategoria = '$id_subcat'");
    $data_subcat = mysqli_fetch_assoc($select_subcat);
    $cat = $data_subcat['cat'];
    $subcat = $data_subcat['subcat'];
    //creamos la ubicacion
    $estructura = "../img/catalogo_productos/".$cat."/".$subcat;
    deleteAll($estructura);

    $query_subcat = mysqli_query($conexion, "DELETE FROM subcategoria WHERE idsubcategoria = '$id_subcat'");
    //$elimino_subcat = 1;

    mysqli_close($conexion);
    if ($query_subcat) {
      $elimino_subcat= 1;
    }else {
      $elimino_subcat = 0;
    }
    echo json_encode($elimino_subcat,JSON_UNESCAPED_UNICODE);
  }
  exit;
}

#eliminar PRODUCTO
  if ($_POST['action'] == 'eliminarProducto') {
  include "accion/conexion.php";
  if (!empty($_POST['producto'])) 
  {
    $id_producto = $_POST['producto'];

    $select_producto = mysqli_query($conexion, "SELECT subcategoria from producto WHERE idproducto = '$id_producto'");
        //aqui vamos a ver si tienen foto o no, para mostrar los iconos acorde
        $datap = mysqli_fetch_assoc($select_producto);
        if($datap['subcategoria'] == null)
        {
            //no tiene sub
            $select_producto_nosub = mysqli_query($conexion, "SELECT categoria.nombre as catproducto, atr1_producto FROM producto INNER JOIN categoria on categoria.idcategoria = producto.categoria WHERE idproducto = '$id_producto'");
            $data_producto = mysqli_fetch_assoc($select_producto_nosub);
            $catproducto = $data_producto['catproducto'];
            $atr1_producto = $data_producto['atr1_producto'];
            //creamos la ubicacion
            $estructura = "../img/catalogo_productos/".$catproducto."/".$atr1_producto;
        }
        else
        {
            //si tiene sub
            $select_producto_full = mysqli_query($conexion, "SELECT categoria.nombre as catproducto, subcategoria.nombre as subcat_producto FROM producto INNER JOIN categoria on categoria.idcategoria = producto.categoria INNER JOIN subcategoria on subcategoria.idsubcategoria = producto.subcategoria WHERE idproducto = '$id_producto'");
            $data_producto = mysqli_fetch_assoc($select_producto_full);
            $catproducto = $data_producto['catproducto'];
            $subcat_producto = $data_producto['subcat_producto'];
            $atr1_producto = $data_producto['atr1_producto'];
             //creamos la ubicacion
            $estructura = "../img/catalogo_productos/".$catproducto."/".$subcat_producto."/".$atr1_producto;
        }
        $archivador = $estructura."/".$id_producto.".png";//.$extencion;

    if (!is_dir($archivador)) 
    {
      unlink($archivador);
    }
    
    $query_producto = mysqli_query($conexion, "DELETE FROM producto WHERE idproducto = '$id_producto'");


    mysqli_close($conexion);
    if ($query_producto) {
      $elimino_producto= 1;
    }else {
      $elimino_producto = 0;
    }
    echo json_encode($elimino_producto,JSON_UNESCAPED_UNICODE);
  }
  exit;
}

##buscar datos sobre el tipo para poder editar tipo
  if ($_POST['action'] == 'SelectTipo') {
  include "accion/conexion.php";
  if (!empty($_POST['tipo'])) {
    $id_tipo = $_POST['tipo'];

    $select_tipo = mysqli_query($conexion, "SELECT idtipo,nombre_tipo from tipo where idtipo = '$id_tipo'");
    mysqli_close($conexion);
    $result = mysqli_num_rows($select_tipo);
    $data_tipo = '';
    if ($result > 0) 
    {
      $data_tipo = mysqli_fetch_assoc($select_tipo);
    }else {
      $data_tipo = 0;
    }
    echo json_encode($data_tipo,JSON_UNESCAPED_UNICODE);
  }
  exit;
}

##buscar datos sobre el tipo para poder editar tipo
  if ($_POST['action'] == 'SelectTipoVenta') {
  include "accion/conexion.php";
  if (!empty($_POST['tipo_venta'])) {
    $id_tipo_venta = $_POST['tipo_venta'];

    $select_tipov = mysqli_query($conexion, "SELECT idtipo_venta,nombre_venta from venta_tipo where idtipo_venta = '$id_tipo_venta'");
    mysqli_close($conexion);
    $result = mysqli_num_rows($select_tipov);
    $data_tipov = '';
    if ($result > 0) 
    {
      $data_tipov = mysqli_fetch_assoc($select_tipov);
    }else {
      $data_tipov = 0;
    }
    echo json_encode($data_tipov,JSON_UNESCAPED_UNICODE);
  }
  exit;
}

##buscar datos sobre el tipo para poder editar tipo
  if ($_POST['action'] == 'SelectModalidadPago') {
  include "accion/conexion.php";
  if (!empty($_POST['modalidad_pago'])) {
    $id_tipo_modalidad = $_POST['modalidad_pago'];

    $select_modalidadp = mysqli_query($conexion, "SELECT idmodalidad_pago,nombre_modalidad from modalidad_pago where idmodalidad_pago = '$id_tipo_modalidad'");
    mysqli_close($conexion);
    $result = mysqli_num_rows($select_modalidadp);
    $data_modalidadp = '';
    if ($result > 0) 
    {
      $data_modalidadp = mysqli_fetch_assoc($select_modalidadp);
    }else {
      $data_modalidadp = 0;
    }
    echo json_encode($data_modalidadp,JSON_UNESCAPED_UNICODE);
  }
  exit;
}

##buscar datos sobre el tipo para poder editar tipo de compra
  if ($_POST['action'] == 'SelectTipoCompra') {
  include "accion/conexion.php";
  if (!empty($_POST['tipo_compra'])) {
    $id_tipo_compra = $_POST['tipo_compra'];

    $select_tipoc = mysqli_query($conexion, "SELECT idtipo_compra,nombre_compra from compra_tipo where idtipo_compra = '$id_tipo_compra'");
    mysqli_close($conexion);
    $result = mysqli_num_rows($select_tipoc);
    $data_tipoc = '';
    if ($result > 0) 
    {
      $data_tipoc = mysqli_fetch_assoc($select_tipoc);
    }else {
      $data_tipoc = 0;
    }
    echo json_encode($data_tipoc,JSON_UNESCAPED_UNICODE);
  }
  exit;
}

##buscar datos sobre el tipo para proveedor
  if ($_POST['action'] == 'SelectProveedor') {
  include "accion/conexion.php";
  if (!empty($_POST['proveedor'])) {
    $id_proveedor = $_POST['proveedor'];

    $select_proveedor = mysqli_query($conexion, "SELECT idproveedor,nombre_proveedor,tel_proveedor from proveedor where idproveedor = '$id_proveedor'");
    mysqli_close($conexion);
    $result = mysqli_num_rows($select_proveedor);
    $data_proveedor = '';
    if ($result > 0) 
    {
      $data_proveedor = mysqli_fetch_assoc($select_proveedor);
    }else {
      $data_proveedor = 0;
    }
    echo json_encode($data_proveedor,JSON_UNESCAPED_UNICODE);
  }
  exit;
}


##buscar datos sobre el puesto para poder editar puesto
  if ($_POST['action'] == 'SelectPuesto') {
  include "accion/conexion.php";
  if (!empty($_POST['puesto'])) {
    $id_puesto = $_POST['puesto'];

    $select_puesto = mysqli_query($conexion, "SELECT puesto,descripcion from puesto where idpuesto = '$id_puesto'");
    mysqli_close($conexion);
    $result = mysqli_num_rows($select_puesto);
    $data_puesto = '';
    if ($result > 0) {
      $data_puesto = mysqli_fetch_assoc($select_puesto);
    }else {
      $data_puesto = 0;
    }
    echo json_encode($data_puesto,JSON_UNESCAPED_UNICODE);
  }
  exit;
}

##buscar datos sobre de la zona para poder editar zona
  if ($_POST['action'] == 'SelectSubzona') {
  include "accion/conexion.php";
  if (!empty($_POST['subzona'])) {
    $id_subzona = $_POST['subzona'];

    $select_subzona = mysqli_query($conexion, "SELECT subzona,idzona from subzonas where idsubzona = '$id_subzona'");
    mysqli_close($conexion);
    $result_subzona = mysqli_num_rows($select_subzona);
    $data_subzona = '';
    if ($result_subzona > 0) {
      $data_subzona = mysqli_fetch_assoc($select_subzona);
    }else {
      $data_subzona = 0;
    }
    echo json_encode($data_subzona,JSON_UNESCAPED_UNICODE);
  }
  exit;
}

##buscar datos sobre de la categoria para poder editar categoria
  if ($_POST['action'] == 'SelectCategoria') 
  {
  include "accion/conexion.php";
  if (!empty($_POST['categoria'])) 
  {
    $id_cat = $_POST['categoria'];

    $select_cat = mysqli_query($conexion, "SELECT * from categoria where idcategoria = '$id_cat'");
    mysqli_close($conexion);
    $result_cat = mysqli_num_rows($select_cat);
    $data_cat = '';
    if ($result_cat > 0) 
    {
      $data_cat = mysqli_fetch_assoc($select_cat);
    }
    else 
    {
      $data_cat = 0;
    }
    echo json_encode($data_cat,JSON_UNESCAPED_UNICODE);
  }
  exit;
}

##buscar datos sobre de la subcategoria para poder editarlos
  if ($_POST['action'] == 'SelectSubCat') 
  {
  include "accion/conexion.php";
  if (!empty($_POST['subcategoria'])) 
  {
    $id_subcat = $_POST['subcategoria'];

    $select_subcat = mysqli_query($conexion, "SELECT * from subcategoria where idsubcategoria = '$id_subcat'");
    mysqli_close($conexion);
    $result_subcat = mysqli_num_rows($select_subcat);
    $data_subcat = '';
    if ($result_subcat > 0) 
    {
      $data_subcat = mysqli_fetch_assoc($select_subcat);
    }
    else 
    {
      $data_subcat = 0;
    }
    echo json_encode($data_subcat,JSON_UNESCAPED_UNICODE);
  }
  exit;
}

##buscar datos sobre de la producto para poder editarlo
  if ($_POST['action'] == 'SelectProducto') 
  {
  include "accion/conexion.php";
  if (!empty($_POST['producto'])) 
  {
    $id_producto = $_POST['producto'];

    $select_producto = mysqli_query($conexion, "SELECT * from producto where idproducto = '$id_producto'");
    mysqli_close($conexion);
    $result_producto = mysqli_num_rows($select_producto);
    if ($result_producto > 0) 
    {
      $data_producto = mysqli_fetch_assoc($select_producto);
    }
    else 
    {
      $data_producto = 0;
    }
    echo json_encode($data_producto,JSON_UNESCAPED_UNICODE);
  }
  exit;
}

  #suspender sucursal
  if ($_POST['action'] == 'suspenderSucursal') {
  include "accion/conexion.php";
  if (!empty($_POST['sucursal'])) {
    $id_sucursal = $_POST['sucursal'];

    $sql1 = mysqli_query($conexion, "SELECT estado from sucursales where idsucursales = $id_sucursal");
    $estado = mysqli_fetch_array($sql1)['estado'];
    if ($estado == 0)
    {
      $newestado = 1;
    }
    else
    {
      $newestado = 0;
    }

    $query_update_sucursal = mysqli_query($conexion, "UPDATE sucursales SET estado = $newestado where idsucursales = $id_sucursal");

    mysqli_close($conexion);
    if ($query_update_sucursal) {
      $suspendio_sucursal = 1;
    }else {
      $suspendio_sucursal = 0;
    }

    if ($suspendio_sucursal == 1 and $newestado == 0)
    {
      $suspendio_sucursal = 1;
    }
    else if($suspendio_sucursal == 1 and $newestado == 1)
    {
      $suspendio_sucursal = 2;
    }
    echo json_encode($suspendio_sucursal,JSON_UNESCAPED_UNICODE);
  }
  exit;
}

#suspender cliente
  if ($_POST['action'] == 'suspenderCliente') {
  include "accion/conexion.php";
  if (!empty($_POST['cliente'])) {
    $id_cliente = $_POST['cliente'];

    $sql1 = mysqli_query($conexion, "SELECT estado_cliente from cliente where idcliente = '$id_cliente'");
    $estadoCliente = mysqli_fetch_array($sql1)['estado_cliente'];
    if ($estadoCliente == 0)
    {
      $newestadoCliente = 1;
    }
    else
    {
      $newestadoCliente = 0;
    }

    $query_update_cliente = mysqli_query($conexion, "UPDATE cliente SET estado_cliente = $newestadoCliente where idcliente = '$id_cliente'");

    mysqli_close($conexion);
    if ($query_update_cliente) 
    {
      $suspendio_cliente = 1;
    }else {
      $suspendio_cliente = 0;
    }

    if ($suspendio_cliente == 1 and $newestadoCliente == 0)
    {
      $suspendio_cliente = 1;
    }
    else if($suspendio_cliente == 1 and $newestadoCliente == 1)
    {
      $suspendio_cliente = 2;
    }
    echo json_encode($suspendio_cliente,JSON_UNESCAPED_UNICODE);
  }
  exit;
}

#asignar como matriz a sucursal
  #eliminar sucursal
  if ($_POST['action'] == 'asignarSucursalMatriz') {
  include "accion/conexion.php";
  if (!empty($_POST['sucursal'])) {
    $id_sucursal = $_POST['sucursal'];

    $sql_select2 = mysqli_query($conexion, "SELECT idsucursales,sucursales from sucursales where matriz = 1");
    $sucursal_old_matriz = mysqli_fetch_assoc($sql_select2);

    $new_name_nomatriz = explode("-", $sucursal_old_matriz['sucursales']);
    $id_sucursal_old_matrix = $sucursal_old_matriz['idsucursales'];
    $sql_select3 = mysqli_query($conexion, "UPDATE sucursales SET sucursales = '$new_name_nomatriz[0]' where idsucursales = $id_sucursal_old_matrix");

    $sql2 = mysqli_query($conexion, "UPDATE sucursales set matriz = 0");
    $query_update2 = mysqli_query($conexion, "UPDATE sucursales SET matriz = 1 where idsucursales = $id_sucursal");

    $sql_select = mysqli_query($conexion, "SELECT sucursales from sucursales where idsucursales = $id_sucursal");
    $name_old_sucursal = mysqli_fetch_array($sql_select)[0];

    $new_name_sucursal = $name_old_sucursal."-Matriz";
    $query_update3 = mysqli_query($conexion, "UPDATE sucursales SET sucursales = '$new_name_sucursal' where idsucursales = $id_sucursal");

    mysqli_close($conexion);
    if ($query_update2) {
      $asigno_matriz = 1;
    }else {
      $asigno_matriz = 0;
    }
    echo json_encode($asigno_matriz,JSON_UNESCAPED_UNICODE);
  }
  exit;
}

// buscar subzonas apartir de las zonas
if ($_POST['action'] == 'searchSubzonas') 
{
  include "accion/conexion.php";
  if (!empty($_POST['zona'])) {
    $idzona = $_POST['zona'];

      $query = mysqli_query($conexion, "SELECT idsubzona,subzona from subzonas where idzona = '$idzona'");
      if (mysqli_num_rows($query) > 0) 
      { 
        $cadena = "<option selected hidden value=''>Seleccione una colonia (subzona)</option>";
        while($row = mysqli_fetch_assoc($query))
        {
          $cadena = $cadena."<option value='".$row['idsubzona']."'>".$row['subzona']."</option>";
        }
      }
      else
      {
        $cadena = 0;
      }
      //calculamos si esa zona se esta en uso para no poder borrarlo
      $queryFind = mysqli_query($conexion, "SELECT count(idcliente) as num from cliente where zona = '$idzona'");
      $resultFind = (int) mysqli_fetch_assoc($queryFind)['num'];

      $array = array("allow_delete" => $resultFind);
      $array_cadena = array("options" => $cadena);
      $array = $array + $array_cadena;
    echo json_encode($array,JSON_UNESCAPED_UNICODE);
  }
  exit;
}

//buscar si la subzona esta siendo usada por algun usuario
if ($_POST['action'] == 'searchSubzonaUsed') 
{
  include "accion/conexion.php";
  if (!empty($_POST['subzona'])) {
      $idsubzona = $_POST['subzona'];

      //calculamos si esa zona se esta en uso para no poder borrarlo
      $queryFindsub = mysqli_query($conexion, "SELECT count(idcliente) as num from cliente where subzona = '$idsubzona'");
      $resultFindsub = (int) mysqli_fetch_assoc($queryFindsub)['num'];
      #$array_sub = array("allow_delete" => $resultFindsub);
    echo json_encode($resultFindsub,JSON_UNESCAPED_UNICODE);
  }
  exit;
}

//buscar si la subzona esta siendo usada por algun usuario
if ($_POST['action'] == 'searchPuestoUsed') 
{
  include "accion/conexion.php";
  if (!empty($_POST['puesto'])) {
      $idpuesto = $_POST['puesto'];

      //calculamos si esa zona se esta en uso para no poder borrarlo
      $queryFindpuesto = mysqli_query($conexion, "SELECT count(idusuario) as num from usuario where puesto = '$idpuesto'");
      $resultFindpuesto = (int) mysqli_fetch_assoc($queryFindpuesto)['num'];
    echo json_encode($resultFindpuesto,JSON_UNESCAPED_UNICODE);
  }
  exit;
}

//buscar si el tipo esta siendo usada por algun usuario
if ($_POST['action'] == 'searchTipoUsed') 
{
  include "accion/conexion.php";
  if (!empty($_POST['tipo'])) {
      $idtipo = $_POST['tipo'];

      //calculamos si esa zona se esta en uso para no poder borrarlo
      $queryFindtipo = mysqli_query($conexion, "SELECT count(idsucursales) as num from sucursales where tipo = '$idtipo'");
      $resultFindtipo = (int) mysqli_fetch_assoc($queryFindtipo)['num'];
    echo json_encode($resultFindtipo,JSON_UNESCAPED_UNICODE);
  }
  exit;
}

//buscar si el tipo de venta esta siendo usada por algun salida o entrada
if ($_POST['action'] == 'searchTipoVentaUsed') 
{
  include "accion/conexion.php";
  if (!empty($_POST['tipo_venta'])) {
      $idtipo_venta = $_POST['tipo_venta'];

      //FALTA, EN PROCESO DE DESARROLLO
      //calculamos si ese tipo de venta esta en uso para no poder borrarlo
      //$queryFindtipo = mysqli_query($conexion, "SELECT count(idsucursales) as num from sucursales where tipo = '$idtipo'");
      //$resultFindtipo = (int) mysqli_fetch_assoc($queryFindtipo)['num'];
      /*if($idtipo_venta == "e1923a6e-361a-11ed-a7ae-d481d7c3a9ad")
      {
        $resultFindtipo = 1;
      }
      else
      {

      }*/
      $resultFindtipo = 0;
    echo json_encode($resultFindtipo,JSON_UNESCAPED_UNICODE);
  }
  exit;
}

//buscar si la modalidad de pago esta siendo usada por algun salida o entrada
if ($_POST['action'] == 'searchModalidadPagoUsed') 
{
  include "accion/conexion.php";
  if (!empty($_POST['modalidad_pago'])) {
      $idmodalidad_pago = $_POST['modalidad_pago'];

      //FALTA, EN PROCESO DE DESARROLLO
      //calculamos si esa modalidad de pago esta en uso para no poder borrarlo
      $resultFindModalidad = 0;
    echo json_encode($resultFindModalidad,JSON_UNESCAPED_UNICODE);
  }
  exit;
}

if ($_POST['action'] == 'searchTipoCompraUsed') 
{
  include "accion/conexion.php";
  if (!empty($_POST['tipo_compra'])) {
      $idtipo_compra = $_POST['tipo_compra'];

      //FALTA, EN PROCESO DE DESARROLLO
      $resultFindtipo = 0;
    echo json_encode($resultFindtipo,JSON_UNESCAPED_UNICODE);
  }
  exit;
}

if ($_POST['action'] == 'searchProveedorUsed') 
{
  include "accion/conexion.php";
  if (!empty($_POST['proveedor'])) {
      $idproveedor = $_POST['proveedor'];

      //FALTA, EN PROCESO DE DESARROLLO
      $resultProveedor = 0;
    echo json_encode($resultProveedor,JSON_UNESCAPED_UNICODE);
  }
  exit;
}

//buscar si la categoria esta siendo usada por algun producto y las subcategorias que tengan esa categoria
if ($_POST['action'] == 'searchCatUsed') 
{
  ob_start();
  session_start();

  include "accion/conexion.php";
  if (!empty($_POST['categoria'])) 
  {
      $idcategoria = $_POST['categoria'];

      //calculamos si esa zona se esta en uso para no poder borrarlo
      $queryFindcat = mysqli_query($conexion, "SELECT count(idproducto) as num from producto where categoria = '$idcategoria'");
      $Findcat = (int) mysqli_fetch_assoc($queryFindcat)['num'];

      //buscamos que subtegorias tiene esa categoria
        $FindSubcats = mysqli_query($conexion, "SELECT idsubcategoria,nombre FROM subcategoria WHERE categoria = '$idcategoria' order by nombre asc");
        if (mysqli_num_rows($FindSubcats) > 0) 
        { 
          $cadena = "<option selected hidden value=''>Selecciona subcategoría</option>";
          while($row = mysqli_fetch_assoc($FindSubcats))
          {
            $cadena = $cadena."<option value='".$row['idsubcategoria']."'>".$row['nombre']."</option>";
          }
          //no mostrar los atributos
          $atrs_productos = 0;
          $atr_labels = 0;
          
        }
        else
        {
          $cadena = 0;
          //buscamos los atributos de categoria
          $select_atributos = mysqli_query($conexion, "SELECT atr1, atr2, atr3, atr4, atr5 from categoria where idcategoria = '$idcategoria'");
          $atr_labels = mysqli_fetch_assoc($select_atributos);
          $select_atrs_productos = mysqli_query($conexion, "SELECT atr1_producto, atr2_producto, atr3_producto, atr4_producto, atr5_producto from producto where categoria = '$idcategoria'");

          $atrs_productos = Array();
          while($row = mysqli_fetch_assoc($select_atrs_productos)) 
          {
              $atrs_productos[] = $row;
          }
        }

        //calculamos la tabla a mostrar que sean solo de esa categoria
          $cadenaTabla = '
<table class="table" id="tbl_productos">
        <thead class="thead-light">
            <tr>
                <th>Descripción</th>
                <th>Nuevo costo</th>
                <th>Costo actual</th>
                <th>Costo IVA</th>
                <th>Nuevo Ext.-P</th>
                <th>Ext.-P</th>
                <th>Ext.-M</th>
                <th>Contado</th>
                <th>Especial</th>
                <th>CR1</th>
                <th>P1</th>
                <th>CR2</th>
                <th>P2</th>
                <th>Quin</th>
                <th>Enga</th>
                <th>GAR</th>
                <th style="text-align: center;">Herramientas</th>
            </tr>
        </thead>
        <tbody>';
     
            $query = mysqli_query($conexion, "SELECT * from producto where categoria = '$idcategoria' order by creado_en desc");
            $result = mysqli_num_rows($query);
            if ($result > 0) 
            {
                while ($data = mysqli_fetch_assoc($query)) 
                {
                    $id_producto = $data['idproducto'];
                    $identificador = $data['identificador'];
                    #revisar que no sean null Ext_p y Ext_m, si los dos son nulos no mostrarlos. Mostrar si y solo los dos son null
                    #POR AHORA NO HAY DE DONDE SACAR Ext_m, por lo tanto solo nos basamos en Ext_p
                    $query_extp = mysqli_query($conexion, "SELECT ext_p from producto where idproducto = '$id_producto'");
                    if(mysqli_fetch_assoc($query_extp)['ext_p'])
                    {
                        #NONULL
                        //aqui vamos a ver si tienen foto o no, para mostrar los iconos acorde
                        if($data['subcategoria'] == null)
                        {
                            $idcategoria = $data['categoria'];
                            $query_meses = mysqli_query($conexion, "SELECT meses_garantia from categoria where idcategoria = '$idcategoria'");
                            $garantia = mysqli_fetch_assoc($query_meses)['meses_garantia'];

                            //no tiene sub
                            $select_producto_nosub = mysqli_query($conexion, "SELECT categoria.nombre as catproducto, atr1_producto FROM producto INNER JOIN categoria on categoria.idcategoria = producto.categoria WHERE idproducto = '$id_producto'");
                            $data_producto = mysqli_fetch_assoc($select_producto_nosub);
                            $catproducto = $data_producto['catproducto'];
                            $atr1_producto = $data_producto['atr1_producto'];
                            //creamos la ubicacion
                            $estructura = "../img/catalogo_productos/".$catproducto."/".$atr1_producto;
                        }
                        else
                        {
                            $idsubcategoria = $data['subcategoria'];
                            $query_meses = mysqli_query($conexion, "SELECT meses_garantia from subcategoria where idsubcategoria = '$idsubcategoria'");
                            $garantia = mysqli_fetch_assoc($query_meses)['meses_garantia'];

                            $select_producto_full = mysqli_query($conexion, "SELECT categoria.nombre as catproducto, subcategoria.nombre as subcat_producto, atr1_producto FROM producto INNER JOIN categoria on categoria.idcategoria = producto.categoria INNER JOIN subcategoria on subcategoria.idsubcategoria = producto.subcategoria WHERE idproducto = '$id_producto'");
                            $data_producto = mysqli_fetch_assoc($select_producto_full);
                            $catproducto = $data_producto['catproducto'];
                            $subcat_producto = $data_producto['subcat_producto'];
                            $atr1_producto = $data_producto['atr1_producto'];
                            //creamos la ubicacion
                            $estructura = "../img/catalogo_productos/".$catproducto."/".$subcat_producto."/".$atr1_producto;
                        }
                        //aqui vamos a ver si tienen foto o no, para mostrar los iconos acorde
                        $archivador = $estructura."/".$identificador.".png";
                        if(is_file($archivador))
                        {
                            $boton_img = "btn btn-primary btn-sm";
                            $siimagen = 1;
                        }
                        else
                        {
                            $boton_img = "btn btn-secondary btn-sm";
                            $siimagen = 0;
                        }

                    $cadenaTabla = $cadenaTabla.'<tr>
                        <td>'.$data['descripcion'].'</td>
                            <td width="110"><input type="number" name="nuevo_costo[]" id="nuevo_costo[]" class="form-control"><input type="text" name="flag_new_costo_idproducto[]" id="flag_new_costo_idproducto[]" value="'.$id_producto.'" readonly hidden></td>
                            <td>'."$".number_format($data['costo'],2, '.', ',').'</td>
                            <td>'."$".number_format($data['costo_iva'],2, '.', ',').'</td>
                            <td width="110"><input type="number" name="nuevo_ext_p[]" id="nuevo_ext_p[]" class="form-control"><input type="text" name="flag_new_extp_idproducto[]" id="flag_new_extp_idproducto[]" value="'.$id_producto.'" readonly hidden></td>
                            <td>'.$data['ext_p'].'</td>
                            <td></td>
                            <td>'."$".number_format($data['costo_contado'],2, '.', ',').'</td>
                            <td>'."$".number_format($data['costo_especial'],2, '.', ',').'</td>
                            <td>'."$".number_format($data['costo_cr1'],2, '.', ',').'</td>
                            <td>'.round($data['costo_p1'],2).'</td>
                            <td>'."$".number_format($data['costo_cr2'],2, '.', ',').'</td>
                            <td>'.round($data['costo_p2'],2).'</td>
                            <td>'."$".number_format($data['costo_eq'],2, '.', ',').'</td>
                            <td>'."$".number_format($data['costo_enganche'],2, '.', ',').'</td>
                            <td>'.$garantia." Ms".'</td>';

                    $cadenaTabla = $cadenaTabla.'<td align="center">';

                    $id_usuario = $_SESSION['iduser'];
                    $sqlpermisos_usuario = mysqli_query($conexion, "SELECT permiso_idpermiso FROM permiso_usuario where permiso_idusuario = '$id_usuario'");
                    $array_permisos = [];
                        while($row = mysqli_fetch_assoc($sqlpermisos_usuario)) 
                        {
                            $array_permisos[] = $row['permiso_idpermiso'];
                        }
                        #print_r($array_permisos);
                        $num_permisos = sizeof($array_permisos);
                        #PERMISOS
                        if($_SESSION['rol'] == "SuperAdmin")
                        {
                          #es super admin y titene permiso a TODO
                          $editar_productos = 1;
                          $eliminar_productos = 1;
                          $imagenes = 1;
                        }
                        else
                        {
                          #permisos asignados
                          $editar_productos = in_array(6, $array_permisos);
                          $eliminar_productos = in_array(7, $array_permisos);
                          $imagenes =  in_array(8, $array_permisos);
                        }
                        $cadenaTabla = $cadenaTabla.'<button data-toggle="modal" data-target="#img_producto" onclick="mostrar_img(\''.$id_producto.'\',\''.$archivador.'\',\''.$siimagen.'\');" class="'.$boton_img.'"><i class="fas fa-camera"></i></button>';
                        $cadenaTabla = $cadenaTabla."&nbsp;";
                        if($editar_productos)
                        {
                          $cadenaTabla = $cadenaTabla.'<button class="btn btn-success btn-sm" data-toggle="modal" data-target="#nuevo_producto" onclick="editar_producto(\''.$id_producto.'\');"><i class="fas fa-edit"></i></button>';
                        }
                        else
                        {
                          $cadenaTabla = $cadenaTabla.'<button disabled="disabled" class="btn btn-success btn-sm"><i class="fas fa-edit"></i></button>';
                        }
                        $cadenaTabla = $cadenaTabla."&nbsp;";
                        if($eliminar_productos)
                        {
                          $cadenaTabla = $cadenaTabla.'<button onClick="eliminar_producto(\''.$id_producto.'\');" class="btn btn-danger btn-sm" type="submit"><i style="color: white;" class="fas fa-trash-alt"></i></button>';
                        }
                        else
                        {
                          $cadenaTabla = $cadenaTabla.'<button disabled="disabled" class="btn btn-danger btn-sm" type="submit"><i style="color: white;" class="fas fa-trash-alt"></i></button>';
                        }
                    $cadenaTabla = $cadenaTabla.'</td></tr>';
                }#if($ext_p != null)
              }
            }
        $cadenaTabla = $cadenaTabla.'</tbody></table>';

        $atrs_productos = array("atrs_productos" => $atrs_productos);
        $atr_labels = array("atr_labels" => $atr_labels);

        $cadenaTabla = array("cadenaTabla" => $cadenaTabla);
        $options = array("options" => $cadena);
        $catUsed = array("catUsed" => $Findcat);
        $resultFindcat = $catUsed + $options + $cadenaTabla + $atrs_productos + $atr_labels;

    echo json_encode($resultFindcat,JSON_UNESCAPED_UNICODE);
  }
  ob_end_flush();
  exit;
}

//buscar si la subcategoria esta siendo usada por algun producto
if ($_POST['action'] == 'searchSubCatUsed') 
{
  ob_start();
  session_start();

  include "accion/conexion.php";
  if (!empty($_POST['subcategoria'])) {
      $idsubcategoria = $_POST['subcategoria'];

      //calculamos si esa zona se esta en uso para no poder borrarlo
      $queryFindsubcat = mysqli_query($conexion, "SELECT count(idproducto) as num from producto where subcategoria = '$idsubcategoria'");
      $subcatused = (int) mysqli_fetch_assoc($queryFindsubcat)['num'];

      //buscamos los atributos de categoria
          $select_atributos = mysqli_query($conexion, "SELECT atr1, atr2, atr3, atr4, atr5 from subcategoria where idsubcategoria = '$idsubcategoria'");
          $atr_labels = mysqli_fetch_assoc($select_atributos);
          $select_atrs_productos = mysqli_query($conexion, "SELECT atr1_producto, atr2_producto, atr3_producto, atr4_producto, atr5_producto from producto where subcategoria = '$idsubcategoria'");

          $atrs_productos = Array();
          while($row = mysqli_fetch_assoc($select_atrs_productos)) 
          {
              $atrs_productos[] = $row;
          }

      //calculamos la tabla a mostrar que sean solo de esa categoria
          $cadenaTabla = '
<table class="table" id="tbl_productos">
        <thead class="thead-light">
            <tr>
                <th>Descripción</th>
                <th>Nuevo costo</th>
                <th>Costo actual</th>
                <th>Costo IVA</th>
                <th>Nuevo Ext.-P</th>
                <th>Ext.-P</th>
                <th>Ext.-M</th>
                <th>Contado</th>
                <th>Especial</th>
                <th>CR1</th>
                <th>P1</th>
                <th>CR2</th>
                <th>P2</th>
                <th>Quin</th>
                <th>Enga</th>
                <th>GAR</th>
                <th style="text-align: center;">Herramientas</th>
            </tr>
        </thead>
        <tbody>';
     
            $query = mysqli_query($conexion, "SELECT * from producto where subcategoria = '$idsubcategoria' order by creado_en desc");
            $result = mysqli_num_rows($query);
            if ($result > 0) 
            {
                while ($data = mysqli_fetch_assoc($query)) 
                {
                    $id_producto = $data['idproducto'];
                    $identificador = $data['identificador'];
                    #revisar que no sean null Ext_p y Ext_m, si los dos son nulos no mostrarlos. Mostrar si y solo los dos son null
                    #POR AHORA NO HAY DE DONDE SACAR Ext_m, por lo tanto solo nos basamos en Ext_p
                    $query_extp = mysqli_query($conexion, "SELECT ext_p from producto where idproducto = '$id_producto'");
                    if(mysqli_fetch_assoc($query_extp)['ext_p'])
                    {
                        #NONULL
                        $idsubcategoria = $data['subcategoria'];
                        $query_meses = mysqli_query($conexion, "SELECT meses_garantia from subcategoria where idsubcategoria = '$idsubcategoria'");
                        $garantia = mysqli_fetch_assoc($query_meses)['meses_garantia'];

                        $select_producto_full = mysqli_query($conexion, "SELECT categoria.nombre as catproducto, subcategoria.nombre as subcat_producto, atr1_producto FROM producto INNER JOIN categoria on categoria.idcategoria = producto.categoria INNER JOIN subcategoria on subcategoria.idsubcategoria = producto.subcategoria WHERE idproducto = '$id_producto'");
                        $data_producto = mysqli_fetch_assoc($select_producto_full);
                        $catproducto = $data_producto['catproducto'];
                        $subcat_producto = $data_producto['subcat_producto'];
                        $atr1_producto = $data_producto['atr1_producto'];
                        //creamos la ubicacion
                        $estructura = "../img/catalogo_productos/".$catproducto."/".$subcat_producto."/".$atr1_producto;

                    //aqui vamos a ver si tienen foto o no, para mostrar los iconos acorde
                    $archivador = $estructura."/".$identificador.".png";
                    if(is_file($archivador))
                    {
                        $boton_img = "btn btn-primary btn-sm";
                        $siimagen = 1;
                    }
                    else
                    {
                        $boton_img = "btn btn-secondary btn-sm";
                        $siimagen = 0;
                    }

                $cadenaTabla = $cadenaTabla.'<tr>
                    <td>'.$data['descripcion'].'</td>
                        <td width="110"><input type="number" name="nuevo_costo[]" id="nuevo_costo[]" class="form-control"><input type="text" name="flag_new_costo_idproducto[]" id="flag_new_costo_idproducto[]" value="'.$id_producto.'" readonly hidden></td>
                        <td>'."$".number_format($data['costo'],2, '.', ',').'</td>
                        <td>'."$".number_format($data['costo_iva'],2, '.', ',').'</td>
                        <td width="110"><input type="number" name="nuevo_ext_p[]" id="nuevo_ext_p[]" class="form-control"><input type="text" name="flag_new_extp_idproducto[]" id="flag_new_extp_idproducto[]" value="'.$id_producto.'" readonly hidden></td>
                        <td>'.$data['ext_p'].'</td>
                        <td></td>
                        <td>'."$".number_format($data['costo_contado'],2, '.', ',').'</td>
                        <td>'."$".number_format($data['costo_especial'],2, '.', ',').'</td>
                        <td>'."$".number_format($data['costo_cr1'],2, '.', ',').'</td>
                        <td>'.round($data['costo_p1'],2).'</td>
                        <td>'."$".number_format($data['costo_cr2'],2, '.', ',').'</td>
                        <td>'.round($data['costo_p2'],2).'</td>
                        <td>'."$".number_format($data['costo_eq'],2, '.', ',').'</td>
                        <td>'."$".number_format($data['costo_enganche'],2, '.', ',').'</td>
                        <td>'.$garantia." Ms".'</td>';

                $cadenaTabla = $cadenaTabla.'<td align="center">';

                $id_usuario = $_SESSION['iduser'];
                $sqlpermisos_usuario = mysqli_query($conexion, "SELECT permiso_idpermiso FROM permiso_usuario where permiso_idusuario = '$id_usuario'");
                $array_permisos = [];
                    while($row = mysqli_fetch_assoc($sqlpermisos_usuario)) 
                    {
                        $array_permisos[] = $row['permiso_idpermiso'];
                    }
                    #print_r($array_permisos);
                    $num_permisos = sizeof($array_permisos);
                    #PERMISOS
                    if($_SESSION['rol'] == "SuperAdmin")
                    {
                      #es super admin y titene permiso a TODO
                      $editar_productos = 1;
                      $eliminar_productos = 1;
                      $imagenes = 1;
                    }
                    else
                    {
                      #permisos asignados
                      $editar_productos = in_array(6, $array_permisos);
                      $eliminar_productos = in_array(7, $array_permisos);
                      $imagenes =  in_array(8, $array_permisos);
                    }

                    $cadenaTabla = $cadenaTabla.'<button data-toggle="modal" data-target="#img_producto" onclick="mostrar_img(\''.$id_producto.'\',\''.$archivador.'\',\''.$siimagen.'\');" class="'.$boton_img.'"><i class="fas fa-camera"></i></button>';
                    $cadenaTabla = $cadenaTabla."&nbsp;";
                    if($editar_productos)
                    {
                      $cadenaTabla = $cadenaTabla.'<button class="btn btn-success btn-sm" data-toggle="modal" data-target="#nuevo_producto" onclick="editar_producto(\''.$id_producto.'\');"><i class="fas fa-edit"></i></button>';
                    }
                    else
                    {
                      $cadenaTabla = $cadenaTabla.'<button disabled="disabled" class="btn btn-success btn-sm"><i class="fas fa-edit"></i></button>';
                    }
                    $cadenaTabla = $cadenaTabla."&nbsp;";
                    if($eliminar_productos)
                    {
                      $cadenaTabla = $cadenaTabla.'<button onClick="eliminar_producto(\''.$id_producto.'\');" class="btn btn-danger btn-sm" type="submit"><i style="color: white;" class="fas fa-trash-alt"></i></button>';
                    }
                    else
                    {
                      $cadenaTabla = $cadenaTabla.'<button disabled="disabled" class="btn btn-danger btn-sm" type="submit"><i style="color: white;" class="fas fa-trash-alt"></i></button>';
                    }
                $cadenaTabla = $cadenaTabla.'</td></tr>';
                }#if($ext_p != null)
              }
            }
        $cadenaTabla = $cadenaTabla.'</tbody></table>';

        $atrs_productos = array("atrs_productos" => $atrs_productos);
        $atr_labels = array("atr_labels" => $atr_labels);

      $cadenaTabla = array("cadenaTabla" => $cadenaTabla);
      $subcatused = array("subcatused" => $subcatused);
      $resultFindsubcat = $subcatused + $cadenaTabla + $atrs_productos + $atr_labels;

    echo json_encode($resultFindsubcat,JSON_UNESCAPED_UNICODE);
  }
  ob_end_flush();
  exit;
}

//buscar si tiene subcategoria la categoria
if ($_POST['action'] == 'FindAtrsCat') 
{
  include "accion/conexion.php";
  if (!empty($_POST['categoria'])) 
  {
      $idcategoria = $_POST['categoria'];

      //calculamos si esa zona se esta en uso para no poder borrarlo
      $queryFindDatacat = mysqli_query($conexion, "SELECT * FROM categoria where idcategoria = '$idcategoria'");
      $resultQueryFindDataCat = mysqli_fetch_assoc($queryFindDatacat);
      $tiene_subcat = $resultQueryFindDataCat['tiene_subcat'];
      if($tiene_subcat == 1)
      {
        //buscamos que subtegorias tiene esa categoria
        $FindSubcats = mysqli_query($conexion, "SELECT idsubcategoria,nombre FROM subcategoria WHERE categoria = '$idcategoria' order by nombre asc");
        $cadena = "<option selected hidden value=''>Agregar una subcategoria</option>";
        if (mysqli_num_rows($FindSubcats) > 0) 
        { 
          $cadena = "<option selected hidden value=''>Seleccione subcategoría</option>";
          while($row = mysqli_fetch_assoc($FindSubcats))
          {
            $cadena = $cadena."<option value='".$row['idsubcategoria']."'>".$row['nombre']."</option>";
          }
        }
        $options = array("options" => $cadena);
        $tiene_subcat = array("tiene_subcat" => $tiene_subcat);
        $resultFindAtrscat = $tiene_subcat + $options;
      }
      else
      {
        //cargamos todo el array
        $resultFindAtrscat = $resultQueryFindDataCat;
      }

    echo json_encode($resultFindAtrscat,JSON_UNESCAPED_UNICODE);
  }
  exit;
}

//buscar si tiene subcategoria la categoria
if ($_POST['action'] == 'FindAtrsSubCat') 
{
  include "accion/conexion.php";
  if (!empty($_POST['subcategoria'])) 
  {
      $idsubcategoria = $_POST['subcategoria'];

      //calculamos si esa zona se esta en uso para no poder borrarlo
      $queryFindDataSubcat = mysqli_query($conexion, "SELECT * FROM subcategoria where idsubcategoria = '$idsubcategoria'");
      $resultQueryFindDataSubCat = mysqli_fetch_assoc($queryFindDataSubcat);
      //cargamos todo el array
      $resultFindAtrsSubcat = $resultQueryFindDataSubCat;
      
    echo json_encode($resultFindAtrsSubcat,JSON_UNESCAPED_UNICODE);
  }
  exit;
}

//====== CONSULTAS PARA LOS FILTROS POR ATRIBUTOS
//ATR 1,2,3,4 y 5
if ($_POST['action'] == 'searchForAtr') 
{
  ob_start();
  session_start();
  include "accion/conexion.php";
  if (!empty($_POST['atr1'])) 
  {
      $atr1 = $_POST['atr1'];
      $atr2 = $_POST['atr2'];
      $atr3 = $_POST['atr3'];
      $atr4 = $_POST['atr4'];
      $atr5 = $_POST['atr5'];
      $idcategoria = $_POST['idcat'];
      $idsubcategoria = $_POST['idsubcat'];
      
      $cadenaTabla = '
<table class="table" id="tbl_productos">
        <thead class="thead-light">
            <tr>
                <th>Descripción</th>
                <th>Nuevo costo</th>
                <th>Costo actual</th>
                <th>Costo IVA</th>
                <th>Nuevo Ext.-P</th>
                <th>Ext.-P</th>
                <th>Ext.-M</th>
                <th>Contado</th>
                <th>Especial</th>
                <th>CR1</th>
                <th>P1</th>
                <th>CR2</th>
                <th>P2</th>
                <th>Quin</th>
                <th>Enga</th>
                <th>GAR</th>
                <th style="text-align: center;">Herramientas</th>
            </tr>
        </thead>
        <tbody>';

            $query_sql = "SELECT * from producto WHERE 1";  
            if($atr1 != "LABEL")
            {
              $query_sql = $query_sql." and atr1_producto like '$atr1'";
            }
            //los demas filtros anidados
            if($atr2 != "LABEL")
            {
              $query_sql = $query_sql." and atr2_producto like '$atr2'";
            }
            if($atr3 != "LABEL")
            {
              $query_sql = $query_sql." and atr3_producto like '$atr3'";
            }
            if($atr4 != "LABEL")
            {
              $query_sql = $query_sql." and atr4_producto like '$atr4'";
            }
            if($atr5 != "LABEL")
            {
              $query_sql = $query_sql." and atr5_producto like '$atr5'";
            }

          if($idsubcategoria == "NoSubcat")
          {
            $query_sql = $query_sql." and categoria = '$idcategoria' order by creado_en desc";
          }
          else
          {
            $query_sql = $query_sql." and categoria = '$idcategoria' and subcategoria = '$idsubcategoria' order by creado_en desc";
          }

            $query = mysqli_query($conexion, $query_sql);
            $result = mysqli_num_rows($query);
            if ($result > 0) 
            {
                while ($data = mysqli_fetch_assoc($query)) 
                {
                  $id_producto = $data['idproducto'];
                  $identificador = $data['identificador'];
                  #revisar que no sean null Ext_p y Ext_m, si los dos son nulos no mostrarlos. Mostrar si y solo los dos son null
                    #POR AHORA NO HAY DE DONDE SACAR Ext_m, por lo tanto solo nos basamos en Ext_p
                    $query_extp = mysqli_query($conexion, "SELECT ext_p from producto where idproducto = '$id_producto'");
                    if(mysqli_fetch_assoc($query_extp)['ext_p'])
                    {
                        #NONULL
                      if($data['subcategoria'] == null)
                        {
                            $idcategoria = $data['categoria'];
                            $query_meses = mysqli_query($conexion, "SELECT meses_garantia from categoria where idcategoria = '$idcategoria'");
                            $garantia = mysqli_fetch_assoc($query_meses)['meses_garantia'];

                            //no tiene sub
                            $select_producto_nosub = mysqli_query($conexion, "SELECT categoria.nombre as catproducto, atr1_producto FROM producto INNER JOIN categoria on categoria.idcategoria = producto.categoria WHERE idproducto = '$id_producto'");
                            $data_producto = mysqli_fetch_assoc($select_producto_nosub);
                            $catproducto = $data_producto['catproducto'];
                            $atr1_producto = $data_producto['atr1_producto'];
                            //creamos la ubicacion
                            $estructura = "../img/catalogo_productos/".$catproducto."/".$atr1_producto;
                        }
                        else
                        {
                            $idsubcategoria = $data['subcategoria'];
                            $query_meses = mysqli_query($conexion, "SELECT meses_garantia from subcategoria where idsubcategoria = '$idsubcategoria'");
                            $garantia = mysqli_fetch_assoc($query_meses)['meses_garantia'];

                            $select_producto_full = mysqli_query($conexion, "SELECT categoria.nombre as catproducto, subcategoria.nombre as subcat_producto, atr1_producto FROM producto INNER JOIN categoria on categoria.idcategoria = producto.categoria INNER JOIN subcategoria on subcategoria.idsubcategoria = producto.subcategoria WHERE idproducto = '$id_producto'");
                            $data_producto = mysqli_fetch_assoc($select_producto_full);
                            $catproducto = $data_producto['catproducto'];
                            $subcat_producto = $data_producto['subcat_producto'];
                            $atr1_producto = $data_producto['atr1_producto'];
                            //creamos la ubicacion
                            $estructura = "../img/catalogo_productos/".$catproducto."/".$subcat_producto."/".$atr1_producto;
                        }                    
                        //aqui vamos a ver si tienen foto o no, para mostrar los iconos acorde
                        $archivador = $estructura."/".$identificador.".png";
                        if(is_file($archivador))
                        {
                            $boton_img = "btn btn-primary btn-sm";
                            $siimagen = 1;
                        }
                        else
                        {
                            $boton_img = "btn btn-secondary btn-sm";
                            $siimagen = 0;
                        }

                    $cadenaTabla = $cadenaTabla.'<tr>
                        <td>'.$data['descripcion'].'</td>
                            <td width="110"><input type="number" name="nuevo_costo[]" id="nuevo_costo[]" class="form-control"><input type="text" name="flag_new_costo_idproducto[]" id="flag_new_costo_idproducto[]" value="'.$id_producto.'" readonly hidden></td>
                            <td>'."$".number_format($data['costo'],2, '.', ',').'</td>
                            <td>'."$".number_format($data['costo_iva'],2, '.', ',').'</td>
                            <td width="110"><input type="number" name="nuevo_ext_p[]" id="nuevo_ext_p[]" class="form-control"><input type="text" name="flag_new_extp_idproducto[]" id="flag_new_extp_idproducto[]" value="'.$id_producto.'" readonly hidden></td>
                            <td>'.$data['ext_p'].'</td>
                            <td></td>
                            <td>'."$".number_format($data['costo_contado'],2, '.', ',').'</td>
                            <td>'."$".number_format($data['costo_especial'],2, '.', ',').'</td>
                            <td>'."$".number_format($data['costo_cr1'],2, '.', ',').'</td>
                            <td>'.round($data['costo_p1'],2).'</td>
                            <td>'."$".number_format($data['costo_cr2'],2, '.', ',').'</td>
                            <td>'.round($data['costo_p2'],2).'</td>
                            <td>'."$".number_format($data['costo_eq'],2, '.', ',').'</td>
                            <td>'."$".number_format($data['costo_enganche'],2, '.', ',').'</td>
                            <td>'.$garantia." Ms".'</td>';

                    $cadenaTabla = $cadenaTabla.'<td align="center">';

                    $id_usuario = $_SESSION['iduser'];
                    $sqlpermisos_usuario = mysqli_query($conexion, "SELECT permiso_idpermiso FROM permiso_usuario where permiso_idusuario = '$id_usuario'");
                    $array_permisos = [];
                        while($row = mysqli_fetch_assoc($sqlpermisos_usuario)) 
                        {
                            $array_permisos[] = $row['permiso_idpermiso'];
                        }
                        #print_r($array_permisos);
                        $num_permisos = sizeof($array_permisos);
                        #PERMISOS
                        if($_SESSION['rol'] == "SuperAdmin")
                        {
                          #es super admin y titene permiso a TODO
                          $editar_productos = 1;
                          $eliminar_productos = 1;
                          $imagenes = 1;
                        }
                        else
                        {
                          #permisos asignados
                          $editar_productos = in_array(6, $array_permisos);
                          $eliminar_productos = in_array(7, $array_permisos);
                          $imagenes =  in_array(8, $array_permisos);
                        }

                        $cadenaTabla = $cadenaTabla.'<button data-toggle="modal" data-target="#img_producto" onclick="mostrar_img(\''.$id_producto.'\',\''.$archivador.'\',\''.$siimagen.'\');" class="'.$boton_img.'"><i class="fas fa-camera"></i></button>';
                        $cadenaTabla = $cadenaTabla."&nbsp;";
                        if($editar_productos)
                        {
                          $cadenaTabla = $cadenaTabla.'<button class="btn btn-success btn-sm" data-toggle="modal" data-target="#nuevo_producto" onclick="editar_producto(\''.$id_producto.'\');"><i class="fas fa-edit"></i></button>';
                        }
                        else
                        {
                          $cadenaTabla = $cadenaTabla.'<button disabled="disabled" class="btn btn-success btn-sm"><i class="fas fa-edit"></i></button>';
                        }
                        $cadenaTabla = $cadenaTabla."&nbsp;";
                        if($eliminar_productos)
                        {
                          $cadenaTabla = $cadenaTabla.'<button onClick="eliminar_producto(\''.$id_producto.'\');" class="btn btn-danger btn-sm" type="submit"><i style="color: white;" class="fas fa-trash-alt"></i></button>';
                        }
                        else
                        {
                          $cadenaTabla = $cadenaTabla.'<button disabled="disabled" class="btn btn-danger btn-sm" type="submit"><i style="color: white;" class="fas fa-trash-alt"></i></button>';
                        }
                    $cadenaTabla = $cadenaTabla.'</td></tr>';
                  }#if($ext_p != null)
              }
            }
        $cadenaTabla = $cadenaTabla.'</tbody></table>';

        $cadenaTabla = array("cadenaTabla" => $cadenaTabla);
        $resultFindforAtr = $cadenaTabla;
      
    echo json_encode($resultFindforAtr,JSON_UNESCAPED_UNICODE);
  }
  ob_end_flush();
  exit;
}
//==== FIN DE LOS FILTROS

//para borrar imgagen del producto
if ($_POST['action'] == 'BorrarImg') 
{
  include "accion/conexion.php";
  if (!empty($_POST['img_producto'])) {
      $url_imgproducto = $_POST['img_producto'];

      unlink($url_imgproducto);
      if(is_file($url_imgproducto))
                {
                    //no se borro, ERROR
                    $borro_img = 0;
                }
                else
                {
                    //se borro la imagen
                    $borro_img = 1;
                }
      
    echo json_encode($borro_img,JSON_UNESCAPED_UNICODE);
  }
  exit;
}

//para activar/desactivar especial
if ($_POST['action'] == 'activarEspecial')
{
  include "accion/conexion.php";
  if (!empty($_POST['status_especial'])) 
  {
      $status_especial = $_POST['status_especial'];
      
      if($status_especial == "si")
      {
        $newstatus_especial = 1;
      }
      else
      {
        $newstatus_especial = 0;
      }
      //actualizamos en la bd
      $update_especial = mysqli_query($conexion, "UPDATE configuracion set valor_int = $newstatus_especial where configuracion = 'activador_especial'");
      if($update_especial)
      {
        $actualizo_especial = 1;
      }
      else
      {
        $actualizo_especial = 0;
      }
    
    echo json_encode($actualizo_especial,JSON_UNESCAPED_UNICODE);
  }
  exit;
}

//para poner o quitar los campos de especial
if ($_POST['action'] == 'act_des_especial')
{
  include "accion/conexion.php";
  $especial = mysqli_query($conexion, "SELECT valor_int from configuracion where configuracion = 'activador_especial'");
  $status_especial = (int) mysqli_fetch_assoc($especial)['valor_int'];

  echo json_encode($status_especial,JSON_UNESCAPED_UNICODE);
  exit;
}

//para poner o quitar los campos de especial
if ($_POST['action'] == 'identificar_producto')
{
  include "accion/conexion.php";
  $identificador = $_POST['identificador'];
  $find_id = mysqli_query($conexion, "SELECT count(idproducto) as num from producto where identificador = '$identificador'");
  $resul_findproduct = (int) mysqli_fetch_assoc($find_id)['num'];

  echo json_encode($resul_findproduct,JSON_UNESCAPED_UNICODE);
  exit;
}

//para buardar la lista con los nuevos costos
if ($_POST['action'] == 'GuardarEditarLista')
{
  include "accion/conexion.php";
  $array_new_costo = $_POST['array_new_costo'];
  $array_idproducto_newcosto = $_POST['array_idproducto_newcosto'];
  //el de exp_p
  $array_new_ext_p = $_POST['array_new_ext_p'];
  $array_idproducto_newext_p = $_POST['array_idproducto_newext_p'];
  /*
  1. si el nuevo costo es menor al costo actual, actualizar solo si tengo 0
  en existencia mueblearia (Ext_m), de lo contrario no actualizar costo
  2. Si el nuevo costo es mayor que el costo actual, SI ACTUALIZARLO SIRMPRE
  3. Si el mismo, no hacer nada
*/

  //anteriormente calculamos si hay en existencia muebleria
  $ext_m = 0; //-> DE MIENTRAS lo dejamos en 0 porque aun no tenemos de donde calcularlo
  //recorremos los nuevos costos puestos y
  //consultamos el costo actual
  $size_costos = sizeof($array_idproducto_newcosto);
  $resul_save_cost = 1;
  for ($i=0; $i < $size_costos; $i++) 
  { 
    if(!empty($array_new_costo[$i]))
    {
      $select_oldcost = mysqli_query($conexion, "SELECT costo, categoria, subcategoria from producto where idproducto = '$array_idproducto_newcosto[$i]'");
      $data_producto = mysqli_fetch_assoc($select_oldcost);
      $actual_cost = (int) $data_producto['costo'];
      $new_costo = $array_new_costo[$i];
      $new_costo = ceil($new_costo);
      if(($new_costo < $actual_cost and $ext_m == 0) or ($new_costo > $actual_cost))
      {
        //actualizamos el costo y calculamos nuevos costos de lo demas
        //primero vemos si tiene solo cat o sub y de ahi sacar la info
        if($data_producto['subcategoria'] == null)
        {
          //no tiene sub
          $idcategoria = $data_producto['categoria'];
          $info_cat = mysqli_query($conexion, "SELECT contado, especial, base_inicial_c1, ganancia_inicial_c1, rango_c1, ganancia_subsecuente_c1, limite_costo_c1, base_inicial_c2, ganancia_inicial_c2, rango_c2, ganancia_subsecuente_c2, limite_costo_c2, meses_pago from categoria where idcategoria = '$idcategoria'");
          $data_costos = mysqli_fetch_assoc($info_cat);
        }
        else
        {
          //si tiene sub, de ahi sacamos la info
          $idsubcategoria = $data_producto['subcategoria'];
          $info_subcat = mysqli_query($conexion, "SELECT contado, especial, base_inicial_c1, ganancia_inicial_c1, rango_c1, ganancia_subsecuente_c1, limite_costo_c1, base_inicial_c2, ganancia_inicial_c2, rango_c2, ganancia_subsecuente_c2, limite_costo_c2, meses_pago from subcategoria where idsubcategoria = '$idsubcategoria'");
          $data_costos = mysqli_fetch_assoc($info_subcat);
        }
        //ya que tenemos la info, calculamos los nuevos costos
        $contado = $data_costos['contado'];
        $especial = $data_costos['especial'];
        //creditos
        $base_inicial_c1 = $data_costos['base_inicial_c1'];
        $ganancia_inicial_c1 = $data_costos['ganancia_inicial_c1'];
        $rango_c1 = $data_costos['rango_c1'];
        $ganancia_subsecuente_c1 = $data_costos['ganancia_subsecuente_c1'];
        $limite_costo_c1 = $data_costos['limite_costo_c1'];
        //credito 2
        $base_inicial_c2 = $data_costos['base_inicial_c2'];
        $ganancia_inicial_c2 = $data_costos['ganancia_inicial_c2'];
        $rango_c2 = $data_costos['rango_c2'];
        $ganancia_subsecuente_c2 = $data_costos['ganancia_subsecuente_c2'];
        $limite_costo_c2 = $data_costos['limite_costo_c2'];

        $meses_pago = $data_costos['meses_pago'];
        $newcosto_iva = ceil($new_costo + ($new_costo*0.16));
        
        $newcosto_contado = ceil($newcosto_iva + ($newcosto_iva*($contado/100)));
        if($especial == null)
        {
          $newcosto_especial = null;
        }
        else
        {
          $newcosto_especial = ceil($newcosto_contado + ($newcosto_contado*($especial/100)));
        }
        //$newcosto_cr1 = $newcosto_iva  + ($newcosto_iva *($credito1/100));
        //$newcosto_cr2 = $newcosto_iva  + ($newcosto_iva *($credito2/100));
        //credito1 
        $cr1 = 0;
        if($newcosto_iva < $base_inicial_c1)
        {
          $cr1 = $ganancia_inicial_c1;
        }
        else if ($newcosto_iva < ($base_inicial_c1 + $rango_c1))
        {
          $cr1 = $ganancia_subsecuente_c1;
        }
        else
        {
          $costo_temp = $base_inicial_c1 + $rango_c1;//2100
          $ganancia_temp = $ganancia_subsecuente_c1;// 80
          while(true)
          {
            $costo_temp = $costo_temp + $rango_c1;//2200,2300
            $ganancia_temp = $ganancia_temp - 1;//79,78
            //2250<2200, 2250<2300,   //2100<=10000,2200<=10000
            if(($newcosto_iva < $costo_temp) || ($costo_temp >= $limite_costo_c1))
            {
              $cr1 = $ganancia_temp;
              break;
            }
          }
        }
        $newcosto_cr1 = ceil($newcosto_iva + ($newcosto_iva*($cr1/100)));

        //credito2
        $cr2 = 0;
        if($newcosto_iva < $base_inicial_c2)
        {
          $cr2 = $ganancia_inicial_c2;
        }
        else if ($newcosto_iva < ($base_inicial_c2 + $rango_c2))
        {
          $cr2 = $ganancia_subsecuente_c2;
        }
        else
        {
          $costo_temp2 = $base_inicial_c2 + $rango_c2;//2100
          $ganancia_temp2 = $ganancia_subsecuente_c2;// 80
          while(true)
          {
            $costo_temp2 = $costo_temp2 + $rango_c2;//2200,2300
            $ganancia_temp2 = $ganancia_temp2 - 1;//79,78
            //2250<2200, 2250<2300,   //2100<=10000,2200<=10000
            if(($newcosto_iva < $costo_temp2) || ($costo_temp2 >= $limite_costo_c2))
            {
              $cr2 = $ganancia_temp2;
              break;
            }
          }
        }
        $newcosto_cr2 = ceil($newcosto_iva + ($newcosto_iva*($cr2/100)));
        
        $new_e_q = ($newcosto_iva/$meses_pago)/2;
        if($new_e_q < 400)
        {
          $new_e_q = 400;
        }
        $new_e_q = ceil($new_e_q);
        
        $new_p1 = ($newcosto_cr1/$new_e_q)/2;
        $new_p2 = ($newcosto_cr2/$new_e_q)/2;
        $id_producto = $array_idproducto_newcosto[$i];
        //fin calculo de nuevos costos, ahora actualizamos en el producto
        $update_costos = mysqli_query($conexion, "UPDATE producto set costo = $new_costo, costo_iva = $newcosto_iva, costo_contado = $newcosto_contado, costo_especial = ".($newcosto_especial == null ? "NULL" : "$newcosto_especial").", costo_cr1 = $newcosto_cr1, costo_cr2 = $newcosto_cr2, costo_p1 = $new_p1, costo_p2 = $new_p2, costo_eq = $new_e_q where idproducto = '$id_producto'");
        if(!$update_costos)
        {
          $resul_save_cost = 0;
        }
      }
    }
  }
  //==== ahora hacemos lo mismo con el ext_p 
  //recorremos los nuevos ext_p puestos y
  //consultamos los exp_p actuales
  $size_ext_p = sizeof($array_idproducto_newext_p);
  $resul_save_exp = 1;
  for ($i=0; $i < $size_ext_p; $i++) 
  { 
    if(!empty($array_new_ext_p[$i]))
    {
      $select_oldext = mysqli_query($conexion, "SELECT ext_p  from producto where idproducto = '$array_idproducto_newext_p[$i]'");
      $data_producto = mysqli_fetch_assoc($select_oldext);
      $actual_ext_p = (int) $data_producto['ext_p'];
      $new_ext_p = $array_new_ext_p[$i];
      if(($new_ext_p < $actual_ext_p and $ext_m == 0) or ($new_ext_p > $actual_ext_p))
      {
        $id_producto = $array_idproducto_newext_p[$i];
        //ahora actualizamos en el producto
        $update_ext_p = mysqli_query($conexion, "UPDATE producto set ext_p = $new_ext_p where idproducto = '$id_producto'");
        if(!$update_ext_p)
        {
          $resul_save_exp = 0;
        }
      }
    }
  }
  //hacemos el calculo para ver si todo salio bien
  if($resul_save_cost and $resul_save_exp)
  {
    //todo chido
    $resul_save_list = 1;
  }
  else
  {
    //todo mal
    $resul_save_list = 0;
  }

  echo json_encode($resul_save_list,JSON_UNESCAPED_UNICODE);
  exit;
}

//para borrar todos los ext_p de los productos
if ($_POST['action'] == 'Drop_ext_p') 
{
  include "accion/conexion.php";
  //eliminar todos los datos de Ext.-p de todos los productos, sin el where para BORRAR TODO
  $sql_drop_extp = mysqli_query($conexion, "UPDATE producto set ext_p = NULL");
  if($sql_drop_extp)
  {
    $borro_ext_p = 1;
  }
  else
  {
    $borro_ext_p = 0;
  }

  //$borro_ext_p = mysqli_error($conexion);
  echo json_encode($borro_ext_p,JSON_UNESCAPED_UNICODE);
  exit;
}

#select and show the hidden productos (tienen null en Ext_p o Ext_m)
if ($_POST['action'] == 'get_hidden_products') 
{
  ob_start();
  session_start();
  include "accion/conexion.php";
  if (!empty($_POST['atr1'])) 
  {
      $atr1 = $_POST['atr1'];
      $atr2 = $_POST['atr2'];
      $atr3 = $_POST['atr3'];
      $atr4 = $_POST['atr4'];
      $atr5 = $_POST['atr5'];
      $idcategoria = $_POST['idcat'];
      $idsubcategoria = $_POST['idsubcat'];
      
      $cadenaTabla = '
<table class="table" id="tbl_productos">
        <thead class="thead-light">
            <tr>
                <th>Descripción</th>
                <th>Nuevo costo</th>
                <th>Costo actual</th>
                <th>Costo IVA</th>
                <th>Nuevo Ext.-P</th>
                <th>Ext.-P</th>
                <th>Ext.-M</th>
                <th>Contado</th>
                <th>Especial</th>
                <th>CR1</th>
                <th>P1</th>
                <th>CR2</th>
                <th>P2</th>
                <th>Quin</th>
                <th>Enga</th>
                <th>GAR</th>
                <th style="text-align: center;">Herramientas</th>
            </tr>
        </thead>
        <tbody>';
          
            if($atr1 != "LABEL")
            {
              $query_sql = "SELECT * from producto where atr1_producto like '$atr1'";
            }
            else
            {
              $query_sql = "SELECT * from producto WHERE 1";
            }
            //los demas filtros anidados
            if($atr2 != "LABEL")
            {
              $query_sql = $query_sql." and atr2_producto like '$atr2'";
            }
            if($atr3 != "LABEL")
            {
              $query_sql = $query_sql." and atr3_producto like '$atr3'";
            }
            if($atr4 != "LABEL")
            {
              $query_sql = $query_sql." and atr4_producto like '$atr4'";
            }
            if($atr5 != "LABEL")
            {
              $query_sql = $query_sql." and atr5_producto like '$atr5'";
            }

          if($idcategoria != "Selecciona categoría") #no ha seleccionada nada
          {
            if($idsubcategoria == "NoSubcat")
            {
              $query_sql = $query_sql." and categoria = '$idcategoria' order by creado_en desc";
            }
            else
            {
              $query_sql = $query_sql." and categoria = '$idcategoria' and subcategoria = '$idsubcategoria' order by creado_en desc";
            }
          }

            $query = mysqli_query($conexion, $query_sql);
            $result = mysqli_num_rows($query);
            if ($result > 0) 
            {
                while ($data = mysqli_fetch_assoc($query)) 
                {
                  $id_producto = $data['idproducto'];
                  $identificador = $data['identificador'];
                  
                          if($data['subcategoria'] == null)
                          {
                              $idcategoria = $data['categoria'];
                              $query_meses = mysqli_query($conexion, "SELECT meses_garantia from categoria where idcategoria = '$idcategoria'");
                              $garantia = mysqli_fetch_assoc($query_meses)['meses_garantia'];

                              //no tiene sub
                              $select_producto_nosub = mysqli_query($conexion, "SELECT categoria.nombre as catproducto, atr1_producto FROM producto INNER JOIN categoria on categoria.idcategoria = producto.categoria WHERE idproducto = '$id_producto'");
                              $data_producto = mysqli_fetch_assoc($select_producto_nosub);
                              $catproducto = $data_producto['catproducto'];
                              $atr1_producto = $data_producto['atr1_producto'];
                              //creamos la ubicacion
                              $estructura = "../img/catalogo_productos/".$catproducto."/".$atr1_producto;
                          }
                          else
                          {
                              $idsubcategoria = $data['subcategoria'];
                              $query_meses = mysqli_query($conexion, "SELECT meses_garantia from subcategoria where idsubcategoria = '$idsubcategoria'");
                              $garantia = mysqli_fetch_assoc($query_meses)['meses_garantia'];

                              $select_producto_full = mysqli_query($conexion, "SELECT categoria.nombre as catproducto, subcategoria.nombre as subcat_producto, atr1_producto FROM producto INNER JOIN categoria on categoria.idcategoria = producto.categoria INNER JOIN subcategoria on subcategoria.idsubcategoria = producto.subcategoria WHERE idproducto = '$id_producto'");
                              $data_producto = mysqli_fetch_assoc($select_producto_full);
                              $catproducto = $data_producto['catproducto'];
                              $subcat_producto = $data_producto['subcat_producto'];
                              $atr1_producto = $data_producto['atr1_producto'];
                              //creamos la ubicacion
                              $estructura = "../img/catalogo_productos/".$catproducto."/".$subcat_producto."/".$atr1_producto;
                          }                    
                          //aqui vamos a ver si tienen foto o no, para mostrar los iconos acorde
                          $archivador = $estructura."/".$identificador.".png";
                          if(is_file($archivador))
                          {
                              $boton_img = "btn btn-primary btn-sm";
                              $siimagen = 1;
                          }
                          else
                          {
                              $boton_img = "btn btn-secondary btn-sm";
                              $siimagen = 0;
                          }

                      $cadenaTabla = $cadenaTabla.'<tr>
                          <td>'.$data['descripcion'].'</td>
                              <td width="110"><input type="number" name="nuevo_costo[]" id="nuevo_costo[]" class="form-control"><input type="text" name="flag_new_costo_idproducto[]" id="flag_new_costo_idproducto[]" value="'.$id_producto.'" readonly hidden></td>
                              <td>'."$".number_format($data['costo'],2, '.', ',').'</td>
                              <td>'."$".number_format($data['costo_iva'],2, '.', ',').'</td>
                              <td width="110"><input type="number" name="nuevo_ext_p[]" id="nuevo_ext_p[]" class="form-control"><input type="text" name="flag_new_extp_idproducto[]" id="flag_new_extp_idproducto[]" value="'.$id_producto.'" readonly hidden></td>
                              <td>'.$data['ext_p'].'</td>
                              <td></td>
                              <td>'."$".number_format($data['costo_contado'],2, '.', ',').'</td>
                              <td>'."$".number_format($data['costo_especial'],2, '.', ',').'</td>
                              <td>'."$".number_format($data['costo_cr1'],2, '.', ',').'</td>
                              <td>'.round($data['costo_p1'],2).'</td>
                              <td>'."$".number_format($data['costo_cr2'],2, '.', ',').'</td>
                              <td>'.round($data['costo_p2'],2).'</td>
                              <td>'."$".number_format($data['costo_eq'],2, '.', ',').'</td>
                              <td>'."$".number_format($data['costo_enganche'],2, '.', ',').'</td>
                              <td>'.$garantia." Ms".'</td>';

                      $cadenaTabla = $cadenaTabla.'<td align="center">';

                      $id_usuario = $_SESSION['iduser'];
                      $sqlpermisos_usuario = mysqli_query($conexion, "SELECT permiso_idpermiso FROM permiso_usuario where permiso_idusuario = '$id_usuario'");
                      $array_permisos = [];
                          while($row = mysqli_fetch_assoc($sqlpermisos_usuario)) 
                          {
                              $array_permisos[] = $row['permiso_idpermiso'];
                          }
                          #print_r($array_permisos);
                          $num_permisos = sizeof($array_permisos);
                          #PERMISOS
                          if($_SESSION['rol'] == "SuperAdmin")
                          {
                            #es super admin y titene permiso a TODO
                            $editar_productos = 1;
                            $eliminar_productos = 1;
                            $imagenes = 1;
                          }
                          else
                          {
                            #permisos asignados
                            $editar_productos = in_array(6, $array_permisos);
                            $eliminar_productos = in_array(7, $array_permisos);
                            $imagenes =  in_array(8, $array_permisos);
                          }

                          $cadenaTabla = $cadenaTabla.'<button data-toggle="modal" data-target="#img_producto" onclick="mostrar_img(\''.$id_producto.'\',\''.$archivador.'\',\''.$siimagen.'\');" class="'.$boton_img.'"><i class="fas fa-camera"></i></button>';
                          $cadenaTabla = $cadenaTabla."&nbsp;";
                          if($editar_productos)
                          {
                            $cadenaTabla = $cadenaTabla.'<button class="btn btn-success btn-sm" data-toggle="modal" data-target="#nuevo_producto" onclick="editar_producto(\''.$id_producto.'\');"><i class="fas fa-edit"></i></button>';
                          }
                          else
                          {
                            $cadenaTabla = $cadenaTabla.'<button disabled="disabled" class="btn btn-success btn-sm"><i class="fas fa-edit"></i></button>';
                          }
                          $cadenaTabla = $cadenaTabla."&nbsp;";
                          if($eliminar_productos)
                          {
                            $cadenaTabla = $cadenaTabla.'<button onClick="eliminar_producto(\''.$id_producto.'\');" class="btn btn-danger btn-sm" type="submit"><i style="color: white;" class="fas fa-trash-alt"></i></button>';
                          }
                          else
                          {
                            $cadenaTabla = $cadenaTabla.'<button disabled="disabled" class="btn btn-danger btn-sm" type="submit"><i style="color: white;" class="fas fa-trash-alt"></i></button>';
                          }
                      $cadenaTabla = $cadenaTabla.'</td></tr>';
              }
            }
        $cadenaTabla = $cadenaTabla.'</tbody></table>';

        $cadenaTabla = array("cadenaTabla" => $cadenaTabla);
        $resultFindforAtr = $cadenaTabla;
    
    #$resultFindforAtr = $query_sql;
    echo json_encode($resultFindforAtr,JSON_UNESCAPED_UNICODE);
  }
  ob_end_flush();
  exit;
}

//para buscar el cliente con en su IDCliente
if ($_POST['action'] == 'buscar_x_id_cliente') 
{
    include "accion/conexion.php";
    $id_cliente = $_POST['idcliente'];
    $select_cliente = mysqli_query($conexion, "SELECT idcliente, nombre_cliente, tel1_cliente, zona, subzona FROM cliente WHERE idcliente = '$id_cliente' AND estado_cliente = 1");
    $data_cliente = mysqli_fetch_assoc($select_cliente);

    $idzona = $data_cliente['zona'];
    $select_cliente_subzona = mysqli_query($conexion, "SELECT idsubzona,subzona from subzonas where idzona = '$idzona'");
    $opciones_subzona = "";
    while($row = mysqli_fetch_assoc($select_cliente_subzona))
    {
      $opciones_subzona = $opciones_subzona."<option value='".$row["idsubzona"]."'>".$row["subzona"]."</option>";
    }
    $data_subzona = array("data_subzona" => $opciones_subzona);

    $result = $data_cliente + $data_subzona;

    echo json_encode($result,JSON_UNESCAPED_UNICODE);
}

//para buscar el cliente con en su IDCliente
if ($_POST['action'] == 'buscar_x_nombre_cliente') 
{
    include "accion/conexion.php";
    $id_cliente = $_POST['idcliente'];
    $select_cliente = mysqli_query($conexion, "SELECT idcliente, nombre_cliente, tel1_cliente, zona, subzona FROM cliente WHERE idcliente = '$id_cliente' AND estado_cliente = 1");
    $data_cliente = mysqli_fetch_assoc($select_cliente);

    $idzona = $data_cliente['zona'];
    $select_cliente_subzona = mysqli_query($conexion, "SELECT idsubzona,subzona from subzonas where idzona = '$idzona'");
    $opciones_subzona = "";
    while($row = mysqli_fetch_assoc($select_cliente_subzona))
    {
      $opciones_subzona = $opciones_subzona."<option value='".$row["idsubzona"]."'>".$row["subzona"]."</option>";
    }
    $data_subzona = array("data_subzona" => $opciones_subzona);

    $result = $data_cliente + $data_subzona;

    echo json_encode($result,JSON_UNESCAPED_UNICODE);
}

//para buscar los productos que existen y mostrarlos
if ($_POST['action'] == 'selectAll_productos') 
{
  include "accion/conexion.php";
  //eliminar todos los datos de Ext.-p de todos los productos, sin el where para BORRAR TODO
  $result = mysqli_query($conexion,"SELECT idproducto,identificador FROM producto order by identificador");
  $result_producto = mysqli_num_rows($result);
  if ($result_producto > 0) 
  {
     $opciones_producto = "<option selected hidden value=''></option>";
     while($row = mysqli_fetch_assoc($result))
     {
       $opciones_producto = $opciones_producto."<option value='".$row["idproducto"]."'>".$row["identificador"]."</option>";
     }
     $data_producto = array("data_producto" => $opciones_producto);
  }
  else 
  {
    $data_producto = 0;
  }
  echo json_encode($data_producto,JSON_UNESCAPED_UNICODE);
  exit;
}

//para buscar los precios del producto que seleciono
if ($_POST['action'] == 'findPrecioProducto') 
{
  include "accion/conexion.php";
  $idtipo_precio = $_POST['idtipo_precio'];
  $idproducto = $_POST['idproducto'];
  //eliminar todos los datos de Ext.-p de todos los productos, sin el where para BORRAR TODO
  $result = mysqli_query($conexion,"SELECT idproducto,costo,costo_iva,costo_contado,costo_especial,costo_cr1,costo_cr2,costo_p1,costo_p2,costo_eq,costo_enganche FROM producto where idproducto = '$idproducto'");

  $data_precios = mysqli_fetch_assoc($result);
  
  echo json_encode($data_precios,JSON_UNESCAPED_UNICODE);
  exit;
}

//para buscar el numero del proveedor
if ($_POST['action'] == 'buscar_tel_proveedor') 
{
  include "accion/conexion.php";
  $idproveedor = $_POST['idproveedor'];
  //eliminar todos los datos de Ext.-p de todos los productos, sin el where para BORRAR TODO
  $result = mysqli_query($conexion,"SELECT tel_proveedor from proveedor where idproveedor = '$idproveedor'");

  $data_tel_p = mysqli_fetch_assoc($result);
  
  echo json_encode($data_tel_p,JSON_UNESCAPED_UNICODE);
  exit;
}

//para saber si es serializado o no
if ($_POST['action'] == 'buscar_si_es_serializado') 
{
  include "accion/conexion.php";
  $idproducto = $_POST['idproducto'];
  //eliminar todos los datos de Ext.-p de todos los productos, sin el where para BORRAR TODO
  $result = mysqli_query($conexion,"SELECT serializado from producto where idproducto = '$idproducto'");
  $es_serializado = (int) mysqli_fetch_assoc($result)['serializado'];
  
  echo json_encode($es_serializado,JSON_UNESCAPED_UNICODE);
  exit;
}

//para buscar las series
if ($_POST['action'] == 'buscar_series_del_producto') 
{
  include "accion/conexion.php";
  $idproducto = $_POST['idproducto'];
  $result = mysqli_query($conexion,"SELECT identrada_producto_serie,serie FROM entrada_productos_serie where producto = '$idproducto' AND vendido = 0 order by serie");
  //$opciones_serie = "<option selected hidden value='0'></option>";
  $opciones_serie = "";
     while($row = mysqli_fetch_assoc($result))
     {
       $select_folio = mysqli_query($conexion,"SELECT entrada_productos_serie.serie,entrada.folio_compra AS folio from entrada_productos_serie INNER JOIN entrada on entrada_productos_serie.entrada = entrada.identrada WHERE entrada_productos_serie.producto = '$idproducto' AND entrada_productos_serie.identrada_producto_serie = '$row[identrada_producto_serie]' order by entrada_productos_serie.serie");
       $folio = mysqli_fetch_assoc($select_folio)['folio'];
       $opciones_serie = $opciones_serie."<option value='".$row["identrada_producto_serie"]."'>".$folio.'-'.$row["serie"]."</option>";
     }
     $data_series = array("series" => $opciones_serie);
  
  echo json_encode($data_series,JSON_UNESCAPED_UNICODE);
  exit;
}

//eliminado logico de entrada, por ahora no se va a usar, faltaria restar del inventario y esta muy cabron eso
if ($_POST['action'] == 'eliminar_entrada') 
{
  include "accion/conexion.php";
  $identrada = $_POST['identrada'];
  //eliminar todos los datos de Ext.-p de todos los productos, sin el where para BORRAR TODO
  $result = mysqli_query($conexion,"UPDATE entrada set borrado_logico = 1 where identrada = '$identrada'");
  if($result)
  {
    $borro_entrada = 1;
  }
  else
  {
    $borro_entrada = 0;
  }
  
  echo json_encode($borro_entrada,JSON_UNESCAPED_UNICODE);
  exit;
}

if ($_POST['action'] == 'suspender_entrada') 
{
  include "accion/conexion.php";
  $identrada = $_POST['identrada'];
  //eliminar todos los datos de Ext.-p de todos los productos, sin el where para BORRAR TODO
  $result = mysqli_query($conexion,"UPDATE entrada set activo = 0 where identrada = '$identrada'");
  if($result)
  {
    $borro_entrada = 1;
  }
  else
  {
    $borro_entrada = 0;
  }
  
  echo json_encode($borro_entrada,JSON_UNESCAPED_UNICODE);
  exit;
}

if ($_POST['action'] == 'suspender_salida') 
{
  include "accion/conexion.php";
  $idsalida = $_POST['idsalida'];
  //eliminar todos los datos de Ext.-p de todos los productos, sin el where para BORRAR TODO
  $result = mysqli_query($conexion,"UPDATE salida set activo = 0 where idsalida = '$idsalida'");
  if($result)
  {
    $borro_salida = 1;
  }
  else
  {
    $borro_salida = 0;
  }
  
  echo json_encode($borro_salida,JSON_UNESCAPED_UNICODE);
  exit;
}

//para transferir almacen
if ($_POST['action'] == 'SelectAlmacen') 
{
  include "accion/conexion.php";
  $idproducto = $_POST['producto'];
  
  $result = mysqli_query($conexion,"SELECT identrada_producto_serie,serie FROM entrada_productos_serie where producto = '$idproducto' AND vendido = 0 order by serie");
  //$opciones_serie = "<option selected hidden value='0'></option>";
  $opciones_serie = "";
     while($row = mysqli_fetch_assoc($result))
     {
       $select_folio = mysqli_query($conexion,"SELECT entrada_productos_serie.serie,entrada.folio_compra AS folio from entrada_productos_serie INNER JOIN entrada on entrada_productos_serie.entrada = entrada.identrada WHERE entrada_productos_serie.producto = '$idproducto' AND entrada_productos_serie.identrada_producto_serie = '$row[identrada_producto_serie]' order by entrada_productos_serie.serie");
       $folio = mysqli_fetch_assoc($select_folio)['folio'];
       $opciones_serie = $opciones_serie."<option value='".$row["identrada_producto_serie"]."'>".$folio.'-'.$row["serie"]."</option>";
     }

     $result_name_p = mysqli_query($conexion,"SELECT identificador from producto where idproducto = '$idproducto'");
     $name_producto = mysqli_fetch_assoc($result_name_p)['identificador'];

     $data_series = array("series" => $opciones_serie);
     $identificador = array("name_producto" => $name_producto);
     $resultTransAlmacen = $data_series + $identificador;

  
  echo json_encode($resultTransAlmacen,JSON_UNESCAPED_UNICODE);
  exit;
}

//para editar el movimiento, EN PROCESO
if ($_POST['action'] == 'SelectMovimiento') 
{
  include "accion/conexion.php";
  $idmovimiento = $_POST['movimiento'];
  //eliminar todos los datos de Ext.-p de todos los productos, sin el where para BORRAR TODO
  $result = mysqli_query($conexion,"SELECT fecha, abono, descuento, recargo, saldo_al_momento from movimiento where idmovimiento = '$idmovimiento'");

  $data_mov = mysqli_fetch_assoc($result);
  
  echo json_encode($data_mov,JSON_UNESCAPED_UNICODE);
  exit;
}

//para sacar los datos para el forecasting
if ($_POST['action'] == 'forecasting') 
{
  include "accion/conexion.php";
  $fecha_inicio = $_POST['fecha_inicio'];
  $fecha_fin = $_POST['fecha_fin'];
  $en_posesion = $_POST['en_posesion'];
  $cobrador = $_POST['cobrador'];

  //LA UNICA FORMA ES CALCULAR LAS FEHCAS DE PAGO DE CADA SALIDA ENTRE LAS FECHAS INDICADAS Y HOY, como se hace en la tabla de historial, para de ahi ir calculando cuando se espera cobrar
  

  //ESTO ES PARA EL MENSUAL
  $query_mensual = mysqli_query($conexion,"SELECT idsalida,no_pagos,pago_parcial,per_dia_pago,dias_pago_mensual,enganche,total_general,nivel_salida FROM salida where modalidad_pago = 'mensual' AND activo = 1 AND per_dia_pago >= '$fecha_inicio'");
  if(mysqli_num_rows($query_mensual) > 0)
  {
   $total_forecast_mensual = 0;
   while ($row = mysqli_fetch_assoc($query_mensual)) 
    {
      $idsalida = $row['idsalida'];
      $query_mov = mysqli_query($conexion, "SELECT saldo_al_momento FROM movimiento WHERE salida = '$idsalida' order by creado_en DESC limit 1");
      $debe_al_momento = floatval(mysqli_fetch_assoc($query_mov)['saldo_al_momento']);
      $cobrar_mensual = $debe_al_momento;
      if($debe_al_momento < $row['pago_parcial'])
      {
        $cobrar_mensual = $row['pago_parcial'];
      }
      list($anio_i, $mes_i, $dia_i) = explode("-", $fecha_inicio);
      list($anio_f, $mes_f, $dia_f) = explode("-", $fecha_fin);
      if($row['dias_pago_mensual'] >= $dia_i and $row['dias_pago_mensual'] <= $dia_f)
      {
        $total_forecast_mensual = $total_forecast_mensual + $cobrar_mensual;
      }
    }   
  }
  


  $data_forecast = $total_forecast_mensual;
  //$data_forecast = mysqli_error($conexion);
  echo json_encode($data_forecast,JSON_UNESCAPED_UNICODE);
  exit;
}

