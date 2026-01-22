<?php
// botica_kmk/Modulos/devoluciones/editar_devolucion_proceso.php

include('../../conexion.php');
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id_devolucion'];
    $tipo = $_POST['tipo'];
    $id_prod = $_POST['id_producto'];
    $cant = $_POST['cantidad'];
    $motivo = $_POST['motivo'];
    $fecha = $_POST['fecha_devolucion'];

    if (!empty($id)) {
        $sql = "UPDATE devoluciones SET 
                tipo=?, id_producto=?, cantidad=?, motivo=?, fecha_devolucion=?
                WHERE id_devolucion=?";
        
        $stmt = $conexion->prepare($sql);
        // "siissi" -> s=string, i=int
        $stmt->bind_param("siissi", $tipo, $id_prod, $cant, $motivo, $fecha, $id);

        if ($stmt->execute()) {
            echo "<script>
                    alert('¡Devolución actualizada correctamente!'); 
                    window.location.href='devoluciones.php';
                  </script>";
        } else {
            echo "Error al actualizar: " . $conexion->error;
        }
        $stmt->close();
    } else {
        echo "<script>alert('Error: ID no encontrado.'); window.location.href='devoluciones.php';</script>";
    }
} else {
    header("Location: devoluciones.php");
}
$conexion->close();
?>