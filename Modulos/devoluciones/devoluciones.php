<?php
// 1. CONEXIÓN
include('../../conexion.php');
session_start();

// 2. SEGURIDAD
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
    <title>Devoluciones - K&M-K S.A.C</title>
    <link rel="stylesheet" href="../../css/usuarios.css">
</head>
<body>

    <header class="header-seccion">
        <div class="header-icon">👨‍⚕️</div>
        <h1 class="titulo-pagina">GESTIÓN DE DEVOLUCIONES</h1>
        <div class="header-icon">⚕️</div>
    </header>

    <div class="contenedor-principal">
        
        <section class="formulario-datos">
            <form action="guardar_devolucion.php" method="POST" id="formDevoluciones">
                
                <input type="hidden" name="id_devolucion" id="input_id_devolucion">

                <fieldset>
                    <legend>REGISTRO DE DEVOLUCIÓN</legend>
                    <div class="grid-formulario">
                        
                        <div class="grupo-input">
                            <label>📦 Producto:</label>
                            <select name="id_producto" id="input_producto" required>
                                <option value="">Seleccione producto...</option>
                                <?php
                                $prods = $conexion->query("SELECT id, descripcion FROM productos");
                                while($p = $prods->fetch_assoc()){
                                    echo "<option value='{$p['id']}'>{$p['descripcion']}</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="grupo-input">
                            <label>🔢 Cantidad:</label>
                            <input type="number" name="cantidad" id="input_cantidad" required min="1">
                        </div>

                        <div class="grupo-input">
                            <label>🔄 Tipo de Devolución:</label>
                            <select name="tipo" id="input_tipo">
                                <option value="Vencimiento">Por Vencimiento</option>
                                <option value="Falla de Fabrica">Falla de Fábrica</option>
                                <option value="Cambio">Cambio de Cliente</option>
                            </select>
                        </div>

                        <div class="grupo-input">
                            <label>📅 Fecha Devolución:</label>
                            <input type="date" name="fecha_devolucion" id="input_fecha" value="<?php echo date('Y-m-d'); ?>">
                        </div>

                        <div class="grupo-input span-2" style="grid-column: span 2;">
                            <label>📝 Motivo / Observación:</label>
                            <input type="text" name="motivo" id="input_motivo" placeholder="Describa el motivo detallado">
                        </div>
                    </div>
                </fieldset>

                <div class="botones-ficha">
                    <button type="button" class="btn-ficha" onclick="limpiarForm()">+ NUEVO</button>
                    <button type="submit" class="btn-ficha" id="btn_principal">💾 GUARDAR</button>
                    <button type="button" class="btn-ficha" onclick="confirmarEliminar()">🗑️ ELIMINAR</button>
                    <button type="button" class="btn-ficha" onclick="window.location.href='reporte_devoluciones_excel.php'">📊 EXCEL</button>
                    <a href="../../principal.php" class="btn-ficha btn-volver">↩️ REGRESAR</a>
                </div>
            </form>
        </section>

        <section class="tabla-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>TIPO</th>
                        <th>PRODUCTO</th>
                        <th>CANTIDAD</th>
                        <th>MOTIVO</th>
                        <th>FECHA</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Consulta uniendo con la tabla productos para mostrar el nombre
                    $sql = "SELECT d.id_devolucion, d.tipo, d.id_producto, d.cantidad, d.motivo, d.fecha_devolucion, p.descripcion 
                            FROM devoluciones d 
                            INNER JOIN productos p ON d.id_producto = p.id";
                    $res = $conexion->query($sql);
                    if ($res && $res->num_rows > 0) {
                        while($f = $res->fetch_assoc()){
                            // Enviamos los datos a JS
                            echo "<tr onclick='cargarDatos(".json_encode($f).")' style='cursor:pointer;'>
                                    <td>{$f['id_devolucion']}</td>
                                    <td>{$f['tipo']}</td>
                                    <td>{$f['descripcion']}</td>
                                    <td>{$f['cantidad']}</td>
                                    <td>{$f['motivo']}</td>
                                    <td>{$f['fecha_devolucion']}</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No hay devoluciones registradas</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </div>

    <script>
        function cargarDatos(datos) {
            document.getElementById('input_id_devolucion').value = datos.id_devolucion;
            document.getElementById('input_producto').value = datos.id_producto;
            document.getElementById('input_cantidad').value = datos.cantidad;
            document.getElementById('input_tipo').value = datos.tipo;
            document.getElementById('input_fecha').value = datos.fecha_devolucion;
            document.getElementById('input_motivo').value = datos.motivo;

            // Cambiar a modo EDICIÓN
            document.getElementById('formDevoluciones').action = "editar_devolucion_proceso.php";
            document.getElementById('btn_principal').innerText = "📝 ACTUALIZAR";
            
            alert("Devolución seleccionada.");
        }

        function limpiarForm() {
            document.getElementById('formDevoluciones').reset();
            document.getElementById('input_id_devolucion').value = "";
            document.getElementById('input_fecha').value = "<?php echo date('Y-m-d'); ?>";

            // Cambiar a modo GUARDAR
            document.getElementById('formDevoluciones').action = "guardar_devolucion.php";
            document.getElementById('btn_principal').innerText = "💾 GUARDAR";
        }

        function confirmarEliminar() {
            const id = document.getElementById('input_id_devolucion').value;
            if (id === "") {
                alert("⚠️ Por favor, seleccione una devolución de la tabla primero.");
                return;
            }
            if (confirm("¿Está seguro de eliminar este registro?")) {
                window.location.href = "eliminar_devolucion.php?id=" + id;
            }
        }
    </script>
</body>
</html>