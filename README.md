# Sistema de Información Comunal (SICOMUNAL)

> Sistema web para la gestión y censo demográfico de consejos comunales en Venezuela.

**Autor:** Luis Pérez — [FACyT, Universidad de Carabobo](http://www.facyt.uc.edu.ve/)

---

## Descripción

SICOMUNAL es una aplicación web desarrollada como Trabajo Especial de Grado (TEG) que permite a los consejos comunales:

- Registrar y gestionar el censo demográfico de su comunidad (grupos familiares, personas)
- Levantar encuestas sobre situación económica, de vivienda, salud, servicios y participación comunitaria
- Gestionar comités comunitarios y sus voceros
- Generar estadísticas y documentos (cuaderno de votación, registro electoral, resumen del censo)
- Enviar correos comunitarios a los empadronados
- Administrar usuarios del sistema con roles diferenciados

## Stack Tecnológico

| Componente | Versión |
|---|---|
| PHP | ≥ 8.1 |
| Symfony | 6.4 LTS |
| Doctrine ORM | 2.14+ |
| MySQL | 8.0 |
| Bootstrap | 3.3.6 |
| jQuery | 2.1.4 |

## Requisitos

- PHP 8.1 o superior con extensiones: `pdo_mysql`, `intl`, `mbstring`, `xml`, `ctype`
- MySQL 8.0 (o compatible)
- Composer

## Instalación

```bash
# 1. Clonar el repositorio
git clone https://github.com/starchild92/SICOMUNAL.git
cd SICOMUNAL

# 2. Instalar dependencias
composer install

# 3. Configurar base de datos en app/config/parameters.yml
#    (copiar desde parameters.yml.dist y ajustar credenciales)

# 4. Crear la base de datos y el esquema
php app/console doctrine:database:create
php app/console doctrine:schema:create

# 5. Limpiar caché
php app/console cache:clear
```

## Configuración

Editar `app/config/parameters.yml` con los datos de conexión a la base de datos:

```yaml
parameters:
    database_host:     127.0.0.1
    database_port:     3306
    database_name:     sicomunal
    database_user:     root
    database_password: ~
    secret:            'cambia_este_valor'
```

## Levantar el servidor de desarrollo

```bash
php app/console server:run
```

La aplicación estará disponible en `http://127.0.0.1:8000`.

## Crear usuario administrador

Después de crear el esquema, insertar un usuario administrador directamente en la base de datos:

```sql
INSERT INTO usuario (username, email, password, roles, enabled, primerNombre, primerApellido)
VALUES (
    'admin',
    'admin@example.com',
    '<hash_bcrypt>',
    '["ROLE_ADMIN","ROLE_USER"]',
    1,
    'Admin',
    'Sistema'
);
```

> El hash de contraseña se puede generar con `php app/console security:hash-password`.

## Estructura del Proyecto

```
src/SICBundle/
├── Controller/      # Controladores (uno por entidad + controladores Admin*)
├── Entity/          # Entidades Doctrine
├── Form/            # Form types de Symfony
├── Repository/      # Repositorios con consultas DQL personalizadas
└── Resources/
    └── views/       # Plantillas Twig

app/
├── config/          # Configuración de Symfony (security, routing, doctrine…)
└── Resources/
    └── views/       # Plantillas base y vistas globales

web/
├── css/             # Bootstrap, Semantic UI, estilos propios
└── js/              # jQuery, Bootstrap JS, plugins
```

## Roles de Usuario

| Rol | Descripción |
|---|---|
| `ROLE_ADMIN` | Administrador: acceso completo, gestión de usuarios y parámetros |
| `ROLE_USER` | Empadronador: puede registrar y editar planillas del censo |

## Licencia

Proyecto académico — Trabajo Especial de Grado, Universidad de Carabobo.
