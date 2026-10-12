 <?php

session_start();

if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once "../includes/mensajes.php";

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Nuevo contacto | AgendaPro</title>

    <link rel="stylesheet" href="../css/estilos.css">

</head>

<body>

    <header class="navbar">

        <div class="contenedor navbar-contenido">

            <a href="listar.php" class="navbar-logo">
                Agenda<span>Pro</span>
            </a>

            <div class="navbar-usuario">

                <span>
                    <?= htmlspecialchars($_SESSION["usuario_nombre"]) ?>
                </span>

                <a href="../auth/logout.php">
                    Cerrar sesión
                </a>

            </div>

        </div>

    </header>

    <main class="contenedor dashboard">

        <section class="formulario-card">

            <div class="formulario-cabecera">

                <h1>Nuevo contacto</h1>

                <p>
                    Agrega una persona a tu agenda personal.
                </p>

            </div>

            <?php mostrarMensaje(); ?> 
            
            <form action="guardar.php" method="POST">

                <div class="form-grupo">

                    <label for="nombre">
                        Nombre completo
                    </label>

                    <input
                        type="text"
                        id="nombre"
                        name="nombre"
                        placeholder="Ej. Juan Pérez"
                        maxlength="150"
                        required
                    >

                </div>

                <div class="form-grupo">

                    <label for="telefono">
                        Teléfono
                    </label>

                    <input
                        type="text"
                        id="telefono"
                        name="telefono"
                        placeholder="Ej. 0991234567"
                        maxlength="30"
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
                        placeholder="Ej. juan@correo.com"
                        maxlength="150"
                        required
                    >

                </div>

                <div class="form-grupo">

                    <label for="ciudad">
                        Ciudad
                    </label>

                    <input
                        type="text"
                        id="ciudad"
                        name="ciudad"
                        placeholder="Ej. Macas"
                        required
                    >

                </div>

                <div class="formulario-acciones">

                    <a
                        href="listar.php"
                        class="btn btn-secundario">
                        Cancelar
                    </a>

                    <button
                        type="submit"
                        class="btn btn-principal">
                        Guardar contacto
                    </button>

                </div>

            </form>

        </section>

    </main>

</body>

</html>