<?php
session_start();

require_once "../includes/mensajes.php";

if (isset($_GET["cerrada"]) && $_GET["cerrada"] === "1") {
    guardarMensaje("exito", "Has cerrado sesión correctamente.");
    header("Location: login.php");
    exit;
}

if (isset($_SESSION["usuario_id"])) {
    header("Location: ../contactos/listar.php");
    exit;
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
                <a href="../index.php">Agenda<span>Pro</span></a>
            </div>

            <div class="auth-cabecera">
                <h1>Bienvenido de nuevo</h1>
                <p>Inicia sesión para acceder a tu agenda personal.</p>
            </div>

            <?php mostrarMensaje(); ?>

            <form action="autenticar.php" method="POST">

                <div class="form-grupo">
                    <label for="correo">Correo electrónico</label>

                    <input
                        type="email"
                        id="correo"
                        name="correo"
                        placeholder="tucorreo@ejemplo.com"
                        maxlength="150"
                        autocomplete="email"
                        required
                    >
                </div>

                <div class="form-grupo">
                    <label for="password">Contraseña</label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Ingresa tu contraseña"
                        autocomplete="current-password"
                        required
                    >
                </div>

                <button
                    type="submit"
                    class="btn btn-principal btn-completo"
                >
                    Iniciar sesión
                </button>

            </form>

            <div class="auth-pie">
                <p>
                    ¿Todavía no tienes una cuenta?
                    <a href="registro.php">Crear cuenta</a>
                </p>
            </div>

        </section>

    </main>

</body>

</html>