# Bulldog Shop - Plataforma de Reservas y Landing Page

Proyecto desarrollado con Laravel 13, Vue 3, Inertia.js (SSR), Tailwind CSS y shadcn-vue. El entorno de desarrollo está completamente contenerizado utilizando Laravel Sail (Docker) para garantizar la compatibilidad entre sistemas operativos.

---

## Instalación y Configuración - Linux (Fedora / Ubuntu)

### Requisitos previos
- Git
- Docker Engine y Docker Compose

### Paso a paso
1. Clonar el repositorio:
    git clone https://github.com/SerchPer11/sw_bullshop
    cd sw_bullshop

2. Configurar el archivo de entorno:
    cp .env.example .env

3. Instalar dependencias de PHP (mediante contenedor temporal):
    docker run --rm -u "$(id -u):$(id -g)" -v "$(pwd):/var/www/html" -w /var/www/html laravelsail/php83-composer:latest composer install --ignore-platform-reqs

4. Levantar los servicios de Docker:
    ./vendor/bin/sail up -d

Puedes crear un alias para sail:
    alias sail='bash vendor/bin/sail'

6. Generar clave de aplicación y migrar la base de datos:
    ./vendor/bin/sail artisan key:generate
    ./vendor/bin/sail artisan migrate

7. Instalar dependencias del frontend (pnpm):
    ./vendor/bin/sail pnpm install

8. Compilar assets e iniciar el servidor de Vite (mantener terminal abierta):
`./vendor/bin/sail pnpm run dev`

---

## Instalación y Configuración - Windows (WSL2)

### Requisitos previos
- Git
- WSL2 (Windows Subsystem for Linux) configurado con una distribución (ej. Ubuntu).
- Docker Desktop con la integración de WSL2 habilitada en la configuración.
- **Importante:** Todos los comandos a continuación deben ejecutarse EXCLUSIVAMENTE desde la terminal de WSL2 (Ubuntu), no desde PowerShell ni CMD.

### Paso a paso
1. Clonar el repositorio desde la terminal de WSL2:
    git clone https://github.com/SerchPer11/sw_bullshop
    cd sw_bullshop

2. Configurar el archivo de entorno:
    cp .env.example .env

3. Instalar dependencias de PHP (mediante contenedor temporal):
    docker run --rm -u "$(id -u):$(id -g)" -v "$(pwd):/var/www/html" -w /var/www/html laravelsail/php83-composer:latest composer install --ignore-platform-reqs

4. Levantar los servicios de Docker:
    ./vendor/bin/sail up -d

5. Generar clave de aplicación y migrar la base de datos:
    ./vendor/bin/sail artisan key:generate
    ./vendor/bin/sail artisan migrate

6. Instalar dependencias del frontend (pnpm):
    ./vendor/bin/sail pnpm install

7. Compilar assets e iniciar el servidor de Vite (mantener terminal abierta):
    ./vendor/bin/sail pnpm run dev

---

## Flujo de Trabajo Diario (Ambos Sistemas)

Tu rutina para iniciar a trabajar cada día será únicamente:

1. Abrir la terminal en la raíz del proyecto (en Windows, usar terminal de WSL2).
2. Levantar Docker: ./vendor/bin/sail up -d
3. Iniciar Vite (mantener abierta): ./vendor/bin/sail pnpm run dev

Para detener los contenedores al finalizar tu jornada:
    ./vendor/bin/sail down

---

## Gestión de Componentes UI (shadcn-vue)

Este proyecto utiliza shadcn-vue. Los componentes se agregan bajo demanda a través del CLI utilizando Sail.

Ejemplo para agregar una tarjeta (card):
 ./vendor/bin/sail npx shadcn-vue@latest add card

Los componentes generados se ubicarán en: resources/js/components/ui/ .
