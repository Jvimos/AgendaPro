<?php
/**
 * Listado de Contactos con Mejoras Avanzadas:
 * Opción A: Búsqueda (Nombre, Correo, Ciudad)
 * Opción B: Ordenamiento (Nombre, Ciudad, Fecha de creación)
 * Opción C: Categorías (Filtro y visualización)
 * Opción D: Contactos favoritos (Filtro y alternancia rápida)
 */

session_start();

if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once "../config/conexion.php";
require_once "../includes/mensajes.php";

$id_usuario = (int)$_SESSION["usuario_id"];

// -------------------------------------------------------------
// OBTENER CATEGORÍAS DISPONIBLES PARA EL FILTRO (Opción C)
// -------------------------------------------------------------
$stmtCat = $conexion->query("SELECT id, nombre FROM categorias ORDER BY id ASC");
$categorias = $stmtCat->fetchAll();

// -------------------------------------------------------------
// PARÁMETROS DE FILTRADO, BÚSQUEDA Y ORDENAMIENTO (Opciones A, B, C, D)
// -------------------------------------------------------------
$q = trim($_GET["q"] ?? "");
$campo = trim($_GET["campo"] ?? "todos");
$categoriaFiltro = trim($_GET["categoria"] ?? "");
$soloFavoritos = (isset($_GET["favoritos"]) && $_GET["favoritos"] === "1");
$orden = trim($_GET["orden"] ?? "fecha_desc");

// Construcción de la consulta con Prepared Statements seguros
$where = ["c.id_usuario = ?"];
$params = [$id_usuario];

// Opción A: Búsqueda por Nombre, Correo y/o Ciudad
if ($q !== "") {
    $paramBusqueda = "%" . $q . "%";
    if ($campo === "nombre") {
        $where[] = "c.nombre LIKE ?";
        $params[] = $paramBusqueda;
    } elseif ($campo === "correo") {
        $where[] = "c.correo LIKE ?";
        $params[] = $paramBusqueda;
    } elseif ($campo === "ciudad") {
        $where[] = "c.ciudad LIKE ?";
        $params[] = $paramBusqueda;
    } else {
        // Búsqueda integral en Nombre, Correo y Ciudad
        $where[] = "(c.nombre LIKE ? OR c.correo LIKE ? OR c.ciudad LIKE ?)";
        $params[] = $paramBusqueda;
        $params[] = $paramBusqueda;
        $params[] = $paramBusqueda;
    }
}

// Opción C: Filtro por Categoría
if ($categoriaFiltro !== "" && filter_var($categoriaFiltro, FILTER_VALIDATE_INT)) {
    $where[] = "c.id_categoria = ?";
    $params[] = (int)$categoriaFiltro;
}

// Opción D: Filtro de Contactos Favoritos
if ($soloFavoritos) {
    $where[] = "c.es_favorito = 1";
}

// Opción B: Ordenamiento seguro con Whitelist
$ordenSql = "c.fecha_creacion DESC, c.id DESC"; // Predeterminado
switch ($orden) {
    case "nombre_asc":
        $ordenSql = "c.nombre ASC";
        break;
    case "nombre_desc":
        $ordenSql = "c.nombre DESC";
        break;
    case "ciudad_asc":
        $ordenSql = "c.ciudad ASC";
        break;
    case "ciudad_desc":
        $ordenSql = "c.ciudad DESC";
        break;
    case "fecha_asc":
        $ordenSql = "c.fecha_creacion ASC, c.id ASC";
        break;
    case "fecha_desc":
    default:
        $ordenSql = "c.fecha_creacion DESC, c.id DESC";
        $orden = "fecha_desc";
        break;
}

// Consulta final
$sql = "SELECT c.id, c.nombre, c.telefono, c.correo, c.ciudad, c.es_favorito, c.fecha_creacion,
               cat.id AS categoria_id, cat.nombre AS categoria_nombre
        FROM contactos c
        LEFT JOIN categorias cat ON c.id_categoria = cat.id
        WHERE " . implode(" AND ", $where) . "
        ORDER BY " . $ordenSql;

$stmt = $conexion->prepare($sql);
$stmt->execute($params);
$contactos = $stmt->fetchAll();

// -------------------------------------------------------------
// ESTADÍSTICAS GENERALES DEL USUARIO
// -------------------------------------------------------------
$stmtTotal = $conexion->prepare("SELECT COUNT(*) FROM contactos WHERE id_usuario = ?");
$stmtTotal->execute([$id_usuario]);
$total_contactos = (int)$stmtTotal->fetchColumn();

$stmtFavs = $conexion->prepare("SELECT COUNT(*) FROM contactos WHERE id_usuario = ? AND es_favorito = 1");
$stmtFavs->execute([$id_usuario]);
$total_favoritos = (int)$stmtFavs->fetchColumn();

// Comprobar si hay filtros activos
$hayFiltrosActivos = ($q !== "" || $categoriaFiltro !== "" || $soloFavoritos || $orden !== "fecha_desc");

// Guardar URL actual para redirección al cambiar favorito
$urlActual = basename($_SERVER['PHP_SELF']) . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '');
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis contactos | AgendaPro</title>
    <link rel="stylesheet" href="../css/estilo.css">
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

        <div class="dashboard-cabecera">
            <div>
                <p class="dashboard-saludo">Panel principal</p>
                <h1>Hola, <?= htmlspecialchars($_SESSION["usuario_nombre"], ENT_QUOTES, "UTF-8") ?></h1>
                <p>
                    <strong>Correo:</strong> <?= htmlspecialchars($_SESSION["usuario_correo"] ?? "", ENT_QUOTES, "UTF-8") ?> &bull; Administra tus contactos con filtros y categorías avanzadas.
                </p>
            </div>

            <a href="crear.php" class="btn btn-principal">
                + Nuevo contacto
            </a>
        </div>

        <!-- Tarjetas de Estadísticas -->
        <section class="estadisticas">
            <div class="estadistica-card">
                <div>
                    <p>Total registrados</p>
                    <strong><?= $total_contactos ?></strong>
                </div>
            </div>

            <div class="estadistica-card estadistica-card-fav">
                <div>
                    <p>Favoritos ⭐</p>
                    <strong><?= $total_favoritos ?></strong>
                </div>
            </div>

            <div class="estadistica-card">
                <div>
                    <p>Mostrados en lista</p>
                    <strong><?= count($contactos) ?></strong>
                </div>
            </div>
        </section>

        <!-- Barra de Pestañas Rápidas (Opción D: Favoritos) -->
        <div class="pestañas-filtro">
            <a href="listar.php" class="pestaña-item <?= !$soloFavoritos ? 'activa' : '' ?>">
                Todos los contactos (<?= $total_contactos ?>)
            </a>
            <a href="listar.php?favoritos=1<?= $q !== '' ? '&q=' . urlencode($q) : '' ?><?= $categoriaFiltro !== '' ? '&categoria=' . urlencode($categoriaFiltro) : '' ?><?= $orden !== 'fecha_desc' ? '&orden=' . urlencode($orden) : '' ?>" class="pestaña-item <?= $soloFavoritos ? 'activa' : '' ?>">
                ⭐ Solo Favoritos (<?= $total_favoritos ?>)
            </a>
        </div>

        <!-- PANEL DE BÚSQUEDA Y FILTROS AVANZADOS (Opciones A, B, C, D) -->
        <section class="panel-filtros">
            <form action="listar.php" method="GET" class="form-filtros">
                <?php if ($soloFavoritos): ?>
                    <input type="hidden" name="favoritos" value="1">
                <?php endif; ?>

                <div class="filtro-campo filtro-busqueda">
                    <label for="q">🔍 Búsqueda:</label>
                    <input 
                        type="text" 
                        name="q" 
                        id="q" 
                        placeholder="Buscar por nombre, correo o ciudad..." 
                        value="<?= htmlspecialchars($q, ENT_QUOTES, "UTF-8") ?>"
                    >
                </div>

                <div class="filtro-campo">
                    <label for="campo">Criterio:</label>
                    <select name="campo" id="campo">
                        <option value="todos" <?= $campo === 'todos' ? 'selected' : '' ?>>Todos los campos</option>
                        <option value="nombre" <?= $campo === 'nombre' ? 'selected' : '' ?>>Solo Nombre</option>
                        <option value="correo" <?= $campo === 'correo' ? 'selected' : '' ?>>Solo Correo</option>
                        <option value="ciudad" <?= $campo === 'ciudad' ? 'selected' : '' ?>>Solo Ciudad</option>
                    </select>
                </div>

                <div class="filtro-campo">
                    <label for="categoria">🏷️ Categoría:</label>
                    <select name="categoria" id="categoria">
                        <option value="">Todas las categorías</option>
                        <?php foreach ($categorias as $cat): ?>
                            <option value="<?= (int)$cat['id'] ?>" <?= ((string)$cat['id'] === $categoriaFiltro) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['nombre'], ENT_QUOTES, "UTF-8") ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="filtro-campo">
                    <label for="orden">⇅ Ordenar por:</label>
                    <select name="orden" id="orden">
                        <option value="fecha_desc" <?= $orden === 'fecha_desc' ? 'selected' : '' ?>>Fecha creación (Recientes)</option>
                        <option value="fecha_asc" <?= $orden === 'fecha_asc' ? 'selected' : '' ?>>Fecha creación (Antiguos)</option>
                        <option value="nombre_asc" <?= $orden === 'nombre_asc' ? 'selected' : '' ?>>Nombre (A → Z)</option>
                        <option value="nombre_desc" <?= $orden === 'nombre_desc' ? 'selected' : '' ?>>Nombre (Z → A)</option>
                        <option value="ciudad_asc" <?= $orden === 'ciudad_asc' ? 'selected' : '' ?>>Ciudad (A → Z)</option>
                        <option value="ciudad_desc" <?= $orden === 'ciudad_desc' ? 'selected' : '' ?>>Ciudad (Z → A)</option>
                    </select>
                </div>

                <div class="filtro-acciones">
                    <button type="submit" class="btn btn-principal btn-filtro">
                        Aplicar filtros
                    </button>
                    <?php if ($hayFiltrosActivos): ?>
                        <a href="listar.php" class="btn btn-secundario btn-limpiar" title="Restablecer todos los filtros">
                            ✕ Limpiar
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </section>

        <!-- SECCIÓN DE RESULTADOS / TABLA DE CONTACTOS -->
        <section class="contactos-seccion">
            <div class="seccion-cabecera">
                <div>
                    <h2>
                        <?= $soloFavoritos ? '⭐ Contactos Favoritos' : 'Mis contactos' ?>
                    </h2>
                    <p>
                        <?php if ($hayFiltrosActivos): ?>
                            Se encontraron <strong><?= count($contactos) ?></strong> resultado(s) según los filtros aplicados.
                        <?php else: ?>
                            Lista completa de contactos registrados en tu agenda.
                        <?php endif; ?>
                    </p>
                </div>
            </div>

            <?php if (empty($contactos)): ?>
                <div class="estado-vacio">
                    <?php if ($hayFiltrosActivos): ?>
                        <h3>No se encontraron resultados</h3>
                        <p>No hay contactos que coincidan con los criterios de búsqueda o filtros seleccionados.</p>
                        <a href="listar.php" class="btn btn-secundario">
                            Restablecer filtros
                        </a>
                    <?php elseif ($soloFavoritos): ?>
                        <h3>No tienes contactos favoritos</h3>
                        <p>Marca contactos con la estrella ⭐ para encontrarlos rápidamente aquí.</p>
                        <a href="listar.php" class="btn btn-secundario">
                            Ver todos los contactos
                        </a>
                    <?php else: ?>
                        <h3>Tu agenda está vacía</h3>
                        <p>Comienza agregando tu primer contacto con categorías y favoritos.</p>
                        <a href="crear.php" class="btn btn-principal">
                            Crear contacto
                        </a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="tabla-contenedor">
                    <table class="tabla-contactos">
                        <thead>
                            <tr>
                                <th style="width: 45px; text-align: center;">Fav</th>
                                <th>Nombre</th>
                                <th>Categoría</th>
                                <th>Teléfono</th>
                                <th>Correo</th>
                                <th>Ciudad</th>
                                <th>Fecha Registro</th>
                                <th style="text-align: center;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($contactos as $contacto): ?>
                                <?php 
                                    $esFav = (int)$contacto["es_favorito"] === 1;
                                    $nombreCategoria = $contacto["categoria_nombre"] ?? "Otro";
                                    $claseCat = "badge-cat-" . strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $nombreCategoria));
                                    $fechaFormato = !empty($contacto["fecha_creacion"]) 
                                        ? date("d/m/Y", strtotime($contacto["fecha_creacion"])) 
                                        : "—";
                                ?>
                                <tr class="<?= $esFav ? 'fila-favorito' : '' ?>">
                                    <!-- Botón Toggle Rápido de Favorito (Opción D) -->
                                    <td style="text-align: center;">
                                        <form action="toggle_favorito.php" method="POST" style="display:inline;">
                                            <input type="hidden" name="id" value="<?= (int)$contacto["id"] ?>">
                                            <input type="hidden" name="retorno" value="<?= htmlspecialchars($urlActual, ENT_QUOTES, "UTF-8") ?>">
                                            <button 
                                                type="submit" 
                                                class="btn-estrella <?= $esFav ? 'activo' : '' ?>" 
                                                title="<?= $esFav ? 'Quitar de favoritos' : 'Marcar como favorito' ?>"
                                                aria-label="<?= $esFav ? 'Quitar de favoritos' : 'Marcar como favorito' ?>"
                                            >
                                                <?= $esFav ? '★' : '☆' ?>
                                            </button>
                                        </form>
                                    </td>

                                    <td>
                                        <strong>
                                            <?= htmlspecialchars($contacto["nombre"], ENT_QUOTES, "UTF-8") ?>
                                        </strong>
                                    </td>

                                    <!-- Badge de Categoría (Opción C) -->
                                    <td>
                                        <span class="badge-categoria <?= $claseCat ?>">
                                            <?= htmlspecialchars($nombreCategoria, ENT_QUOTES, "UTF-8") ?>
                                        </span>
                                    </td>

                                    <td>
                                        <a href="tel:<?= htmlspecialchars($contacto["telefono"], ENT_QUOTES, "UTF-8") ?>" class="enlace-contacto">
                                            <?= htmlspecialchars($contacto["telefono"], ENT_QUOTES, "UTF-8") ?>
                                        </a>
                                    </td>

                                    <td>
                                        <a href="mailto:<?= htmlspecialchars($contacto["correo"], ENT_QUOTES, "UTF-8") ?>" class="enlace-contacto">
                                            <?= htmlspecialchars($contacto["correo"], ENT_QUOTES, "UTF-8") ?>
                                        </a>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($contacto["ciudad"], ENT_QUOTES, "UTF-8") ?>
                                    </td>

                                    <!-- Fecha de creación (Opción B) -->
                                    <td style="font-size: 13px; color: var(--gris-texto);">
                                        <?= htmlspecialchars($fechaFormato, ENT_QUOTES, "UTF-8") ?>
                                    </td>

                                    <td class="acciones" style="text-align: center;">
                                        <a href="editar.php?id=<?= (int)$contacto["id"] ?>" class="accion-editar" title="Editar contacto">
                                            Editar
                                        </a>

                                        <form action="eliminar.php" method="POST"
                                            onsubmit="return confirm('¿Estás seguro de eliminar a <?= htmlspecialchars(addslashes($contacto["nombre"]), ENT_QUOTES, "UTF-8") ?>?');"
                                            style="display:inline;">
                                            <input type="hidden" name="id" value="<?= (int)$contacto["id"] ?>">
                                            <button type="submit" class="accion-eliminar" title="Eliminar contacto">
                                                Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>

    </main>

</body>
</html>
