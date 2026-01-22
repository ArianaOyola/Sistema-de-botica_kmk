<?php
// botica_kmk/Modulos/ventas/procesar_venta.php

include('../../conexion.php'); 
session_start();

if (!isset($_SESSION['usuario'])) { 
    header("Location: ../../index.php"); 
    exit(); 
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. DATOS DE CABECERA
    $id_cliente = $_POST['id_cliente'];
    $id_empleado = $_POST['id_empleado'];
    $serie = $_POST['serie'];
    $numero = $_POST['numero'];
    $total = $_POST['total_final'];
    
    // Fecha actual del servidor (es más seguro que la del formulario)
    $fecha_actual = date("Y-m-d H:i:s"); 

    // 2. DATOS DEL DETALLE
    $productos = $_POST['productos'] ?? [];
    $cantidades = $_POST['cantidades'] ?? [];
    $precios = $_POST['precios'] ?? [];

    if (empty($productos)) {
        echo "<script>alert('Error: El carrito está vacío'); window.history.back();</script>";
        exit();
    }

    // 3. TRANSACCIÓN
    $conexion->begin_transaction();

    try {
        // Insertar Venta
        $sqlVenta = "INSERT INTO ventas (id_cliente, id_empleado, fecha_venta, serie_comprobante, nro_comprobante, total) 
                     VALUES (?, ?, ?, ?, ?, ?)";
        $stmtVenta = $conexion->prepare($sqlVenta);
        $stmtVenta->bind_param("iisssd", $id_cliente, $id_empleado, $fecha_actual, $serie, $numero, $total);
        $stmtVenta->execute();
        
        $id_venta_generada = $conexion->insert_id;

        // Insertar Detalles y Actualizar Stock
        $sqlDetalle = "INSERT INTO detalle_venta (id_venta, id_producto, cantidad, precio_unitario) VALUES (?, ?, ?, ?)";
        $stmtDetalle = $conexion->prepare($sqlDetalle);

        for ($i = 0; $i < count($productos); $i++) {
            $id_p = $productos[$i];
            $cant = $cantidades[$i];
            $prec = $precios[$i];

            $stmtDetalle->bind_param("iiid", $id_venta_generada, $id_p, $cant, $prec);
            $stmtDetalle->execute();

            // Restar stock
            $conexion->query("UPDATE productos SET stock = stock - $cant WHERE id = $id_p"); 
        }

        $conexion->commit();
        echo "<script>
                alert('¡Venta registrada con éxito!'); 
                window.location.href='ventas.php';
              </script>";

    } catch (Exception $e) {
        $conexion->rollback();
        echo "Error: " . $e->getMessage();
    }
} else {
    header("Location: ventas.php");
}
?>