# WoloxChallenge

API REST Application made with PHP 7 & Symfony 4

### Requisitos
- Tener instalado PHP 7
- Tener instalado composer
- Tener una base de datos local MySql

### Instrucciones de uso
Para lenvantar la aplicación se debe

1. Clonar el proyecto de manera local
2. Editar el archivo .env con los parametros de la base de datos
   - `DATABASE_URL=mysql://root:root@127.0.0.1:3306/WolloxChallenge_db` por ejemplo
3. Abrir la consola en el directorio del proyecto y ejecutar los siguientes comandos:
   - `composer install`
   - `php bin/console doctrine:schema:create`
   - `symfony server:start`

La aplicación se levantará en el puerto 8000 de manera que la ruta inicial será: **localhost:8000**

### Persistencia
El modelo de datos que utiliza la aplicación consta de una única tabla 'User' con 4 columnas (id, name, email, image).

### End Points
##### Metodos GET
- Obtener todos los usuarios
  - `localhost:8080/users`
- Obtener user especifico
  - `localhost:8080/users/<idUser>`
    
##### Metodos POST & PUT
Las siguientes solicitudes necesitan un objeto con el siguiente formato en el body del request para poder ser llevadas a cabo correctamente.

`{"name": "<name>",
  "email": "<email>",
  "image": "<imageUrl>"
}`

- POST Crear un nuevo usuario
    - `localhost:8080/users`
    
- PUT Actualizar un usuario
    - `localhost:8080/users/<idUser>`

### Tests
Para correr los tests se debe

1. Abrir la consola en el directorio del proyecto y ejecutar el siguiente comando:
   - `php bin/phpunit`
   
### Aclaraciones
Al querer dar de alta o editar un usuario se realizan 4 validaciones
- Que el nombre no sea nulo.
- Que el email no sea nulo.
- Que el email sea un email válido (email@dominio.com).
- Que no haya un usuario registrado con el mismo email.
