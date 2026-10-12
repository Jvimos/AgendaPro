
# AgendaPro — Agenda Personal de Contactos

AgendaPro es una aplicación web desarrollada en PHP y MySQL que permite a cada usuario administrar su agenda personal de contactos mediante un sistema de autenticación.

## Características

- Registro de usuarios.
- Inicio y cierre de sesión.
- Contraseñas protegidas con `password_hash()` y verificadas con `password_verify()`.
- Sesiones para restringir el acceso a las páginas privadas.
- Registro, consulta, edición y eliminación de contactos.
- Separación de los contactos por usuario.
- Consultas preparadas mediante PDO.
- Validación de datos en el servidor.
- Interfaz adaptable a diferentes tamaños de pantalla.

## Tecnologías

- PHP
- MySQL
- HTML5
- CSS3
- JavaScript
- PDO

## Requisitos

- XAMPP con Apache y MySQL.
- PHP compatible con el código del proyecto.
- Navegador web.
- Editor de código, como Visual Studio Code (opcional).

## Instalación

1. Instalar e iniciar Apache y MySQL desde XAMPP.
2. Copiar la carpeta `AgendaContactos` dentro de `htdocs`.
3. Abrir phpMyAdmin en `http://localhost/phpmyadmin`.
4. Importar el archivo `agenda_contactos.sql`.
5. Revisar la configuración de conexión en `config/conexion.php`.
6. Abrir `http://localhost/AgendaContactos/` en el navegador.
7. Crear una cuenta e iniciar sesión.

## Base de datos

La base de datos se llama `agenda_contactos` y contiene:

- `usuarios`: almacena los datos de las cuentas.
- `contactos`: almacena los contactos vinculados a cada usuario mediante `id_usuario`.

La contraseña de cada usuario se almacena como un hash, no como texto plano.

## Estructura del proyecto

- `auth/`: registro, inicio y cierre de sesión.
- `config/`: conexión a la base de datos.
- `contactos/`: operaciones de gestión de contactos.
- `css/`: estilos de la interfaz.
- `js/`: scripts JavaScript.
- `includes/`: funciones compartidas y mensajes.
- `index.php`: página principal.
- `agenda_contactos.sql`: estructura de la base de datos.

## Seguridad

La aplicación utiliza sesiones, consultas preparadas, validación de datos y verificación de la propiedad de los contactos para impedir que un usuario gestione los registros de otro.

## Autor

Vimos Lema Juan Carlos 

## Proyecto académico

Desarrollo de una Agenda Personal de Contactos con Autenticación en PHP.
