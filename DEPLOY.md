# 🚀 Despliegue en producción (VPS con nginx + Docker)

Esta guía despliega la invitación en tu VPS, que **ya tiene nginx sirviendo otros
sitios**. La app corre en su propio `docker-compose` escuchando en un **puerto local**
(`127.0.0.1:8090`), y tu nginx del host la publica con tu dominio + HTTPS. No se tocan
tus otros sitios. MySQL queda con volumen persistente y **sin exponerse a internet**.

---

## 1. Apunta el DNS

En tu proveedor de dominio crea un registro **A** del (sub)dominio que usarás hacia la IP
de tu VPS. Ej: `invitacion.tudominio.com → IP_DEL_VPS`. Espera a que propague.

## 2. Clona el proyecto en el VPS

```bash
cd /opt            # o donde guardes tus apps (ej. /var/www)
git clone TU_REPO_GIT invitacion
cd invitacion
```

## 3. Crea y configura el .env de producción

```bash
cp .env.production.example .env
nano .env     # completa: APP_URL, DB_PASSWORD, RESEND_API_KEY, ADMIN_*, etc.
```

Genera la clave de la app:

```bash
HOST_UID=$(id -u) HOST_GID=$(id -g) \
  docker compose -f docker-compose.prod.yml run --rm --no-deps -u www-data -e HOME=/tmp app \
  php artisan key:generate
```

> Asegúrate de que `APP_URL` use **https://** y tu dominio real.

## 4. Primer despliegue

```bash
bash deploy/deploy.sh
```

Esto construye la imagen, levanta los contenedores (app, nginx interno, MySQL), instala
dependencias, compila los assets, migra la base, crea los admins y cachea rutas/vistas.

Verifica que responde localmente:

```bash
curl -I http://127.0.0.1:8090      # debería dar HTTP 200
```

## 5. Publica con tu nginx del host + HTTPS

Copia el vhost de ejemplo y ajústalo:

```bash
sudo cp deploy/nginx-invitacion.conf.example /etc/nginx/sites-available/invitacion
sudo nano /etc/nginx/sites-available/invitacion     # cambia el server_name por tu dominio
sudo ln -s /etc/nginx/sites-available/invitacion /etc/nginx/sites-enabled/
sudo nginx -t && sudo systemctl reload nginx
```

Genera el certificado SSL (gratis, Let's Encrypt):

```bash
sudo certbot --nginx -d invitacion.tudominio.com
```

Certbot añade el bloque HTTPS y la redirección automáticamente. ¡Listo! Abre
`https://invitacion.tudominio.com` 🎉

---

## 🔄 Actualizar (cuando cambies algo)

```bash
cd /opt/invitacion
bash deploy/deploy.sh
```

## ✏️ Cambiar contenido / imágenes en producción

- **Textos, fecha, mapa, etc.:** edita `config/event.php` (o las variables `EVENT_*` del
  `.env`) y reinicia: `docker compose -f docker-compose.prod.yml restart app`.
- **Imágenes:** reemplaza los archivos en `public/images/` (mismos nombres) y reinicia.
  No hace falta recompilar.
- **Contraseña/segundo admin:** edita `ADMIN*` en `.env` y corre:
  `docker compose -f docker-compose.prod.yml exec -u www-data -e HOME=/tmp app php artisan db:seed --force`

## 💾 Backup de la base de datos

```bash
docker compose -f docker-compose.prod.yml exec mysql \
  sh -c 'exec mysqldump -u root -p"$MYSQL_ROOT_PASSWORD" invitacion' > backup_$(date +%F).sql
```

Restaurar:

```bash
cat backup_2026-06-10.sql | docker compose -f docker-compose.prod.yml exec -T mysql \
  sh -c 'exec mysql -u root -p"$MYSQL_ROOT_PASSWORD" invitacion'
```

## 📧 Correos (Resend)

En el `.env` de producción pon `MAIL_MAILER=resend`, tu `RESEND_API_KEY` y un
`MAIL_FROM_ADDRESS` con un dominio **verificado** en Resend. Reinicia `app`.
Prueba enviando una confirmación desde la invitación.

## 🛠️ Comandos útiles

```bash
docker compose -f docker-compose.prod.yml logs -f app      # logs de la app
docker compose -f docker-compose.prod.yml ps               # estado
docker compose -f docker-compose.prod.yml down             # apagar (sin borrar datos)
docker compose -f docker-compose.prod.yml restart app      # reiniciar la app
```

> Apagar con `down` **no** borra la base (vive en el volumen `dbdata`). Para borrar todo
> incluido los datos: `docker compose -f docker-compose.prod.yml down -v` (¡cuidado!).
