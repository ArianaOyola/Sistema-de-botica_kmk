<?php
// 1. CONEXIÓN: Retrocedemos dos niveles para llegar a la raíz
include('../../conexion.php'); 
session_start();

// 2. SEGURIDAD: Si no hay sesión, redirige al login en la raíz
if (!isset($_SESSION['usuario'])) { 
    header("Location: ../../index.php"); 
    exit(); 
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Clientes - Inversiones K&M-K</title>
    <link rel="stylesheet" href="../../css/clientes.css">
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
                        <div class="campo"><label>Código:</label><input type="text" name="codigo" id="input_codigo"></div>
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
                    <button type="reset" class="btn-accion" onclick="limpiarForm()">+ NUEVO</button>
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
            document.getElementById('formClientes').action = "guardar_cliente.php";
            document.getElementById('btn_principal').innerHTML = "💾 GUARDAR";
            document.getElementById('input_id').value = "";
        }

        function modoEditar() {
            const id = document.getElementById('input_id').value;
            if (id === "") {
                alert("Seleccione un cliente de la tabla inferior para editar.");
            } else {
                alert("Modo edición activado. Realice los cambios y presione ACTUALIZAR.");
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