# CONTEXTO DEL SISTEMA - MAGNOR RECICLAJE / ARECUPERAR
> Extraído del archivo: `RECIBO ARECUPERAR 2026.xlsm`  
> Fecha de análisis: 2026-05-07

---

## 1. DESCRIPCIÓN GENERAL

Sistema de gestión de recibo de materiales reciclables para la empresa **ARECUPERAR**, ubicada en **Ocaña (Norte de Santander, Colombia)**. Gestiona el proceso de pesaje, pago y registro de materiales que entregan los recicladores de oficio. Genera datos compatibles con el sistema **SUI** (Sistema Único de Información) del gobierno colombiano para reporte obligatorio de balance de masa.

---

## 2. HOJAS DEL ARCHIVO EXCEL

| Hoja | Función |
|---|---|
| `RECIBO` | Formulario de ingreso de materiales por reciclador |
| `RECICLADORES` | Catálogo maestro de recicladores registrados |
| `MATERIALES` | Catálogo de materiales con precios y códigos SUI |
| `REGISTRO` | Base de datos de todas las transacciones (balance de masa) |
| `DASHBOARD` | Panel de control con totales y rankings |
| `CODIGO_BOTONES` | Código VBA de los botones del sistema |
| `Hoja1` | Vacía (sin uso) |
| `USO` | Vacía (sin uso activo) |

---

## 3. HOJA: RECIBO (Formulario de Entrada)

### Estructura del formulario

| Campo | Descripción |
|---|---|
| N° RECIBO | Número consecutivo automático del recibo |
| FECHA | Fecha del recibo |
| RECICLADOR | Nombre del reciclador (seleccionado de lista) |
| RUTA | Ruta de recolección del día |
| DOCUMENTO | Número de cédula del reciclador |
| TELÉFONO | Teléfono de contacto |
| DIRECCIÓN | Dirección del reciclador |
| MUNICIPIO | Municipio (generalmente Ocaña) |

### Tabla de materiales (filas 12-21, hasta 10 ítems por recibo)

| Columna | Descripción |
|---|---|
| N° | Número de ítem |
| MATERIAL | Nombre del material (lista desplegable) |
| KG | Kilogramos recibidos |
| PRECIO KG | Precio por kilogramo (automático desde tabla MATERIALES) |
| SUBTOTAL | KG × PRECIO KG |

### Totales del recibo

| Campo | Cálculo |
|---|---|
| SUBTOTAL | Suma de todos los subtotales |
| RETEFUENTE | Retención en la fuente aplicable |
| **TOTAL A PAGAR** | SUBTOTAL - RETEFUENTE |

---

## 4. HOJA: RECICLADORES (Catálogo Maestro)

### Columnas

| Col | Campo | Descripción |
|---|---|---|
| A | ID | Identificador numérico único |
| B | NOMBRE | Nombre completo del reciclador |
| C | DOCUMENTO | Número de cédula |
| D | TELÉFONO | Número de contacto |
| E | DIRECCIÓN | Dirección de residencia (formato KDX XXX-XXX) |
| F | MUNICIPIO | Municipio (Ocaña principalmente) |
| G | ACTIVO | Si/No |
| H | LUNES | Ruta de recolección los lunes |
| I | MARTES | Ruta de recolección los martes |
| J | MIÉRCOLES | Ruta de recolección los miércoles |
| K | JUEVES | Ruta de recolección los jueves |
| L | VIERNES | Ruta de recolección los viernes |
| M | SÁBADO | Ruta de recolección los sábados |
| N | DOMINGO | Ruta de recolección los domingos |
| O | PLACA | Placa del vehículo (999 = sin vehículo / default) |

### Estadísticas
- **Total recicladores registrados:** ~77
- **Municipio principal:** Ocaña, Norte de Santander
- **Estado:** Todos marcados como "Si" (activos)

### Ejemplo de recicladores

| ID | Nombre | Documento | Teléfono |
|---|---|---|---|
| 1 | ALEJO PÉREZ PÉREZ | 5528028 | 3204433879 |
| 2 | ALFONSO PABÓN GUERRERO | 12520822 | N/T |
| 3 | ALIRIO ALBERTO PABÓN | 88141122 | 3167291735 |
| 14 | CRISTO FERNANDO AMAYA JULIO | 5470631 | 3222863338 |
| 17 | DIANA MILEIDY PUENTE CABALLERO | 1064840915 | 3227883197 |

---

## 5. HOJA: MATERIALES (Catálogo con Códigos SUI)

### Columnas

| Campo | Descripción |
|---|---|
| CODIGO SUI | Código oficial del Sistema Único de Información |
| FAMILIA | Categoría del material |
| MATERIAL | Nombre específico del material |
| PRECIO | Precio de compra en COP por kilogramo |

### Catálogo completo por familia

#### METALES (Código SUI: 101-199)
| Código SUI | Material | Precio COP/kg |
|---|---|---|
| 101 | ALUMINIO LATA | 7.000 |
| 101 | ALUMINIO GRUESO | 5.500 |
| 101 | ALUMINIO BLANDO | 5.500 |
| 101 | ALUMINIO PERFIL | 7.000 |
| 101 | ALUMINIO BANDA | 4.000 |
| 101 | ALUMINIO GUAYA | 8.000 |
| 101 | ALUMINIO RINES | 6.500 |
| 102 | CHATARRA GRIS | 1.200 |
| 102 | CHATARRA | 700 |
| 103 | COBRE #1 | 34.000 |
| 103 | COBRE #2 | 33.000 |
| 104 | BRONCE | 18.000 |
| 105 | ANTIMONIO | 5.500 |
| 106 | ACERO | 2.800 |
| 199 | OTROS METALES | 5.500 |

#### PAPEL Y CARTÓN (Código SUI: 201-299)
| Código SUI | Material | Precio COP/kg |
|---|---|---|
| 201 | ARCHIVO | 700 |
| 202 | CARTÓN | 350 |
| 203 | CUBETAS O PANELES | 50 |
| 204 | PERIÓDICO | 200 |
| 205 | PLEGADIZA | 150 |
| 206 | TETRA PACK | 150 |
| 207 | PLASTIFICADO | 0 |
| 208 | KRAF | 0 |
| 299 | OTROS PAPEL Y CARTÓN | 350 |

#### PLÁSTICOS (Código SUI: 301-399)
| Código SUI | Material | Precio COP/kg |
|---|---|---|
| 301 | ACRÍLICO | 0 |
| 302 | PASTA | 800 |
| 302 | PASTA NEGRA | 700 |
| 302 | PASTA SILLA | 1.500 |
| 302 | PASTA LAVADORA | 1.400 |
| 303 | PET TRANSPARENTE | 1.000 |
| 303 | PET AMBAR | 300 |
| 303 | PET VERDE | 300 |
| 303 | PET ACEITE | 150 |
| 303 | PET REVUELTO | 500 |
| 304 | PVC TUVO | 300 |
| 304 | PVC LAMINA | 200 |
| 305 | BOLSA | 600 |
| 306 | MANGUERA | 800 |
| 307 | SOPLADO | 700 |
| 308 | POLIPROPILENO | 0 |
| 399 | OTROS PLÁSTICOS | 0 |

#### VIDRIO (Código SUI: 499)
| Código SUI | Material | Precio COP/kg |
|---|---|---|
| 499 | OTROS VIDRIOS | 50 |

#### RECHAZO (Código SUI: 799)
| Código SUI | Material | Precio COP/kg |
|---|---|---|
| 799 | RECHAZO | — |

**Total materiales:** 44 registros

---

## 6. HOJA: REGISTRO (Balance de Masa - Tabla Principal)

Esta es la hoja más importante. Cada fila es **una línea de material por recibo**.

### Columnas completas

| Col | Campo | Descripción |
|---|---|---|
| A | RECIBO | Número del recibo |
| B | FECHA | Fecha de la transacción |
| C | RECICLADOR | Nombre del reciclador |
| D | DOCUMENTO | Cédula del reciclador |
| E | MATERIAL | Nombre del material |
| F | KG | Kilogramos brutos recibidos |
| G | PRECIO DE KG | Precio pagado por kg |
| H | TOTAL | Pago total (KG × PRECIO) |
| I | SEMANA | Número de semana del año |
| J | KG RECHAZADO | Kilogramos rechazados (impurezas) |
| K | KG NETO | KG - KG RECHAZADO |
| L | NUECA | Número Único de Establecimiento Comercial Autorizado |
| M | NUMACRO | Número de Macroruta |
| N | TIPO DE IDENTIFICACION DEL RECICLADOR U OPERARIO | Tipo de doc (1 = CC) |
| O | NUMERO DE IDENTIFICACION DEL RECICLADOR U OPERARIO | Número de documento |
| P | PLACA DEL VEHICULO QUE INGRESA EL MATERIAL | Placa del vehículo |
| Q | CANTIDAD DE MATERIAL ENTRANTE | Toneladas (KG / 1000) |
| R | TIPO DE MATERIAL ENTRANTE | Código SUI del material |
| S | RECHAZO ASOCIADO AL MATERIAL ENTRANTE | Toneladas de rechazo |
| T | TIPO DE SITIO DE DESTINO DEL RECHAZO | Código tipo de sitio |
| U | NUMERO UNICO DEL SITIO DEL DESTINO | Código del sitio de destino |

### Ejemplo de registro real

| Campo | Valor |
|---|---|
| RECIBO | 1 |
| FECHA | 2026-06-05 |
| RECICLADOR | ALIRIO ALBERTO PABÓN |
| DOCUMENTO | 88141122 |
| MATERIAL | PET TRANSPARENTE |
| KG | 100 |
| PRECIO KG | 1.000 |
| TOTAL | 100.000 |
| SEMANA | 1 |
| KG RECHAZADO | 1 |
| KG NETO | 99 |
| NUECA | 1286754498 |
| NUMACRO | 449054498 |
| TIPO ID | 1 (CC) |
| PLACA | 999 |
| CANT. ENTRANTE (ton) | 0.1 |
| TIPO MATERIAL | 303 |
| RECHAZO (ton) | 0.001 |
| TIPO SITIO DESTINO | 1 |
| N° SITIO DESTINO | 206654498 |

### Fórmula del Balance de Masa
```
KG NETO = KG BRUTO - KG RECHAZADO
CANT. ENTRANTE (ton) = KG / 1000
RECHAZO (ton) = KG RECHAZADO / 1000
TOTAL = KG × PRECIO DE KG
```

---

## 7. HOJA: DASHBOARD

### Indicadores globales
| Indicador | Descripción |
|---|---|
| TOTAL PAGADO | Suma total pagada en el período |
| TOTAL KG | Total de kilogramos recibidos |
| TOTAL RECIBOS | Cantidad de recibos generados |

### Tablas del dashboard
- **TOP RECICLADORES:** Listado de todos los recicladores con su total pagado
- **MATERIALES:** Listado de materiales con total KG recibidos
- Filtro por **MES**

---

## 8. LÓGICA VBA (Botones del Sistema)

### `GuardarRecibo()`
1. Valida que se haya seleccionado un reciclador
2. Recorre las filas 12-21 de la hoja RECIBO
3. Por cada material con nombre Y kilogramos > 0, guarda una fila en REGISTRO con:
   - N° recibo, fecha, reciclador, documento
   - Material, KG, precio, total
4. Limpia el formulario y aumenta el número de recibo en 1
5. Muestra confirmación al usuario

### `EditarRecibo()`
1. Solicita número de recibo via InputBox
2. Busca todas las filas de ese recibo en REGISTRO
3. Carga los datos de vuelta al formulario RECIBO para edición

### `ImprimirRecibo()`
1. Imprime la hoja RECIBO directamente

---

## 9. FLUJO DEL PROCESO

```
[Reciclador llega con material]
        ↓
[Seleccionar reciclador en RECIBO]
        ↓
[Pesar materiales → ingresar KG por material]
        ↓
[Sistema calcula: precio automático, subtotal, total]
        ↓
[GuardarRecibo() → datos van a hoja REGISTRO]
        ↓
[REGISTRO acumula el balance de masa]
        ↓
[DASHBOARD muestra resúmenes y totales]
        ↓
[Exportar datos del REGISTRO al sistema SUI]
```

---

## 10. CAMPOS SUI (Reporte al Gobierno)

El sistema genera datos para el **SUI** (Superintendencia de Servicios Públicos Domiciliarios). Los campos críticos son:

| Campo SUI | Descripción | Ejemplo |
|---|---|---|
| NUECA | N° Único Establecimiento Comercial | 1286754498 |
| NUMACRO | N° de Macroruta | 449054498 |
| Tipo ID | 1 = CC, 2 = NIT, etc. | 1 |
| N° Identificación | Documento del reciclador | 88141122 |
| Placa vehículo | Placa del vehículo | 999 |
| Cantidad entrante (ton) | KG / 1000 | 0.1 |
| Tipo material | Código SUI | 303 (PET) |
| Rechazo (ton) | KG rechazado / 1000 | 0.001 |
| Tipo sitio destino | Código tipo de sitio | 1 |
| N° único sitio destino | Código del sitio | 206654498 |

---

## 11. MÓDULOS DEL SISTEMA A DESARROLLAR

Con base en el Excel, el sistema requiere los siguientes módulos:

### 11.1 Módulo: Gestión de Recicladores
- CRUD de recicladores
- Campos: ID, nombre, documento, teléfono, dirección, municipio, activo, rutas por día, placa
- Filtrado por activo/inactivo
- Búsqueda por nombre o documento

### 11.2 Módulo: Catálogo de Materiales
- CRUD de materiales
- Campos: código SUI, familia, material, precio
- Agrupación por familia
- Actualización masiva de precios

### 11.3 Módulo: Recibo de Materiales
- Formulario de ingreso de recibo
- Selección de reciclador (autocomplete)
- Tabla de materiales (hasta N ítems)
  - Selección de material (autocomplete con precio automático)
  - Ingreso de KG brutos
  - Ingreso de KG rechazados
  - Cálculo automático de KG netos, subtotal, total
- Número de recibo consecutivo automático
- Generación de PDF del recibo
- Edición de recibos existentes

### 11.4 Módulo: Registro / Balance de Masa
- Listado de todas las transacciones
- Filtros por: fecha, reciclador, material, semana
- Exportación a Excel/CSV
- Campos SUI automáticos

### 11.5 Módulo: Dashboard / Reportes
- Total pagado por período (día/semana/mes)
- Total KG por período
- Total recibos por período
- Ranking de recicladores por KG o por pago
- Ranking de materiales más recibidos
- Gráficas de tendencias

### 11.6 Módulo: Exportación SUI
- Generación del archivo de reporte para el SUI
- Campos requeridos ya mapeados en REGISTRO
- Validaciones de completitud

---

## 12. REGLAS DE NEGOCIO IDENTIFICADAS

1. **Un recibo puede tener múltiples materiales** (relación 1:N recibo-materiales)
2. **El precio por KG se toma automáticamente** del catálogo de materiales
3. **KG Neto = KG Bruto - KG Rechazado**
4. **La cantidad para SUI se reporta en toneladas** (KG / 1000)
5. **La semana** se asigna al momento del registro
6. **El número de recibo es consecutivo** y aumenta automáticamente al guardar
7. **Los recicladores tienen rutas asignadas por día** de la semana
8. **Los campos NUECA y NUMACRO** son fijos de la empresa (1286754498 / 449054498)
9. **La placa 999** indica que el reciclador no tiene vehículo registrado
10. **El tipo de identificación 1** corresponde a Cédula de Ciudadanía (CC)

---

## 13. TECNOLOGÍAS SUGERIDAS PARA EL SISTEMA

| Componente | Opción recomendada |
|---|---|
| Backend | Python (FastAPI) o Node.js (Express) |
| Base de datos | PostgreSQL o SQLite |
| Frontend | React o Vue.js |
| Reportes PDF | ReportLab (Python) o Puppeteer |
| Exportación Excel | openpyxl o xlsx |
| Alternativa simple | Python + Tkinter (desktop) |

---

## 14. ESTRUCTURA DE BASE DE DATOS PROPUESTA

### Tabla: `recicladores`
```sql
CREATE TABLE recicladores (
    id          INTEGER PRIMARY KEY,
    nombre      VARCHAR(150) NOT NULL,
    documento   VARCHAR(20) UNIQUE NOT NULL,
    telefono    VARCHAR(20),
    direccion   VARCHAR(200),
    municipio   VARCHAR(100),
    activo      BOOLEAN DEFAULT TRUE,
    placa       VARCHAR(10),
    ruta_lunes      TEXT,
    ruta_martes     TEXT,
    ruta_miercoles  TEXT,
    ruta_jueves     TEXT,
    ruta_viernes    TEXT,
    ruta_sabado     TEXT,
    ruta_domingo    TEXT
);
```

### Tabla: `materiales`
```sql
CREATE TABLE materiales (
    id          INTEGER PRIMARY KEY,
    codigo_sui  INTEGER NOT NULL,
    familia     VARCHAR(50),
    material    VARCHAR(100) NOT NULL,
    precio      DECIMAL(10,2) DEFAULT 0
);
```

### Tabla: `recibos`
```sql
CREATE TABLE recibos (
    id          INTEGER PRIMARY KEY,
    numero      INTEGER UNIQUE NOT NULL,
    fecha       DATE NOT NULL,
    reciclador_id INTEGER REFERENCES recicladores(id),
    subtotal    DECIMAL(12,2),
    retefuente  DECIMAL(12,2),
    total       DECIMAL(12,2),
    semana      INTEGER
);
```

### Tabla: `detalle_recibo` (balance de masa)
```sql
CREATE TABLE detalle_recibo (
    id              INTEGER PRIMARY KEY,
    recibo_id       INTEGER REFERENCES recibos(id),
    material_id     INTEGER REFERENCES materiales(id),
    kg_bruto        DECIMAL(10,3),
    kg_rechazado    DECIMAL(10,3),
    kg_neto         DECIMAL(10,3) GENERATED ALWAYS AS (kg_bruto - kg_rechazado),
    precio_kg       DECIMAL(10,2),
    total           DECIMAL(12,2),
    -- Campos SUI
    nueca           VARCHAR(20),
    numacro         VARCHAR(20),
    tipo_id_reciclador  INTEGER DEFAULT 1,
    placa_vehiculo  VARCHAR(10),
    tipo_sitio_destino  INTEGER DEFAULT 1,
    numero_sitio_destino VARCHAR(20)
);
```

---

*Documento generado automáticamente a partir del análisis del archivo `RECIBO ARECUPERAR 2026.xlsm`*
