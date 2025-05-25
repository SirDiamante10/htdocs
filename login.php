<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión - Cluster Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #1e306e 0%, #4158d0 50%, #c850c0 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            background: rgba(255,255,255,0.07);
            border-radius: 20px;
            padding: 2.5rem 2.5rem 2rem 2.5rem;
            box-shadow: 0 8px 32px 0 rgba(31,38,135,0.37);
            backdrop-filter: blur(4px);
            max-width: 380px;
            width: 100%;
        }
        .profile-pic {
            width: 90px;
            height: 90px;
            background: rgba(255,255,255,0.12);
            border-radius: 50%;
            margin: 0 auto 1.5rem auto;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .profile-pic img {
            width: 90px;
            height: 90px;
            object-fit: contain;
        }
        .form-control, .form-check-label {
            background: rgba(255,255,255,0.55);
        }
        .login-title {
            text-align: center;
            margin-bottom: 1rem;
            color: #fff;
            letter-spacing: 1px;
        }
        .forgot-link {
            float: right;
            font-size: 0.97em;
            color: #ddd;
            text-decoration: none;
        }
        .forgot-link:hover {
            color: #fff;
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="login-container shadow-lg">
    <div class="profile-pic mb-3">
        <img src="assets/img/logo.png" alt="Logo">
        <!-- Cambia la ruta por la de tu archivo, por ejemplo: src="assets/img/b29d10dd9cb31e8509246024b63a780a-removebg-preview.png" -->
    </div>
    <h2 class="login-title">Acceso al sistema</h2>
    <?php
    session_start();
    if (isset($_SESSION['error_login'])) {
        echo "<div class='alert alert-danger py-2'>" . $_SESSION['error_login'] . "</div>";
        unset($_SESSION['error_login']);
    }
    ?>
    <form action="php/auth/login.php" method="POST">
        <div class="mb-3 input-group">
            <span class="input-group-text bg-transparent border-0"><i class="bi bi-person"></i></span>
            <input type="email" name="email" class="form-control" placeholder="Correo electrónico" required>
        </div>
        <div class="mb-3 input-group">
            <span class="input-group-text bg-transparent border-0"><i class="bi bi-lock"></i></span>
            <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
        </div>
        <div class="mb-3 form-check d-flex align-items-center">
            <input type="checkbox" class="form-check-input me-2" id="rememberMe">
            <label class="form-check-label me-auto" for="rememberMe">Recuérdame</label>
            <a href="#" class="forgot-link">¿Olvidaste tu contraseña?</a>
        </div>
        <button type="submit" class="btn btn-primary w-100 py-2">Ingresar</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
