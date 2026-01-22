<?php
// botica_kmk/Modulos/productos/eliminar_producto.php

// 1. INCLUIR CONEXIÓN
include('../../conexion.php');
session_start();

// 2. SEGURIDAD: Verificar sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: ../../index.php");
    exit();
}

// 3. PROCESO DE ELIMINACIÓN
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Usamos sentencia preparada para mayor seguridad
    $stmt = $conexion->prepare("DELETE FROM productos WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>
                alert('¡Producto eliminado correctamente!');
                window.location.href='productos.php';
              </script>";
    } else {
        echo "<script>
                alert('Error al eliminar: " . $conexion->error . "');
                window.location.href='productos.php';
              </script>";
    }
    
    $stmt->close();
} else {
    header("Location: productos.php");
}

$conexion->close();
?>