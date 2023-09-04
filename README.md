## Tecnologías Utilizadas

**Tecnologías:** Symfony, Twig, Bootstrap, JQuery.


## Como Utilizarlo

Clonar el proyecto

```bash
  [git clone https://github.com/jorgeale90/mkarestaurante](https://github.com/jorgeale90/Sistema_COVID19.git)
```

Acceder al directorio de dicho proyecto

```bash
  cd Sistema_COVID19
```

Instalar las dependencias

```bash
  composer install
```

Crear la Base de Datos

```bash
  php bin/console doctrine:database:create
```

Actualizar las tablas de la Base de Datos

```bash
  php bin/console doctrine:schema:update --force
```

Ejecutar el servidor

```bash
  symfony server:start
```
