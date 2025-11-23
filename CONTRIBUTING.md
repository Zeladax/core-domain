# Guía de contribución

¡Gracias por tu interés en contribuir a [Incadev Uns Core Domain](https://github.com/incadev-uns/core-domain)! Esta guía te ayudará a realizar aportes de manera eficiente y siguiendo las mejores prácticas del proyecto.

Para garantizar que tus contribuciones sean aceptadas y fáciles de integrar, por favor sigue los pasos detallados en esta guía.

## Requisitos previos

Antes de comenzar, asegúrate de **forkear** el repositorio y **clonarlo** en tu máquina local.

### 1. Forkea el repositorio

Ve al repositorio de GitHub y haz click en el botón de Fork en la parte superior derecha para crear tu propia copia del proyecto.

### 2. Clona tu fork

Una vez que hayas forkeado el repositorio, clónalo a tu máquina local con el siguiente comando:

``` bash
git clone https://github.com/TU_USUARIO/core-domain.git
cd core-domain
```

### 3. Instala las dependencias

Después de clonar el repositorio, instala las dependencias del proyecto con el siguiente comando:

``` bash
composer install
```

### 4. Mantén tu fork y tu repositorio local actualizado

Antes de hacer un push o enviar un Pull Request, es muy importante asegurarte de que tu fork y tu repositorio local estén al día con los últimos cambios del repositorio principal.

## Validación de calidad antes de contribuir

Ejecuta los siguientes comandos para asegurar que tu contribución cumple
con los estándares del proyecto:

``` bash
composer format
composer analyse
composer test
composer run build
```

### En caso de errores durante la validación

Si alguno de estos comandos falla:

1. Revisa el mensaje de error para identificar el comando que falló.
2. Ejecuta los siguientes comandos de recuperación:

    ``` bash
    composer clear-cache
    composer dump-autoload
    ```

3. Una vez completados, **vuelve a intentar ejecutar** el o los comandos que fallaron.

Si tras repetir el proceso los errores continúan, revisa con más detalle el mensaje de error o consulta al equipo del proyecto antes de enviar tu contribución.

## Cómo contribuir

### 1. Contribución con Seeders

- Crea un archivo referente a tu tema dentro de:

    ``` bash
    /src/Database/Seeders/MiTemaSeeder.php
    ```

    **Regla importante:**

    Utiliza los *modelos* del paquete `IncadevUns/CoreDomain` para crear y recuperar registros dentro de los seeders. **No uses `DB::statement`, consultas SQL crudas ni inserciones directas** que omitan la capa Eloquent, salvo una justificación técnica sólida y revisada por el equipo. Usar modelos mantiene la consistencia entre entornos.

    **Buenas prácticas al referenciar registros**

    - Cuando necesites referenciar registros ya existentes (por ejemplo para relaciones), **no uses el `id` directamente**. En la medida de lo posible busca un campo único y estable (por ejemplo `email` para usuarios) y búscalo por ese campo. Esto evita dependencias frágiles entre entornos y migraciones.
    - Emplea métodos como `firstOrCreate`, `updateOrCreate` o `firstWhere` para evitar duplicados y hacer los seeders idempotentes.

- Registra tu seeder en:

    ``` bash
    /src/Database/Seeders/IncadevSeeder.php
    ```

    Ejemplo:

    ``` php
    <?php

    namespace IncadevUns\CoreDomain\Database\Seeders;

    use Illuminate\Database\Seeder;

    class IncadevSeeder extends Seeder
    {
        public function run(): void
        {
            $this->call([
                // otros seeders ...
                MiTemaSeeder::class,
            ]);
        }
    }
    ```

- No olvides ejecutar las pruebas antes de enviar tu PR.

### 2. Contribución con Modelos y Migraciones

#### Migraciones

Las migraciones existentes siguen el formato:

    2025_10_31_000NN0

-   El último **0** permite enumerar nuevas migraciones dentro del mismo
    grupo.
-   Mantén este formato para asegurar el orden correcto entre
    migraciones.
-   **No se permiten migraciones que creen triggers, stored procedures,
    funciones especiales como `enum()` u otros elementos dependientes
    del motor.** Todo eso será manejado desde Laravel para garantizar escalabilidad
    entre motores SQL.

#### Modelos

Los modelos deben incluir:

-   `fillable` correctamente definidos.
-   `casts`, `guarded`, `hidden`, `with`, `appends` según corresponda.
-   Relaciones bien descritas.
-   Documentación clara del propósito del modelo y sus reglas.

## Antes de enviar tu Pull Request

Asegúrate de:

-   Ejecutar todos los comandos de validación (`format`, `analyse`, `test`, `build`).
-   Mantener el estándar de estilo definido por el proyecto.
-   No introducir cambios no relacionados con tu aporte específico.
-   Documentar cualquier decisión técnica relevante.

------------------------------------------------------------------------

¡Gracias por contribuir a [**incadev-uns/core-domain**](https://github.com/incadev-uns/core-domain)!
