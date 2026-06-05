# 🎉⚽ Invitación de Cumpleaños — Edición Mundialista

Invitación digital (mobile-first, animada) + panel privado para llevar el control de
invitados. Hecha con **Laravel 13 + Inertia + Vue 3 + Tailwind 4 + Leaflet + Resend**,
todo dentro de **Docker**.

## 🚀 Levantar el proyecto

```bash
# Genera el archivo con tu UID/GID (solo la primera vez)
printf "UID=%s\nGID=%s\n" "$(id -u)" "$(id -g)" > .env.docker

# Construir y levantar todo
docker compose --env-file .env --env-file .env.docker up -d --build

# Migrar + crear el usuario admin
docker compose exec -u www-data app php artisan migrate --seed
```

Luego abre:

| URL | Qué es |
|-----|--------|
| http://localhost:8080 | La invitación |
| http://localhost:8080/login | Acceso al panel |
| http://localhost:8080/admin | Panel de confirmaciones (requiere login) |

> El contenedor `node` corre Vite en modo dev (HMR) en el puerto 5173.

## 🔑 Credenciales del panel

Se crean desde el `.env` con el seeder. **Cambia esto antes de subir a tu servidor:**

```env
ADMIN_EMAIL="tucorreo@gmail.com"
ADMIN_PASSWORD="una-clave-segura"
ADMIN_NAME="Tu Nombre"
```

Tras cambiarlas: `docker compose exec -u www-data app php artisan db:seed --force`

## ✏️ Personalizar la invitación

Todo el contenido editable está en **`config/event.php`** (o variables `EVENT_*` en `.env`):
nombre, fecha/hora, cuenta regresiva, dirección, coordenadas del mapa, dress code, textos.

- **Imagen del dress code:** reemplaza `public/images/dresscode.svg` (o cambia la ruta
  en `config/event.php`).
- **Mapa:** ajusta `map_lat` / `map_lng` con las coordenadas reales del lugar.

## 📧 Correos con Resend

Por defecto los correos se escriben en `storage/logs/laravel.log` (`MAIL_MAILER=log`).
Para enviarlos de verdad con Resend:

```env
MAIL_MAILER=resend
RESEND_API_KEY=re_xxxxxxxx
MAIL_FROM_ADDRESS="invitacion@tudominio.com"   # remitente verificado en Resend
ADMIN_NOTIFY_EMAIL="tucorreo@gmail.com"        # te avisa de cada confirmación
```

Se envían dos correos al confirmar: confirmación al invitado y aviso al anfitrión.

## 🧩 Cómo está organizado

- **Invitación:** `resources/js/Pages/Invitation.vue` + secciones en
  `resources/js/Components/invitation/` (Hero, Countdown, EventDetails, LocationMap,
  DressCode, RsvpForm, Footer).
- **Animaciones de scroll:** `Components/ui/Reveal.vue` (`@vueuse/motion`).
- **RSVP:** `RsvpController@store` hace upsert por correo (no duplica) y dispara los correos.
- **Panel:** `Admin/DashboardController` + `Pages/Admin/Dashboard.vue`.
- **Datos del evento:** `config/event.php` → `App\Support\EventData`.

## 🛠️ Comandos útiles

```bash
docker compose logs -f node          # ver Vite / errores de build
docker compose exec -u www-data app php artisan migrate:fresh --seed
docker compose exec node npm run build   # build de producción a public/build
```

## 📦 Despliegue en tu servidor

```bash
docker compose exec node npm run build   # genera public/build
rm -f public/hot                         # usa los assets compilados (no el dev server)
```
En producción puedes apagar el contenedor `node`. Ajusta `APP_URL`, `APP_DEBUG=false`
y las credenciales/keys reales en el `.env` del servidor.
