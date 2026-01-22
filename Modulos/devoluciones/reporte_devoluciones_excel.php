<?php
// botica_kmk/Modulos/devoluciones/reporte_devoluciones_excel.php

include('../../conexion.php');
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../../index.php");
    exit();
}

header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=Reporte_Devoluciones_" . date("Y-m-d") . ".xls");
header("Pragma: no-cache");
header("Expires: 0");

// Unimos con la tabla productos para ver el nombre real del producto
$sql = "SELECT d.id_devolucion, d.tipo, p.descripcion, d.cantidad, d.motivo, d.fecha_devolucion 
        FROM devoluciones d 
        INNER JOIN productos p ON d.id_producto = p.id";
$res = $conexion->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        table { width: 100%; border-collapse: collapse; font-family: Arial; }
        th { background-color: #1B365D; color: white; border: 1px solid #000; padding: 10px; }
        td { border: 1px solid #000; padding: 8px; text-align: center; }
        .titulo { background-color: #2A8B8B; color: white; font-weight: bold; text-align: center; font-size: 18px; }
    </style>
</head>
<body>
    <table>
        <thead>
            <tr><td colspan="6" class="titulo">REPORTE DE DEVOLUCIONES - K&M-K S.A.C</td></tr>
            <tr>
                <th>ID</th>
                <th>TIPO</th>
                <th>PRODUCTO</th>
                <th>CANTIDAD</th>
                <th>MOTIVO</th>
                <th>FECHA</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($res && $res->num_rows > 0) {
                while($f = $res->fetch_assoc()){
                    echo "<tr>
                            <td>{$f['id_devolucion']}</td>
                            <td>{$f['tipo']}</td>
                            <td>{$f['descripcion']}</td>
                            <td>{$f['cantidad']}</td>
                            <td>{$f['motivo']}</td>
                            <td>{$f['fecha_devolucion']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No hay devoluciones registradas</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
<?php $conexion->close(); ?>