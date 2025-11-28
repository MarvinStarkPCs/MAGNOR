import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, useForm } from '@inertiajs/react';
import { useState } from 'react';

export default function Show({ caja, totalIngresos, totalEgresos, montoEsperado }) {
    const [showCloseModal, setShowCloseModal] = useState(false);

    const { data, setData, post, processing, errors } = useForm({
        monto_cierre: '',
        observaciones: '',
    });

    const handleClose = (e) => {
        e.preventDefault();
        post(route('cajas.close', caja.id), {
            onSuccess: () => setShowCloseModal(false),
        });
    };

    const formatCurrency = (value) => {
        return new Intl.NumberFormat('es-CO', {
            style: 'currency',
            currency: 'COP',
            minimumFractionDigits: 0,
        }).format(value || 0);
    };

    const formatDate = (dateString) => {
        return new Date(dateString).toLocaleDateString('es-CO');
    };

    const formatDateTime = (dateString) => {
        return new Date(dateString).toLocaleString('es-CO');
    };

    const diferenciaCierre = data.monto_cierre ? parseFloat(data.monto_cierre) - parseFloat(montoEsperado) : 0;

    return (
        <AuthenticatedLayout
            header={
                <div className="flex justify-between items-center">
                    <h2 className="text-xl font-semibold text-gray-800">Detalles de Caja</h2>
                    <div className="flex items-center gap-3">
                        {caja.estado === 'abierta' && (
                            <button
                                onClick={() => setShowCloseModal(true)}
                                className="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-semibold shadow-md"
                            >
                                <svg className="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                Cerrar Caja
                            </button>
                        )}
                        <Link
                            href={route('cajas.index')}
                            className="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition font-semibold"
                        >
                            Volver
                        </Link>
                    </div>
                </div>
            }
        >
            <Head title="Detalles de Caja" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {/* Información general */}
                    <div className="bg-white shadow-sm rounded-lg p-6 mb-6">
                        <div className="flex items-center justify-between mb-6">
                            <div>
                                <h3 className="text-2xl font-bold text-gray-900">Caja del {formatDate(caja.fecha)}</h3>
                                <span className={`inline-flex items-center mt-2 px-3 py-1 rounded-full text-sm font-semibold ${
                                    caja.estado === 'abierta'
                                        ? 'bg-green-100 text-green-800'
                                        : 'bg-gray-100 text-gray-800'
                                }`}>
                                    {caja.estado === 'abierta' ? 'Abierta' : 'Cerrada'}
                                </span>
                            </div>
                        </div>

                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div className="border-l-4 border-blue-500 pl-4">
                                <p className="text-sm text-gray-500">Usuario Apertura</p>
                                <p className="text-lg font-semibold text-gray-900">{caja.usuario_apertura.name}</p>
                                <p className="text-sm text-gray-500">{formatDateTime(caja.hora_apertura)}</p>
                            </div>
                            {caja.estado === 'cerrada' && (
                                <div className="border-l-4 border-red-500 pl-4">
                                    <p className="text-sm text-gray-500">Usuario Cierre</p>
                                    <p className="text-lg font-semibold text-gray-900">{caja.usuario_cierre?.name || 'N/A'}</p>
                                    <p className="text-sm text-gray-500">{caja.hora_cierre ? formatDateTime(caja.hora_cierre) : 'N/A'}</p>
                                </div>
                            )}
                        </div>
                    </div>

                    {/* Resumen financiero */}
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                        <div className="bg-white p-6 rounded-lg shadow">
                            <div className="flex items-center justify-between">
                                <div>
                                    <p className="text-sm text-gray-500 font-medium">Monto Apertura</p>
                                    <p className="text-2xl font-bold text-gray-900 mt-1">{formatCurrency(caja.monto_apertura)}</p>
                                </div>
                                <div className="p-3 bg-blue-100 rounded-full">
                                    <svg className="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div className="bg-white p-6 rounded-lg shadow">
                            <div className="flex items-center justify-between">
                                <div>
                                    <p className="text-sm text-gray-500 font-medium">Ingresos</p>
                                    <p className="text-2xl font-bold text-green-600 mt-1">{formatCurrency(totalIngresos)}</p>
                                </div>
                                <div className="p-3 bg-green-100 rounded-full">
                                    <svg className="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M7 11l5-5m0 0l5 5m-5-5v12" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div className="bg-white p-6 rounded-lg shadow">
                            <div className="flex items-center justify-between">
                                <div>
                                    <p className="text-sm text-gray-500 font-medium">Egresos</p>
                                    <p className="text-2xl font-bold text-red-600 mt-1">{formatCurrency(totalEgresos)}</p>
                                </div>
                                <div className="p-3 bg-red-100 rounded-full">
                                    <svg className="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div className="bg-white p-6 rounded-lg shadow">
                            <div className="flex items-center justify-between">
                                <div>
                                    <p className="text-sm text-gray-500 font-medium">Monto Esperado</p>
                                    <p className="text-2xl font-bold text-primary-600 mt-1">{formatCurrency(montoEsperado)}</p>
                                </div>
                                <div className="p-3 bg-primary-100 rounded-full">
                                    <svg className="w-6 h-6 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Si está cerrada, mostrar info de cierre */}
                    {caja.estado === 'cerrada' && (
                        <div className="bg-white shadow-sm rounded-lg p-6 mb-6">
                            <h4 className="text-lg font-semibold text-gray-900 mb-4">Información de Cierre</h4>
                            <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <p className="text-sm text-gray-500">Monto de Cierre</p>
                                    <p className="text-xl font-bold text-gray-900 mt-1">{formatCurrency(caja.monto_cierre)}</p>
                                </div>
                                <div>
                                    <p className="text-sm text-gray-500">Monto Esperado</p>
                                    <p className="text-xl font-bold text-gray-900 mt-1">{formatCurrency(caja.monto_esperado)}</p>
                                </div>
                                <div>
                                    <p className="text-sm text-gray-500">Diferencia</p>
                                    <p className={`text-xl font-bold mt-1 ${
                                        parseFloat(caja.diferencia) === 0
                                            ? 'text-green-600'
                                            : parseFloat(caja.diferencia) > 0
                                            ? 'text-blue-600'
                                            : 'text-red-600'
                                    }`}>
                                        {formatCurrency(caja.diferencia)}
                                    </p>
                                </div>
                            </div>
                        </div>
                    )}

                    {/* Movimientos */}
                    <div className="bg-white shadow-sm rounded-lg overflow-hidden">
                        <div className="p-6 border-b flex items-center justify-between">
                            <h4 className="text-lg font-semibold text-gray-900">Movimientos</h4>
                            {caja.estado === 'abierta' && (
                                <Link
                                    href={route('movimientos-caja.create')}
                                    className="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition font-semibold text-sm"
                                >
                                    <svg className="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 4v16m8-8H4" />
                                    </svg>
                                    Nuevo Movimiento
                                </Link>
                            )}
                        </div>
                        <div className="overflow-x-auto">
                            <table className="min-w-full divide-y divide-gray-200">
                                <thead className="bg-gray-50">
                                    <tr>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha/Hora</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Categoría</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Concepto</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Monto</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Responsable</th>
                                    </tr>
                                </thead>
                                <tbody className="bg-white divide-y divide-gray-200">
                                    {caja.movimientos && caja.movimientos.length > 0 ? (
                                        caja.movimientos.map((movimiento) => (
                                            <tr key={movimiento.id} className="hover:bg-gray-50">
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {formatDateTime(movimiento.fecha_hora)}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <span className={`inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold ${
                                                        movimiento.tipo === 'ingreso'
                                                            ? 'bg-green-100 text-green-800'
                                                            : 'bg-red-100 text-red-800'
                                                    }`}>
                                                        {movimiento.tipo}
                                                    </span>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {movimiento.categoria.nombre}
                                                </td>
                                                <td className="px-6 py-4 text-sm text-gray-900">
                                                    {movimiento.concepto}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <span className={`font-bold ${
                                                        movimiento.tipo === 'ingreso' ? 'text-green-600' : 'text-red-600'
                                                    }`}>
                                                        {movimiento.tipo === 'ingreso' ? '+' : '-'} {formatCurrency(movimiento.monto)}
                                                    </span>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {movimiento.responsable.name}
                                                </td>
                                            </tr>
                                        ))
                                    ) : (
                                        <tr>
                                            <td colSpan="6" className="px-6 py-8 text-center text-gray-500">
                                                No hay movimientos registrados
                                            </td>
                                        </tr>
                                    )}
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {/* Observaciones */}
                    {caja.observaciones && (
                        <div className="mt-6 bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <h4 className="text-sm font-semibold text-gray-700 mb-2">Observaciones</h4>
                            <p className="text-sm text-gray-600 whitespace-pre-wrap">{caja.observaciones}</p>
                        </div>
                    )}
                </div>
            </div>

            {/* Modal de cierre */}
            {showCloseModal && (
                <div className="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
                    <div className="relative bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
                        <div className="p-6">
                            <div className="flex items-center mb-4">
                                <div className="p-3 bg-red-100 rounded-full mr-4">
                                    <svg className="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <h3 className="text-lg font-semibold text-gray-900">Cerrar Caja</h3>
                            </div>

                            <form onSubmit={handleClose} className="space-y-4">
                                <div className="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <p className="text-sm text-blue-800">
                                        <strong>Monto Esperado:</strong> {formatCurrency(montoEsperado)}
                                    </p>
                                </div>

                                <div>
                                    <label htmlFor="monto_cierre" className="block text-sm font-medium text-gray-700 mb-2">
                                        Monto de Cierre (Efectivo Real) *
                                    </label>
                                    <input
                                        type="number"
                                        id="monto_cierre"
                                        value={data.monto_cierre}
                                        onChange={(e) => setData('monto_cierre', e.target.value)}
                                        step="0.01"
                                        min="0"
                                        className={`block w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent ${
                                            errors.monto_cierre ? 'border-red-500' : 'border-gray-300'
                                        }`}
                                        placeholder="0.00"
                                        required
                                        autoFocus
                                    />
                                    {errors.monto_cierre && <p className="mt-1 text-sm text-red-600">{errors.monto_cierre}</p>}
                                </div>

                                {data.monto_cierre && (
                                    <div className={`p-4 rounded-lg ${
                                        diferenciaCierre === 0
                                            ? 'bg-green-50 border border-green-200'
                                            : diferenciaCierre > 0
                                            ? 'bg-blue-50 border border-blue-200'
                                            : 'bg-red-50 border border-red-200'
                                    }`}>
                                        <p className={`text-sm font-semibold ${
                                            diferenciaCierre === 0
                                                ? 'text-green-800'
                                                : diferenciaCierre > 0
                                                ? 'text-blue-800'
                                                : 'text-red-800'
                                        }`}>
                                            Diferencia: {formatCurrency(diferenciaCierre)}
                                            {diferenciaCierre === 0 && ' ✓ (Cuadra perfecto)'}
                                            {diferenciaCierre > 0 && ' (Sobrante)'}
                                            {diferenciaCierre < 0 && ' (Faltante)'}
                                        </p>
                                    </div>
                                )}

                                <div>
                                    <label htmlFor="observaciones_cierre" className="block text-sm font-medium text-gray-700 mb-2">
                                        Observaciones del Cierre
                                    </label>
                                    <textarea
                                        id="observaciones_cierre"
                                        value={data.observaciones}
                                        onChange={(e) => setData('observaciones', e.target.value)}
                                        rows="3"
                                        className="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                        placeholder="Notas adicionales sobre el cierre..."
                                    ></textarea>
                                </div>

                                <div className="flex items-center justify-end gap-3 pt-4 border-t">
                                    <button
                                        type="button"
                                        onClick={() => setShowCloseModal(false)}
                                        className="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-semibold"
                                        disabled={processing}
                                    >
                                        Cancelar
                                    </button>
                                    <button
                                        type="submit"
                                        disabled={processing}
                                        className="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-semibold disabled:opacity-50"
                                    >
                                        {processing ? 'Cerrando...' : 'Cerrar Caja'}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            )}
        </AuthenticatedLayout>
    );
}
