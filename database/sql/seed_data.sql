-- =============================================================================
-- DATOS INICIALES DEL SISTEMA ARECUPERAR
-- Ejecutar DESPUÉS de schema.sql
-- =============================================================================

SET search_path TO reciclaje, public;

-- =============================================================================
-- TIPOS DE DOCUMENTO (Colombia)
-- =============================================================================
INSERT INTO tipos_documento (id, codigo, descripcion) VALUES
    (1, 'CC',  'Cédula de Ciudadanía'),
    (2, 'NIT', 'Número de Identificación Tributaria'),
    (3, 'CE',  'Cédula de Extranjería'),
    (4, 'PA',  'Pasaporte'),
    (5, 'TI',  'Tarjeta de Identidad'),
    (6, 'RC',  'Registro Civil'),
    (7, 'DE',  'Documento Extranjero');

-- =============================================================================
-- DÍAS DE LA SEMANA
-- =============================================================================
INSERT INTO dias_semana (id, nombre, abreviatura) VALUES
    (1, 'Lunes',     'LUN'),
    (2, 'Martes',    'MAR'),
    (3, 'Miércoles', 'MIE'),
    (4, 'Jueves',    'JUE'),
    (5, 'Viernes',   'VIE'),
    (6, 'Sábado',    'SAB'),
    (7, 'Domingo',   'DOM');

-- =============================================================================
-- TIPOS DE SITIO DE DESTINO (SUI)
-- =============================================================================
INSERT INTO tipos_sitio_destino (id, descripcion) VALUES
    (1, 'Estación de clasificación y aprovechamiento (ECA)'),
    (2, 'Planta de aprovechamiento'),
    (3, 'Relleno sanitario'),
    (4, 'Celda de seguridad'),
    (5, 'Bodega de almacenamiento');

-- =============================================================================
-- MUNICIPIOS
-- =============================================================================
INSERT INTO municipios (nombre, departamento, codigo_dane) VALUES
    ('Ocaña',        'Norte de Santander', '54498'),
    ('Ábrego',       'Norte de Santander', '54003'),
    ('Convención',   'Norte de Santander', '54206'),
    ('El Carmen',    'Norte de Santander', '54245'),
    ('La Playa',     'Norte de Santander', '54347'),
    ('San Calixto',  'Norte de Santander', '54670'),
    ('Teorama',      'Norte de Santander', '54800'),
    ('Hacarí',       'Norte de Santander', '54313');

-- =============================================================================
-- CONFIGURACIÓN DE LA EMPRESA
-- =============================================================================
INSERT INTO configuracion_empresa (clave, valor, descripcion) VALUES
    ('empresa_nombre',          'ARECUPERAR',       'Razón social de la empresa'),
    ('empresa_nit',             '900000000-0',      'NIT de la empresa'),
    ('empresa_municipio',       'Ocaña',            'Municipio sede'),
    ('empresa_departamento',    'Norte de Santander','Departamento sede'),
    ('empresa_telefono',        '',                 'Teléfono de contacto'),
    ('empresa_direccion',       '',                 'Dirección de la empresa'),
    ('nueca',                   '1286754498',       'Número Único de Establecimiento Comercial Autorizado (SUI)'),
    ('numacro',                 '449054498',        'Número de Macroruta asignado por la SSPD (SUI)'),
    ('numero_sitio_destino',    '206654498',        'Número único del sitio de destino del rechazo'),
    ('tipo_sitio_destino',      '1',                'Tipo de sitio de destino del rechazo (1=ECA)'),
    ('placa_sin_vehiculo',      '999',              'Código de placa para recicladores sin vehículo'),
    ('recibo_consecutivo',      '1',                'Próximo número de recibo a usar'),
    ('retefuente_porcentaje',   '0',                'Porcentaje de retención en la fuente (%)'),
    ('porcentaje_rechazo_defecto', '1',             'Porcentaje de rechazo por defecto si no se pesa (%)');

-- =============================================================================
-- VEHÍCULOS
-- =============================================================================
INSERT INTO vehiculos (placa, tipo) VALUES
    ('999',    'Sin vehículo / carreta manual'),
    ('ZOD786', 'Vehículo motorizado');

-- =============================================================================
-- FAMILIAS DE MATERIAL (con códigos SUI)
-- =============================================================================
INSERT INTO familias_material (nombre, codigo_sui) VALUES
    ('METALES',         100),
    ('PAPEL Y CARTÓN',  200),
    ('PLÁSTICOS',       300),
    ('VIDRIO',          400),
    ('RECHAZO',         700);

-- =============================================================================
-- MATERIALES (con precios del Excel original)
-- =============================================================================

-- METALES
WITH f AS (SELECT id FROM familias_material WHERE nombre = 'METALES')
INSERT INTO materiales (familia_id, codigo_sui, nombre, precio_actual) VALUES
    ((SELECT id FROM f), 101, 'ALUMINIO LATA',     7000),
    ((SELECT id FROM f), 101, 'ALUMINIO GRUESO',   5500),
    ((SELECT id FROM f), 101, 'ALUMINIO BLANDO',   5500),
    ((SELECT id FROM f), 101, 'ALUMINIO PERFIL',   7000),
    ((SELECT id FROM f), 101, 'ALUMINIO BANDA',    4000),
    ((SELECT id FROM f), 101, 'ALUMINIO GUAYA',    8000),
    ((SELECT id FROM f), 101, 'ALUMINIO RINES',    6500),
    ((SELECT id FROM f), 102, 'CHATARRA GRIS',     1200),
    ((SELECT id FROM f), 102, 'CHATARRA',           700),
    ((SELECT id FROM f), 103, 'COBRE #1',          34000),
    ((SELECT id FROM f), 103, 'COBRE #2',          33000),
    ((SELECT id FROM f), 104, 'BRONCE',            18000),
    ((SELECT id FROM f), 105, 'ANTIMONIO',          5500),
    ((SELECT id FROM f), 106, 'ACERO',              2800),
    ((SELECT id FROM f), 199, 'OTROS METALES',      5500);

-- PAPEL Y CARTÓN
WITH f AS (SELECT id FROM familias_material WHERE nombre = 'PAPEL Y CARTÓN')
INSERT INTO materiales (familia_id, codigo_sui, nombre, precio_actual) VALUES
    ((SELECT id FROM f), 201, 'ARCHIVO',                700),
    ((SELECT id FROM f), 202, 'CARTÓN',                 350),
    ((SELECT id FROM f), 203, 'CUBETAS O PANELES',       50),
    ((SELECT id FROM f), 204, 'PERIÓDICO',              200),
    ((SELECT id FROM f), 205, 'PLEGADIZA',              150),
    ((SELECT id FROM f), 206, 'TETRA PACK',             150),
    ((SELECT id FROM f), 207, 'PLASTIFICADO',             0),
    ((SELECT id FROM f), 208, 'KRAF',                     0),
    ((SELECT id FROM f), 299, 'OTROS PAPEL Y CARTÓN',   350);

-- PLÁSTICOS
WITH f AS (SELECT id FROM familias_material WHERE nombre = 'PLÁSTICOS')
INSERT INTO materiales (familia_id, codigo_sui, nombre, precio_actual) VALUES
    ((SELECT id FROM f), 301, 'ACRÍLICO',              0),
    ((SELECT id FROM f), 302, 'PASTA',               800),
    ((SELECT id FROM f), 302, 'PASTA NEGRA',         700),
    ((SELECT id FROM f), 302, 'PASTA SILLA',        1500),
    ((SELECT id FROM f), 302, 'PASTA LAVADORA',     1400),
    ((SELECT id FROM f), 303, 'PET TRANSPARENTE',   1000),
    ((SELECT id FROM f), 303, 'PET AMBAR',           300),
    ((SELECT id FROM f), 303, 'PET VERDE',           300),
    ((SELECT id FROM f), 303, 'PET ACEITE',          150),
    ((SELECT id FROM f), 303, 'PET REVUELTO',        500),
    ((SELECT id FROM f), 304, 'PVC TUBO',            300),
    ((SELECT id FROM f), 304, 'PVC LAMINA',          200),
    ((SELECT id FROM f), 305, 'BOLSA',               600),
    ((SELECT id FROM f), 306, 'MANGUERA',            800),
    ((SELECT id FROM f), 307, 'SOPLADO',             700),
    ((SELECT id FROM f), 308, 'POLIPROPILENO',         0),
    ((SELECT id FROM f), 399, 'OTROS PLÁSTICOS',       0);

-- VIDRIO
WITH f AS (SELECT id FROM familias_material WHERE nombre = 'VIDRIO')
INSERT INTO materiales (familia_id, codigo_sui, nombre, precio_actual) VALUES
    ((SELECT id FROM f), 499, 'OTROS VIDRIOS', 50);

-- RECHAZO
WITH f AS (SELECT id FROM familias_material WHERE nombre = 'RECHAZO')
INSERT INTO materiales (familia_id, codigo_sui, nombre, precio_actual) VALUES
    ((SELECT id FROM f), 799, 'RECHAZO', 0);

-- =============================================================================
-- HISTORIAL INICIAL DE PRECIOS (precio de arranque)
-- =============================================================================
INSERT INTO historial_precios (material_id, precio, vigente_desde)
SELECT id, precio_actual, CURRENT_DATE
FROM materiales
WHERE precio_actual > 0;

-- =============================================================================
-- RECICLADORES (importados del Excel - muestra representativa)
-- =============================================================================
DO $$
DECLARE
    v_ocana_id INTEGER;
    v_veh_999  INTEGER;
    v_veh_zod  INTEGER;
BEGIN
    SELECT id INTO v_ocana_id FROM municipios WHERE nombre = 'Ocaña';
    SELECT id INTO v_veh_999  FROM vehiculos WHERE placa = '999';
    SELECT id INTO v_veh_zod  FROM vehiculos WHERE placa = 'ZOD786';

    INSERT INTO recicladores
        (tipo_documento_id, documento, nombre, telefono, direccion, municipio_id, vehiculo_id, numero_sitio_destino, tipo_sitio_destino_id)
    VALUES
        (1,'5528028',   'ALEJO PÉREZ PÉREZ',                    '3204433879', 'KDX 284-991 Barrio Junín',           v_ocana_id, v_veh_999, '206654498', 1),
        (1,'12520822',  'ALFONSO PABÓN GUERRERO',               NULL,         'KDX 217-307 Brr. El Dorado',         v_ocana_id, v_veh_999, '206654498', 1),
        (1,'88141122',  'ALIRIO ALBERTO PABÓN',                 '3167291735', 'KDX 088-580 Brr. Las Colinas',       v_ocana_id, v_veh_999, '206654498', 1),
        (1,'13357815',  'ALVARO DE JESUS PICON MANTILLA',       NULL,         'Barrio Simon Bolivar',               NULL,       v_veh_999, '206654498', 1),
        (1,'27764436',  'ANTONIA QUINTERO CALDERÓN',            '3175597912', 'Brr. Simón Bolívar Parte Baja',      v_ocana_id, v_veh_999, '206654498', 1),
        (1,'6227271',   'ARNOLDO CELESTINO HERRERA SÁNCHEZ',    '3180215040', 'Brr la perla',                       v_ocana_id, v_veh_999, '206654498', 1),
        (1,'13084631',  'BERTHA YADIRA COLMENARES ARROLLO',     '3212675643', 'Brr la perla',                       v_ocana_id, v_veh_999, '206654498', 1),
        (1,'5974496',   'BRITHEY ESMERALDA GUTIERREZ VERA',     '3203218346', 'Hatillo - Transparencia 1',          v_ocana_id, v_veh_999, '206654498', 1),
        (1,'5084647',   'CARLOS ARTURO MEJÍA ROJAS',            '3125096017', 'KDX 906-360 Brr. El Dorado',         v_ocana_id, v_veh_999, '206654498', 1),
        (1,'88281105',  'CARLOS VICENTE MARTÍNEZ',              '3184091938', 'Cra. 10A-32 Gustavo Alayon',         NULL,       v_veh_999, '206654498', 1),
        (1,'37317139',  'CARMEN CECILIA MEZA ASCANIO',          '3227226362', 'KDX 909-120 Brr. El Dorado',         v_ocana_id, v_veh_999, '206654498', 1),
        (1,'37318870',  'CARMEN OLINTA MELO CLARO',             '3138370065', 'KDX 114-025 Brr. El Carmen',         NULL,       v_veh_999, '206654498', 1),
        (1,'1004864110','CARMENZA RINCON PACHECO',              '3184592781', 'KDX 265-240 Brr. Los Fundadores',    v_ocana_id, v_veh_999, '206654498', 1),
        (1,'5470631',   'CRISTO FERNANDO AMAYA JULIO',          '3222863338', 'Brr el peñon',                       v_ocana_id, v_veh_zod, '206654498', 1),
        (1,'27745768',  'DELMIRA BALAGUERA',                    '3170935963', NULL,                                 NULL,       v_veh_999, '206654498', 1),
        (1,'1091679067','DENIER ELIECER CARRASCAL SÁNCHEZ',     '3218161229', 'CALL 13 # 24A-14 B. EL PEÑON',       v_ocana_id, v_veh_999, '206654498', 1),
        (1,'1064840915','DIANA MILEIDY PUENTE CABALLERO',       '3227883197', 'BR. LA PERLA',                       v_ocana_id, v_veh_999, '206654498', 1),
        (1,'5975221',   'EDUARDO JOSÉ BASTIDAS',                '3134663843', 'Hatillo - Transparencia 1',          NULL,       v_veh_999, '206654498', 1),
        (1,'22828163',  'ESMERALDA VERA DÍAZ',                  '3203167834', 'Brr. Juan XXIII',                    v_ocana_id, v_veh_999, '206654498', 1),
        (1,'88142844',  'EUCLIDES BAUTISTA BAYONA',             '3157863157', NULL,                                 NULL,       v_veh_999, '206654498', 1),
        (1,'5459799',   'EUSEBIO BAYONA GUERERO',               '3157863157', 'KDX 295-390 Brr. Simón Bolívar',     v_ocana_id, v_veh_999, '206654498', 1),
        (1,'13177382',  'FABIO DANILO PALLARES CARRASCAL',      '3242592568', 'KDX 112-350 Brr. El Carmen',         v_ocana_id, v_veh_999, '206654498', 1),
        (1,'1757979',   'FRANCISCO ANTONIO ILLERA BARBOSA',     '3124722539', 'BR. JUAN 23',                        v_ocana_id, v_veh_999, '206654498', 1),
        (1,'6795346',   'FREDDY REYES',                         '3134644769', 'KDX 278-260 Brr. Juan XXIII',        v_ocana_id, v_veh_999, '206654498', 1),
        (1,'13360501',  'GABRIEL ANGEL PEÑA TRILLOS',           NULL,         'KDX 558-535 Brr. El Carmen',         v_ocana_id, v_veh_999, '206654498', 1),
        (1,'1148441078','GERMÁN ALONSO PÁEZ TARAZONA',          '3163214416', 'KDX 311-130 Brr. Simón Bolívar',     v_ocana_id, v_veh_999, '206654498', 1),
        (1,'13363957',  'HERMES ANTONIO QUINTERO',              '3202965397', 'KDX 558-389 Brr. El Carmen',         v_ocana_id, v_veh_999, '206654498', 1),
        (1,'88144580',  'JAIRO ALFONSO RUEDAS',                 '3124875596', 'KDX 405-300 Brr. Libardo Alonso',    v_ocana_id, v_veh_999, '206654498', 1),
        (1,'88135539',  'JAIRO GALEANO PRADA',                  '3184667077', 'Cra.28 D 9-29 Barrio el Carmen',     v_ocana_id, v_veh_999, '206654498', 1),
        (1,'37314486',  'MARIA ESPERANZA PACHECO',              '3167291735', 'KDX 088-580 Brr. Colina del Tanque', v_ocana_id, v_veh_999, '206654498', 1);
END $$;

-- =============================================================================
-- RUTAS DE RECICLADORES (muestra)
-- =============================================================================
DO $$
DECLARE
    v_id INTEGER;
BEGIN
    -- ALEJO PÉREZ PÉREZ
    SELECT id INTO v_id FROM recicladores WHERE documento = '5528028';
    INSERT INTO rutas_reciclador (reciclador_id, dia_id, descripcion) VALUES
        (v_id, 1, 'no trabaja'),
        (v_id, 2, 'junin, san francisco, centro, santa cruz, escuela concentracion IV centenario, Mercado'),
        (v_id, 3, 'NO TRABAJA'),
        (v_id, 4, 'junin, san francisco, centro, santa cruz, escuela concentracion IV centenario, Mercado'),
        (v_id, 5, 'NO TRABAJA'),
        (v_id, 6, 'junin, san francisco, centro, santa cruz, escuela concentracion IV centenario, Mercado');

    -- ALIRIO ALBERTO PABÓN
    SELECT id INTO v_id FROM recicladores WHERE documento = '88141122';
    INSERT INTO rutas_reciclador (reciclador_id, dia_id, descripcion) VALUES
        (v_id, 1, 'bruselas, peñon'),
        (v_id, 2, 'cristo rey, circunvalar'),
        (v_id, 3, 'comuneros'),
        (v_id, 4, 'bruselas, peñon'),
        (v_id, 5, 'cristo rey, circunvalar'),
        (v_id, 6, 'no trabaja');
END $$;

-- =============================================================================
-- MENSAJE DE CONFIRMACIÓN
-- =============================================================================
DO $$
BEGIN
    RAISE NOTICE '✅ Datos iniciales cargados correctamente en el schema reciclaje';
    RAISE NOTICE '   - % tipos de documento', (SELECT COUNT(*) FROM tipos_documento);
    RAISE NOTICE '   - % municipios',         (SELECT COUNT(*) FROM municipios);
    RAISE NOTICE '   - % materiales',         (SELECT COUNT(*) FROM materiales);
    RAISE NOTICE '   - % recicladores',       (SELECT COUNT(*) FROM recicladores);
END $$;
