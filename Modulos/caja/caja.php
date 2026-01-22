<?php
// 1. CONEXIÓN
include('../../conexion.php');
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../../index.php");
    exit();
}

// 2. OBTENER FECHA (Por defecto HOY)
$fecha_filtro = isset($_POST['fecha_busqueda']) ? $_POST['fecha_busqueda'] : date('Y-m-d');

// 3. LÓGICA AUTOMÁTICA: Sumar ventas del día desde la tabla VENTAS
$sql_ventas = "SELECT IFNULL(SUM(total), 0.00) as total_dia FROM ventas WHERE DATE(fecha_venta) = '$fecha_filtro'";
$res_ventas = $conexion->query($sql_ventas);
$row_ventas = $res_ventas->fetch_assoc();
$ingresos_automaticos = $row_ventas['total_dia'];

// 4. GUARDAR CIERRE DE CAJA
if (isset($_POST['accion']) && $_POST['accion'] == 'guardar') {
    $caja_inicial = $_POST['caja_inicial'];
    $ingresos = $_POST['ingresos']; 
    $total_final = $_POST['total_final']; 
    
    // Verificar si ya existe caja para esta fecha
    $check = $conexion->query("SELECT id_caja FROM caja WHERE fecha = '$fecha_filtro'");
    
    if($check->num_rows == 0){
        // Usamos 'ingresos_ventas' (nombre correcto en tu BD)
        $sql = "INSERT INTO caja (fecha, caja_inicial, ingresos_ventas, ganancia) VALUES ('$fecha_filtro', '$caja_inicial', '$ingresos', '$total_final')";
        
        if($conexion->query($sql)){
            echo "<script>alert('¡Caja cerrada correctamente!'); window.location.href='caja.php';</script>";
        } else {
            echo "<script>alert('Error al guardar: " . $conexion->error . "');</script>";
        }
    } else {
        echo "<script>alert('⚠️ YA EXISTE un cierre para esta fecha. Usa el botón EDITAR.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Movimiento de Caja - K&M-K</title>
    <link rel="stylesheet" href="../../css/caja.css">
</head>
<body>

    <header class="header-caja">
        <div class="icono-header">👨‍⚕️</div>
        <h1>MOVIMIENTO DE CAJA DIARIA</h1>
        <div class="icono-header">⚕️</div>
    </header>

    <div class="contenedor-caja">
        <div class="panel-izq">
            <form action="caja.php" method="POST" id="formCaja">
                
                <input type="hidden" name="id_caja" id="input_id_caja">
                <input type="hidden" name="accion" value="guardar">
                
                <div class="fila-doble">
                    <div class="input-group">
                        <label>📅 FECHA DE ARQUEO:</label>
                        <input type="date" name="fecha_busqueda" id="input_fecha" value="<?php echo $fecha_filtro; ?>" class="inp-fecha" onchange="this.form.submit()">
                    </div>
                    
                    <div class="input-group">
                        <label>💰 CAJA INICIAL (Sencillo):</label>
                        <input type="number" step="0.01" name="caja_inicial" id="txt_inicial" value="0.00" class="inp-dinero" oninput="calcularTotal()">
                    </div>
                </div>

                <div class="fila-doble">
                    <div class="input-group">
                        <label>📈 INGRESOS POR VENTAS (Automático):</label>
                        <input type="number" step="0.01" name="ingresos" id="txt_ingresos" value="<?php echo $ingresos_automaticos; ?>" class="inp-auto" readonly>
                    </div>

                    <div class="input-group">
                        <label>💵 TOTAL EN CAJA (Saldo Final):</label>
                        <input type="number" step="0.01" name="total_final" id="txt_total" value="0.00" class="inp-total" readonly>
                    </div>
                </div>
            </form>
        </div>

        <div class="panel-der">
            <button type="button" class="btn-caja" id="btn_guardar" onclick="guardarCaja()">💾 GUARDAR</button>
            
            <button type="button" class="btn-caja" onclick="editarCaja()">📝 EDITAR</button>
            
            <button type="button" class="btn-caja" onclick="confirmarEliminar()">🗑️ ELIMINAR</button>
            
            <a href="../../principal.php" class="btn-caja">↩️ REGRESAR</a>
        </div>
    </div>

    <div class="tabla-container">
        <table>
            <thead>
                <tr>
                    <th>FECHA</th>
                    <th>C. INICIAL</th>
                    <th>VENTAS (SISTEMA)</th>
                    <th>TOTAL EN CAJA</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $historial = $conexion->query("SELECT * FROM caja ORDER BY fecha DESC LIMIT 5");
                if($historial && $historial->num_rows > 0){
                    while($h = $historial->fetch_assoc()){
                        // Obtenemos los valores correctos de la BD
                        $ingresos_reales = isset($h['ingresos_ventas']) ? $h['ingresos_ventas'] : $h['ingresos'];
                        
                        // AL HACER CLIC: Llamamos a cargarDatos() con la info de la fila
                        echo "<tr onclick='cargarDatos(".json_encode($h).")' style='cursor:pointer;'>
                                <td>{$h['fecha']}</td>
                                <td>S/ {$h['caja_inicial']}</td>
                                <td>S/ {$ingresos_reales}</td>
                                <td class='monto-verde'>S/ {$h['ganancia']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No hay cierres registrados aún.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        // Calcular suma total
        function calcularTotal() {
            let inicial = parseFloat(document.getElementById('txt_inicial').value) || 0;
            let ventas = parseFloat(document.getElementById('txt_ingresos').value) || 0;
            let total = inicial + ventas;
            document.getElementById('txt_total').value = total.toFixed(2);
        }
        
        // 1. CARGAR DATOS AL HACER CLIC EN LA TABLA
        function cargarDatos(datos) {
            document.getElementById('input_id_caja').value = datos.id_caja;
            document.getElementById('input_fecha').value = datos.fecha;
            document.getElementById('txt_inicial').value = datos.caja_inicial;
            
            // Ajuste por si el campo se llama distinto en tu BD
            let ingresos = datos.ingresos_ventas || datos.ingresos;
            document.getElementById('txt_ingresos').value = ingresos;
            
            document.getElementById('txt_total').value = datos.ganancia;
            
            alert("Registro del " + datos.fecha + " seleccionado.");
        }

        // 2. FUNCIÓN GUARDAR (Submit normal)
        function guardarCaja() {
            document.getElementById('formCaja').action = "caja.php";
            document.getElementById('formCaja').submit();
        }

        // 3. FUNCIÓN EDITAR
        function editarCaja() {
            let id = document.getElementById('input_id_caja').value;
            if (id === "") {
                alert("⚠️ Primero selecciona una fila de la tabla para editar.");
                return;
            }
            // Cambiamos el destino del formulario para actualizar
            // IMPORTANTE: Asegúrate que tus inputs tengan el 'name' que espera editar_caja_proceso.php
            // Como truco rápido, renombramos los inputs temporalmente antes de enviar si es necesario,
            // pero lo ideal es editar editar_caja_proceso.php para que reciba 'caja_inicial' en vez de 'caja_initial'.
            
            document.getElementById('formCaja').action = "editar_caja_proceso.php"; 
            
            // Modificamos el 'name' de fecha para que coincida con el proceso de edición (que espera 'fecha', no 'fecha_busqueda')
            document.getElementById('input_fecha').name = "fecha"; 
            
            document.getElementById('formCaja').submit();
        }

        // 4. FUNCIÓN ELIMINAR
        function confirmarEliminar() {
            let id = document.getElementById('input_id_caja').value;
            if (id === "") {
                alert("⚠️ Primero selecciona una fila de la tabla para eliminar.");
                return;
            }
            if (confirm("¿Seguro que deseas eliminar este cierre de caja?")) {
                window.location.href = "eliminar_caja.php?id_caja=" + id;
            }
        }
        
        window.onload = calcularTotal;
    </script>

</body>
</html>