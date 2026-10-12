<?php
session_start();
require_once "../includes/mensajes.php";

if (isset($_GET["cerrada"]) && $_GET["cerrada"] === "1") {
    $mensaje_cierre = true;
} else {
    $mensaje_cierre = false;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Iniciar sesión | AgendaPro</title>

    <link rel="stylesheet" href="../css/estilos.css">
</head>

<body>

    <main class="pagina-auth">

        <section class="auth-contenedor">

            <div class="auth-marca">
                <a href="../index.php">
                    Agenda<span>Pro</span>
                </a>
            </div>

            <div class="auth-cabecera">

                <h1>Bienvenido de nuevo</h1>

                <p>
                    Inicia sesión para acceder a tu agenda personal.
                </p>

            </div>
            
            <?php
            mostrarMensaje();

            if ($mensaje_cierre) {
            echo '<div class="mensaje-exito" role="alert">';
            echo 'Has cerrado sesión correctamente.';
            echo '</div>';
       }
           ?>

            <form action="autenticar.php" method="POST">

                <div class="form-grupo">

                    <label for="correo">
                        Correo electrónico
                    </label>

                    <input
                        type="email"
                        id="correo"
                        name="correo"
                        placeholder="ejemplo@correo.com"
                        required
                    >

                </div>

                <div class="form-grupo">

                    <label for="password">
                        Contraseña
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Ingresa tu contraseña"
                        required
                    >

                </div>

                <button
                    type="submit"
                    class="btn btn-principal btn-completo">
                    Iniciar sesión
                </button>

            </form>

            <div class="auth-pie">

                <p>
                    ¿No tienes una cuenta?
                    <a href="registro.php">
                        Crear cuenta
                    </a>
                </p>

            </div>

        </section>

    </main>

</body>

</html>