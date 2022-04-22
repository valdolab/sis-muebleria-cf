<?php 
ob_start();
include_once "header.php";
include "accion/conexion.php";

if (!empty($_POST)) 
{
    $ban = $_POST['action'];
    if($ban == "insert_producto")
    {
        //insertamos el nuevo producto
        $identificador = $_POST['identificador'];
        $codigo_barras = $_POST['codigo_barras'];
        $categoria_producto = $_POST['categoria_producto'];
        if(isset($_POST['subcategoria_producto']))
        {
            $subcategoria_producto = $_POST['subcategoria_producto'];
        }
        else
        {
            $subcategoria_producto = 0;
        }
        //$subcategoria_producto = $_POST['subcategoria_producto'];
        $descripcion = $_POST['descripcion'];
        if (isset($_POST['serializado']))
            {
                $serializado = 1;
            }
            else
            {
                $serializado = 0;
            }
        $atr1_producto = $_POST['atr1_producto'];
        $atr2_producto = $_POST['atr2_producto'];
        $atr3_producto = $_POST['atr3_producto'];
        $atr4_producto = $_POST['atr4_producto'];
        $atr5_producto = $_POST['atr5_producto'];
        $stock_min = $_POST['stock_min'];
        $stock_max = $_POST['stock_max'];
        $ext_p = $_POST['ext_p'];
        $costo = $_POST['costo'];
        $costo_iva = $_POST['costo_iva'];
        $costo_contado = $_POST['costo_contado'];
        $costo_especial = $_POST['costo_especial'];
        $costo_cr1 = $_POST['costo_cr1'];
        $costo_cr2 = $_POST['costo_cr2'];
        $costo_p1 = $_POST['costo_p1'];
        $costo_p2 = $_POST['costo_p2'];
        $costo_eq = $_POST['costo_eq'];

        /*echo $costo;
        echo "/";
        echo $costo_contado;
        echo "/";
        echo $subcategoria_producto;*/
        
        $insert_producto = mysqli_query($conexion, "INSERT INTO producto(idproducto, identificador, codigo_barras, categoria, subcategoria, descripcion, serializado, atr1_producto, atr2_producto, atr3_producto, atr4_producto, atr5_producto, stock_min, stock_max, ext_p, costo, costo_iva, costo_contado, costo_especial, costo_cr1, costo_cr2, costo_p1, costo_p2, costo_eq) VALUES (UUID(),".(!empty($identificador) ? "'$identificador'" : "NULL").", ".(!empty($codigo_barras) ? "'$codigo_barras'" : "NULL").", '$categoria_producto', ".($subcategoria_producto!=0 ? "'$subcategoria_producto'" : "NULL").", ".(!empty($descripcion) ? "'$descripcion'" : "NULL").", $serializado, '$atr1_producto', ".(!empty($atr2_producto) ? "'$atr2_producto'" : "NULL").", ".(!empty($atr3_producto) ? "'$atr3_producto'" : "NULL").", ".(!empty($atr4_producto) ? "'$atr4_producto'" : "NULL").", ".(!empty($atr5_producto) ? "'$atr5_producto'" : "NULL").", ".(!empty($stock_min) ? "'$stock_min'" : "NULL").", ".(!empty($stock_max) ? "'$stock_max'" : "NULL").", ".(!empty($ext_p) ? "'$ext_p'" : "NULL").", $costo, $costo_iva, $costo_contado, $costo_especial, $costo_cr1, $costo_cr2, $costo_p1, $costo_p2, $costo_eq)");
        if ($insert_producto) 
        {
            $modal = "$('#mensaje_success').modal('show');";
        }
        else
        {
            $modal = "$('#mensaje_error').modal('show');";
            //echo mysqli_error($conexion);
        }
    }
}
?>

<div style="posicion: fixed; top: 15%;" id="mensaje_success" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-group">
                    
                    <div align="center" >
                        <br>
                        <!-- <img src="../img/ok.gif" width="100px" height="100px"> -->
                        <div class="swal2-header">
                            <div class="swal2-icon swal2-success swal2-icon-show" style="display: flex;">
                                <div class="swal2-success-circular-line-left" style="background-color: rgb(255, 255, 255);"></div>
                                <span class="swal2-success-line-tip"></span>
                                <span class="swal2-success-line-long"></span>
                                <div class="swal2-success-ring"></div>
                                <div class="swal2-success-fix" style="background-color: rgb(255, 255, 255);"></div>
                                <div class="swal2-success-circular-line-right" style="background-color: rgb(255, 255, 255);"></div>
                            </div>    
                            <h2 id="swal2-title" class="swal2-title" style="display: flex;">¡Listo!</h2>
                        </div>

                        <div class="swal2-content">
                            <div id="swal2-content" class="swal2-html-container" style="display: block;">
                                Producto registrado correctamete
                            </div>
                        </div>
                        <div class="swal2-actions">
                            <a href="productos.php" class="swal2-confirm swal2-styled" type="button" style="display: inline-block;">Ok</a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div style="posicion: fixed; top: 15%;" id="mensaje_error" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-group">
                    
                    <div align="center" >
                        <br>
                        <!-- <img src="../img/ok.gif" width="100px" height="100px"> -->
                        <div class="swal2-header">
                            <div class="swal2-icon swal2-error swal2-icon-show" style="display: flex;">
                                <span class="swal2-x-mark">
                                    <span class="swal2-x-mark-line-left"></span>
                                    <span class="swal2-x-mark-line-right"></span>
                                </span>
                            </div>    
                            <h2 id="swal2-title" class="swal2-title" style="display: flex;">Oops... Ocurrio un problema!</h2>
                        </div>

                        <div class="swal2-content">
                            <div id="swal2-content" class="swal2-html-container" style="display: block;">
                                El nuevo producto no se guardo correctamente, intente nuevamente.
                            </div>
                        </div>
                        <div class="swal2-actions">
                            <a href="productos.php" class="swal2-confirm swal2-styled" type="button" style="display: inline-block;">Ok</a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div id="prueba">
    <!-- <input type="text" name="pruebai" id="pruebai">-->
</div>

<div id="img_producto" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
        <form action="" method="post" autocomplete="on" id="formAdd_img">
            <div class="modal-header">
                <h3 class="modal-title">Imagén del producto</h3>
                <div class="row">
                    <div class="col-lg-6">
                        <button type="button" class="btn btn-lg btn-danger" data-dismiss="modal" aria-label="Close" id="btn_cancerlarimg">
                            Cancelar
                        </button>
                    </div>
                    <div class="col-lg-6">
                        <input type="submit" value="Subir" class="btn btn-lg btn-success" id="btn_subirimg">
                    </div>
                </div>
            </div>
            <br>
            <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-10">
                          <div class="form-group">
                                <label for="nombre_cat">Cargar la imágen del producto que se desee subir en <strong>jpg</strong> o <strong>png</strong></label>
                                  <div class="custom-file">
                                      <input type="file" class="custom-file-input" id="customFileLang" lang="es">
                                      <label class="custom-file-label" for="customFileLang" data-browse="Seleccionar imágen">Ninguna imágen selecionada</label>
                                    </div>
                            </div>  
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-8">
                            <h4><strong>Imágen actual del producto:</strong></h4>
                            <br>
                            <img src="../img/compra_facil.png" height="300" width="300">
                        </div>
                    </div>
                    <input value="load_img" name="action" id="action" hidden>
            </div>
        </form>
        </div>
    </div>
</div>

<div id="nueva_cat" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
        <form action="" method="post" autocomplete="on" id="formAdd_cat">
            <div class="modal-header">
                <h3 class="modal-title">Detalle Categoría</h3>
                <div class="row">
                    <div class="col-lg-6">
                        <button type="button" class="btn btn-lg btn-danger" data-dismiss="modal" aria-label="Close" id="btn_cancerlarcat">
                            Cancelar
                        </button>
                    </div>
                    <div class="col-lg-6">
                        <input type="submit" value="Guardar" class="btn btn-lg btn-success" id="btn_guardarcat">
                    </div>
                </div>
            </div>
            <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-4">
                          <div class="form-group">
                                <label for="nombre_cat">Nombre</label>
                                <div class="input-group">
                                  <input type="text" class="form-control" name="nombre_cat" id="nombre_cat" required onkeyup="mayusculas(this)">
                                  &nbsp;
                                  <input id="tiene_subcat" name="tiene_subcat" type="checkbox" data-toggle="toggle" data-onstyle="primary" data-offstyle="secondary" data-size="m" data-on="SI" data-off="NO">
                                </div>
                            </div>  
                        </div>

                        <div class="col-lg-4">
                          <div class="form-group">
                                <label for="atr1">Atributo 1</label>
                                <input type="text" class="form-control" name="atr1" id="atr1" disabled value="MARCA" onkeyup="mayusculas(this)">
                            </div>  
                        </div>

                        <div class="col-lg-4">
                          <div class="form-group">
                                <label for="atr2">Atributo 2</label>
                                <input type="text" class="form-control" name="atr2" id="atr2" onkeyup="mayusculas(this)">
                            </div>  
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                          <div class="form-group">
                                <label for="atr3">Atributo 3</label>
                                <input type="text" class="form-control" name="atr3" id="atr3" onkeyup="mayusculas(this)">
                            </div>  
                        </div>

                        <div class="col-lg-4">
                          <div class="form-group">
                                <label for="atr4">Atributo 4</label>
                                <input type="text" class="form-control" name="atr4" id="atr4" onkeyup="mayusculas(this)">
                            </div>  
                        </div>

                        <div class="col-lg-4">
                          <div class="form-group">
                                <label for="atr5">Atributo 5</label>
                                <input type="text" class="form-control" name="atr5" id="atr5" onkeyup="mayusculas(this)">
                            </div>  
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="form-group col-lg-3">
                            <label for="contado">Contado:</label>
                            <div class="input-group mb-3">
                              <input name="contado" id="contado" type="number" class="form-control" aria-label="Monto en pesos mexicanos" required>
                              <div class="input-group-append">
                                <span class="input-group-text">%</span>
                              </div>
                            </div>
                        </div>
                        <div class="form-group col-lg-3">
                            <label for="especial">Especial:</label>
                            <div class="input-group mb-3">
                              <input name="especial" id="especial" type="number" class="form-control" aria-label="Monto en pesos mexicanos" required>
                              <div class="input-group-append">
                                <span class="input-group-text">%</span>
                              </div>
                            </div>
                        </div>
                        <div class="form-group col-lg-3">
                            <label for="credito1">Credito 1:</label>
                            <div class="input-group mb-3">
                              <input name="credito1" id="credito1" type="number" class="form-control" aria-label="Monto en pesos mexicanos" required>
                              <div class="input-group-append">
                                <span class="input-group-text">%</span>
                              </div>
                            </div>
                        </div>
                        <div class="form-group col-lg-3">
                            <label for="credito2">Credito 2:</label>
                            <div class="input-group mb-3">
                              <input name="credito2" id="credito2" type="number" class="form-control" aria-label="Monto en pesos mexicanos" required>
                              <div class="input-group-append">
                                <span class="input-group-text">%</span>
                              </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3"></div>
                        <div class="form-group col-lg-3">
                            <label for="mesespago">Meses de pago:</label>
                            <input name="mesespago" id="mesespago" type="number" class="form-control" aria-label="Monto en pesos mexicanos" required>
                        </div>
                        <div class="form-group col-lg-3">
                            <label for="garantia">Meses de garantía:</label>
                            <input name="garantia" id="garantia" type="number" class="form-control" aria-label="Monto en pesos mexicanos" required>
                        </div>
                    </div>
                    <input value="insert_categoria" name="action" id="action" hidden>
                    <input type="text" value="" id="flagidcategoria" name="flagidcategoria" hidden>
            </div>
        </form>
        </div>
    </div>
</div>

<div id="nueva_subcat" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
        <form action="" method="post" autocomplete="on" id="formAdd_subcat">
            <div class="modal-header">
                <h3 class="modal-title">Detalle Subcategoría</h3>
                <div class="row">
                    <div class="col-lg-6">
                        <!-- agrandar SELECT -->
                        <select class="form-control" id="categoria_subcategoria" name="categoria_subcategoria">
                                <option hidden selected>Selecciona categoría</option>
                                <?php
                                    #codigo para la lista de sucursales que se extraen de la base de datos
                                    $result_cat = mysqli_query($conexion,"SELECT idcategoria,nombre FROM categoria order by nombre asc");
                                    if (mysqli_num_rows($result_cat) > 0) 
                                    {  
                                      while($row = mysqli_fetch_assoc($result_cat))
                                      {
                                        echo "<option value='".$row["idcategoria"]."'>".$row["nombre"]."</option>";
                                      }
                                    }
                                ?>  
                            </select>
                    </div>
                    <div class="col-lg-3">
                        <button type="button" class="btn btn-lg btn-danger" data-dismiss="modal" aria-label="Close" id="btn_cancerlarsubcat">
                            Cancelar
                        </button>
                    </div>
                    <div class="col-lg-3">
                        <input type="submit" class="btn btn-lg btn-success" value="Guardar" id="btn_guardarsubcat">
                    </div>
                </div>
            </div>
            <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-4">
                          <div class="form-group">
                                <label for="nombre_subcat">Nombre</label>
                                <input type="text" class="form-control" name="nombre_subcat" id="nombre_subcat" required onkeyup="mayusculas(this);">
                            </div>  
                        </div>

                        <div class="col-lg-4">
                          <div class="form-group">
                                <label for="atr1_sub">Atributo 1</label>
                                <input type="text" class="form-control" name="atr1_sub" id="atr1_sub" value="MARCA" disabled>
                            </div>  
                        </div>

                        <div class="col-lg-4">
                          <div class="form-group">
                                <label for="atr2_sub">Atributo 2</label>
                                <input type="text" class="form-control" name="atr2_sub" id="atr2_sub" onkeyup="mayusculas(this);">
                            </div>  
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                          <div class="form-group">
                                <label for="atr3_sub">Atributo 3</label>
                                <input type="text" class="form-control" name="atr3_sub" id="atr3_sub" onkeyup="mayusculas(this);">
                            </div>  
                        </div>

                        <div class="col-lg-4">
                          <div class="form-group">
                                <label for="atr4_sub">Atributo 4</label>
                                <input type="text" class="form-control" name="atr4_sub" id="atr4_sub" onkeyup="mayusculas(this);">
                            </div>  
                        </div>

                        <div class="col-lg-4">
                          <div class="form-group">
                                <label for="atr5_sub">Atributo 5</label>
                                <input type="text" class="form-control" name="atr5_sub" id="atr5_sub" onkeyup="mayusculas(this);">
                            </div>  
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="form-group col-lg-3">
                            <label for="contado_sub">Contado:</label>
                            <div class="input-group mb-3">
                              <input name="contado_sub" id="contado_sub" type="number" class="form-control" aria-label="Monto en pesos mexicanos">
                              <div class="input-group-append">
                                <span class="input-group-text">%</span>
                              </div>
                            </div>
                        </div>
                        <div class="form-group col-lg-3">
                            <label for="especial_sub">Especial:</label>
                            <div class="input-group mb-3">
                              <input name="especial_sub" id="especial_sub" type="number" class="form-control" aria-label="Monto en pesos mexicanos" required>
                              <div class="input-group-append">
                                <span class="input-group-text">%</span>
                              </div>
                            </div>
                        </div>
                        <div class="form-group col-lg-3">
                            <label for="credito1_sub">Credito 1:</label>
                            <div class="input-group mb-3">
                              <input name="credito1_sub" id="credito1_sub" type="number" class="form-control" aria-label="Monto en pesos mexicanos" required>
                              <div class="input-group-append">
                                <span class="input-group-text">%</span>
                              </div>
                            </div>
                        </div>
                        <div class="form-group col-lg-3">
                            <label for="credito2_sub">Credito 2:</label>
                            <div class="input-group mb-3">
                              <input name="credito2_sub" id="credito2_sub" type="number" class="form-control" aria-label="Monto en pesos mexicanos" required>
                              <div class="input-group-append">
                                <span class="input-group-text">%</span>
                              </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3"></div>
                        <div class="form-group col-lg-3">
                            <label for="mesespagosub">Meses de pago:</label>
                            <input name="mesespago_sub" id="mesespago_sub" type="number" class="form-control" aria-label="Monto en pesos mexicanos" required>
                        </div>
                        <div class="form-group col-lg-3">
                            <label for="garantia_sub">Meses garantía:</label>
                            <input name="garantia_sub" id="garantia_sub" type="number" class="form-control" aria-label="Monto en pesos mexicanos" required>
                        </div>
                    </div>
                    <input value="insert_subcategoria" name="action" id="action" hidden>
                    <input type="text" value="" id="flagidsubcategoria" name="flagidsubcategoria" hidden>
            </div>
        </form>
        </div>
    </div>
</div>

<div id="nuevo_producto" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog  modal-xl" role="document">
        <div class="modal-content">
        <form action="" method="post" autocomplete="on" id="formAdd_producto">
            <div class="modal-header">
                <h3 class="modal-title">Detalle del Producto</h3>
                <div class="row">
                    <div class="col-lg-6">
                        <button type="button" class="btn btn-lg btn-danger" data-dismiss="modal" aria-label="Close">
                            Cancelar
                        </button>
                    </div>
                    <div class="col-lg-6">
                        <input type="submit" value="Guardar" class="btn btn-lg btn-success">
                    </div>
                </div>
            </div>
            <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-3">
                          <div class="form-group">
                                <label for="nombre_cat">Identificador</label>
                                <input type="text" class="form-control" name="identificador" id="identificador">
                            </div>  
                        </div>

                        <div class="col-lg-3">
                          <div class="form-group">
                                <label for="atr1">Código de Barras</label>
                                <input type="text" class="form-control" name="codigo_barras" id="codigo_barras">
                            </div>  
                        </div>

                        <div class="col-lg-2">
                            <label>Categoría:</label>
                            <select class="form-control" id="categoria_producto" name="categoria_producto" required>
                                <option selected hidden value="">Selecciona categoría</option>
                                <?php
                                    #codigo para la lista de sucursales que se extraen de la base de datos
                                    $result_cat = mysqli_query($conexion,"SELECT idcategoria,nombre FROM categoria order by nombre asc");
                                    if (mysqli_num_rows($result_cat) > 0) 
                                    {  
                                      while($row = mysqli_fetch_assoc($result_cat))
                                      {
                                        echo "<option value='".$row["idcategoria"]."'>".$row["nombre"]."</option>";
                                      }
                                    }
                                ?>  
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <label>Subcategoriía:</label>
                            <select class="form-control" id="subcategoria_producto" name="subcategoria_producto" required>
                                <option selected hidden value="">Selecciona subcategoría</option>
                            </select>
                        </div>
                        <div class="col-lg-1">
                            <label for="serializado">Serializado</label>
                            <input onchange="" id="serializado" name="serializado" value="si_serializado" type="checkbox" data-toggle="toggle" data-onstyle="primary" data-offstyle="secondary" data-size="m" data-on="SI" data-off="NO">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <label>Descripción</label>
                            <textarea  class="form-control" name="descripcion" title="Ingrese la descripción del producto" id="descripcion" placeholder="Ingrese la descripción detallada del producto" maxlength="50000"></textarea>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-4">
                          <div class="form-group">
                                <label for="atr1">Atributo 1: </label>
                                <label id="label_atr1" style="font-weight: bold;"></label>
                                <input type="text" class="form-control" name="atr1_producto" id="atr1_producto" readonly required>
                            </div>  
                        </div>
                        <div class="col-lg-4">
                          <div class="form-group">
                                <label for="atr1">Atributo 2: </label>
                                <label id="label_atr2" style="font-weight: bold;"></label>
                                <input type="text" class="form-control" name="atr2_producto" id="atr2_producto" readonly required>
                            </div>  
                        </div>
                        <div class="col-lg-4">
                          <div class="form-group">
                                <label for="atr1">Atributo 3: </label>
                                <label id="label_atr3" style="font-weight: bold;"></label>
                                <input type="text" class="form-control" name="atr3_producto" id="atr3_producto" readonly required>
                            </div>  
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                          <div class="form-group">
                                <label for="atr1">Atributo 4: </label>
                                <label id="label_atr4" style="font-weight: bold;"></label>
                                <input type="text" class="form-control" name="atr4_producto" id="atr4_producto" readonly required>
                            </div>  
                        </div>
                        <div class="col-lg-4">
                          <div class="form-group">
                                <label for="atr1">Atributo 5: </label>
                                <label id="label_atr5" style="font-weight: bold;"></label>
                                <input type="text" class="form-control" name="atr5_producto" id="atr5_producto" readonly required>
                            </div>  
                        </div>
                        <div class="col-lg-2">
                            <label for="stock_min">Stock Min: </label>
                            <input type="text" class="form-control" name="stock_min" id="stock_min">
                        </div>
                        <div class="col-lg-2">
                            <label for="stock_max">Stock Max: </label>
                            <input type="text" class="form-control" name="stock_max" id="stock_max">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label for="ext_p">EXT.-P:</label>
                              <input name="ext_p" id="ext_p" type="number" class="form-control" aria-label="Monto en pesos mexicanos">
                        </div>
                        <div class="form-group col">
                            <label for="costo">COSTO:</label>
                            <div class="input-group mb-3">
                                <div class="input-group-append">
                                <span class="input-group-text">$</span>
                              </div>
                              <input name="costo" id="costo" type="number" class="form-control" aria-label="Monto en pesos mexicanos" required min="0" onkeypress="return event.charCode != 45">
                            </div>
                        </div>
                        <div class="form-group col">
                            <label for="costoiva">COSTO+IVA:</label>
                            <!-- calcular el costo mas iva con el costo anterior -->
                            <div class="input-group mb-3">
                            <div class="input-group-append">
                                <span class="input-group-text">$</span>
                              </div>
                              <input name="costo_iva" id="costo_iva" type="number" class="form-control" aria-label="Monto en pesos mexicanos" required readonly>
                              </div>
                        </div>
                        <div class="form-group col">
                            <label for="contado">Contado:</label>
                            <div class="input-group mb-3">
                            <div class="input-group-append">
                                <span class="input-group-text">$</span>
                              </div>
                              <input name="costo_contado" id="costo_contado" type="number" class="form-control" aria-label="Monto en pesos mexicanos" required readonly>
                            </div>
                        </div>
                        <div class="form-group col">
                            <label for="especial">Especial:</label>
                            <div class="input-group mb-3">
                            <div class="input-group-append">
                                <span class="input-group-text">$</span>
                              </div>
                              <input name="costo_especial" id="costo_especial" type="number" class="form-control" aria-label="Monto en pesos mexicanos" required readonly>
                          </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label for="cr1">CR1:</label>
                            <div class="input-group mb-3">
                            <div class="input-group-append">
                                <span class="input-group-text">$</span>
                              </div>
                              <input name="costo_cr1" id="costo_cr1" type="number" class="form-control" aria-label="Monto en pesos mexicanos" required readonly>
                          </div>
                        </div>
                        <div class="form-group col">
                            <label for="cr2">CR2:</label>
                            <div class="input-group mb-3">
                            <div class="input-group-append">
                                <span class="input-group-text">$</span>
                              </div>
                              <input name="costo_cr2" id="costo_cr2" type="number" class="form-control" aria-label="Monto en pesos mexicanos" required readonly>
                          </div>
                        </div>
                        <div class="form-group col">
                            <label for="p1">P1:</label>
                              <input name="costo_p1" id="costo_p1" type="number" class="form-control" aria-label="Monto en pesos mexicanos" required readonly>
                        </div>
                        <div class="form-group col">
                            <label for="p2">P2:</label>
                              <input name="costo_p2" id="costo_p2" type="number" class="form-control" aria-label="Monto en pesos mexicanos" required readonly>
                        </div>
                        <div class="form-group col">
                            <label for="e_q">E-Q:</label>
                            <div class="input-group mb-3">
                            <div class="input-group-append">
                                <span class="input-group-text">$</span>
                              </div>
                              <input name="costo_eq" id="costo_eq" type="number" class="form-control" aria-label="Monto en pesos mexicanos" required readonly>
                          </div>
                        </div>
                    </div>
                    <input value="insert_producto" name="action" id="action" hidden>
            </div>
        </form>
        </div>
    </div>
</div>

<div class="col-lg-12">
<div class="card">
<div class="card-body">
    <div class="row">
        <div class="col-lg-8">
            <h4><strong>CATALOGO DE PRODUCTOS</strong></h4>
            <!--<input type="" name="prueba" id="prueba">-->
        </div>

        <div align="right" class="col-lg-4">
            <button data-toggle="modal" data-target="#nuevo_producto" class="btn btn-primary" type="button" ><i class="fas fa-plus"></i> Nuevo producto</button> 
        </div>
    </div>
</div>
</div>

<div class="card">
<div class="card-body">
<!-- <form action="" method="post" autocomplete="on"> -->
    <div class="row">
        <div class="col-lg-2">
            <label>Categoría:</label>
            <button data-toggle="modal" data-target="#nueva_cat" title="Agregar nueva categoría" class="btn btn-primary btn-xs" type="button" onclick="nueva_categoria();"><i class="fas fa-plus"></i></button>
            <button disabled data-toggle="modal" data-target="#nueva_cat" onclick="editar_categoria();" title="editar categoría" class="btn btn-success btn-xs" type="button" href="#" id="btnedit_categoria"><i class="fas fa-edit"></i></button>
            <button disabled onclick="eliminar_categoria();" title="Eliminar categoría" class="btn btn-danger btn-xs" type="button" href="#" id="btneliminar_categoria"><i class="fas fa-trash"></i></button>

            <select class="form-control" id="categoria" name="categoria">
                <option selected hidden>Selecciona categoría</option>
                <?php
                    #codigo para la lista de sucursales que se extraen de la base de datos
                    $result_cat = mysqli_query($conexion,"SELECT idcategoria,nombre FROM categoria order by nombre asc");
                    if (mysqli_num_rows($result_cat) > 0) 
                    {  
                      while($row = mysqli_fetch_assoc($result_cat))
                      {
                        echo "<option value='".$row["idcategoria"]."'>".$row["nombre"]."</option>";
                      }
                    }
                ?>  
            </select>
        </div>
        <div class="col-lg-2">
            <label>Subcategoría:</label>
            <button data-toggle="modal" data-target="#nueva_subcat" title="Agregar nueva subcategoría" class="btn btn-primary btn-xs" type="button" onclick="nueva_subcategoria();" ><i class="fas fa-plus"></i></button>
            <button disabled data-toggle="modal" data-target="#nueva_subcat" onclick="editar_subcategoria();" title="editar subcategoria" class="btn btn-success btn-xs" type="button" href="#" id="btnedit_subcategoria"><i class="fas fa-edit"></i></button>
            <button disabled onclick="eliminar_subcategoria();" title="Eliminar subcategoria" class="btn btn-danger btn-xs" type="button" href="#" id="btneliminar_subcategoria"><i class="fas fa-trash"></i></button>

           <select class="form-control" id="subcategoria" name="subcategoria">
            </select>
        </div>
        <div align="left" class="col-lg-1">
            <label>Atributo 1:</label>
            <select class="form-control" id="filtro_atr1" name="filtro_atr1">
                <option selected hidden>---</option>
            </select>
        </div>
        <div align="left" class="col-lg-1">
            <label>Atributo 2:</label>
            <select class="form-control" id="filtro_atr2" name="filtro_atr2">
                <option selected hidden>---</option>
            </select>
        </div>
        <div align="left" class="col-lg-1">
            <label>Atributo 3:</label>
            <select class="form-control" id="filtro_atr3" name="filtro_atr3">
                <option selected hidden>---</option>
            </select>
        </div>
        <div align="left" class="col-lg-1">
            <label>Atributo 4:</label>
            <select class="form-control" id="filtro_atr4" name="filtro_atr4">
                <option selected hidden>---</option>
            </select>
        </div>
        <div align="left" class="col-lg-1">
            <label>Atributo 5:</label>
            <select class="form-control" id="filtro_atr5" name="filtro_atr5">
                <option selected hidden>---</option>
            </select>
        </div>
        <div class="col-lg-3">
            
            <div class="row">
              <div class="col-12 col-sm-2">
                  <button onclick="mostrar_costoiva();" type="button" class="btn btn-primary py-3 btn-sm" style="width: 75px !important;">Ver Costos</button>
              </div>
              &nbsp;&nbsp;&nbsp;
              <div class="col-12 col-sm-3">
                <div class="row">
                    <div class="col-12 col-sm-12" align="center">
                        <button type="button" class="btn btn-primary py-3 btn-sm" style="width: 88px !important; height: 45px !important; padding: 0px;">Editar Lista</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-6" align="center" style="padding-right: 0px; padding-left: 12px;">
                        <button onClick='pausar_lista();' class='btn btn-warning btn-sm btn-block' type='button'><i style='color: white;' class='fas fa-pause'></i></button>
                    </div>
                    <div class="col-12 col-sm-6" align="center" style="padding-left: 0px; padding-right: 0px;">
                        <button onClick='guardar_lista();' class='btn btn-success btn-sm btn-block' type='button'><i style='color: white;' class='fas fa-save'></i></button>
                    </div>
                </div>
              </div>

              <div class="col-12 col-sm-3">
                <button type="button" class="btn btn-primary py-3 btn-sm" style="width: 95px !important;">Descargar Catalogo</button>
              </div>
              <div class="col-12 col-sm-3">
                  <button type="button" class="btn btn-primary py-3 btn-sm" style="width: 95px !important;">Descargar Lista</button>
              </div>
            </div>

        </div>
    </div>
<!-- </form> -->
</div>
</div>

<div class="card">
<div class="card-body">
<div class="table-responsive">
    <table class="table" id="tbl">
        <thead class="thead-light">
            <tr>
                <th>Descripción</th>
                <th>Nuevo costo</th>
                <th>Costo actual</th>
                <th>Costo+IVA</th>
                <th>Ext.-P</th>
                <th>Ext-M</th>
                <th>Contado</th>
                <th>Especial</th>
                <th>CR1</th>
                <th>P1</th>
                <th>CR2</th>
                <th>P2</th>
                <th>E-Q</th>
                <th>GAR</th>
                <th style="text-align: center;">Herramientas</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $query = mysqli_query($conexion, "SELECT * from producto");
            $result = mysqli_num_rows($query);
            if ($result > 0) 
            {
                while ($data = mysqli_fetch_assoc($query)) 
                {
                    $id_producto = $data['idproducto'];
                    //aqui vamos a ver si tienen foto o no, para mostrar los iconos acorde
                    if($data['categoria'] == null)
                    {
                        $idsubcategoria = $data['subcategoria'];
                        $query_meses = mysqli_query($conexion, "SELECT meses_pago from subcategoria where idsubcategoria = '$idsubcategoria'");
                        $garantia = mysqli_fetch_assoc($query_meses)['meses_pago'];
                    }
                    else
                    {
                        $idcategoria = $data['categoria'];
                        $query_meses = mysqli_query($conexion, "SELECT meses_pago from categoria where idcategoria = '$idcategoria'");
                        $garantia = mysqli_fetch_assoc($query_meses)['meses_pago'];
                    }
             ?>
                <tr>
                        <td><?php echo $data['descripcion']; ?></td>
                        <td>Aqui nuevo costo</td>
                        <td><?php echo "$".number_format($data['costo'],2, '.', ','); ?></td>
                        <td><?php echo "$".number_format($data['costo_iva'],2, '.', ','); ?></td>
                        <td><?php echo $data['ext_p']; ?></td>
                        <td>asd</td>
                        <td><?php echo "$".number_format($data['costo_contado'],2, '.', ','); ?></td>
                        <td><?php echo "$".number_format($data['costo_especial'],2, '.', ','); ?></td>
                        <td><?php echo "$".number_format($data['costo_cr1'],2, '.', ','); ?></td>
                        <td><?php echo $data['costo_p1']; ?></td>
                        <td><?php echo "$".number_format($data['costo_cr2'],2, '.', ','); ?></td>
                        <td><?php echo $data['costo_p2']; ?></td>
                        <td><?php echo "$".number_format($data['costo_eq'],2, '.', ','); ?></td>
                        <td><?php echo $garantia." Meses" ?></td>
                        <td align="center">
                                <button data-toggle="modal" data-target="#img_producto" class="btn btn-secondary btn-sm"><i class='fas fa-camera'></i></button>
                                <button class="btn btn-success btn-sm"><i class='fas fa-edit'></i></button>
                                <button onClick='eliminar_producto("<?php echo $id_producto; ?>");' class='btn btn-danger btn-sm' type='submit'><i style='color: white;' class='fas fa-trash-alt'></i></button>
                        </td>
                    </tr>
            <?php 
                }
            } 
            ?>
            <tr>
                        <td>Lavadora LG con secadora</td>
                        <td>$4,000</td>
                        <td>$5,000</td>
                        <td>$5,500</td>
                        <td>4</td>
                        <td>4</td>
                        <td>$5,000</td>
                        <td>$4,000</td>
                        <td>$5,000</td>
                        <td>$4,000</td>
                        <td>$4,000</td>
                        <td>$5,000</td>
                        <td>$4,000</td>
                        <td>5</td>
                        <td align="center">
                                <a href="#" class="btn btn-primary btn-sm"><i class='fas fa-camera'></i></a>
                                <a href="#" class="btn btn-success btn-sm"><i class='fas fa-edit'></i></a>
                                <button onClick='eliminar_producto()' class='btn btn-danger btn-sm' type='submit'><i style='color: white;' class='fas fa-trash-alt'></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>Lavadora LG con secadora</td>
                        <td><input type="number" name="" id="" class="form-control"></td>
                        <td>$5,000</td>
                        <td>$5,500</td>
                        <td>7</td>
                        <td>4</td>
                        <td>$5,000</td>
                        <td>$4,000</td>
                        <td>$5,000</td>
                        <td>$4,000</td>
                        <td>$4,000</td>
                        <td>$5,000</td>
                        <td>$4,000</td>
                        <td>5</td>
                        <td align="center">
                                <a href="#" class="btn btn-secondary btn-sm"><i class='fas fa-camera'></i></a>
                                <a href="#" class="btn btn-success btn-sm"><i class='fas fa-edit'></i></a>
                                <button onClick='eliminar_producto()' class='btn btn-danger btn-sm' type='submit'><i style='color: white;' class='fas fa-trash-alt'></i></button>
                        </td>
                    </tr>
        </tbody>
    </table>
</div>
</div>
</div>
</div>

<br><br>

<script type="text/javascript">
function visualizar(idusuario,lista)
        {
          //alert(m+"  "+q);
          var xmlhttp;      
            if (window.XMLHttpRequest)
            {
            xmlhttp=new XMLHttpRequest();
            }
          else
            {
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
            }
          xmlhttp.onreadystatechange=function()
            {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
             {
             document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
             }
            }

          $("#my_modal_title").html("Sucursales permitidas a "+idusuario);  
          $("#listamodal_sucursales").html(lista); 

          $('#ver_sucursales').modal('show');
        }

</script>

<?php 
ob_end_flush();
include_once "footer.php"; 
?>
