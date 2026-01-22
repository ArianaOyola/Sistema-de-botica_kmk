<?php
// 1. INCLUIR CONEXIÓN: Retrocedemos dos niveles para llegar a la raíz
include('../../conexion.php');
session_start();

// 2. SEGURIDAD: Verificar sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: ../../index.php");
    exit();
}

// 3. RECIBIR DATOS DEL FORMULARIO
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Es vital recibir el ID para saber qué registro actualizar
    $id_caja = $_POST['id_caja'];
    $fecha = $_POST['fecha'];
    $caja_inicial = $_POST['caja_initial'];
    $ingresos_ventas = $_POST['ingresos_ventas'];
    $ganancia = $_POST['ganancia'];

    // Validar que el ID no esté vacío
    if (!empty($id_caja)) {
        // 4. PREPARAR LA CONSULTA DE ACTUALIZACIÓN
        $sql = "UPDATE caja SET 
                fecha = '$fecha', 
                caja_inicial = '$caja_inicial', 
                ingresos_ventas = '$ingresos_ventas', 
                ganancia = '$ganancia' 
                WHERE id_caja = '$id_caja'";

        if ($conexion->query($sql) === TRUE) {
            echo "<script>
                    alert('¡Registro de caja actualizado correctamente!');
                    window.location.href = 'caja.php';
                  </script>";
        } else {
            echo "Error al actualizar: " . $conexion->error;
        }
    } else {
        echo "<script>
                alert('Error: ID de caja no encontrado.');
                window.location.href = 'caja.php';
              </script>";
    }
} else {
    // Si intentan entrar al archivo sin enviar el formulario, redirigir
    header("Location: caja.php");
    exit();
}

$conexion->close();
?>