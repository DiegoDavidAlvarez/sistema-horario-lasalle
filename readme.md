# Guía de Instalación del Proyecto (Laravel)

Este documento detalla los pasos necesarios para clonar y configurar el proyecto en un entorno local utilizando **XAMPP**.

-----

## Requisitos Previos

Antes de empezar, asegúrate de tener instalado:

  * [XAMPP](https://www.apachefriends.org/) (PHP 8.x)
  * [Composer](https://getcomposer.org/)
  * [Node.js & NPM](https://nodejs.org/)

-----

## Pasos para la instalación

### 1\. Clonar el repositorio

Abre tu terminal y dirígete a la carpeta `htdocs` de tu instalación de XAMPP para clonar el proyecto:

```bash
cd C:/xampp/htdocs
git clone https://github.com/tu-usuario/nombre-del-proyecto.git
cd nombre-del-proyecto
```

### 2\. Instalar dependencias de PHP

Ejecuta el siguiente comando para instalar todas las librerías de Laravel:

```bash
composer install
```

### 3\. Configurar el archivo de entorno

Debes crear el archivo `.env` a partir de la plantilla de ejemplo:

  * **En Windows (CMD):** `copy .env.example .env`
  * **En terminal (Bash):** `cp .env.example .env`

> **Nota:** Abre el archivo `.env` y asegúrate de configurar el nombre de tu base de datos en la línea `DB_DATABASE=tu_base_de_datos`.

### 4\. Generar la clave de aplicación

Este paso es obligatorio para que Laravel funcione correctamente:

```bash
php artisan key:generate
```

### 5\. Migraciones y Base de Datos

Asegúrate de tener **MySQL** activo en tu panel de XAMPP y de haber creado la base de datos en PHPMyAdmin. Luego ejecuta:

```bash
php artisan migrate:fresh --seed
```

### 6\. Configuración del Frontend

Instala las dependencias de JavaScript y compila los archivos necesarios:

```bash
npm install
npm run dev
```

-----

## Ejecución del proyecto

Para visualizar la página web, mantén ejecutando el comando anterior (`npm run dev`) y en una **nueva terminal** inicia el servidor de Laravel:

```bash
php artisan serve
```

Ahora puedes entrar a: **[http://127.0.0.1:8000](https://www.google.com/search?q=http://127.0.0.1:8000)**