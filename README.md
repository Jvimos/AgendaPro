# AgendaPro — Agenda Personal de Contactos

Aplicación web desarrollada en PHP y MySQL para la gestión académica y profesional de una agenda personal de contactos, integrando autenticación de usuarios, control de sesiones y aislamiento estricto de información.

---

## Ficha Académica

* **Institución:** UNIVERSIDAD PARTICULAR DE LOJA
* **Carrera / Programa:** Tecnologia de la información 
* **Asignatura / Módulo:** Desarrollo web
* **Estudiante:** Vimos Lema Juan Carlos
* **Docente / Tutora:** TORRES GUARNIZO DIANA ALEXANDRA
* **Fecha de Entrega:** 12/11/2026
* **Repositorio en GitHub:** https://github.com/Jvimos/AgendaPro

---

## Video de Presentación y Sustentación

* **Enlace del Video:** `https://drive.google.com/file/d/1PMJumT2uN_CYyzp9UEWyL71ucNxHI3_P/view?usp=sharing`

---

## Descripción y Objetivo del Proyecto

AgendaPro es un sistema web concebido para que cada usuario registrado disponga de un entorno privado donde registrar, consultar, actualizar y eliminar contactos de forma organizada y segura.

El objetivo principal es aplicar buenas prácticas de desarrollo web backend en PHP con arquitectura cliente-servidor y base de datos relacional MySQL, cumpliendo con los estándares de seguridad requeridos: cifrado unidireccional de contraseñas, gestión segura de sesiones, sentencias preparadas contra inyección SQL y control estricto de autorización para evitar el acceso no autorizado a datos ajenos.

---

## Tecnologías Utilizadas

* **Lenguaje Backend:** PHP (utilizando extensión PDO para persistencia).
* **Base de Datos:** MySQL / MariaDB (motor InnoDB, codificación UTF-8 Multibyte).
* **Servidor Web:** Apache (entorno XAMPP).
* **Frontend:** HTML5 semántico y CSS3 responsivo.

---

## Requisitos del Sistema

* Servidor local con Apache y MySQL (XAMPP recomendado).
* PHP con soporte activo para PDO y las extensiones estándar `pdo_mysql`, `mbstring` y `session`.
* Navegador web moderno (Chrome, Firefox, Edge, etc.).

---

## Instalación Paso a Paso

1. Clonar o descargar el repositorio dentro del directorio de publicación del servidor web:
   * En XAMPP para Windows: `C:\xampp\htdocs\AgendaContactos`
2. Iniciar los módulos de **Apache** y **MySQL** desde el Panel de Control de XAMPP.
3. Importar la base de datos relacional (instrucciones en la siguiente sección).
4. Verificar la configuración de conexión en `config/conexion.php` según las credenciales de su MySQL local.
5. Abrir el navegador e ingresar a la URL:
   ```text
   http://localhost/AgendaContactos/
   ```

---

## Base de Datos e Importación

El proyecto incluye el script SQL completo en la raíz: `agenda_contactos.sql`.

### Pasos para importar:
1. Abrir phpMyAdmin en el navegador: `http://localhost/phpmyadmin/`.
2. Dirigirse a la pestaña **Importar**.
3. Seleccionar el archivo `agenda_contactos.sql` y hacer clic en **Importar** (o ejecutar su contenido en la pestaña **SQL**).

### Estructura relacional:
* **Base de datos:** `agenda_contactos` (juego de caracteres `utf8mb4` con cotejamiento `utf8mb4_unicode_ci`).
* **Tabla `usuarios`:** Almacena la cuenta de acceso (`id`, `nombre`, `correo` único, `password` como hash de 255 caracteres y `fecha_registro`).
* **Tabla `contactos`:** Almacena los contactos registrados (`id`, `id_usuario`, `nombre`, `telefono`, `correo`, `ciudad`).
* **Integridad referencial:** La tabla `contactos` implementa la restricción de clave foránea `fk_contactos_usuario` vinculada a `usuarios(id)` con reglas `ON DELETE CASCADE` y `ON UPDATE CASCADE`.

---

## Configuración de MySQL y Credenciales

La conexión se gestiona mediante PDO en el archivo `config/conexion.php`.

* **Instalación estándar de XAMPP:**
  El usuario por defecto de MySQL es `root` sin contraseña (`$password = "";`).
* **Instalación con contraseña personalizada:**
  Si su servidor local de MySQL utiliza contraseña, edite la variable en `config/conexion.php`:
  ```php
  $host = "localhost";
  $db = "agenda_contactos";
  $usuario = "root";
  $password = "su_contraseña_aqui";
  ```
* **Plantilla de referencia:**
  Se proporciona el archivo `config/conexion.example.php` como plantilla segura sin credenciales reales para entornos de prueba o despliegue.

---

## Estructura del Proyecto

```text
AgendaContactos/
│
├── auth/                       # Módulo de autenticación y control de acceso
│   ├── autenticar.php          # Validación de credenciales y apertura de sesión
│   ├── login.php               # Formulario de inicio de sesión
│   ├── logout.php             # Cierre y destrucción de sesión
│   ├── registrar.php           # Procesamiento de registro y hashing de contraseña
│   └── registro.php            # Formulario de creación de cuenta
│
├── config/                     # Configuración de base de datos
│   ├── conexion.php            # Conexión activa a MySQL vía PDO
│   └── conexion.example.php    # Plantilla de configuración de referencia
│
├── contactos/                  # Módulo CRUD de contactos (rutas privadas)
│   ├── actualizar.php          # Procesamiento de actualización de contacto
│   ├── crear.php               # Formulario de registro de nuevo contacto
│   ├── editar.php              # Formulario de edición (con validación de pertenencia)
│   ├── eliminar.php            # Procesamiento de eliminación vía POST
│   ├── guardar.php             # Procesamiento y almacenamiento de nuevo contacto
│   └── listar.php              # Panel principal y consulta de contactos del usuario
│
├── css/                        # Estilos visuales
│   └── estilo.css              # Hoja de estilos CSS responsiva del sistema
│
├── includes/                   # Componentes de soporte
│   ├── mensajes.php            # Funciones para mensajes flash de sesión (éxito/error)
│   ├── header.php              # Archivo base reservado para modularización futura
│   ├── navbar.php              # Archivo base reservado para modularización futura
│   └── footer.php              # Archivo base reservado para modularización futura
│
├── js/                         # Scripts de cliente
│   └── app.js                  # Archivo base reservado para scripts futuros
│
├── .gitignore                  # Exclusiones de control de versiones
├── agenda_contactos.sql        # Script de creación de base de datos y tablas
├── index.php                   # Página pública de bienvenida / redirección
└── README.md                   # Documentación académica y técnica del proyecto
```

*(Nota: Los archivos `header.php`, `navbar.php`, `footer.php` y `app.js` se conservan en la estructura como elementos base previstos para futuras ampliaciones de modularización).*

---

## Funcionamiento del Sistema

### 1. Autenticación y Cuentas
* **Registro (`auth/registro.php`):** Permite registrar nuevos usuarios solicitando nombre, correo electrónico y contraseña con confirmación. Valida que el correo no esté registrado previamente.
* **Inicio de Sesión (`auth/login.php`):** Autentica al usuario contra la base de datos y crea la sesión en el servidor. Si el usuario ya está autenticado, el sistema lo redirige directamente a la lista de contactos.
* **Cierre de Sesión (`auth/logout.php`):** Cierra la sesión activa, destruye las cookies asociadas y redirige a la pantalla de inicio de sesión con un mensaje informativo.

### 2. Gestión de Contactos (CRUD)
* **Crear (C):** `contactos/crear.php` y `contactos/guardar.php` permiten registrar un nuevo contacto asociándolo obligatoriamente al identificador del usuario en sesión (`id_usuario`).
* **Consultar / Listar (R):** `contactos/listar.php` presenta la lista exclusiva de contactos del usuario autenticado, indicando el total registrado o un estado informativo si la agenda está vacía.
* **Actualizar (U):** `contactos/editar.php` y `contactos/actualizar.php` cargan y modifican los datos de un contacto, validando previamente que pertenezca al usuario en sesión.
* **Eliminar (D):** `contactos/eliminar.php` suprime el contacto mediante una solicitud `POST` tras confirmación del usuario, verificando la autorización de propiedad.

---

## Seguridad Implementada

El proyecto incorpora los mecanismos de seguridad requeridos por la rúbrica de evaluación:

1. **Protección de Contraseñas:**
   * Las contraseñas se procesan mediante `password_hash($password, PASSWORD_DEFAULT)`, generando un hash seguro mediante el algoritmo estándar Bcrypt.
   * La verificación en el inicio de sesión se realiza con `password_verify()`. En ningún momento se almacenan ni comparan contraseñas en texto plano.
2. **Gestión Segura de Sesiones:**
   * Control de sesiones con `session_start()`.
   * Protección contra fijación de sesión mediante `session_regenerate_id(true)` tras una autenticación exitosa.
   * Cierre seguro de sesión en `logout.php`, limpiando el arreglo `$_SESSION`, destruyendo la cookie de sesión en el cliente y ejecutando `session_destroy()`.
3. **Protección de Páginas Privadas:**
   * Todas las páginas y controladores del módulo `contactos/` verifican la existencia de `$_SESSION["usuario_id"]`. Si no hay sesión activa, redirigen de inmediato al login.
4. **Aislamiento de Contactos y Protección Anti-IDOR (Insecure Direct Object References):**
   * Todas las operaciones de lectura, actualización y eliminación filtran forzosamente por `id_usuario = ?`.
   * Si un usuario intenta editar o eliminar un contacto ajeno o inexistente manipulando el parámetro `id` en la URL o en el formulario, el sistema deniega la acción, emite un mensaje de error y redirige a la lista de contactos.
5. **Prevención de Inyección SQL:**
   * 100% de las consultas interactivas se ejecutan mediante sentencias preparadas de PDO con parámetros vinculados (`prepare()` y `execute()`).
6. **Prevención de XSS (Cross-Site Scripting):**
   * Toda la información dinámica suministrada por el usuario se escapa antes de imprimirse en las vistas utilizando `htmlspecialchars($variable, ENT_QUOTES, "UTF-8")`.

---

## Guía de Pruebas Recomendadas para la Evaluación

1. **Prueba de Registro e Inicio de Sesión:**
   * Crear una cuenta con nombre, correo y contraseña.
   * Iniciar sesión y comprobar la bienvenida personalizada en el panel.
2. **Prueba de Operaciones CRUD:**
   * Registrar un nuevo contacto y verificar que aparezca en el listado.
   * Editar los datos del contacto creado y guardar los cambios.
   * Eliminar el contacto mediante el botón de eliminación y confirmar la alerta.
3. **Prueba de Protección de Rutas Privadas:**
   * Cerrar sesión y tratar de acceder directamente en la barra del navegador a:
     `http://localhost/AgendaContactos/contactos/listar.php`
   * Confirmar que el sistema bloquea el acceso y redirige a `login.php`.
4. **Prueba de Aislamiento de Información entre Usuarios:**
   * Registrar dos cuentas con correos distintos (ej. Usuario A y Usuario B).
   * Con el Usuario A, crear dos contactos.
   * Cerrar sesión e ingresar con el Usuario B: verificar que la agenda del Usuario B aparezca vacía y no muestre los contactos del Usuario A.
5. **Prueba de Seguridad por Manipulación de URL (Anti-IDOR):**
   * Con la sesión del Usuario B abierta, intentar ingresar a la URL de edición con el ID de un contacto del Usuario A (por ejemplo: `contactos/editar.php?id=1`).
   * Verificar que el sistema rechace el acceso con el mensaje: *"El contacto no existe o no tienes permiso para acceder"* y redirija al listado.
