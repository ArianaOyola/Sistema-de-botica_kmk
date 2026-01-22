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
    <title>Proveedores - Inversiones K&M-K S.A.C</title>
    <link rel="stylesheet" href="../../css/proveedores.css">
</head>
<body>

    <header class="header-seccion">
        <div class="header-icon">👨‍⚕️</div>
        <h1 class="titulo-pagina">REGISTRO DE PROVEEDORES</h1>
        <div class="header-icon">⚕️</div>
    </header>

    <div class="contenedor-principal">
        <section class="formulario-datos">
            <form action="guardar_proveedor.php" method="POST" id="formProveedores">
                
                <input type="hidden" name="id_proveedor" id="input_id_proveedor">

                <fieldset>
                    <legend>DATOS DEL PROVEEDOR</legend>
                    <div class="grid-formulario">
                        
                        <div class="grupo-input">
                            <label>Razón Social:</label>
                            <input type="text" name="razon_social" id="input_razon" required placeholder="Ej: Distribuidora SAC">
                        </div>

                        <div class="grupo-input">
                            <label>RUC:</label>
                            <input type="text" name="ruc" id="input_ruc" maxlength="11" placeholder="11 dígitos">
                        </div>

                        <div class="grupo-input">
                            <label>DNI:</label>
                            <input type="text" name="dni" id="input_dni" maxlength="8" placeholder="8 dígitos">
                        </div>

                        <div class="grupo-input span-2">
                            <label>Dirección:</label>
                            <input type="text" name="direccion" id="input_direccion" placeholder="Av. / Calle / Distrito">
                        </div>

                        <div class="grupo-input">
                            <label>Teléfono:</label>
                            <input type="text" name="telefono" id="input_telefono">
                        </div>

                        <div class="grupo-input">
                            <label>Email:</label>
                            <input type="email" name="email" id="input_email">
                        </div>

                        <div class="grupo-input">
                            <label>Nombre Banco:</label>
                            <input type="text" name="nombre_banco" id="input_banco">
                        </div>

                        <div class="grupo-input">
                            <label>Nro. Cuenta:</label>
                            <input type="text" name="nro_cuenta" id="input_cuenta">
                        </div>
                    </div>
                </fieldset>

                <div class="botones-ficha">
                    <button type="button" class="btn-ficha" onclick="limpiarForm()">+ NUEVO</button>
                    
                    <button type="submit" class="btn-ficha" id="btn_principal">💾 GUARDAR</button>
                    
                    <button type="button" class="btn-ficha" onclick="confirmarEliminar()">🗑️ ELIMINAR</button>
                    
                    <button type="button" class="btn-ficha" onclick="window.location.href='reporte_proveedores_excel.php'">📊 EXCEL</button>
                    
                    <a href="../../principal.php" class="btn-ficha btn-volver">↩️ REGRESAR</a>
                </div>
            </form>
        </section>

        <section class="tabla-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Razón Social</th>
                        <th>RUC/DNI</th>
                        <th>Teléfono</th>
                        <th>Banco / Cuenta</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM proveedores";
                    $res = $conexion->query($sql);
                    if ($res && $res->num_rows > 0) {
                        while($f = $res->fetch_assoc()){
                            $documento = (!empty($f['ruc'])) ? $f['ruc'] : $f['dni'];
                            
                            // Pasamos todos los datos a JSON para usarlos en JS al hacer clic
                            echo "<tr onclick='cargarDatos(".json_encode($f).")' style='cursor:pointer;'>
                                    <td>{$f['id_proveedor']}</td>
                                    <td>{$f['razon_social']}</td>
                                    <td>{$documento}</td>
                                    <td>{$f['telefono']}</td>
                                    <td>{$f['nombre_banco']} - {$f['nro_cuenta']}</td>
                                    <td><b style='color:green'>{$f['estado']}</b></td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No hay proveedores registrados</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </div>

    <script>
        function cargarDatos(datos) {
            // 1. Llenar los inputs con los datos de la fila seleccionada
            document.getElementById('input_id_proveedor').value = datos.id_proveedor;
            document.getElementById('input_razon').value = datos.razon_social;
            document.getElementById('input_ruc').value = datos.ruc;
            document.getElementById('input_dni').value = datos.dni;
            document.getElementById('input_direccion').value = datos.direccion;
            document.getElementById('input_telefono').value = datos.telefono;
            document.getElementById('input_email').value = datos.email;
            document.getElementById('input_banco').value = datos.nombre_banco;
            document.getElementById('input_cuenta').value = datos.nro_cuenta;

            // 2. Cambiar el formulario a modo "EDICIÓN"
            document.getElementById('formProveedores').action = "editar_proveedor_proceso.php";
            document.getElementById('btn_principal').innerText = "📝 ACTUALIZAR";
            
            alert("Proveedor seleccionado. Puede editar sus datos o eliminarlo.");
        }

        function limpiarForm() {
            // 1. Limpiar todos los campos
            document.getElementById('formProveedores').reset();
            document.getElementById('input_id_proveedor').value = "";

            // 2. Regresar el formulario a modo "GUARDAR NUEVO"
            document.getElementById('formProveedores').action = "guardar_proveedor.php";
            document.getElementById('btn_principal').innerText = "💾 GUARDAR";
        }

        function confirmarEliminar() {
            const id = document.getElementById('input_id_proveedor').value;
            if (id === "") {
                alert("⚠️ Seleccione un proveedor de la tabla primero.");
                return;
            }
            if (confirm("¿Está seguro de eliminar al proveedor seleccionado?")) {
                window.location.href = "eliminar_proveedor.php?id=" + id;
            }
        }
    </script>
</body>
</html>