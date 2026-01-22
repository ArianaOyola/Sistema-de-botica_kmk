<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inversiones K&M-K S.A.C - Inicio de Sesión</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>⚕️</text></svg>">
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

    <section class="left-side">
        <div class="avatar-container">
            <img src="img/logo_farmacia.png" alt="Logo Farmacia" class="logo-img">
        </div>
        <div class="company-tag">"Inversiones K&M-K S.A.C"</div>
    </section>
    <section class="right-side">
        <div class="login-box">
            <h2>INICIAR SESIÓN</h2>
            
            <form action="validar.php" method="POST">
                <div class="form-group">
                    <label>👤 USUARIO:</label>
                    <input type="text" name="usuario" placeholder="usuario@gmail.com" required>
                </div>
                
                <div class="form-group">
                    <label>🛡️ CONTRASEÑA:</label>
                    <input type="password" name="contrasena" placeholder="******" required>
                </div>
                
                <div class="btn-container">
                    <button type="submit" class="btn">✔️ INGRESAR</button>
                </div>

                <div style="text-align: center; margin-top: 40px; display: flex; flex-direction: column; gap: 15px;">
                    
                    <a href="recuperar.php" style="color: #FFFFFF !important; text-decoration: none; font-size: 14px;">
                        ¿Olvidaste tu contraseña?
                    </a>
                    
                    <a href="registro.php" style="color: #FFFFFF !important; text-decoration: none; font-size: 14px;">
                        ¿No tienes cuenta? <b style="color: #2A8B8B;">Regístrate aquí</b>
                    </a>
                    
                </div>
            </form>
        </div>
    </section>

</body>
</html>