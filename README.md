# CRUD_biblioteca
SD1 PROGRAMACION 2 M2

## Instrucciones de Instalación para Desarrollo

Este proyecto utiliza Docker para la base de datos y Laravel para el backend. Sigue estos pasos para lanzar la aplicación en tu entorno local.

### Prerrequisitos

-   Docker y Docker Compose
-   PHP (versión requerida por el proyecto, revisa `composer.json`)
-   Composer
-   Node.js y npm

### 1. Clonar el Repositorio

```bash
git clone <URL_DEL_REPOSITORIO>
cd CRUD_biblioteca
```

### 2. Levantar la Base de Datos

El proyecto utiliza PostgreSQL en un contenedor de Docker.

```bash
docker-compose up -d
```

Esto iniciará un servicio de base de datos PostgreSQL.

### 3. Configurar el Entorno de Laravel

Navega al directorio `src` y configura tu archivo de entorno.

```bash
cd src
cp .env.example .env
```

Ahora, abre el archivo `.env` y actualiza las variables de la base de datos para que coincidan con la configuración de `docker-compose.yaml`:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=biblioteca
DB_USERNAME=laravel
DB_PASSWORD=pg.laravel.biblioteca
```

### 4. Instalar Dependencias

Instala las dependencias de PHP con Composer:

```bash
composer install
```

### 5. Generar la Clave de la Aplicación

```bash
php artisan key:generate
```

### 6. Ejecutar las Migraciones y Seeders

Para crear la estructura de la base de datos y llenarla con datos iniciales:

```bash
php artisan migrate --seed
```

### 7. Instalar Dependencias de Frontend

```bash
npm install
```

### 8. Compilar los Assets

```bash
npm run dev
```

### 9. Iniciar el Servidor de Desarrollo

Finalmente, inicia el servidor de desarrollo de Laravel:

```bash
php artisan serve
```

La aplicación estará disponible en `http://localhost:8000`.
