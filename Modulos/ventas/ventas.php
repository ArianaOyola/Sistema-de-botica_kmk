<?php
// 1. CONEXIÓN
include('../../conexion.php'); 
session_start();

if (!isset($_SESSION['usuario'])) { 
    header("Location: ../../index.php"); 
    exit(); 
}

$id_empleado_sesion = $_SESSION['id_empleado'] ?? 0; 

// --- LÓGICA PARA NÚMERO DE VENTA AUTOMÁTICO ---
$sql_num = "SELECT nro_comprobante FROM ventas ORDER BY id_venta DESC LIMIT 1";
$res_num = $conexion->query($sql_num);
$siguiente_numero = "000001"; // Por defecto si es la primera venta

if ($res_num && $res_num->num_rows > 0) {
    $fila = $res_num->fetch_assoc();
    $ultimo = intval($fila['nro_comprobante']);
    $siguiente = $ultimo + 1;
    $siguiente_numero = str_pad($siguiente, 6, "0", STR_PAD_LEFT);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Realizar Venta - Inversiones K&M-K S.A.C</title>
    <link rel="stylesheet" href="../../css/ventas.css"> 
    <style>
        /* Estilo pequeño para el checkbox de Publico General */
        .check-general {
            display: flex; align-items: center; gap: 5px; margin-bottom: 5px;
            font-size: 14px; font-weight: bold; color: #1B365D; cursor: pointer;
        }
    </style>
</head>
<body>
    <header class="header-seccion">
        <div class="header-icon">👨‍⚕️</div>
        <h1 class="titulo-pagina">REALIZAR VENTA</h1>
        <div class="header-icon">⚕️</div>
    </header>

    <div class="contenedor-principal">
        <form action="procesar_venta.php" method="POST" id="form-venta" onsubmit="return validarVenta()">
            <input type="hidden" name="id_empleado" value="<?php echo $id_empleado_sesion; ?>">
            
            <input type="hidden" name="id_cliente" id="id_cliente_hidden"> 

            <div class="layout-superior">
                <div class="seccion-venta">
                    <fieldset>
                        <legend>DATOS DEL CLIENTE</legend>
                        
                        <label class="check-general">
                            <input type="checkbox" id="chk_publico" onchange="togglePublicoGeneral()"> 
                            Venta a Público General (Sin registro)
                        </label>

                        <div class="campo-inline">
                            <div class="input-group">
                                <label>Cliente:</label>
                                <select id="select_cliente" style="width:100%" onchange="seleccionarCliente()">
                                    <option value="">Buscar cliente registrado...</option>
                                    <?php
                                    // Listamos clientes (Excluyendo al ID 1 si quieres, o dejándolo)
                                    $cli = $conexion->query("SELECT id, nombres, apellidos, dni FROM clientes WHERE id != 1");
                                    while($c = $cli->fetch_assoc()){
                                        echo "<option value='{$c['id']}' data-doc='{$c['dni']}'>{$c['nombres']} {$c['apellidos']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="input-group">
                                <label>RUC/DNI:</label>
                                <input type="text" id="cliente_doc" readonly placeholder="Número doc.">
                            </div>
                        </div>
                    </fieldset>

                    <fieldset style="margin-top: 15px;">
                        <legend>DATOS DEL PRODUCTO</legend>
                        <div class="campo-inline">
                            <div class="input-group">
                                <label>Producto:</label>
                                <select id="select_producto" onchange="actualizarPrecio()">
                                    <option value="">Seleccione un producto...</option>
                                    <?php
                                    $prods = $conexion->query("SELECT id, descripcion, precio_venta, stock FROM productos WHERE stock > 0"); 
                                    while($p = $prods->fetch_assoc()){
                                        echo "<option value='{$p['id']}' data-precio='{$p['precio_venta']}' data-stock='{$p['stock']}'>{$p['descripcion']} (Stock: {$p['stock']})</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="input-group" style="max-width: 120px;">
                                <label>Cant:</label>
                                <input type="number" id="prod_cant" value="1" min="1">
                            </div>
                        </div>
                        <div class="campo-inline" style="margin-top: 10px;">
                            <div class="input-group">
                                <label>Precio:</label>
                                <input type="text" id="prod_precio" readonly placeholder="S/ 0.00">
                            </div>
                            <button type="button" class="btn-agregar-pos" onclick="agregarAlCarrito()">➕ AGREGAR</button>
                        </div>
                    </fieldset>
                </div>

                <div class="seccion-info-venta">
                    <div class="cuadro-fecha">
                        <div class="input-group-vertical">
                            <label>📅 FECHA DE EMISIÓN:</label>
                            <input type="text" name="fecha_emision" value="<?php echo date('d/m/Y'); ?>" readonly class="input-calendario" style="text-align:center;">
                        </div>
                        <div class="input-group-vertical" style="margin-top: 15px;">
                            <label>📑 SERIE:</label>
                            <input type="text" name="serie" value="001" readonly class="input-fijo" style="text-align:center;">
                        </div>
                        <div class="input-group-vertical" style="margin-top: 15px;">
                            <label>🔢 VENTA N° (Auto):</label>
                            <input type="text" name="numero" value="<?php echo $siguiente_numero; ?>" readonly class="input-fijo" style="font-size: 20px; color: red; text-align:center;">
                        </div>
                    </div>
                </div>
            </div>

            <div class="tabla-container">
                <table id="tabla-ventas">
                    <thead>
                        <tr>
                            <th>Cant.</th>
                            <th>Descripción</th>
                            <th>P. Unitario</th>
                            <th>Subtotal</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <div class="footer-venta">
                <div class="resumen-totales">
                    <div class="total-item">V. VENTA: S/ <span id="txt-subtotal">0.00</span></div>
                    <div class="total-item">I.G.V (18%): S/ <span id="txt-igv">0.00</span></div>
                    <div class="total-item total-final">TOTAL A PAGAR: S/ <span id="txt-total">0.00</span></div>
                    <input type="hidden" name="total_final" id="input_total_final">
                </div>

                <div class="acciones-finales">
                    <button type="submit" class="btn-accion">💾 GUARDAR VENTA</button>
                    <a href="../../principal.php" class="btn-accion" style="text-decoration:none;">↩️ REGRESAR</a>
                </div>
            </div>
        </form>
    </div>

    <script>
        let carrito = [];

        // --- LÓGICA DE CLIENTES ---
        function togglePublicoGeneral() {
            const check = document.getElementById('chk_publico');
            const select = document.getElementById('select_cliente');
            const inputDoc = document.getElementById('cliente_doc');
            const hiddenId = document.getElementById('id_cliente_hidden');

            if (check.checked) {
                // Modo Público General
                select.disabled = true;
                select.value = ""; 
                inputDoc.value = "00000000";
                hiddenId.value = "1"; // ID 1 es el cliente "PÚBLICO GENERAL" en la BD
            } else {
                // Modo Cliente Registrado
                select.disabled = false;
                inputDoc.value = "";
                hiddenId.value = "";
            }
        }

        function seleccionarCliente() {
            const select = document.getElementById('select_cliente');
            const doc = select.options[select.selectedIndex].getAttribute('data-doc');
            const id = select.value;

            document.getElementById('cliente_doc').value = doc || "";
            document.getElementById('id_cliente_hidden').value = id;
        }

        // --- LÓGICA DE PRODUCTOS ---
        function actualizarPrecio() {
            const select = document.getElementById('select_producto');
            const precio = select.options[select.selectedIndex].getAttribute('data-precio');
            document.getElementById('prod_precio').value = precio ? 'S/ ' + precio : '';
        }

        function agregarAlCarrito() {
            const select = document.getElementById('select_producto');
            const id = select.value;
            const nombre = select.options[select.selectedIndex].text.split(' (')[0]; // Solo nombre sin el stock
            const precioData = select.options[select.selectedIndex].getAttribute('data-precio');
            const stockData = parseInt(select.options[select.selectedIndex].getAttribute('data-stock'));
            const cant = parseInt(document.getElementById('prod_cant').value);

            if(!id) return alert("Seleccione un producto");
            if(cant <= 0) return alert("Cantidad inválida");
            if(cant > stockData) return alert("⚠️ Stock insuficiente. Solo quedan " + stockData);
            
            // Verificar si ya está en carrito
            const existe = carrito.find(p => p.id === id);
            if(existe) {
                alert("El producto ya está en la lista. Bórrelo si desea cambiar la cantidad.");
                return;
            }

            const precio = parseFloat(precioData);
            const subtotal = precio * cant;
            
            carrito.push({ id, nombre, precio, cant, subtotal });
            renderizarTabla();
        }

        function renderizarTabla() {
            const tbody = document.querySelector('#tabla-ventas tbody');
            tbody.innerHTML = '';
            let v_venta = 0;

            carrito.forEach((item, index) => {
                v_venta += item.subtotal;
                tbody.innerHTML += `
                    <tr>
                        <td>${item.cant}</td>
                        <td>${item.nombre}</td>
                        <td>S/ ${item.precio.toFixed(2)}</td>
                        <td>S/ ${item.subtotal.toFixed(2)}</td>
                        <td>
                            <button type="button" class="btn-del" onclick="eliminar(${index})">❌</button>
                            <input type="hidden" name="productos[]" value="${item.id}">
                            <input type="hidden" name="cantidades[]" value="${item.cant}">
                            <input type="hidden" name="precios[]" value="${item.precio}">
                        </td>
                    </tr>`;
            });

            // Cálculos matemáticos de Perú (Total incluye IGV)
            let total = v_venta; 
            let subtotal = total / 1.18;
            let igv = total - subtotal;

            document.getElementById('txt-subtotal').innerText = subtotal.toFixed(2);
            document.getElementById('txt-igv').innerText = igv.toFixed(2);
            document.getElementById('txt-total').innerText = total.toFixed(2);
            document.getElementById('input_total_final').value = total.toFixed(2);
        }

        function eliminar(index) {
            carrito.splice(index, 1);
            renderizarTabla();
        }

        function validarVenta() {
            const idCliente = document.getElementById('id_cliente_hidden').value;
            if(!idCliente) {
                alert("⚠️ Debe seleccionar un cliente o marcar 'Público General'.");
                return false;
            }
            if(carrito.length === 0) {
                alert("⚠️ El carrito de compras está vacío.");
                return false;
            }
            return true;
        }
    </script>
</body>
</html>