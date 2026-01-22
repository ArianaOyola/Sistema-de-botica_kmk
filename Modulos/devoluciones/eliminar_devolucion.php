<?php
// botica_kmk/Modulos/devoluciones/eliminar_devolucion.php

include('../../conexion.php');
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../../index.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conexion->prepare("DELETE FROM devoluciones WHERE id_devolucion = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>
                alert('Devolución eliminada correctamente');
                window.location.href='devoluciones.php';
              </script>";
    } else {
        echo "<script>
                alert('Error al eliminar: " . $conexion->error . "');
                window.location.href='devoluciones.php';
              </script>";
    }
    
    $stmt->close();
} else {
    header("Location: devoluciones.php");
}
$conexion->close();
?>