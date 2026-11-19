# AgendaPro — Agenda Personal de Contactos (Versión Mejorada y Despliegue)

Aplicación web desarrollada en PHP y MySQL para la gestión académica y profesional de una agenda personal de contactos, integrando autenticación de usuarios, control de sesiones, aislamiento estricto de información, y ahora con **mejoras avanzadas de búsqueda, ordenamiento, categorías relacionales, contactos favoritos y despliegue en hosting gratuito**.

---

## Ficha Académica

* **Institución:** UNIVERSIDAD TÉCNICA PARTICULAR DE LOJA (UTPL)
* **Carrera / Programa:** Tecnologías de la Información
* **Asignatura / Módulo:** Desarrollo Web / Programación Web
* **Actividad:** [APEB1-30%] Actividad: Despliegue y Mejoras de una Aplicación Web en un Hosting Gratuito
* **Estudiante:** Vimos Lema Juan Carlos
* **Docente / Tutora:** TORRES GUARNIZO DIANA ALEXANDRA
* **Repositorio en GitHub:** https://github.com/Jvimos/AgendaPro

---

## Enlaces de Acceso y Entrega

> 🌐 **URL Pública del Sistema en Línea (Hosting Gratuito):**  
> `http://vimsjordi.free.nf/AgendaContactos/`

> 🐙 **Repositorio en GitHub:**  
> `https://github.com/Jvimos/AgendaPro`

> 💾 **Script SQL Actualizado:**  
> Ubicado en la raíz del proyecto: [`agenda_contactos.sql`](./agenda_contactos.sql)

> 📹 **Video de Presentación y Sustentación:**  
> `https://drive.google.com/file/d/1PMJumT2uN_CYyzp9UEWyL71ucNxHI3_P/view?usp=sharing`

---

## Fundamentación Teórica (Resultado de Aprendizaje)

### Diferencia entre Programación Orientada a Eventos y Programación de Línea de Comandos

| Criterio | Programación de Línea de Comandos (CLI) | Programación Orientada a Eventos (Web / GUI) |
| :--- | :--- | :--- |
| **Flujo de Ejecución** | **Secuencial y síncrono:** El programa sigue un orden predeterminado de principio a fin, ejecutando instrucción por instrucción. | **Asíncrono y reactivo:** El flujo no es predecible; la aplicación permanece a la espera de acciones o sucesos (eventos) generados por el usuario o el sistema. |
| **Interacción con el Usuario** | El usuario responde a indicaciones estrictas (`prompts`), ingresando datos por texto en la consola cuando el flujo del programa se detiene para pedirlo. | El usuario interactúa libremente con múltiples controles gráficos (botones, campos de búsqueda, selectores, estrellas de favoritos, formularios). |
| **Punto de Control** | El programa tiene el control absoluto de qué paso sigue y cuándo se solicitan los datos. | El usuario tiene el control: decide cuándo buscar, cuándo ordenar, cuándo marcar un favorito o cuándo editar un contacto. |
| **Manejo de Respuestas** | Funciones bloqueantes de entrada/salida (`fgets()`, `input()`, `cin`). | Disparadores (`Event Handlers`) y solicitudes HTTP (`GET`, `POST`) que procesan eventos como `onsubmit`, `onclick`, `onchange`, despachando respuestas dinámicas en el servidor. |

**Aplicación en este proyecto:**  
AgendaPro implementa un modelo **orientado a eventos web**. Cada interacción del usuario —pulsar la estrella para alternar favoritos, enviar un término de búsqueda, cambiar el criterio de ordenamiento, seleccionar una categoría o enviar un formulario— genera un evento HTTP (peticiones `GET` o `POST`). El backend en PHP actúa como despachador de eventos, procesando la solicitud mediante controladores específicos (`listar.php`, `guardar.php`, `actualizar.php`, `toggle_favorito.php`, `eliminar.php`), interactuando con la base de datos y devolviendo una vista actualizada en tiempo real con mensajes de retroalimentación inmediata.

---

## Mejoras Implementadas

En esta entrega se incorporaron **las cuatro mejoras propuestas** para alcanzar la máxima calificación (Excelente - 2.5 pts):

### 1. Opción A: Búsqueda Avanzada de Contactos
* Permite buscar contactos en tiempo real por:
  * **Nombre**
  * **Correo electrónico**
  * **Ciudad**
* Incorpora un selector de criterio que permite realizar:
  * **Búsqueda integral (Todos los campos):** Busca coincidencias simultáneamente en nombre, correo o ciudad.
  * **Búsqueda específica:** Filtra únicamente por el campo seleccionado (solo nombre, solo correo o solo ciudad).
* Mantiene los valores de búsqueda en el formulario para comodidad del usuario y ofrece un botón rápido de **"✕ Limpiar"** para restablecer la vista.

### 2. Opción B: Ordenamiento Dinámico Multicriterio
* Se incorporó el campo `fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP` en la tabla `contactos`.
* Permite ordenar la lista de contactos bajo los siguientes criterios:
  * **Fecha de creación (Más recientes primero)** — *Criterio predeterminado*
  * **Fecha de creación (Más antiguos primero)**
  * **Nombre (A → Z)**
  * **Nombre (Z → A)**
  * **Ciudad (A → Z)**
  * **Ciudad (Z → A)**
* Implementado con validación estricta por lista blanca (`whitelist`) en PHP para garantizar protección absoluta contra inyecciones SQL en cláusulas `ORDER BY`.

### 3. Opción C: Categorías Relacionales
* Se creó la tabla relacional `categorias` con los registros obligatorios:
  1. `Familia`
  2. `Amigos`
  3. `Trabajo`
  4. `Universidad`
  5. `Otro`
* **Integridad Referencial:** Cada contacto está vinculado a una categoría mediante una clave foránea (`fk_contactos_categoria`) con `ON DELETE RESTRICT ON UPDATE CASCADE`.
* **Visualización:** En la tabla se muestra un *Badge* estilizado con un color distintivo para cada categoría (violeta, verde, naranja, azul y gris).
* **Filtro por Categoría:** En el panel de control se incluye un selector para ver contactos de una categoría específica o de todas las categorías.
* **Gestión en Formularios:** Tanto en `crear.php` como en `editar.php`, se incluye un campo desplegable `<select>` cargado dinámicamente desde la tabla `categorias`.

### 4. Opción D: Contactos Favoritos
* Se agregó la columna `es_favorito TINYINT(1) NOT NULL DEFAULT 0` en la tabla `contactos`.
* **Alternancia Rápida (Toggle):** Cada fila de la tabla cuenta con un botón interactivo de estrella (★ dorado si es favorito, ☆ gris si no lo es). Con un solo clic, se ejecuta una petición `POST` al controlador `toggle_favorito.php` alternando su estado al instante y regresando a la vista actual sin perder los filtros activos.
* **Pestaña de Acceso Rápido:** Encabezado con pestañas para alternar entre **"Todos los contactos"** y **"⭐ Solo Favoritos"**.
* **Gestión en Formularios:** Casilla de verificación (*checkbox*) en las pantallas de creación y edición para marcar o desmarcar un contacto como favorito.

---

## Tecnologías Utilizadas

* **Backend:** PHP 8.x (Extensiones: `PDO`, `pdo_mysql`, `mbstring`, `session`).
* **Base de Datos:** MySQL / MariaDB (Motor InnoDB, codificación `utf8mb4` y cotejamiento `utf8mb4_unicode_ci`).
* **Frontend:** HTML5 semántico, CSS3 moderno con variables CSS, diseño adaptativo (Grid & Flexbox).
* **Servidor Web:** Apache (entorno local con XAMPP y servidores en la nube en hosting gratuito).
* **Seguridad:** Hashing Bcrypt (`password_hash`), Sentencias preparadas (`PDO::prepare`), Sanitización contextual (`htmlspecialchars`), Protección Anti-CSRF/Anti-IDOR y regeneración de ID de sesión.

---

## Guía de Despliegue en un Hosting Gratuito (Paso a Paso)

Para cumplir con la **Parte 2** de la actividad, utilice **InfinityFree**. A continuación, se detallan los pasos para InfinityFree:

### Paso 1: Crear la cuenta en el Hosting
1. Ingresar a [InfinityFree](https://www.infinityfree.com/) y registrarse con un correo electrónico.
2. En el panel de control, hacer clic en **"Create Account"**.
3. Elegir un subdominio gratuito (por ejemplo: `agendapro-juanvimos.infinityfreeapp.com`).
4. Completar el proceso para activar la cuenta de hosting.

### Paso 2: Crear la Base de Datos MySQL
1. En el panel de control de la cuenta creada en InfinityFree, entrar a **"MySQL Databases"**.
2. Crear una nueva base de datos (por ejemplo: `agenda_contactos`).
3. Anotar los datos de conexión provistos:
   * **MySQL Hostname:** (Ejemplo: `sql305.infinityfree.com`)
   * **MySQL Database Name:** (Ejemplo: `if0_12345678_agenda_contactos`)
   * **MySQL Username:** (Ejemplo: `if0_12345678`)
   * **MySQL Password:** (La contraseña de tu cuenta de InfinityFree)

### Paso 3: Importar el Script SQL en phpMyAdmin
1. Hacer clic en el botón **"phpMyAdmin"** junto a la base de datos recién creada.
2. Seleccionar la base de datos en el panel izquierdo.
3. Ir a la pestaña **"Importar"** (o pestaña **"SQL"**).
4. Seleccionar el archivo `agenda_contactos.sql` del proyecto y presionar **"Continuar"** para ejecutarlo.

### Paso 4: Configurar `config/conexion.php` para el Hosting
En tu archivo `config/conexion.php`, configura las variables con los datos del hosting:

```php
// Configuración para el Hosting Gratuito (InfinityFree - vimsjordi.free.nf):
$host = "sql213.infinityfree.com";             // Host provisto por InfinityFree
$db = "if0_43468314_agenda_contactos";         // Nombre completo de la BD
$usuario = "if0_43468314";                     // Usuario de la BD
$password = "tu_password_del_hosting";         // Contraseña de la cuenta
$charset = "utf8mb4";
```

### Paso 5: Subir los Archivos al Servidor
1. Entrar al **Online File Manager** (o conectarse por FTP mediante FileZilla).
2. Abrir la carpeta raíz pública: `htdocs/`.
3. Subir todos los archivos y carpetas del proyecto:
   * `auth/`
   * `config/` (con las credenciales del hosting en `conexion.php`)
   * `contactos/`
   * `css/`
   * `includes/`
   * `index.php`
4. ¡Listo! Abrir la URL pública en el navegador y verificar el funcionamiento completo.

---

## Instrucciones para Instalar el Proyecto Localmente (XAMPP)

1. **Copiar el proyecto:**
   Clonar o copiar la carpeta `AgendaContactos` en el directorio de Apache:
   ```text
   C:\xampp\htdocs\AgendaContactos
   ```
2. **Iniciar servicios:**
   Abrir el Panel de Control de XAMPP y presionar **Start** en **Apache** y **MySQL**.
3. **Importar la Base de Datos:**
   * Abrir `http://localhost/phpmyadmin/`.
   * Crear una base de datos llamada `agenda_contactos` (cotejamiento `utf8mb4_unicode_ci`).
   * Ir a la pestaña **Importar**, seleccionar `agenda_contactos.sql` y ejecutar.
4. **Verificar conexión:**
   Asegurarse de que `config/conexion.php` tenga las credenciales locales:
   ```php
   $host = "localhost";
   $db = "agenda_contactos";
   $usuario = "root";
   $password = "";
   ```
5. **Ejecutar la aplicación:**
   Abrir el navegador web e ingresar a:
   ```text
   http://localhost/AgendaContactos/
   ```

---

## Estructura del Directorio del Proyecto

```text
AgendaContactos/
│
├── auth/                       # Módulo de autenticación de usuarios
│   ├── autenticar.php          # Verificación de credenciales con password_verify
│   ├── login.php               # Formulario de inicio de sesión
│   ├── logout.php              # Cierre y destrucción segura de sesión
│   ├── registrar.php           # Creación de usuarios con hash Bcrypt
│   └── registro.php            # Formulario de registro de nuevas cuentas
│
├── config/                     # Configuración de base de datos
│   ├── conexion.php            # Conexión PDO activa (Local / Hosting)
│   └── conexion.example.php    # Plantilla de ejemplo
│
├── contactos/                  # Módulo de gestión de contactos (Rutas protegidas)
│   ├── actualizar.php          # Procesamiento UPDATE (con categorías y favoritos)
│   ├── crear.php               # Formulario nuevo contacto (selector de categoría y favorito)
│   ├── editar.php              # Formulario edición (con datos y estado actual)
│   ├── eliminar.php            # Eliminación segura vía POST
│   ├── guardar.php             # Procesamiento INSERT (con validaciones de categoría)
│   ├── listar.php              # Panel principal con Búsqueda, Orden, Categorías y Favoritos
│   └── toggle_favorito.php     # Controlador de alternancia rápida de Favorito (⭐)
│
├── css/                        # Hojas de estilo
│   └── estilo.css              # CSS responsivo con badges, filtros y diseño moderno
│
├── includes/                   # Componentes auxiliares
│   ├── mensajes.php            # Mensajes Flash de sesión (éxito/error)
│   ├── header.php              # Plantilla base
│   ├── navbar.php              # Barra de navegación
│   └── footer.php              # Pie de página
│
├── js/
│   └── app.js                  # Scripts adicionales de cliente
│
├── .gitignore                  # Exclusiones de Git
├── agenda_contactos.sql        # Script SQL completo (BD, tablas, categorías y relaciones)
├── index.php                   # Punto de entrada / redirección inicial
└── README.md                   # Documentación oficial del proyecto y entrega
```

---

## Capturas de Pantalla del Sistema

### 1. Panel Principal con Búsqueda, Filtros y Badges de Categoría
*Vista de la lista de contactos mostrando la barra de búsqueda por nombre, correo y ciudad, selector de ordenamiento, badges de colores por categoría y contador estadístico.*

<img width="1376" height="856" alt="image" src="https://github.com/user-attachments/assets/2beb662d-913c-49ca-87f7-24688b4ca965" />


### 2. Filtro de Contactos Favoritos (Opción D)
*Vista filtrada con la pestaña "Solo Favoritos", mostrando las estrellas activas y el aislamiento de datos.*

<img width="1550" height="873" alt="image" src="https://github.com/user-attachments/assets/91113e6b-5822-4f86-9e06-fad748192164" />


### 3. Formulario de Creación con Categorías y Favoritos (Opciones C y D)
*Formulario con el menú desplegable de categorías (Familia, Amigos, Trabajo, Universidad, Otro) y el checkbox de favorito.*

<img width="928" height="904" alt="image" src="https://github.com/user-attachments/assets/90ad6853-fecf-4ee9-90e6-9d608a53e739" />


### 4. Despliegue en Hosting Gratuito
*Captura de pantalla de la aplicación funcionando con una URL pública en el navegador.*

<img width="1738" height="931" alt="image" src="https://github.com/user-attachments/assets/0816b111-7de8-4588-a4a9-5bab9090c6a6" />


## Verificación de Seguridad y Buenas Prácticas

1. **Aislamiento Multiusuario:** Ningún usuario puede ver, editar ni eliminar contactos pertenecientes a otro usuario (`WHERE id_usuario = ?`).
2. **Consultas Preparadas (PDO):** Cero vulnerabilidades de inyección SQL mediante el uso sistemático de `prepare()` y `execute()`.
3. **Protección XSS:** Escapado riguroso de salidas mediante `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')`.
4. **Contraseñas Seguras:** Algoritmo nativo Bcrypt con factor de costo adaptable.
5. **Sesiones Seguras:** Control riguroso de sesión y protección contra fijación mediante `session_regenerate_id(true)`.

---

## Entregables de la Actividad

1. **URL del sistema funcionando:** `http://vimsjordi.free.nf/AgendaContactos/`
2. **Repositorio en GitHub:** `https://github.com/Jvimos/AgendaPro`
3. **Script SQL actualizado:** [`agenda_contactos.sql`](./agenda_contactos.sql)
