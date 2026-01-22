<?php include('conexion.php'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario - K&M-K</title>
    <link rel="stylesheet" href="css/estilos.css">
    <style>
        .login-box { max-width: 450px; } /* Un poco más ancho */
        .grid-registro { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
    </style>
</head>
<body>
    <section class="left-side">
        <div class="avatar-container">📝</div>
        <div class="company-tag">NUEVO USUARIO</div>
    </section>

    <section class="right-side">
        <div class="login-box">
            <h2>REGISTRARSE</h2>
            <form action="" method="POST">
                <div class="grid-registro">
                    <div class="form-group"><label>DNI:</label><input type="text" name="dni" required maxlength="8"></div>
                    <div class="form-group"><label>Nombres:</label><input type="text" name="nombres" required></div>
                    <div class="form-group"><label>Apellidos:</label><input type="text" name="apellidos" required></div>
                    <div class="form-group"><label>Teléfono:</label><input type="text" name="telefono"></div>
                </div>
                
                <div class="form-group"><label>Cargo / Especialidad:</label>
                    <select name="especialidad" style="width:100%; padding:10px;">
                        <option value="Administrador">Administrador</option>
                        <option value="Farmacéutico">Farmacéutico</option>
                        <option value="Técnico">Técnico</option>
                        <option value="Cajero">Cajero</option>
                    </select>
                </div>

                <div class="form-group"><label>📧 Email (Usuario):</label><input type="email" name="email" required placeholder="correo@gmail.com"></div>
                <div class="form-group"><label>🔒 Contraseña:</label><input type="password" name="pass" required></div>

                <div class="btn-container">
                    <button type="submit" name="btn_registro" class="btn btn-ingresar">GUARDAR</button>
                    <button type="button" class="btn btn-salir" onclick="window.location.href='index.php'">VOLVER</button>
                </div>
            </form>
            <?php
            if(isset($_POST['btn_registro'])) {
                $dni = $_POST['dni'];
                $nom = $_POST['nombres'];
                $ape = $_POST['apellidos'];
                $tel = $_POST['telefono'];
                $esp = $_POST['especialidad'];
                $usr = $_POST['email']; // Usamos el email como usuario
                $pas = $_POST['pass'];
                $sex = 'No especificado';
                
                // Generar código automático simple
                $res = $conexion->query("SELECT id_empleado FROM empleados ORDER BY id_empleado DESC LIMIT 1");
                $next = ($res->num_rows > 0) ? $res->fetch_assoc()['id_empleado'] + 1 : 1;
                $cod = "EMP-" . str_pad($next, 3, "0", STR_PAD_LEFT);

                $sql = "INSERT INTO empleados (codigo, dni, nombres, apellidos, telefono, especialidad, usuario, email, contrasena, sexo) 
                        VALUES ('$cod', '$dni', '$nom', '$ape', '$tel', '$esp', '$usr', '$usr', '$pas', '$sex')";
                
                if($conexion->query($sql)) {
                    echo "<script>alert('¡Usuario creado! Ahora puedes ingresar.'); window.location.href='index.php';</script>";
                } else {
                    echo "<p style='color:red; text-align:center;'>Error: El DNI o Email ya existen.</p>";
                }
            }
            ?>
        </div>
    </section>
</body>
</html>