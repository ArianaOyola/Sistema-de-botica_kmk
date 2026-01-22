<?php
// botica_kmk/Modulos/proveedores/eliminar_proveedor.php

include('../../conexion.php');
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../../index.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conexion->prepare("DELETE FROM proveedores WHERE id_proveedor = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>
                alert('Proveedor eliminado correctamente');
                window.location.href='proveedores.php';
              </script>";
    } else {
        echo "<script>
                alert('Error al eliminar: " . $conexion->error . "');
                window.location.href='proveedores.php';
              </script>";
    }
    $stmt->close();
} else {
    header("Location: proveedores.php");
}
$conexion->close();
?>