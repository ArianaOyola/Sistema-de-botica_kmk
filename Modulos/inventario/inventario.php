<?php
// 1. CONEXIÓN: Retrocedemos dos niveles para llegar a la raíz (../../)
include('../../conexion.php');
session_start();

// 2. SEGURIDAD: Si no hay sesión iniciada, redirige al login en la raíz
if (!isset($_SESSION['usuario'])) { 
    header("Location: ../../index.php"); 
    exit(); 
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Control de Inventario - K&M-K S.A.C</title>
    <link rel="stylesheet" href="../../css/inventario.css">
</head>
<body>

    <header class="header-seccion">
        <div class="header-icon">👨‍⚕️</div>
        <h1 class="titulo-pagina">CONTROL DE INVENTARIO GENERAL</h1>
        <div class="header-icon">⚕️</div>
    </header>

    <div class="contenedor-principal">
        <section class="panel-control">
            <div class="grid-resumen">
                <div class="tarjeta-info">
                    <label>🔍 Buscar Producto:</label>
                    <input type="text" placeholder="Nombre, lote o categoría...">
                </div>
                <div class="tarjeta-info">
                    <label>📅 Filtrar por Vencimiento:</label>
                    <select>
                        <option>Todos los productos</option>
                        <option>Vencen en 30 días</option>
                        <option>Ya vencidos</option>
                    </select>
                </div>
                <div class="tarjeta-info">
                    <button class="btn-inventario" onclick="window.location.href='reporte_excel.php'">📊 GENERAR REPORTE</button>
                    
                    <a href="../../principal.php" class="btn-inventario btn-volver" style="text-decoration:none;">↩️ VOLVER</a>
                </div>
            </div>
        </section>

        <section class="tabla-container">
            <table>
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Descripción</th>
                        <th>Categoría</th>
                        <th>Stock Actual</th>
                        <th>Precio Venta</th>
                        <th>Vencimiento</th>
                        <th>Estado Stock</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Consulta a la tabla productos para mostrar el inventario
                    $sql = "SELECT id, descripcion, categoria, stock, precio_venta, fecha_vencimiento FROM productos";
                    $res = $conexion->query($sql);
                    if ($res && $res->num_rows > 0) {
                        while($f = $res->fetch_assoc()){
                            // Lógica de alertas visuales para el stock
                            $clase_stock = ($f['stock'] <= 5) ? 'stock-bajo' : 'stock-ok';
                            $texto_stock = ($f['stock'] <= 5) ? '⚠️ AGOTÁNDOSE' : '✅ DISPONIBLE';
                            
                            echo "<tr>
                                    <td>" . str_pad($f['id'], 5, "0", STR_PAD_LEFT) . "</td>
                                    <td>{$f['descripcion']}</td>
                                    <td>{$f['categoria']}</td>
                                    <td class='$clase_stock'><b>{$f['stock']}</b></td>
                                    <td>S/ " . number_format($f['precio_venta'], 2) . "</td>
                                    <td>{$f['fecha_vencimiento']}</td>
                                    <td><span class='badge $clase_stock'>$texto_stock</span></td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No hay productos en el inventario</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </div>
</body>
</html>