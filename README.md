# Inicio de la aplicación 

Ejecutar el comando `composer install` desde la carpeta raiz del proyecto, para la instalación de todas las librerias necesarias.

## Ejecución de la aplicación 

### Configurar fichero .env

Hay un ficheo de ejemplo llamado `.env.example`, crear un nuevo fichero llamado `.env` con el contenido del fichero mencionado anteriormente. Configura donde tendras tu BBDD.

### Creación de las tablas

Una vez se haya configurado el fichero `.env` y tengamos creada la base de datos con el nombre indicado en el fichero `.env`. Iremos a la terminal exactamente a la ruta raiz de nuestro proyecto y ejecutaremos el comando `php artisan migrate` y se me crearán las tablas en la BBDD.

### Arrancar el servidor

Si se ha realizado todos los pasos anteriores está listo para funcionar, de nuevo abrimos la terminal y nos vamos a la carpeta raiz de nuestro proyecto y ejecutaremos el comando `php artisan serve`. Se arrancara el servidor y estará listo para recibir peticiones.

### Más

Si se quiere conecer los endpoints del servidor basta con escribir en la terminal en la ruta raiz de nuestro proyecto el comando `php artisan route:list`.

### Sobre la aplicación

Realizada por: `Fernando Márquez Rodríguez`\
Correo: `fernandomarquezrodriguez01@gmail.com`\
Github: `nandimarqz`
