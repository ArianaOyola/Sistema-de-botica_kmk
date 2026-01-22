<?php
// botica_kmk/Modulos/empleado/eliminar_empleado.php

include('../../conexion.php');
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../../index.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Usamos sentencia preparada para seguridad
    $stmt = $conexion->prepare("DELETE FROM empleados WHERE id_empleado = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>
                alert('Empleado eliminado correctamente');
                window.location.href='empleados.php';
              </script>";
    } else {
        echo "<script>
                alert('Error al eliminar: " . $conexion->error . "');
                window.location.href='empleados.php';
              </script>";
    }
    
    $stmt->close();
} else {
    header("Location: empleados.php");
}
$conexion->close();
?>