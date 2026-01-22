<?php
// 1. INCLUIR CONEXIÓN: Retrocedemos dos niveles para llegar a la raíz (../../)
include('../../conexion.php');
session_start();

// 2. SEGURIDAD: Verificar que el usuario esté logueado
if (!isset($_SESSION['usuario'])) {
    header("Location: ../../index.php");
    exit();
}

// 3. RECIBIR DATOS DEL FORMULARIO
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Los nombres deben coincidir con el atributo 'name' en devoluciones.php
    $tipo = $_POST['tipo'];
    $id_prod = $_POST['id_producto'];
    $cant = $_POST['cantidad'];
    $motivo = $_POST['motivo'];
    $fecha = $_POST['fecha_devolucion'];

    // 4. PREPARAR LA SENTENCIA SQL
    $sql = "INSERT INTO devoluciones (tipo, id_producto, cantidad, motivo, fecha_devolucion) VALUES (?, ?, ?, ?, ?)";
    
    // Uso de sentencias preparadas para seguridad
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("siiss", $tipo, $id_prod, $cant, $motivo, $fecha);

    if ($stmt->execute()) {
        // Si el registro es exitoso, muestra una alerta y redirige al listado
        echo "<script>
                alert('¡Devolución registrada con éxito!');
                window.location.href='devoluciones.php';
              </script>";
    } else {
        // En caso de error, muestra el detalle
        echo "Error al registrar la devolución: " . $conexion->error;
    }

    $stmt->close();
} else {
    // Si se intenta entrar directamente, redirige a la página de devoluciones
    header("Location: devoluciones.php");
}

$conexion->close();
?>