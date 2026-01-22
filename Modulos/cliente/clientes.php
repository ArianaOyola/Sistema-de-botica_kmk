<?php
// 1. CONEXIÓN
include('../../conexion.php'); 
session_start();

// 2. SEGURIDAD
if (!isset($_SESSION['usuario'])) { 
    header("Location: ../../index.php"); 
    exit(); 
}

// 3. GENERADOR DE CÓDIGO AUTOMÁTICO (CLI-XXX)
$sql_ultimo = "SELECT codigo FROM clientes ORDER BY id DESC LIMIT 1";
$res_ultimo = $conexion->query($sql_ultimo);
$nuevo_codigo = "CLI-001"; // Valor inicial por defecto

if ($res_ultimo && $res_ultimo->num_rows > 0) {
    $fila = $res_ultimo->fetch_assoc();
    $ultimo_codigo = $fila['codigo']; 
    
    // Intentamos extraer el número. Asumimos formato "XXX-000"
    // Si el código anterior era "GEN-000", el sistema intentará seguir la secuencia o reiniciará si cambia el prefijo.
    // Aquí forzamos el prefijo CLI para estandarizar.
    
    // Extraemos solo los números del final (ignoramos los primeros 4 caracteres "CLI-")
    $numero = (int)substr($ultimo_codigo, 4); 
    $numero++;
    
    // Rellenamos con ceros: CLI-005
    $nuevo_codigo = "CLI-" . str_pad($numero, 3, "0", STR_PAD_LEFT);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Clientes - Inversiones K&M-K</title>
    <link rel="stylesheet" href="../../css/clientes.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>⚕️</text></svg>">
</head>
<body>

    <div class="contenedor-clientes">
        <header class="header-seccion">
            <div class="header-icon">👨‍⚕️</div>
            <h1 class="titulo-pagina">REGISTRO DE CLIENTES</h1>
            <div class="header-icon">⚕️</div>
        </header>

        <section class="formulario-datos">
            <form action="guardar_cliente.php" method="POST" id="formClientes">
                <input type="hidden" name="id" id="input_id">
                
                <fieldset>
                    <legend>DATOS DEL CLIENTE</legend>
                    <div class="grid-formulario">
                        <div class="campo">
                            <label>Código (Auto):</label>
                            <input type="text" name="codigo" id="input_codigo" value="<?php echo $nuevo_codigo; ?>" readonly style="background-color: #E0E0E0; font-weight: bold; color: #555;">
                        </div>

                        <div class="campo">
                            <label>Sexo:</label>
                            <select name="sexo" id="input_sexo">
                                <option value="Masculino">Masculino</option>
                                <option value="Femenino">Femenino</option>
                            </select>
                        </div>
                        <div class="campo"><label>E-mail:</label><input type="email" name="email" id="input_email"></div>
                        
                        <div class="campo"><label>Nombres:</label><input type="text" name="nombres" id="input_nombres" required></div>
                        <div class="campo"><label>DNI:</label><input type="text" name="dni" id="input_dni" maxlength="8" required></div>
                        <div class="campo"><label>Dirección:</label><input type="text" name="direccion" id="input_direccion"></div>
                        
                        <div class="campo"><label>Apellidos:</label><input type="text" name="apellidos" id="input_apellidos" required></div>
                        <div class="campo"><label>RUC:</label><input type="text" name="ruc" id="input_ruc" maxlength="11"></div>
                        <div class="campo"><label>Teléfono:</label><input type="text" name="telefono" id="input_telefono"></div>
                    </div>
                </fieldset>

                <div class="acciones-inferiores">
                    <button type="button" class="btn-accion" onclick="limpiarForm()">+ NUEVO</button>
                    
                    <button type="submit" class="btn-accion" id="btn_principal">💾 GUARDAR</button>
                    <button type="button" class="btn-accion" onclick="modoEditar()">📝 EDITAR</button>
                    <button type="button" class="btn-accion" onclick="confirmarEliminar()">🗑️ ELIMINAR</button>
                    <a href="../../principal.php" class="btn-accion btn-volver" style="text-decoration:none; display:inline-block; text-align:center;">↩️ REGRESAR</a>
                </div>
            </form>
        </section>

        <section class="tabla-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th><th>CÓDIGO</th><th>NOMBRES</th><th>APELLIDOS</th><th>DNI/RUC</th><th>TELÉFONO</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM clientes";
                    $res = $conexion->query($sql);
                    if ($res && $res->num_rows > 0) {
                        while($f = $res->fetch_assoc()){
                            $doc = $f['ruc'] ? $f['dni']." / ".$f['ruc'] : $f['dni'];
                            // Al hacer clic en la fila, se cargan los datos en el formulario para editar
                            echo "<tr onclick='cargarDatos(".json_encode($f).")' style='cursor:pointer;'>
                                    <td>{$f['id']}</td>
                                    <td>{$f['codigo']}</td>
                                    <td>{$f['nombres']}</td>
                                    <td>{$f['apellidos']}</td>
                                    <td>{$doc}</td>
                                    <td>{$f['telefono']}</td>
                                  </tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </div>

    <script>
        function cargarDatos(datos) {
            document.getElementById('input_id').value = datos.id;
            document.getElementById('input_codigo').value = datos.codigo;
            document.getElementById('input_nombres').value = datos.nombres;
            document.getElementById('input_apellidos').value = datos.apellidos;
            document.getElementById('input_sexo').value = datos.sexo;
            document.getElementById('input_dni').value = datos.dni;
            document.getElementById('input_ruc').value = datos.ruc;
            document.getElementById('input_direccion').value = datos.direccion;
            document.getElementById('input_telefono').value = datos.telefono;
            document.getElementById('input_email').value = datos.email;
            
            // Cambiamos el action para que al dar click en GUARDAR se ejecute la edición
            document.getElementById('formClientes').action = "editar_cliente_proceso.php";
            document.getElementById('btn_principal').innerHTML = "💾 ACTUALIZAR";
        }

        function limpiarForm() {
            // Limpiamos todo el formulario
            document.getElementById('formClientes').reset();
            
            // RESTAURAMOS EL CÓDIGO AUTOMÁTICO (Importante)
            document.getElementById('input_codigo').value = "<?php echo $nuevo_codigo; ?>";
            
            // Restauramos el botón a modo Guardar
            document.getElementById('formClientes').action = "guardar_cliente.php";
            document.getElementById('btn_principal').innerHTML = "💾 GUARDAR";
            document.getElementById('input_id').value = "";
        }

        function modoEditar() {
            const id = document.getElementById('input_id').value;
            if (id === "") {
                alert("Seleccione un cliente de la tabla inferior para editar.");
            } else {
                // Submit directo si ya hay datos cargados, o simplemente aviso
                document.getElementById('formClientes').submit();
            }
        }

        function confirmarEliminar() {
            const id = document.getElementById('input_id').value;
            if (id === "") {
                alert("Por favor, seleccione un cliente de la tabla primero.");
                return;
            }
            if (confirm("¿Está seguro de que desea eliminar a este cliente?")) {
                window.location.href = "eliminar_cliente.php?id=" + id;
            }
        }
    </script>
</body>
</html>