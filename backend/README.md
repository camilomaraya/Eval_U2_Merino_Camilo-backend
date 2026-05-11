# Fintech Solutions S.A. — Sprint 1

Evaluación Sumativa Unidad 2 — API RESTful para gestión de clientes.

---

## Requisitos previos

- Docker Desktop instalado y corriendo
- Git instalado
- Puerto 8080 libre

No se requiere PHP ni Composer instalados localmente.

---

## Instalación y puesta en marcha

```bash
# 1. Clonar el repositorio
git clone https://github.com/camilomaraya/eva2_merino_camilo.git
cd eva2_merino_camilo/backend

# 2. Copiar el archivo de entorno
cp .env.example .env

# 3. Configurar la base de datos en .env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=fintech
DB_USERNAME=fintech_user
DB_PASSWORD=fintech_pass

# 4. Levantar los contenedores
docker compose up -d --build

# 5. Instalar dependencias y generar clave
docker compose exec app composer install
docker compose exec app php artisan key:generate

# 6. Ejecutar migraciones
docker compose exec app php artisan migrate
```

---

## Estructura del proyecto

```
backend/
├── app/
│   ├── Http/Controllers/
│   │   ├── HealthController.php       ← Endpoint de salud
│   │   └── ClienteController.php      ← CRUD de clientes
│   └── Models/
│       └── Cliente.php                ← Modelo Eloquent → tabla clientes
├── database/migrations/
│   └── xxxx_create_clientes_table.php ← Estructura de la tabla
├── routes/
│   └── api.php                        ← Definición de endpoints
├── docker/
│   ├── php/dockerfile                 ← Imagen PHP 8.4-FPM
│   └── nginx/default.conf            ← Configuración del servidor web
├── docker-compose.yaml                ← Orquestación de contenedores
├── .env                               ← Variables de entorno (no versionado)
└── EVA2_CAMILO_MERINO.postman_collection.json ← Colección Postman
```

---

## Endpoints disponibles

### Health Check

| Método | URL | Descripción | Código |
|--------|-----|-------------|--------|
| GET | `/api/health` | Verifica que el backend esté operativo | 200 |

Respuesta:
```json
{
    "status": "online",
    "version": "1.0.0",
    "environment": "docker"
}
```

### CRUD de Clientes

| Método | URL | Descripción | Código |
|--------|-----|-------------|--------|
| GET | `/api/v1/clientes` | Listar todos los clientes | 200 |
| POST | `/api/v1/clientes` | Registrar un nuevo cliente | 201 |
| GET | `/api/v1/clientes/{id}` | Consultar un cliente por ID | 200 / 404 |

### Ejemplo de creación (POST)

```json
{
    "rut": "12345678-9",
    "nombre": "Ana",
    "apellido": "Rojas",
    "email": "ana@fintech.local",
    "telefono": "+56911112222"
}
```

### Validaciones

El endpoint POST valida los datos antes de guardar:

- `rut`: obligatorio, máximo 12 caracteres, único
- `nombre`: obligatorio, máximo 50 caracteres
- `apellido`: obligatorio, máximo 50 caracteres
- `email`: obligatorio, formato email válido, máximo 100 caracteres, único
- `telefono`: opcional, máximo 20 caracteres

Si se envía un `rut` o `email` que ya existe, la API responde con código **422 Unprocessable Entity**.

---

## Base de datos

La tabla `clientes` se crea mediante migración de Laravel:

| Campo | Tipo | Restricciones |
|-------|------|--------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT |
| rut | VARCHAR(12) | NOT NULL, UNIQUE |
| nombre | VARCHAR(50) | NOT NULL |
| apellido | VARCHAR(50) | NOT NULL |
| email | VARCHAR(100) | NOT NULL, UNIQUE |
| telefono | VARCHAR(20) | NULLABLE |
| created_at | TIMESTAMP | Automático |
| updated_at | TIMESTAMP | Automático |

### Comandos útiles de migraciones

```bash
# Ejecutar migraciones
docker compose exec app php artisan migrate

# Ver estado de migraciones
docker compose exec app php artisan migrate:status

# Revertir última migración
docker compose exec app php artisan migrate:rollback

# Rehacer migraciones desde cero
docker compose exec app php artisan migrate:fresh
```

---

## Arquitectura Docker

El proyecto corre sobre tres contenedores:

- **fintech_app** (PHP 8.4-FPM): Ejecuta el código Laravel.
- **fintech_web** (Nginx): Servidor web que recibe las peticiones HTTP en el puerto 8080 y las redirige a PHP-FPM.
- **fintech_db** (MySQL 8.0): Motor de base de datos. Los datos persisten en un volumen Docker (`dbdata`).

---

## Pruebas con Postman

La colección `EVA2_CAMILO_MERINO.postman_collection.json` incluye las siguientes peticiones:

1. **Health Check** — GET `/api/health`
2. **Listar Clientes** — GET `/api/v1/clientes`
3. **Crear Cliente** — POST `/api/v1/clientes`
4. **Ver Cliente** — GET `/api/v1/clientes/1`
5. **Error Validación - Email Duplicado** — POST `/api/v1/clientes` (demuestra respuesta 422)

Para importarla: abrir Postman → Import → seleccionar el archivo JSON.

---

## Detener el proyecto

```bash
docker compose down
```
