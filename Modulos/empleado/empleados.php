<?php
// 1. CONEXIÓN
include('../../conexion.php'); 
session_start();

// 2. SEGURIDAD
if (!isset($_SESSION['usuario'])) { 
    header("Location: ../../index.php"); 
    exit(); 
}

// 3. GENERADOR DE CÓDIGO AUTOMÁTICO (EMP-XXX)
$sql_ultimo = "SELECT codigo FROM empleados ORDER BY id_empleado DESC LIMIT 1";
$res_ultimo = $conexion->query($sql_ultimo);
$nuevo_codigo = "EMP-001"; // Valor inicial si la tabla está vacía

if ($res_ultimo && $res_ultimo->num_rows > 0) {
    $fila = $res_ultimo->fetch_assoc();
    $ultimo_codigo = $fila['codigo']; 
    
    // Extraemos el número final (ignora "EMP-")
    $numero = (int)substr($ultimo_codigo, 4); 
    $numero++;
    
    // Rellenamos con ceros: EMP-016
    $nuevo_codigo = "EMP-" . str_pad($numero, 3, "0", STR_PAD_LEFT);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Empleados - K&M-K S.A.C</title>
    <link rel="stylesheet" href="../../css/usuarios.css">
</head>
<body>

    <header class="header-seccion">
        <div class="header-icon">👨‍⚕️</div>
        <h1 class="titulo-pagina">CONTROL DE EMPLEADOS</h1>
        <div class="header-icon">⚕️</div>
    </header>

    <div class="contenedor-principal">
        
        <section class="formulario-datos">
            <form action="guardar_empleado.php" method="POST" id="formEmpleados">
                
                <input type="hidden" name="id_empleado" id="input_id_empleado">

                <fieldset>
                    <legend>FICHA DEL PERSONAL</legend>
                    <div class="grid-formulario">
                        
                        <div class="grupo-input">
                            <label>🆔 Código:</label>
                            <input type="text" name="codigo" id="input_codigo" value="<?php echo $nuevo_codigo; ?>" readonly style="background-color: #E0E0E0; font-weight:bold;">
                        </div>
                        
                        <div class="grupo-input">
                            <label>🪪 DNI:</label>
                            <input type="text" name="dni" id="input_dni" maxlength="8" required placeholder="8 dígitos">
                        </div>

                        <div class="grupo-input">
                            <label>👤 Nombres:</label>
                            <input type="text" name="nombres" id="input_nombres" required>
                        </div>

                        <div class="grupo-input">
                            <label>👤 Apellidos:</label>
                            <input type="text" name="apellidos" id="input_apellidos" required>
                        </div>

                        <div class="grupo-input">
                            <label>🚻 Sexo:</label>
                            <select name="sexo" id="input_sexo">
                                <option value="Masculino">Masculino</option>
                                <option value="Femenino">Femenino</option>
                            </select>
                        </div>

                        <div class="grupo-input">
                            <label>📞 Teléfono:</label>
                            <input type="text" name="telefono" id="input_telefono">
                        </div>

                        <div class="grupo-input">
                            <label>🎓 Especialidad / Cargo:</label>
                            <select name="especialidad" id="input_especialidad">
                                <option value="">Seleccione...</option>
                                <option value="Administrador">Administrador</option>
                                <option value="Farmacéutico">Farmacéutico</option>
                                <option value="Técnico en Farmacia">Técnico en Farmacia</option>
                                <option value="Practicante">Practicante</option>
                                <option value="Cajero">Cajero</option>
                            </select>
                        </div>
                    </div>
                </fieldset>

                <div class="botones-ficha">
                    <button type="button" class="btn-ficha" onclick="limpiarForm()">+ NUEVO</button>
                    <button type="submit" class="btn-ficha" id="btn_principal">💾 GUARDAR</button>
                    <button type="button" class="btn-ficha" onclick="confirmarEliminar()">🗑️ ELIMINAR</button>
                    <button type="button" class="btn-ficha" onclick="window.location.href='reporte_empleados_excel.php'">📊 EXCEL</button>
                    <a href="../../principal.php" class="btn-ficha btn-volver">↩️ REGRESAR</a>
                </div>
            </form>
        </section>

        <section class="tabla-container">
            <table>
                <thead>
                    <tr>
                        <th>CÓDIGO</th>
                        <th>DNI</th>
                        <th>NOMBRES Y APELLIDOS</th>
                        <th>SEXO</th>
                        <th>TELÉFONO</th>
                        <th>ESPECIALIDAD</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Consulta exacta a tus columnas
                    $sql = "SELECT id_empleado, codigo, dni, nombres, apellidos, sexo, telefono, especialidad FROM empleados";
                    $res = $conexion->query($sql);
                    
                    if ($res && $res->num_rows > 0) {
                        while($f = $res->fetch_assoc()){
                            // Enviamos los datos a JS para poder editar al hacer clic
                            echo "<tr onclick='cargarDatos(".json_encode($f).")' style='cursor:pointer;'>
                                    <td>{$f['codigo']}</td>
                                    <td>{$f['dni']}</td>
                                    <td>{$f['nombres']} {$f['apellidos']}</td>
                                    <td>{$f['sexo']}</td>
                                    <td>{$f['telefono']}</td>
                                    <td>{$f['especialidad']}</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No hay empleados registrados</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </div>

    <script>
        function cargarDatos(datos) {
            // Llenar formulario con los datos de la BD
            document.getElementById('input_id_empleado').value = datos.id_empleado;
            document.getElementById('input_codigo').value = datos.codigo;
            document.getElementById('input_dni').value = datos.dni;
            document.getElementById('input_nombres').value = datos.nombres;
            document.getElementById('input_apellidos').value = datos.apellidos;
            document.getElementById('input_sexo').value = datos.sexo;
            document.getElementById('input_telefono').value = datos.telefono;
            document.getElementById('input_especialidad').value = datos.especialidad;

            // Cambiar botón a MODO EDICIÓN
            document.getElementById('formEmpleados').action = "editar_empleado_proceso.php";
            document.getElementById('btn_principal').innerText = "📝 ACTUALIZAR";
            
            alert("Empleado seleccionado: " + datos.nombres);
        }

        function limpiarForm() {
            document.getElementById('formEmpleados').reset();
            document.getElementById('input_id_empleado').value = "";
            
            // Restaurar el código automático generado por PHP
            document.getElementById('input_codigo').value = "<?php echo $nuevo_codigo; ?>";

            // Cambiar botón a MODO GUARDAR
            document.getElementById('formEmpleados').action = "guardar_empleado.php";
            document.getElementById('btn_principal').innerText = "💾 GUARDAR";
        }

        function confirmarEliminar() {
            const id = document.getElementById('input_id_empleado').value;
            if (id === "") {
                alert("⚠️ Por favor, seleccione un empleado de la tabla primero.");
                return;
            }
            if (confirm("¿Seguro que desea eliminar a este empleado permanentemente?")) {
                window.location.href = "eliminar_empleado.php?id=" + id;
            }
        }
    </script>
</body>
</html>