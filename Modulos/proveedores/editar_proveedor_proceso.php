<?php
// botica_kmk/Modulos/proveedores/editar_proveedor_proceso.php

include('../../conexion.php');
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id_proveedor']; // Ojo: Este name debe coincidir con el hidden en proveedores.php
    $razon = $_POST['razon_social'];
    $ruc = $_POST['ruc'];
    $dni = $_POST['dni'];
    $dir = $_POST['direccion'];
    $tel = $_POST['telefono'];
    $email = $_POST['email'];
    $banco = $_POST['nombre_banco'];
    $cuenta = $_POST['nro_cuenta'];

    if (!empty($id)) {
        $sql = "UPDATE proveedores SET 
                razon_social=?, ruc=?, dni=?, direccion=?, 
                telefono=?, email=?, nombre_banco=?, nro_cuenta=? 
                WHERE id_proveedor=?";
        
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ssssssssi", $razon, $ruc, $dni, $dir, $tel, $email, $banco, $cuenta, $id);

        if ($stmt->execute()) {
            echo "<script>
                    alert('¡Proveedor actualizado correctamente!'); 
                    window.location.href='proveedores.php';
                  </script>";
        } else {
            echo "Error al actualizar: " . $conexion->error;
        }
        $stmt->close();
    } else {
        echo "<script>alert('Error: ID no encontrado.'); window.location.href='proveedores.php';</script>";
    }
} else {
    header("Location: proveedores.php");
}
$conexion->close();
?>