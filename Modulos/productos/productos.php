<?php
// 1. CONEXIÓN: Retrocedemos dos niveles para llegar a la raíz (../../)
include('../../conexion.php'); 
session_start();

// 2. SEGURIDAD: Si no hay sesión iniciada, redirige al login en la raíz
if (!isset($_SESSION['usuario'])) { 
    header("Location: ../../index.php"); 
    exit(); 
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Productos - Inversiones K&M-K S.A.C</title>
    <link rel="stylesheet" href="../../css/productos.css">
</head>
<body>

    <header class="header-seccion">
        <div class="header-icon">👨‍⚕️</div> <h1 class="titulo-pagina">REGISTRO DE PRODUCTOS</h1>
        <div class="header-icon">⚕️</div> </header>

    <div class="contenedor-principal">
        
        <section class="formulario-datos">
            <form action="guardar_producto.php" method="POST">
                <input type="hidden" name="id_producto" id="input_id_producto">

                <fieldset>
                    <legend>DATOS DEL PRODUCTO</legend>
                    <div class="grid-formulario">
                        <div class="campo">
                            <label>ID:</label>
                            <input type="text" disabled placeholder="Auto" id="visual_id">
                        </div>
                        <div class="campo">
                            <label>Categoría:</label>
                            <select name="categoria">
                                <option value="Salud">Salud</option>
                                <option value="Madres y Bebes">Madres y Bebés</option>
                                <option value="Nutricion">Nutrición</option>
                                <option value="Dermatologia">Dermatología</option>
                            </select>
                        </div>
                        <div class="campo">
                            <label>Laboratorios:</label>
                            <input type="text" name="laboratorio" placeholder="Ej: Medifarma">
                        </div>
                        
                        <div class="campo">
                            <label>Descripción:</label>
                            <input type="text" name="descripcion" required placeholder="Nombre del medicamento">
                        </div>
                        <div class="campo">
                            <label>Stock:</label>
                            <input type="number" name="stock" required>
                        </div>
                        <div class="campo">
                            <label>Registro Sanitario:</label>
                            <input type="text" name="registro_sanitario">
                        </div>
                        
                        <div class="campo">
                            <label>Presentación:</label>
                            <input type="text" name="presentacion" placeholder="Ej: Tableta, Jarabe">
                        </div>
                        <div class="campo">
                            <label>Precio de Compra:</label>
                            <input type="number" step="0.01" name="precio_compra">
                        </div>
                        <div class="campo">
                            <label>Fecha de Vencimiento:</label>
                            <input type="date" name="fecha_vencimiento" required>
                        </div>

                        <div class="campo">
                            <label>Concentración:</label>
                            <input type="text" name="concentracion">
                        </div>
                        <div class="campo">
                            <label>Precio de Venta:</label>
                            <input type="number" step="0.01" name="precio_venta">
                        </div>
                        <div class="campo-estado">
                            <label>Estado:</label>
                            <input type="checkbox" name="estado" value="Activo" checked> Activo
                        </div>
                    </div>
                </fieldset>

                <div class="busqueda-bar">
                    <input type="text" placeholder="Buscar por descripción..." class="input-buscar">
                    <button type="button" class="btn-tabla">MOSTRAR TODO</button>
                    <button type="button" class="btn-tabla">IMPRIMIR</button>
                </div>

                <div class="acciones-inferiores">
                    <button type="reset" class="btn-accion" onclick="limpiarSeleccion()">+ NUEVO</button>
                    <button type="submit" class="btn-accion">💾 GUARDAR</button>
                    
                    <button type="button" class="btn-accion" onclick="confirmarEliminar()">🗑️ ELIMINAR</button>
                    
                    <a href="../../principal.php" class="btn-accion" style="text-decoration:none;">↩️ REGRESAR</a>
                </div>
            </form>
        </section>

        <section class="tabla-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Presentación</th>
                        <th>Descripción</th>
                        <th>Stock</th>
                        <th>Precio Venta</th>
                        <th>Vencimiento</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Consulta a la tabla productos
                    $sql = "SELECT id, presentacion, descripcion, stock, precio_venta, fecha_vencimiento, estado FROM productos";
                    $res = $conexion->query($sql);
                    
                    if ($res && $res->num_rows > 0) {
                        while($f = $res->fetch_assoc()){
                            // CAMBIO 3: Evento onclick en la fila para seleccionar
                            echo "<tr onclick='seleccionarProducto({$f['id']})' style='cursor:pointer;'>
                                    <td>{$f['id']}</td>
                                    <td>{$f['presentacion']}</td>
                                    <td>{$f['descripcion']}</td>
                                    <td>{$f['stock']}</td>
                                    <td>S/ {$f['precio_venta']}</td>
                                    <td>{$f['fecha_vencimiento']}</td>
                                    <td style='color:green; font-weight:bold;'>{$f['estado']}</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No hay productos registrados aún</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </div>

    <script>
        // Función para capturar el ID cuando haces clic en la tabla
        function seleccionarProducto(id) {
            document.getElementById('input_id_producto').value = id;
            document.getElementById('visual_id').value = id; // Para que lo veas en el campo gris
            alert("Producto ID " + id + " seleccionado.");
        }

        function limpiarSeleccion() {
            document.getElementById('input_id_producto').value = "";
            document.getElementById('visual_id').value = "";
        }

        // Función para confirmar y enviar a borrar
        function confirmarEliminar() {
            var id = document.getElementById('input_id_producto').value;
            
            if (id === "") {
                alert("⚠️ Por favor, seleccione un producto de la tabla primero haciendo clic en él.");
                return;
            }

            if (confirm("¿Está seguro de que desea eliminar el producto con ID " + id + "?")) {
                // Redirige al archivo que creamos
                window.location.href = "eliminar_producto.php?id=" + id;
            }
        }
    </script>

</body>
</html>