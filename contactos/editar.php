<?php
/**
 * Formulario para Editar Contacto
 * Incluye Categorías (Opción C) y Favoritos (Opción D)
 */

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

// Obtener información del contacto
$sql = "SELECT id, id_categoria, nombre, telefono, correo, ciudad, es_favorito
        FROM contactos
        WHERE id = ? AND id_usuario = ?";

$stmt = $conexion->prepare($sql);
$stmt->execute([(int)$id, $_SESSION["usuario_id"]]);
$contacto = $stmt->fetch();

if (!$contacto) {
    guardarMensaje("error", "El contacto no existe o no tienes permiso para acceder a él.");
    header("Location: listar.php");
    exit;
}

// Obtener todas las categorías (Opción C)
$stmtCat = $conexion->query("SELECT id, nombre FROM categorias ORDER BY id ASC");
$categorias = $stmtCat->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar contacto | AgendaPro</title>
    <link rel="stylesheet" href="../css/estilo.css?v=<?= time() ?>">
</head>

<body>

    <header class="navbar">
        <div class="contenedor navbar-contenido">
            <a href="listar.php" class="navbar-logo">
                Agenda<span>Pro</span>
            </a>

            <div class="navbar-usuario">
                <span>
                    <?= htmlspecialchars($_SESSION["usuario_nombre"], ENT_QUOTES, "UTF-8") ?>
                    <span style="display: block; font-size: 12px; font-weight: normal; color: var(--gris-texto);">
                        <?= htmlspecialchars($_SESSION["usuario_correo"] ?? "", ENT_QUOTES, "UTF-8") ?>
                    </span>
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
                <p>Actualiza la información, categoría o estado de favorito de este contacto.</p>
            </div>

            <form action="actualizar.php" method="POST">

                <input type="hidden" name="id" value="<?= (int)$contacto["id"] ?>">

                <div class="form-grupo">
                    <label for="nombre">Nombre completo *</label>
                    <input
                        type="text"
                        id="nombre"
                        name="nombre"
                        maxlength="150"
                        value="<?= htmlspecialchars($contacto["nombre"], ENT_QUOTES, "UTF-8") ?>"
                        required
                    >
                </div>

                <!-- Selector de Categoría (Opción C) -->
                <div class="form-grupo">
                    <label for="id_categoria">🏷️ Categoría *</label>
                    <select id="id_categoria" name="id_categoria" required>
                        <option value="">-- Selecciona una categoría --</option>
                        <?php foreach ($categorias as $cat): ?>
                            <option value="<?= (int)$cat['id'] ?>" <?= ((int)$cat['id'] === (int)$contacto['id_categoria']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['nombre'], ENT_QUOTES, "UTF-8") ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-grupo">
                    <label for="telefono">Teléfono *</label>
                    <input
                        type="tel"
                        id="telefono"
                        name="telefono"
                        maxlength="30"
                        value="<?= htmlspecialchars($contacto["telefono"], ENT_QUOTES, "UTF-8") ?>"
                        required
                    >
                </div>

                <div class="form-grupo">
                    <label for="correo">Correo electrónico *</label>
                    <input
                        type="email"
                        id="correo"
                        name="correo"
                        maxlength="150"
                        value="<?= htmlspecialchars($contacto["correo"], ENT_QUOTES, "UTF-8") ?>"
                        required
                    >
                </div>

                <div class="form-grupo">
                    <label for="ciudad">Ciudad *</label>
                    <input
                        type="text"
                        id="ciudad"
                        name="ciudad"
                        maxlength="100"
                        value="<?= htmlspecialchars($contacto["ciudad"], ENT_QUOTES, "UTF-8") ?>"
                        required
                    >
                </div>

                <!-- Checkbox de Favorito (Opción D) -->
                <div class="form-grupo form-grupo-checkbox">
                    <label class="checkbox-contenedor">
                        <input 
                            type="checkbox" 
                            id="es_favorito" 
                            name="es_favorito" 
                            value="1"
                            <?= ((int)$contacto["es_favorito"] === 1) ? 'checked' : '' ?>
                        >
                        <span class="checkbox-etiqueta">
                            ⭐ Marcar como contacto favorito
                        </span>
                    </label>
                    <small style="color: var(--gris-texto); display: block; margin-top: 4px;">
                        Permite filtrar y acceder rápidamente a este contacto desde la vista principal.
                    </small>
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
