<?php
include('../../conexion.php');
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigo = $_POST['codigo'];
    $dni = $_POST['dni'];
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $sexo = $_POST['sexo'];
    $telefono = $_POST['telefono'];
    $especialidad = $_POST['especialidad'];

    // Insertar solo los campos existentes en tu BD
    $sql = "INSERT INTO empleados (codigo, dni, nombres, apellidos, sexo, telefono, especialidad) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssssss", $codigo, $dni, $nombres, $apellidos, $sexo, $telefono, $especialidad);

    if ($stmt->execute()) {
        echo "<script>
                alert('¡Empleado registrado con éxito!');
                window.location.href='empleados.php';
              </script>";
    } else {
        echo "Error al registrar: " . $conexion->error;
    }
    $stmt->close();
}
$conexion->close();
?>