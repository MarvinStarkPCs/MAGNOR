-- =============================================================================
-- VISTAS DEL SISTEMA ARECUPERAR
-- Ejecutar DESPUÉS de schema.sql y seed_data.sql
-- =============================================================================

SET search_path TO reciclaje, public;

-- =============================================================================
-- VISTA: RECIBOS COMPLETOS (cabecera + reciclador)
-- =============================================================================
CREATE OR REPLACE VIEW v_recibos AS
SELECT
    r.id,
    r.numero,
    r.fecha,
    r.semana,
    r.anio,
    rc.id           AS reciclador_id,
    rc.nombre       AS reciclador,
    rc.documento,
    td.codigo       AS tipo_documento,
    m.nombre        AS municipio,
    r.subtotal,
    r.retefuente,
    r.total,
    r.anulado,
    r.observaciones,
    r.creado_en,
    (SELECT COUNT(*) FROM detalle_recibo d WHERE d.recibo_id = r.id) AS total_items,
    (SELECT COALESCE(SUM(d.kg_bruto), 0) FROM detalle_recibo d WHERE d.recibo_id = r.id) AS total_kg_bruto,
    (SELECT COALESCE(SUM(d.kg_neto),  0) FROM detalle_recibo d WHERE d.recibo_id = r.id) AS total_kg_neto
FROM recibos r
JOIN recicladores rc  ON rc.id = r.reciclador_id
JOIN tipos_documento td ON td.id = rc.tipo_documento_id
LEFT JOIN municipios m ON m.id = rc.municipio_id;

COMMENT ON VIEW v_recibos IS 'Cabecera del recibo enriquecida con datos del reciclador';

-- =============================================================================
-- VISTA: DETALLE DE RECIBOS COMPLETO
-- =============================================================================
CREATE OR REPLACE VIEW v_detalle_recibos AS
SELECT
    dr.id,
    r.numero        AS numero_recibo,
    r.fecha,
    r.semana,
    r.anio,
    rc.nombre       AS reciclador,
    rc.documento,
    m.nombre        AS material,
    f.nombre        AS familia,
    m.codigo_sui,
    dr.kg_bruto,
    dr.kg_rechazado,
    dr.kg_neto,
    dr.precio_kg,
    dr.subtotal,
    dr.cantidad_entrante_ton,
    dr.rechazo_ton,
    r.anulado
FROM detalle_recibo dr
JOIN recibos      r  ON r.id  = dr.recibo_id
JOIN recicladores rc ON rc.id = r.reciclador_id
JOIN materiales   m  ON m.id  = dr.material_id
JOIN familias_material f ON f.id = m.familia_id;

COMMENT ON VIEW v_detalle_recibos IS 'Líneas de recibo con todos los campos del balance de masa';

-- =============================================================================
-- VISTA: DASHBOARD - RESUMEN POR MES
-- =============================================================================
CREATE OR REPLACE VIEW v_resumen_mensual AS
SELECT
    r.anio,
    EXTRACT(MONTH FROM r.fecha)::SMALLINT AS mes,
    TO_CHAR(r.fecha, 'YYYY-MM') AS periodo,
    COUNT(DISTINCT r.id)                    AS total_recibos,
    COUNT(DISTINCT r.reciclador_id)         AS total_recicladores,
    COALESCE(SUM(d.kg_bruto),    0)         AS total_kg_bruto,
    COALESCE(SUM(d.kg_rechazado),0)         AS total_kg_rechazado,
    COALESCE(SUM(d.kg_neto),     0)         AS total_kg_neto,
    COALESCE(SUM(r.total),       0)         AS total_pagado,
    ROUND(COALESCE(SUM(d.kg_rechazado),0) / NULLIF(SUM(d.kg_bruto),0) * 100, 2) AS pct_rechazo
FROM recibos r
JOIN detalle_recibo d ON d.recibo_id = r.id
WHERE r.anulado = FALSE
GROUP BY r.anio, mes, periodo
ORDER BY r.anio DESC, mes DESC;

COMMENT ON VIEW v_resumen_mensual IS 'KPIs del dashboard agregados por mes';

-- =============================================================================
-- VISTA: DASHBOARD - RESUMEN POR SEMANA
-- =============================================================================
CREATE OR REPLACE VIEW v_resumen_semanal AS
SELECT
    r.anio,
    r.semana,
    COUNT(DISTINCT r.id)                    AS total_recibos,
    COUNT(DISTINCT r.reciclador_id)         AS total_recicladores,
    COALESCE(SUM(d.kg_bruto),    0)         AS total_kg_bruto,
    COALESCE(SUM(d.kg_rechazado),0)         AS total_kg_rechazado,
    COALESCE(SUM(d.kg_neto),     0)         AS total_kg_neto,
    COALESCE(SUM(r.total),       0)         AS total_pagado,
    ROUND(COALESCE(SUM(d.kg_rechazado),0) / NULLIF(SUM(d.kg_bruto),0) * 100, 2) AS pct_rechazo
FROM recibos r
JOIN detalle_recibo d ON d.recibo_id = r.id
WHERE r.anulado = FALSE
GROUP BY r.anio, r.semana
ORDER BY r.anio DESC, r.semana DESC;

-- =============================================================================
-- VISTA: TOP RECICLADORES
-- =============================================================================
CREATE OR REPLACE VIEW v_top_recicladores AS
SELECT
    rc.id,
    rc.nombre,
    rc.documento,
    COUNT(DISTINCT r.id)             AS total_recibos,
    COALESCE(SUM(d.kg_bruto),    0)  AS total_kg_bruto,
    COALESCE(SUM(d.kg_neto),     0)  AS total_kg_neto,
    COALESCE(SUM(r.total),       0)  AS total_pagado,
    MAX(r.fecha)                     AS ultimo_recibo,
    RANK() OVER (ORDER BY SUM(r.total) DESC NULLS LAST) AS ranking_pagado,
    RANK() OVER (ORDER BY SUM(d.kg_neto) DESC NULLS LAST) AS ranking_kg
FROM recicladores rc
LEFT JOIN recibos r       ON r.reciclador_id = rc.id AND r.anulado = FALSE
LEFT JOIN detalle_recibo d ON d.recibo_id = r.id
WHERE rc.activo = TRUE
GROUP BY rc.id, rc.nombre, rc.documento
ORDER BY total_pagado DESC;

COMMENT ON VIEW v_top_recicladores IS 'Ranking de recicladores por KG y por total pagado (año en curso)';

-- =============================================================================
-- VISTA: MATERIALES MÁS RECIBIDOS
-- =============================================================================
CREATE OR REPLACE VIEW v_top_materiales AS
SELECT
    m.id,
    m.nombre        AS material,
    f.nombre        AS familia,
    m.codigo_sui,
    m.precio_actual,
    COUNT(DISTINCT r.id)             AS total_recibos,
    COALESCE(SUM(d.kg_bruto),    0)  AS total_kg_bruto,
    COALESCE(SUM(d.kg_neto),     0)  AS total_kg_neto,
    COALESCE(SUM(d.subtotal),    0)  AS total_pagado,
    ROUND(COALESCE(SUM(d.kg_rechazado),0) / NULLIF(SUM(d.kg_bruto),0) * 100, 2) AS pct_rechazo_promedio
FROM materiales m
JOIN familias_material f ON f.id = m.familia_id
LEFT JOIN detalle_recibo d ON d.material_id = m.id
LEFT JOIN recibos r       ON r.id = d.recibo_id AND r.anulado = FALSE
WHERE m.activo = TRUE
GROUP BY m.id, m.nombre, f.nombre, m.codigo_sui, m.precio_actual
ORDER BY total_kg_neto DESC;

COMMENT ON VIEW v_top_materiales IS 'Ranking de materiales por volumen y valor';

-- =============================================================================
-- VISTA: EXPORTACIÓN SUI (formato plano para reporte)
-- =============================================================================
CREATE OR REPLACE VIEW v_exportacion_sui AS
SELECT
    r.numero                                        AS recibo,
    r.fecha,
    rc.nombre                                       AS reciclador,
    td.id                                           AS tipo_id_reciclador,
    rc.documento                                    AS numero_id_reciclador,
    COALESCE(v.placa, cfg_placa.valor)              AS placa_vehiculo,
    (SELECT valor FROM configuracion_empresa WHERE clave = 'nueca')    AS nueca,
    (SELECT valor FROM configuracion_empresa WHERE clave = 'numacro')  AS numacro,
    m.codigo_sui                                    AS tipo_material_entrante,
    dr.cantidad_entrante_ton                        AS cantidad_material_entrante_ton,
    dr.rechazo_ton                                  AS rechazo_ton,
    COALESCE(rc.tipo_sitio_destino_id, 1)           AS tipo_sitio_destino,
    COALESCE(rc.numero_sitio_destino,
        (SELECT valor FROM configuracion_empresa WHERE clave = 'numero_sitio_destino'))
                                                    AS numero_sitio_destino,
    r.semana,
    dr.kg_bruto,
    dr.kg_rechazado,
    dr.kg_neto,
    dr.precio_kg,
    dr.subtotal
FROM detalle_recibo dr
JOIN recibos           r   ON r.id   = dr.recibo_id
JOIN recicladores      rc  ON rc.id  = r.reciclador_id
JOIN tipos_documento   td  ON td.id  = rc.tipo_documento_id
JOIN materiales        m   ON m.id   = dr.material_id
LEFT JOIN vehiculos    v   ON v.id   = rc.vehiculo_id
CROSS JOIN LATERAL (
    SELECT valor FROM configuracion_empresa WHERE clave = 'placa_sin_vehiculo'
) cfg_placa
WHERE r.anulado = FALSE
ORDER BY r.fecha, r.numero;

COMMENT ON VIEW v_exportacion_sui IS 'Vista lista para generar el archivo de reporte al SUI con todos los campos requeridos';

-- =============================================================================
-- VISTA: BALANCE DE MASA CONSOLIDADO POR MATERIAL Y MES
-- =============================================================================
CREATE OR REPLACE VIEW v_balance_masa_mensual AS
SELECT
    r.anio,
    EXTRACT(MONTH FROM r.fecha)::SMALLINT AS mes,
    TO_CHAR(r.fecha, 'YYYY-MM')           AS periodo,
    f.nombre                              AS familia,
    m.nombre                              AS material,
    m.codigo_sui,
    SUM(dr.kg_bruto)                      AS kg_bruto_total,
    SUM(dr.kg_rechazado)                  AS kg_rechazado_total,
    SUM(dr.kg_neto)                       AS kg_neto_total,
    SUM(dr.cantidad_entrante_ton)         AS toneladas_entrantes,
    SUM(dr.rechazo_ton)                   AS toneladas_rechazo,
    SUM(dr.subtotal)                      AS valor_pagado,
    COUNT(DISTINCT r.id)                  AS num_recibos,
    ROUND(SUM(dr.kg_rechazado) / NULLIF(SUM(dr.kg_bruto),0) * 100, 2) AS pct_rechazo
FROM detalle_recibo dr
JOIN recibos           r ON r.id  = dr.recibo_id
JOIN materiales        m ON m.id  = dr.material_id
JOIN familias_material f ON f.id  = m.familia_id
WHERE r.anulado = FALSE
GROUP BY r.anio, mes, periodo, f.nombre, m.nombre, m.codigo_sui
ORDER BY periodo DESC, kg_neto_total DESC;

COMMENT ON VIEW v_balance_masa_mensual IS 'Balance de masa consolidado por material y mes - base para reportes SUI';

-- =============================================================================
-- VISTA: RECICLADORES CON RUTAS
-- =============================================================================
CREATE OR REPLACE VIEW v_recicladores_rutas AS
SELECT
    rc.id,
    rc.nombre,
    rc.documento,
    td.codigo           AS tipo_documento,
    rc.telefono,
    rc.direccion,
    m.nombre            AS municipio,
    rc.activo,
    v.placa,
    v.tipo              AS tipo_vehiculo,
    MAX(CASE WHEN rr.dia_id = 1 THEN rr.descripcion END) AS ruta_lunes,
    MAX(CASE WHEN rr.dia_id = 2 THEN rr.descripcion END) AS ruta_martes,
    MAX(CASE WHEN rr.dia_id = 3 THEN rr.descripcion END) AS ruta_miercoles,
    MAX(CASE WHEN rr.dia_id = 4 THEN rr.descripcion END) AS ruta_jueves,
    MAX(CASE WHEN rr.dia_id = 5 THEN rr.descripcion END) AS ruta_viernes,
    MAX(CASE WHEN rr.dia_id = 6 THEN rr.descripcion END) AS ruta_sabado,
    MAX(CASE WHEN rr.dia_id = 7 THEN rr.descripcion END) AS ruta_domingo
FROM recicladores rc
JOIN tipos_documento   td ON td.id = rc.tipo_documento_id
LEFT JOIN municipios   m  ON m.id  = rc.municipio_id
LEFT JOIN vehiculos    v  ON v.id  = rc.vehiculo_id
LEFT JOIN rutas_reciclador rr ON rr.reciclador_id = rc.id
GROUP BY rc.id, rc.nombre, rc.documento, td.codigo,
         rc.telefono, rc.direccion, m.nombre, rc.activo, v.placa, v.tipo;

COMMENT ON VIEW v_recicladores_rutas IS 'Recicladores con sus rutas de cada día en columnas (pivot)';
