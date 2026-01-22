<?php
// 1. INCLUIR CONEXIÓN
include('../../conexion.php');
session_start();

// 2. SEGURIDAD
if (!isset($_SESSION['usuario'])) {
    header("Location: ../../index.php");
    exit();
}

// 3. PROCESO DE ELIMINACIÓN CON CONTROL DE ERRORES
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Preparamos la orden de borrado
    $stmt = $conexion->prepare("DELETE FROM clientes WHERE id = ?");
    $stmt->bind_param("i", $id);

    // AQUÍ ESTÁ EL TRUCO: INTENTAR (TRY) Y CAPTURAR (CATCH) EL ERROR
    try {
        $stmt->execute();
        
        // Si llega aquí, es que borró sin problemas
        echo "<script>
                alert('✅ Cliente eliminado con éxito.');
                window.location.href='clientes.php';
              </script>";

    } catch (mysqli_sql_exception $e) {
        // Código 1451 es el error de "Llave foránea" (Foreign Key)
        if ($e->getCode() == 1451) {
            echo "<script>
                    alert('⚠️ NO SE PUEDE ELIMINAR: Este cliente tiene VENTAS o PEDIDOS registrados en el historial.\\n\\nEl sistema lo protege para no perder la información de las ventas.');
                    window.location.href='clientes.php';
                  </script>";
        } else {
            // Cualquier otro error
            echo "<script>
                    alert('❌ Ocurrió un error inesperado: " . $e->getMessage() . "');
                    window.location.href='clientes.php';
                  </script>";
        }
    }
    
    $stmt->close();
} else {
    header("Location: clientes.php");
}

$conexion->close();
?>