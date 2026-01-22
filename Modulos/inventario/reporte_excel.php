<?php
// botica_kmk/Modulos/inventario/reporte_excel.php

// 1. INCLUIR CONEXIÓN
include('../../conexion.php');
session_start();

// 2. SEGURIDAD: Verificar sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: ../../index.php");
    exit();
}

// 3. CONFIGURAR HEADERS PARA DESCARGAR COMO EXCEL
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=Reporte_Inventario_" . date("Y-m-d") . ".xls");
header("Pragma: no-cache");
header("Expires: 0");

// 4. CONSULTA A LA BASE DE DATOS
$sql = "SELECT id, descripcion, categoria, stock, precio_compra, precio_venta, fecha_vencimiento, laboratorio FROM productos";
$res = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        /* Estilos básicos para que la tabla de Excel se vea ordenada */
        table { width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; }
        th { background-color: #1B365D; color: white; border: 1px solid #000000; padding: 10px; }
        td { border: 1px solid #000000; padding: 8px; text-align: center; }
        
        /* Colores de alerta */
        .alerta-roja { background-color: #FFCCCC; color: #990000; }
        .alerta-verde { background-color: #CCFFCC; color: #006600; }
        
        .titulo { font-size: 20px; font-weight: bold; text-align: center; background-color: #2A8B8B; color: white; height: 50px; }
    </style>
</head>
<body>
    <table>
        <thead>
            <tr>
                <td colspan="8" class="titulo">REPORTE GENERAL DE INVENTARIO - K&M-K S.A.C</td>
            </tr>
            <tr>
                <th>ID</th>
                <th>DESCRIPCIÓN</th>
                <th>CATEGORÍA</th>
                <th>LABORATORIO</th>
                <th>STOCK</th>
                <th>P. COMPRA</th>
                <th>P. VENTA</th>
                <th>VENCIMIENTO</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($res && $res->num_rows > 0) {
                while($f = $res->fetch_assoc()){
                    // Lógica de colores según el stock (igual que en tu sistema)
                    // Si stock <= 5, pintamos la celda de rojo suave
                    $color_stock = ($f['stock'] <= 5) ? 'style="background-color: #FFCCCC; color: red; font-weight:bold;"' : '';
                    
                    echo "<tr>
                            <td>{$f['id']}</td>
                            <td>{$f['descripcion']}</td>
                            <td>{$f['categoria']}</td>
                            <td>{$f['laboratorio']}</td>
                            <td $color_stock>{$f['stock']}</td>
                            <td>S/ " . number_format($f['precio_compra'], 2) . "</td>
                            <td>S/ " . number_format($f['precio_venta'], 2) . "</td>
                            <td>{$f['fecha_vencimiento']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No hay productos registrados</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
<?php $conexion->close(); ?>