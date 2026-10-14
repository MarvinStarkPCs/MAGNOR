-- =============================================================================
-- SISTEMA ARECUPERAR - GESTIÓN DE RECICLADORES
-- Base de datos: PostgreSQL 14+
-- =============================================================================

-- Extensiones
CREATE EXTENSION IF NOT EXISTS "pgcrypto";
CREATE EXTENSION IF NOT EXISTS "unaccent";

-- =============================================================================
-- SCHEMA PRINCIPAL
-- =============================================================================
CREATE SCHEMA IF NOT EXISTS reciclaje;
SET search_path TO reciclaje, public;

-- =============================================================================
-- TABLAS DE CATÁLOGO / LOOKUP
-- =============================================================================

CREATE TABLE municipios (
    id          SERIAL PRIMARY KEY,
    nombre      VARCHAR(100) NOT NULL,
    departamento VARCHAR(100) NOT NULL DEFAULT 'Norte de Santander',
    codigo_dane VARCHAR(8),
    activo      BOOLEAN NOT NULL DEFAULT TRUE,
    CONSTRAINT uq_municipio_nombre UNIQUE (nombre, departamento)
);
COMMENT ON TABLE municipios IS 'Catálogo de municipios para recicladores y rutas';

-- -----------------------------------------------------------------------------

CREATE TABLE tipos_documento (
    id          SMALLINT PRIMARY KEY,
    codigo      VARCHAR(5) NOT NULL UNIQUE,   -- CC, NIT, CE, PA, TI
    descripcion VARCHAR(80) NOT NULL
);
COMMENT ON TABLE tipos_documento IS 'Tipos de documento de identidad válidos en Colombia (códigos SUI)';

-- -----------------------------------------------------------------------------

CREATE TABLE dias_semana (
    id          SMALLINT PRIMARY KEY,          -- 1=Lunes … 7=Domingo
    nombre      VARCHAR(12) NOT NULL UNIQUE,
    abreviatura CHAR(3) NOT NULL UNIQUE
);
COMMENT ON TABLE dias_semana IS 'Días de la semana para asignación de rutas';

-- -----------------------------------------------------------------------------

CREATE TABLE tipos_sitio_destino (
    id          SMALLINT PRIMARY KEY,
    descripcion VARCHAR(100) NOT NULL
);
COMMENT ON TABLE tipos_sitio_destino IS 'Tipos de sitio de destino del rechazo según SUI';

-- =============================================================================
-- FAMILIAS Y MATERIALES
-- =============================================================================

CREATE TABLE familias_material (
    id          SERIAL PRIMARY KEY,
    nombre      VARCHAR(60) NOT NULL UNIQUE,
    codigo_sui  SMALLINT,                       -- rango base SUI: 100, 200, 300…
    activo      BOOLEAN NOT NULL DEFAULT TRUE
);
COMMENT ON TABLE familias_material IS 'Clasificación de materiales por familia (SUI)';

-- -----------------------------------------------------------------------------

CREATE TABLE materiales (
    id              SERIAL PRIMARY KEY,
    familia_id      INTEGER NOT NULL REFERENCES familias_material(id),
    codigo_sui      SMALLINT NOT NULL,           -- código SUI exacto del material
    nombre          VARCHAR(100) NOT NULL,
    precio_actual   NUMERIC(10,2) NOT NULL DEFAULT 0 CHECK (precio_actual >= 0),
    activo          BOOLEAN NOT NULL DEFAULT TRUE,
    creado_en       TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    CONSTRAINT uq_material_nombre UNIQUE (nombre)
);
CREATE INDEX idx_materiales_familia ON materiales(familia_id);
CREATE INDEX idx_materiales_codigo_sui ON materiales(codigo_sui);
COMMENT ON TABLE materiales IS 'Catálogo de materiales reciclables con precio vigente';

-- -----------------------------------------------------------------------------

CREATE TABLE historial_precios (
    id              SERIAL PRIMARY KEY,
    material_id     INTEGER NOT NULL REFERENCES materiales(id),
    precio          NUMERIC(10,2) NOT NULL CHECK (precio >= 0),
    vigente_desde   DATE NOT NULL DEFAULT CURRENT_DATE,
    vigente_hasta   DATE,                        -- NULL = precio vigente actual
    registrado_por  VARCHAR(100),
    CONSTRAINT chk_vigencia CHECK (vigente_hasta IS NULL OR vigente_hasta > vigente_desde)
);
CREATE INDEX idx_historial_precios_material ON historial_precios(material_id, vigente_desde DESC);
COMMENT ON TABLE historial_precios IS 'Trazabilidad de cambios de precio por material';

-- =============================================================================
-- CONFIGURACIÓN DE LA EMPRESA
-- =============================================================================

CREATE TABLE configuracion_empresa (
    clave       VARCHAR(60) PRIMARY KEY,
    valor       TEXT NOT NULL,
    descripcion TEXT,
    actualizado_en TIMESTAMPTZ NOT NULL DEFAULT NOW()
);
COMMENT ON TABLE configuracion_empresa IS 'Parámetros globales de la empresa (NUECA, NUMACRO, etc.)';

-- =============================================================================
-- VEHÍCULOS
-- =============================================================================

CREATE TABLE vehiculos (
    id          SERIAL PRIMARY KEY,
    placa       VARCHAR(10) NOT NULL UNIQUE,
    tipo        VARCHAR(50),                     -- bicicleta, carreta, moto, camión
    activo      BOOLEAN NOT NULL DEFAULT TRUE
);
COMMENT ON TABLE vehiculos IS 'Vehículos usados por recicladores para recolección';

-- =============================================================================
-- RECICLADORES
-- =============================================================================

CREATE TABLE recicladores (
    id                  SERIAL PRIMARY KEY,
    tipo_documento_id   SMALLINT NOT NULL REFERENCES tipos_documento(id) DEFAULT 1,
    documento           VARCHAR(20) NOT NULL,
    nombre              VARCHAR(150) NOT NULL,
    telefono            VARCHAR(20),
    direccion           VARCHAR(250),
    municipio_id        INTEGER REFERENCES municipios(id),
    activo              BOOLEAN NOT NULL DEFAULT TRUE,
    vehiculo_id         INTEGER REFERENCES vehiculos(id),
    numero_sitio_destino VARCHAR(20),            -- código SUI del sitio donde entrega el rechazo
    tipo_sitio_destino_id SMALLINT REFERENCES tipos_sitio_destino(id),
    creado_en           TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    actualizado_en      TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    CONSTRAINT uq_reciclador_documento UNIQUE (tipo_documento_id, documento)
);
CREATE INDEX idx_recicladores_documento ON recicladores(documento);
CREATE INDEX idx_recicladores_nombre    ON recicladores USING GIN (to_tsvector('spanish', unaccent(nombre)));
CREATE INDEX idx_recicladores_activo    ON recicladores(activo) WHERE activo = TRUE;
COMMENT ON TABLE recicladores IS 'Recicladores de oficio registrados en ARECUPERAR';

-- -----------------------------------------------------------------------------

CREATE TABLE rutas_reciclador (
    id              SERIAL PRIMARY KEY,
    reciclador_id   INTEGER NOT NULL REFERENCES recicladores(id) ON DELETE CASCADE,
    dia_id          SMALLINT NOT NULL REFERENCES dias_semana(id),
    descripcion     TEXT,                        -- barrios / sectores cubiertos
    CONSTRAINT uq_ruta_dia UNIQUE (reciclador_id, dia_id)
);
COMMENT ON TABLE rutas_reciclador IS 'Rutas de recolección asignadas por reciclador y día de la semana';

-- =============================================================================
-- RECIBOS (CABECERA)
-- =============================================================================

CREATE TABLE recibos (
    id              SERIAL PRIMARY KEY,
    numero          INTEGER NOT NULL,            -- consecutivo de negocio (visible)
    fecha           DATE NOT NULL DEFAULT CURRENT_DATE,
    reciclador_id   INTEGER NOT NULL REFERENCES recicladores(id),
    semana          SMALLINT NOT NULL GENERATED ALWAYS AS (EXTRACT(WEEK FROM fecha)::SMALLINT) STORED,
    anio            SMALLINT NOT NULL GENERATED ALWAYS AS (EXTRACT(YEAR FROM fecha)::SMALLINT) STORED,
    subtotal        NUMERIC(14,2) NOT NULL DEFAULT 0,
    retefuente      NUMERIC(14,2) NOT NULL DEFAULT 0 CHECK (retefuente >= 0),
    total           NUMERIC(14,2) NOT NULL GENERATED ALWAYS AS (subtotal - retefuente) STORED,
    observaciones   TEXT,
    anulado         BOOLEAN NOT NULL DEFAULT FALSE,
    anulado_en      TIMESTAMPTZ,
    anulado_motivo  TEXT,
    creado_en       TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    actualizado_en  TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    CONSTRAINT uq_recibo_numero UNIQUE (numero)
);
CREATE INDEX idx_recibos_reciclador ON recibos(reciclador_id);
CREATE INDEX idx_recibos_fecha      ON recibos(fecha DESC);
CREATE INDEX idx_recibos_semana     ON recibos(anio, semana);
COMMENT ON TABLE recibos IS 'Cabecera del recibo de compra de materiales reciclables';

-- =============================================================================
-- DETALLE DEL RECIBO (LÍNEAS - BALANCE DE MASA)
-- =============================================================================

CREATE TABLE detalle_recibo (
    id                  SERIAL PRIMARY KEY,
    recibo_id           INTEGER NOT NULL REFERENCES recibos(id) ON DELETE CASCADE,
    material_id         INTEGER NOT NULL REFERENCES materiales(id),
    kg_bruto            NUMERIC(10,3) NOT NULL CHECK (kg_bruto > 0),
    kg_rechazado        NUMERIC(10,3) NOT NULL DEFAULT 0 CHECK (kg_rechazado >= 0),
    kg_neto             NUMERIC(10,3) NOT NULL GENERATED ALWAYS AS (kg_bruto - kg_rechazado) STORED,
    precio_kg           NUMERIC(10,2) NOT NULL CHECK (precio_kg >= 0),
    subtotal            NUMERIC(14,2) NOT NULL GENERATED ALWAYS AS (ROUND(kg_bruto * precio_kg, 2)) STORED,

    -- Campos SUI (balance de masa para reporte)
    cantidad_entrante_ton NUMERIC(12,6) NOT NULL GENERATED ALWAYS AS (ROUND(kg_bruto / 1000.0, 6)) STORED,
    rechazo_ton           NUMERIC(12,6) NOT NULL GENERATED ALWAYS AS (ROUND(kg_rechazado / 1000.0, 6)) STORED,

    CONSTRAINT chk_rechazo_menor_bruto CHECK (kg_rechazado <= kg_bruto)
);
CREATE INDEX idx_detalle_recibo_recibo   ON detalle_recibo(recibo_id);
CREATE INDEX idx_detalle_recibo_material ON detalle_recibo(material_id);
COMMENT ON TABLE detalle_recibo IS 'Líneas del recibo: un registro por material. Contiene el balance de masa KG bruto / rechazado / neto';

-- =============================================================================
-- SNAPSHOTS SUI (EXPORTACIÓN REGULATORIA)
-- =============================================================================

CREATE TABLE exportaciones_sui (
    id              SERIAL PRIMARY KEY,
    periodo_inicio  DATE NOT NULL,
    periodo_fin     DATE NOT NULL,
    generado_en     TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    generado_por    VARCHAR(100),
    archivo_nombre  VARCHAR(200),
    total_registros INTEGER,
    total_kg_neto   NUMERIC(14,3),
    estado          VARCHAR(20) NOT NULL DEFAULT 'PENDIENTE'  -- PENDIENTE, ENVIADO, ERROR
);
COMMENT ON TABLE exportaciones_sui IS 'Historial de archivos de reporte generados para el SUI';

-- -----------------------------------------------------------------------------

CREATE TABLE detalle_exportacion_sui (
    id                      SERIAL PRIMARY KEY,
    exportacion_id          INTEGER NOT NULL REFERENCES exportaciones_sui(id) ON DELETE CASCADE,
    detalle_recibo_id       INTEGER NOT NULL REFERENCES detalle_recibo(id),

    -- Campos exactos del formato SUI
    nueca                   VARCHAR(20) NOT NULL,
    numacro                 VARCHAR(20) NOT NULL,
    tipo_id_reciclador      SMALLINT NOT NULL,
    numero_id_reciclador    VARCHAR(20) NOT NULL,
    placa_vehiculo          VARCHAR(10),
    cantidad_material_ton   NUMERIC(12,6) NOT NULL,
    tipo_material_sui       SMALLINT NOT NULL,      -- código SUI del material
    rechazo_ton             NUMERIC(12,6) NOT NULL,
    tipo_sitio_destino      SMALLINT,
    numero_sitio_destino    VARCHAR(20),

    CONSTRAINT uq_detalle_sui UNIQUE (exportacion_id, detalle_recibo_id)
);
COMMENT ON TABLE detalle_exportacion_sui IS 'Registro fila a fila del archivo SUI exportado';

-- =============================================================================
-- AUDITORÍA
-- =============================================================================

CREATE TABLE auditoria (
    id              BIGSERIAL PRIMARY KEY,
    tabla           VARCHAR(60) NOT NULL,
    operacion       CHAR(1) NOT NULL,            -- I=INSERT, U=UPDATE, D=DELETE
    registro_id     INTEGER,
    datos_antes     JSONB,
    datos_despues   JSONB,
    usuario         VARCHAR(100),
    ip              INET,
    registrado_en   TIMESTAMPTZ NOT NULL DEFAULT NOW()
);
CREATE INDEX idx_auditoria_tabla ON auditoria(tabla, registrado_en DESC);
COMMENT ON TABLE auditoria IS 'Trazabilidad de cambios críticos en el sistema';

-- =============================================================================
-- FUNCIONES Y TRIGGERS
-- =============================================================================

-- Actualizar campo actualizado_en automáticamente
CREATE OR REPLACE FUNCTION fn_set_actualizado_en()
RETURNS TRIGGER LANGUAGE plpgsql AS $$
BEGIN
    NEW.actualizado_en = NOW();
    RETURN NEW;
END;
$$;

CREATE TRIGGER trg_recicladores_actualizado_en
    BEFORE UPDATE ON recicladores
    FOR EACH ROW EXECUTE FUNCTION fn_set_actualizado_en();

CREATE TRIGGER trg_recibos_actualizado_en
    BEFORE UPDATE ON recibos
    FOR EACH ROW EXECUTE FUNCTION fn_set_actualizado_en();

-- -----------------------------------------------------------------------------
-- Recalcular subtotal del recibo cuando cambian sus líneas
CREATE OR REPLACE FUNCTION fn_recalcular_subtotal_recibo()
RETURNS TRIGGER LANGUAGE plpgsql AS $$
DECLARE
    v_recibo_id INTEGER;
BEGIN
    v_recibo_id := COALESCE(NEW.recibo_id, OLD.recibo_id);

    UPDATE recibos
    SET subtotal = (
        SELECT COALESCE(SUM(subtotal), 0)
        FROM detalle_recibo
        WHERE recibo_id = v_recibo_id
    )
    WHERE id = v_recibo_id;

    RETURN NULL;
END;
$$;

CREATE TRIGGER trg_detalle_recalc_subtotal
    AFTER INSERT OR UPDATE OR DELETE ON detalle_recibo
    FOR EACH ROW EXECUTE FUNCTION fn_recalcular_subtotal_recibo();

-- -----------------------------------------------------------------------------
-- Registrar precio en historial cuando cambia el precio de un material
CREATE OR REPLACE FUNCTION fn_historial_precio_material()
RETURNS TRIGGER LANGUAGE plpgsql AS $$
BEGIN
    IF OLD.precio_actual IS DISTINCT FROM NEW.precio_actual THEN
        -- Cerrar precio anterior
        UPDATE historial_precios
        SET vigente_hasta = CURRENT_DATE - 1
        WHERE material_id = NEW.id AND vigente_hasta IS NULL;

        -- Insertar nuevo precio
        INSERT INTO historial_precios (material_id, precio, vigente_desde)
        VALUES (NEW.id, NEW.precio_actual, CURRENT_DATE);
    END IF;
    RETURN NEW;
END;
$$;

CREATE TRIGGER trg_material_historial_precio
    AFTER UPDATE OF precio_actual ON materiales
    FOR EACH ROW EXECUTE FUNCTION fn_historial_precio_material();

-- =============================================================================
-- ÍNDICES ADICIONALES DE RENDIMIENTO
-- =============================================================================

-- Búsquedas frecuentes en reportes
CREATE INDEX idx_recibos_anulado      ON recibos(anulado) WHERE anulado = FALSE;
CREATE INDEX idx_detalle_kg_neto      ON detalle_recibo(recibo_id, kg_neto);
CREATE INDEX idx_materiales_activo    ON materiales(activo) WHERE activo = TRUE;
CREATE INDEX idx_exportaciones_estado ON exportaciones_sui(estado);
