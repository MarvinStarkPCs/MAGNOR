# Base de Datos - Sistema ARECUPERAR

## Motor recomendado
**PostgreSQL 14+**

## Archivos

| Archivo | Descripción | Ejecutar |
|---|---|---|
| `schema.sql` | Tablas, restricciones, índices, triggers y funciones | 1° |
| `seed_data.sql` | Datos iniciales: materiales, recicladores, config | 2° |
| `views.sql` | Vistas para dashboard, reportes y exportación SUI | 3° |

## Instalación rápida

```bash
# Crear base de datos
psql -U postgres -c "CREATE DATABASE arecuperar;"

# Ejecutar archivos en orden
psql -U postgres -d arecuperar -f schema.sql
psql -U postgres -d arecuperar -f seed_data.sql
psql -U postgres -d arecuperar -f views.sql
```

## Diagrama de entidades

```
configuracion_empresa          municipios
       │                           │
       │                    recicladores ──── tipos_documento
       │                    │    │    │
       │               vehiculos  │  rutas_reciclador ── dias_semana
       │                          │
       │                       recibos
       │                          │
       │                   detalle_recibo ─── materiales ─── familias_material
       │                          │               │
       │                          │         historial_precios
       │                          │
       │               exportaciones_sui
       │                          │
       │               detalle_exportacion_sui
       │
     auditoria
```

## Tablas principales

| Tabla | Filas esperadas | Propósito |
|---|---|---|
| `recicladores` | ~80-200 | Catálogo de recicladores de oficio |
| `materiales` | ~44 (fijo) | Catálogo de materiales y precios |
| `recibos` | ~5-20/día | Cabecera de cada recibo |
| `detalle_recibo` | ~10-60/día | Líneas de material (balance de masa) |
| `registro` (via vista) | histórico | Equivalente al REGISTRO del Excel |

## Vistas disponibles

| Vista | Uso |
|---|---|
| `v_recibos` | Listado de recibos con datos del reciclador |
| `v_detalle_recibos` | Líneas de recibo con material y familia |
| `v_resumen_mensual` | KPIs por mes (dashboard) |
| `v_resumen_semanal` | KPIs por semana |
| `v_top_recicladores` | Ranking de recicladores |
| `v_top_materiales` | Ranking de materiales por volumen |
| `v_balance_masa_mensual` | Balance de masa agregado por mes |
| `v_exportacion_sui` | Datos listos para exportar al SUI |
| `v_recicladores_rutas` | Recicladores con rutas en columnas |

## Parámetros de empresa (tabla `configuracion_empresa`)

Editar directamente en la base de datos o via la interfaz del sistema:

| Clave | Descripción |
|---|---|
| `nueca` | Número Único de Establecimiento Comercial Autorizado |
| `numacro` | Número de Macroruta |
| `numero_sitio_destino` | Código SUI del sitio de destino |
| `retefuente_porcentaje` | % de retención en la fuente |
| `recibo_consecutivo` | Próximo número de recibo |
