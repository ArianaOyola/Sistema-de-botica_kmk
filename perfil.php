<?php
// 1. CONEXIÓN
include('conexion.php');
session_start();

// 2. SEGURIDAD
if (!isset($_SESSION['usuario'])) { 
    header("Location: index.php"); 
    exit(); 
}

// 3. OBTENER DATOS ACTUALIZADOS DEL USUARIO
$id_user = $_SESSION['id_empleado'];
$sql = "SELECT * FROM empleados WHERE id_empleado = '$id_user'";
$res = $conexion->query($sql);
$datos = $res->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Perfil - K&M-K S.A.C</title>
    <link rel="stylesheet" href="css/perfil.css">
</head>
<body>

    <header class="header-seccion">
        <div class="header-icon">👨‍⚕️</div>
        <h1 class="titulo-pagina">MI PERFIL DE USUARIO</h1>
        <div class="header-icon">⚙️</div>
    </header>

    <div class="contenedor-principal">
        <section class="formulario-datos">
            <form method="POST">
                <fieldset>
                    <legend>DATOS DE MI CUENTA</legend>
                    
                    <div class="grid-formulario">
                        <div class="grupo-input">
                            <label>👤 Nombres:</label>
                            <input type="text" value="<?php echo $datos['nombres']; ?>" readonly class="input-readonly">
                        </div>

                        <div class="grupo-input">
                            <label>👤 Apellidos:</label>
                            <input type="text" value="<?php echo $datos['apellidos']; ?>" readonly class="input-readonly">
                        </div>

                        <div class="grupo-input">
                            <label>🎓 Cargo / Especialidad:</label>
                            <input type="text" value="<?php echo $datos['especialidad']; ?>" readonly class="input-readonly">
                        </div>

                        <div class="grupo-input">
                            <label>🪪 DNI:</label>
                            <input type="text" value="<?php echo $datos['dni']; ?>" readonly class="input-readonly">
                        </div>

                        <div class="grupo-input span-2">
                            <label>📧 Usuario / Email (Acceso):</label>
                            <input type="email" name="usuario" value="<?php echo $datos['usuario']; ?>" required>
                        </div>

                        <div class="grupo-input">
                            <label>🔒 Contraseña Actual:</label>
                            <input type="text" value="<?php echo $datos['contrasena']; ?>" readonly class="input-readonly">
                        </div>

                        <div class="grupo-input">
                            <label>🔑 Nueva Contraseña (Opcional):</label>
                            <input type="password" name="nueva_pass" placeholder="Escriba para cambiar...">
                        </div>
                    </div>
                </fieldset>

                <div class="botones-ficha">
                    <button type="submit" name="actualizar" class="btn-ficha">💾 ACTUALIZAR DATOS</button>
                    <a href="principal.php" class="btn-ficha btn-volver">↩️ REGRESAR</a>
                </div>
            </form>

            <?php
            // LÓGICA DE ACTUALIZACIÓN
            if(isset($_POST['actualizar'])) {
                $nuevo_user = $_POST['usuario'];
                $nueva_pass = $_POST['nueva_pass'];
                
                // Si el campo nueva contraseña está vacío, mantenemos la anterior
                $pass_final = !empty($nueva_pass) ? $nueva_pass : $datos['contrasena'];

                $sql_update = "UPDATE empleados SET usuario = '$nuevo_user', contrasena = '$pass_final' WHERE id_empleado = '$id_user'";
                
                if($conexion->query($sql_update)) {
                    // Actualizamos la variable de sesión por si cambió el nombre (aunque aquí editamos usuario)
                    $_SESSION['usuario'] = $datos['nombres']; 
                    echo "<script>
                            alert('¡Datos actualizados correctamente!');
                            window.location.href='principal.php';
                          </script>";
                } else {
                    echo "<script>alert('Error al actualizar');</script>";
                }
            }
            ?>
        </section>
    </div>

</body>
</html>