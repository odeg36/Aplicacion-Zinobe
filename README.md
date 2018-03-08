La aplicación ha sido realizada mediante el uso del framework Symfony en su versión 3 y Sonata Admin para el área administrativa.


La aplicación requiere tener instalado php7.1 y intl de php. Ademas de un motor de base de datos, por ejemplo MySql.


Para poder ejecutar el proyecto, por favor:


1) Crear una base de datos con el nombre que se desee.


2) Ejecutar los siguientes comandos desde terminal.


Es necesario otorgar permisos a las carpetas cache y logs del proyecto. Por favor basarse en este link https://symfony.com/doc/3.4/setup/file_permissions.html según el sistema operativo que se maneje.

$ php composer.phar update

Durante la actualizacion de componentes, se le pedira los datos para poder conectarse a la base de datos: Motor, usuario, contraseña, nombre de la base de datos; Por favor suministrar estos datos. Las demas opciones se pueden dejar de forma predefinida.

$ php bin/console do:sc:up --force

Este comando creara el esquema de base de datos

$ php bin/console assets:install web
$ php bin/console assetic:dump
$ php bin/console bazinga:js-translation:dump

Estos comandos son para el uso de los archivos como css, javascript e imágenes.

$ php bin/console doctrine:fixture:load --append

Este comando creara registros en la base de datos para la correcta utilización de el área administrativa. Con este comando se creara un usuario super administrador con el que podrá autenticarse: 
Usuario: administrador
Contraseña: 1q2w3e4r5t

$ php bin/console server:run

Este comando permite ejecutar el proyecto en el navegador en caso de no contar con un directorio para ejecutar el despliegue mediante apache.

Características del sistema.

El sistema ofrece un dashboard para poder listar y crear usuarios y permisos, y un buscador a mano izquierda para buscar en cualquier modulo administrativo. Además en la lista de usuarios es posible filtrar usuarios por nombre, correo y grupos.

El inicio de la aplicación támbien ofrece un formulario de registro para usuarios no autenticados.

Agradezco la atención brindada.