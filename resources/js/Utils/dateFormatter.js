/**
 * Formatea una fecha a formato colombiano (día/mes/año)
 * @param {string|Date} date - Fecha a formatear
 * @returns {string} Fecha formateada
 */
export const formatDate = (date) => {
    if (!date) return '';
    const d = new Date(date);
    return new Intl.DateTimeFormat('es-CO', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        timeZone: 'America/Bogota'
    }).format(d);
};

/**
 * Formatea una fecha y hora a formato colombiano
 * @param {string|Date} date - Fecha y hora a formatear
 * @returns {string} Fecha y hora formateada
 */
export const formatDateTime = (date) => {
    if (!date) return '';
    const d = new Date(date);
    return new Intl.DateTimeFormat('es-CO', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: true,
        timeZone: 'America/Bogota'
    }).format(d);
};

/**
 * Formatea solo la hora a formato colombiano
 * @param {string|Date} date - Fecha con hora a formatear
 * @returns {string} Hora formateada
 */
export const formatTime = (date) => {
    if (!date) return '';
    const d = new Date(date);
    return new Intl.DateTimeFormat('es-CO', {
        hour: '2-digit',
        minute: '2-digit',
        hour12: true,
        timeZone: 'America/Bogota'
    }).format(d);
};

/**
 * Formatea una fecha a formato largo (ejemplo: 15 de noviembre de 2025)
 * @param {string|Date} date - Fecha a formatear
 * @returns {string} Fecha en formato largo
 */
export const formatDateLong = (date) => {
    if (!date) return '';
    const d = new Date(date);
    return new Intl.DateTimeFormat('es-CO', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        timeZone: 'America/Bogota'
    }).format(d);
};

/**
 * Formatea moneda a formato colombiano (Peso Colombiano - COP)
 * @param {number} value - Valor a formatear
 * @param {string} currency - Código de moneda (default: COP)
 * @returns {string} Moneda formateada
 */
export const formatCurrency = (value, currency = 'COP') => {
    return new Intl.NumberFormat('es-CO', {
        style: 'currency',
        currency: currency,
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(value || 0);
};

/**
 * Formatea un número sin decimales
 * @param {number} value - Valor a formatear
 * @returns {string} Número formateado
 */
export const formatNumber = (value) => {
    return new Intl.NumberFormat('es-CO').format(value || 0);
};
