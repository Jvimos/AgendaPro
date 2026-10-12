<?php

session_start();

if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once "../config/conexion.php";
require_once "../includes/mensajes.php";

$id = $_GET["id"] ?? "";

if (!filter_var($id, FILTER_VALIDATE_INT) || (int)$id < 1) {
    guardarMensaje("error", "El contacto seleccionado no es válido.");
    header("Location: listar.php");
    exit;
}

$sql = "SELECT id, nombre, telefono, correo, ciudad
        FROM contactos
        WHERE id = ? AND id_usuario = ?";

$stmt = $conexion->prepare($sql);

$stmt->execute([
    (int)$id,
    $_SESSION["usuario_id"]
]);

$contacto = $stmt->fetch();

if (!$contacto) {
    guardarMensaje(
        "error",
        "El contacto no existe o no tienes permiso para acceder."
    );

    header("Location: listar.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Editar contacto | AgendaPro</title>

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
                    <?= htmlspecialchars(
                        $_SESSION["usuario_nombre"],
                        ENT_QUOTES,
                        "UTF-8"
                    ) ?>
                </span>

                <a href="../auth/logout.php">Cerrar sesión</a>
            </div>

        </div>
    </header>

    <main class="contenedor dashboard">

        <?php mostrarMensaje(); ?>

        <section class="formulario-card">

            <div class="formulario-cabecera">
                <h1>Editar contacto</h1>
                <p>Actualiza la información de este contacto.</p>
            </div>

            <form action="actualizar.php" method="POST">

                <input
                    type="hidden"
                    name="id"
                    value="<?= (int)$contacto["id"] ?>"
                >

                <div class="form-grupo">
                    <label for="nombre">Nombre completo</label>

                    <input
                        type="text"
                        id="nombre"
                        name="nombre"
                        maxlength="150"
                        value="<?= htmlspecialchars(
                            $contacto["nombre"],
                            ENT_QUOTES,
                            "UTF-8"
                        ) ?>"
                        required
                    >
                </div>

                <div class="form-grupo">
                    <label for="telefono">Teléfono</label>

                    <input
                        type="text"
                        id="telefono"
                        name="telefono"
                        maxlength="30"
                        value="<?= htmlspecialchars(
                            $contacto["telefono"],
                            ENT_QUOTES,
                            "UTF-8"
                        ) ?>"
                        required
                    >
                </div>

                <div class="form-grupo">
                    <label for="correo">Correo electrónico</label>

                    <input
                        type="email"
                        id="correo"
                        name="correo"
                        maxlength="150"
                        value="<?= htmlspecialchars(
                            $contacto["correo"],
                            ENT_QUOTES,
                            "UTF-8"
                        ) ?>"
                        required
                    >
                </div>

                <div class="form-grupo">
                    <label for="ciudad">Ciudad</label>

                    <input
                        type="text"
                        id="ciudad"
                        name="ciudad"
                        maxlength="100"
                        value="<?= htmlspecialchars(
                            $contacto["ciudad"],
                            ENT_QUOTES,
                            "UTF-8"
                        ) ?>"
                        required
                    >
                </div>

                <div class="formulario-acciones">

                    <a href="listar.php" class="btn btn-secundario">
                        Cancelar
                    </a>

                    <button type="submit" class="btn btn-principal">
                        Guardar cambios
                    </button>

                </div>

            </form>

        </section>

    </main>

</body>

</html>

