<?php
// botica_kmk/principal.php
session_start();
if (!isset($_SESSION['usuario'])) { header("Location: index.php"); exit(); }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Principal - Inversiones K&M-K S.A.C</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>⚕️</text></svg>">
    <link rel="stylesheet" href="css/principal.css">
    <style>
        /* ESTILOS DE EMERGENCIA PARA FORZAR EL DISEÑO */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            background-color: #2A8B8B;
            overflow-x: hidden; /* Evita scroll horizontal */
        }
        main { flex: 1; display: flex; justify-content: center; align-items: center; }
        
        /* FOOTER FORZADO */
        .footer-forced {
            background-color: #FFFFFF;
            border-top: 3px solid #333;
            width: 100%;
            height: 90px;
            
            display: flex !important;           
            flex-direction: row !important;     
            align-items: center !important;
            
            /* AQUÍ ESTÁ EL TRUCO: Centramos todo el conjunto */
            justify-content: center !important; 
            
            /* Separación entre el grupo de botones grandes y el grupo de perfil */
            gap: 60px !important; 
            
            padding: 0 20px;
        }

        /* GRUPO 1: BOTONES PRINCIPALES */
        .grupo-botones {
            display: flex !important;
            flex-direction: row !important;
            gap: 20px !important;
        }

        .btn-grande {
            text-decoration: none;
            color: #1B365D;
            font-weight: bold;
            padding: 12px 25px;
            border: 1px solid #CCCCCC;
            background: #FFFFFF;
            font-size: 14px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
            transition: all 0.3s;
        }
        .btn-grande:hover { background-color: #f2f2f2; transform: translateY(-2px); }

        /* GRUPO 2: HERRAMIENTAS (PERFIL/SALIR) */
        .grupo-tools {
            /* YA NO USAMOS POSITION ABSOLUTE */
            display: flex !important;
            flex-direction: row !important;
            gap: 15px !important;
            
            /* Una pequeña línea gris a la izquierda para separarlos visualmente */
            padding-left: 30px;
            border-left: 2px solid #EEE;
        }

        .btn-tool-forced {
            text-decoration: none;
            font-size: 13px;
            font-weight: bold;
            padding: 10px 15px;
            border-radius: 5px;
            background: white;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: all 0.3s;
        }
        
        .btn-perfil { color: #1B365D; border: 1px solid #1B365D; }
        .btn-perfil:hover { background: #1B365D; color: white; }

        .btn-salir { color: #d9534f; border: 1px solid #d9534f; }
        .btn-salir:hover { background: #d9534f; color: white; }

    </style>
</head>
<body>

    <header class="header-principal">
        <nav class="nav-superior">
            <a href="Modulos/pedidos/pedidos.php">📁 PEDIDOS</a>
            <a href="Modulos/productos/productos.php">🛒 PRODUCTOS</a>
            <a href="Modulos/inventario/inventario.php">📦 INVENTARIO</a>
            <a href="Modulos/proveedores/proveedores.php">👤 PROVEEDORES</a>
            <a href="Modulos/empleado/empleados.php">👥 EMPLEADOS</a>
            <a href="Modulos/devoluciones/devoluciones.php">📋 DEVOLUCIONES</a>
        </nav>
    </header>

    <main class="main-content">
        <div class="logo-central">
            <h1>BOTICA</h1>
            <div class="iconos-salud">⚕️ ❤️ 🧪 🧬</div>
            <h2>"Inversiones K&M-K S.A.C"</h2>
            
            <div class="bienvenida-user">
                <div class="saludo-texto">
                    Hola, <b><?php echo $_SESSION['usuario']; ?></b>
                </div>
                <div class="cargo-texto">
                    ( <?php echo isset($_SESSION['cargo']) ? $_SESSION['cargo'] : 'Personal'; ?> )
                </div>
            </div>
        </div>
    </main>

    <footer class="footer-forced">
        
        <div class="grupo-botones">
            <a href="Modulos/cliente/clientes.php" class="btn-grande">👥 CLIENTES</a>
            <a href="Modulos/caja/caja.php" class="btn-grande">🏦 CAJA</a>
            <a href="Modulos/medicamentos/medicamentos.php" class="btn-grande">💊 MEDICAMENTOS</a>
            <a href="Modulos/ventas/ventas.php" class="btn-grande">💰 VENTAS</a>
        </div>

        <div class="grupo-tools">
            <a href="perfil.php" class="btn-tool-forced btn-perfil">👤 Mi Perfil</a>
            <a href="logout.php" class="btn-tool-forced btn-salir">❌ Salir</a>
        </div>

    </footer>

</body>
</html>