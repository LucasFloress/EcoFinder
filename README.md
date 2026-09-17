# EcoFinder - Plataforma de Economía Circular y Gestión de Reciclaje

EcoFinder es una plataforma web integral orientada a la articulación eficiente entre generadores de residuos reciclables (vecinos, comercios e instituciones) y recuperadores urbanos o cooperativas. El sistema optimiza el circuito de recolección selectiva mediante un esquema transaccional de correspondencia entre oferta y demanda, asegurando trazabilidad, control de inventarios de materiales en tiempo real y consistencia operativa.

Desarrollado como proyecto integrador final para la carrera de **Técnico Universitario en Programación** de la **Universidad Tecnológica Nacional (UTN - Facultad Regional Haedo)**.

---

## 🎯 Problema y Solución

* **Problemática:** Desconexión geográfica y logística entre los actores que generan materiales reutilizables y quienes los recolectan, generando intermediación ineficiente, acumulación desordenada y falta de métricas sobre volúmenes recuperados.
* **Solución Implementada:** Una aplicación web transaccional que centraliza la publicación de lotes reciclables, administra las solicitudes de retiro con validaciones de estado en tiempo real y permite clasificar materiales por peso, tipo y disponibilidad horaria.

---

## 🚀 Características y Módulos Principales

### 1. Gestión Transaccional de Oferta y Demanda
* Flujo automatizado de publicación, reserva y confirmación de retiros de materiales reciclables.
* Control de estados de cada solicitud (Pendiente, Asignada, En Tránsito, Entregada, Cancelada) con validación de reglas de negocio.

### 2. Inventario y Clasificación de Materiales en Tiempo Real
* Administración categorizada de recursos (plásticos, metales, cartón/papel, vidrio, electrónicos).
* Actualización atómica de stock y volúmenes disponibles para evitar inconsistencias por pedidos concurrentes.

### 3. Roles, Seguridad y Perfiles Diferenciados
* Sistema de autenticación con control de acceso basado en roles:
  * **Generador:** Publicación de puntos de retiro, volumen estimado y disponibilidad.
  * **Recuperador / Cooperativa:** Visualización de ofertas territoriales, reclamo de lotes y confirmación de recepción.
  * **Administrador:** Métricas operativas, control de usuarios y auditoría de la plataforma.

### 4. Arquitectura y Código Limpio
* Separación de responsabilidades bajo el patrón **Model-View-Controller (MVC)** de Laravel.
* Implementación de migraciones, seeders estructurados, request validations y consultas relacionales optimizadas.

---

## 🛠️ Stack Tecnológico

* **Backend:** PHP 8.x con Laravel Framework
* **Base de Datos:** MySQL (Diseño relacional en 3FN, transacciones ACID e índices)
* **Frontend:** Blade Templating Engine, Tailwind CSS, Vite, JavaScript (ES6+)
* **Entorno de Desarrollo y Control:** Git, Composer, NPM
* **Testing:** PHPUnit / Laravel Test Suite

---

## 🏛️ Modelo de Datos (Entidades Centrales)

* `users`: Registro general con perfiles diferenciados y credenciales seguras.
* `categories`: Tipología de materiales reciclables (plástico, papel, cartón, metales, etc.).
* `posts_materiales`: Publicaciones de lotes generados con detalle de peso estimado, fotos y ubicación.
* `recolecciones_transacciones`: Asignación del retiro, vinculación generador-recuperador y seguimiento de estados.
* `puntos_entrega`: Registros de ubicaciones de acopio o coordenadas de retiro en territorio.

---

## 📋 Requisitos Previos

Asegúrate de contar con el siguiente entorno instalado localmente:

* PHP >= 8.1
* Composer
* Node.js & NPM
* Servidor MySQL / MariaDB (o entorno local como XAMPP, Laragon o Docker)

---

## ⚙️ Instalación y Puesta en Marcha

1. Clonar el repositorio:
git clone https://github.com/LucasFloress/ecofinder.git
cd ecofinder

2. Instalar dependencias del backend:
composer install

3. Instalar dependencias del frontend:
npm install

4. Configurar el entorno:
cp .env.example .env
php artisan key:generate

5. Configurar la base de datos:
Abrir el archivo .env y establecer los parámetros de conexión de MySQL:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecofinder_db
DB_USERNAME=root
DB_PASSWORD=

6. Ejecutar migraciones y datos de prueba:
php artisan migrate --seed

7. Compilar recursos de interfaz y levantar servidores:
En una terminal:
npm run dev

En otra terminal:
php artisan serve

La aplicación quedará accesible en http://127.0.0.1:8000.

---

## 👨‍💻 Autor

Lucas Ezequiel Flores  
* Técnico Universitario en Programación – Universidad Tecnológica Nacional (UTN FRH)  
* LinkedIn: https://linkedin.com/in/lucas-ezequiel-flores  
* GitHub: https://github.com/LucasFloress  
* Email: floreslucas125@gmail.com    
