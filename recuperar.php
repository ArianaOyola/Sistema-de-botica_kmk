<?php include('conexion.php'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Contraseña - K&M-K</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <section class="left-side">
        <div class="avatar-container">🔐</div>
        <div class="company-tag">RECUPERAR ACCESO</div>
    </section>
    <section class="right-side">
        <div class="login-box">
            <h2>RESTABLECER CONTRASEÑA</h2>
            <form action="" method="POST">
                <div class="form-group">
                    <label>Introduce tu DNI:</label>
                    <input type="text" name="dni" required maxlength="8">
                </div>
                <div class="form-group">
                    <label>Introduce tu Email:</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Nueva Contraseña:</label>
                    <input type="password" name="new_pass" required>
                </div>
                <div class="btn-container">
                    <button type="submit" name="btn_reset" class="btn btn-ingresar">CAMBIAR</button>
                    <button type="button" class="btn btn-salir" onclick="window.location.href='index.php'">CANCELAR</button>
                </div>
            </form>
            <?php
            if(isset($_POST['btn_reset'])) {
                $dni = $_POST['dni'];
                $email = $_POST['email'];
                $pass = $_POST['new_pass'];

                // Verificamos que coincidan
                $check = $conexion->query("SELECT id_empleado FROM empleados WHERE dni='$dni' AND email='$email'");
                if($check->num_rows > 0) {
                    $conexion->query("UPDATE empleados SET contrasena='$pass' WHERE dni='$dni'");
                    echo "<script>alert('¡Contraseña actualizada!'); window.location.href='index.php';</script>";
                } else {
                    echo "<p style='color:red; text-align:center; margin-top:10px;'>Datos incorrectos. No se encontró coincidencia.</p>";
                }
            }
            ?>
        </div>
    </section>
</body>
</html>