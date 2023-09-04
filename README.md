# Sistema_COVID19

El proyecto Sistema_COVID19 se centra en el control de pacientes con la enfermedad del Covid-19. Este proporciona al personal médico una mejor gestión de su trabajo.

## Tecnologías Utilizadas

**Tecnologías:** Symfony, Twig, Bootstrap, JQuery.


## Como Utilizarlo

Clonar el proyecto

```bash
  git clone https://github.com/jorgeale90/Sistema_COVID19
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
