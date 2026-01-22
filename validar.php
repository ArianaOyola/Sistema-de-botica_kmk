<?php
// botica_kmk/validar.php
include('conexion.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['usuario'];
    $pass = $_POST['contrasena'];

    // CORRECCIÓN: Quitamos "AND estado = 'Activo'" porque esa columna no existe en tu BD
    $consulta = $conexion->prepare("SELECT id_empleado, nombres, apellidos, especialidad FROM empleados WHERE usuario = ? AND contrasena = ?");
    
    // Bind parameters: "ss" significa que enviamos 2 strings (usuario y contraseña)
    $consulta->bind_param("ss", $user, $pass);
    $consulta->execute();
    $resultado = $consulta->get_result();

    if ($resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();
        
        // GUARDAMOS DATOS EN LA SESIÓN
        $_SESSION['id_empleado'] = $fila['id_empleado'];
        $_SESSION['usuario'] = $fila['nombres'];      // Para el saludo "Hola, Ariana"
        $_SESSION['cargo'] = $fila['especialidad'];   // Para la etiqueta de cargo

        header("Location: principal.php");
    } else {
        echo "<script>
                alert('Usuario o contraseña incorrectos');
                window.location.href='index.php';
              </script>";
    }
}
?>