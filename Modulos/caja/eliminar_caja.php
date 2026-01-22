<?php
// 1. INCLUIR CONEXIÓN: Retrocedemos dos niveles para llegar a la raíz (../../)
include('../../conexion.php');
session_start();

// 2. SEGURIDAD: Verificar que el usuario esté logueado
if (!isset($_SESSION['usuario'])) {
    header("Location: ../../index.php");
    exit();
}

// 3. PROCESO DE ELIMINACIÓN
if (isset($_GET['id_caja'])) {
    $id = $_GET['id_caja'];

    // Usamos una sentencia preparada para evitar inyecciones SQL (Seguridad)
    $stmt = $conexion->prepare("DELETE FROM caja WHERE id_caja = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Si se elimina con éxito, muestra alerta y redirige a la tabla de caja
        echo "<script>
                alert('Registro de caja eliminado correctamente');
                window.location.href='caja.php';
              </script>";
    } else {
        // En caso de error de base de datos
        echo "<script>
                alert('Error al eliminar: " . $conexion->error . "');
                window.location.href='caja.php';
              </script>";
    }
    
    $stmt->close();
} else {
    // Si no se recibe un ID, simplemente regresa a la página de caja
    header("Location: caja.php");
}

$conexion->close();
?>