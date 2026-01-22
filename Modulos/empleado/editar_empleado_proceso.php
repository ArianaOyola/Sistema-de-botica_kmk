<?php
include('../../conexion.php');
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id_empleado'];
    $codigo = $_POST['codigo'];
    $dni = $_POST['dni'];
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $sexo = $_POST['sexo'];
    $telefono = $_POST['telefono'];
    $especialidad = $_POST['especialidad'];

    if (!empty($id)) {
        $sql = "UPDATE empleados SET 
                codigo=?, dni=?, nombres=?, apellidos=?, 
                sexo=?, telefono=?, especialidad=?
                WHERE id_empleado=?";
        
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("sssssssi", $codigo, $dni, $nombres, $apellidos, $sexo, $telefono, $especialidad, $id);

        if ($stmt->execute()) {
            echo "<script>
                    alert('¡Empleado actualizado correctamente!'); 
                    window.location.href='empleados.php';
                  </script>";
        } else {
            echo "Error al actualizar: " . $conexion->error;
        }
        $stmt->close();
    }
}
$conexion->close();
?>