<?php
session_start();
require_once "../includes/mensajes.php";
mostrarMensaje();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Crear cuenta | AgendaPro</title>

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

                <h1>Crear una cuenta</h1>

                <p>
                    Regístrate para comenzar a administrar tus contactos.
                </p>

            </div> 

            <form action="registrar.php" method="POST">

                <div class="form-grupo">

                    <label for="nombre">
                        Nombre completo
                    </label>

                    <input
                        type="text"
                        id="nombre"
                        name="nombre"
                        placeholder="Ingresa tu nombre"
                        maxlength="100"
                        required
                    >

                </div>

                <div class="form-grupo">

                    <label for="correo">
                        Correo electrónico
                    </label>

                    <input
                        type="email"
                        id="correo"
                        name="correo"
                        placeholder="ejemplo@correo.com"
                        maxlength="150"
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
                        placeholder="Crea una contraseña"
                        minlength="6"
                        maxlength="72"
                        required
                    >

                </div>

                <div class="form-grupo">

                    <label for="password_confirmar">
                        Confirmar contraseña
                    </label>

                    <input
                        type="password"
                        id="password_confirmar"
                        name="password_confirmar"
                        placeholder="Repite tu contraseña"
                        minlength="6"
                        maxlength="72"
                        required
                    >

                </div>

                <button
                    type="submit"
                    class="btn btn-principal btn-completo">
                    Crear cuenta
                </button>

            </form>

            <div class="auth-pie">

                <p>
                    ¿Ya tienes una cuenta?
                    <a href="login.php">
                        Iniciar sesión
                    </a>
                </p>

            </div>

        </section>

    </main>

</body>

</html>