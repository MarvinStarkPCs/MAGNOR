import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, router } from '@inertiajs/react';
import { useState } from 'react';

export default function Index({ movimientos, cajaAbierta, categorias, totalIngresos, totalEgresos, filters }) {
    const [search, setSearch] = useState(filters.search || '');
    const [tipoFilter, setTipoFilter] = useState(filters.tipo || '');

    const handleSearch = (e) => {
        e.preventDefault();
        router.get(route('movimientos-caja.index'), {
            search,
            tipo: tipoFilter,
        }, {
            preserveState: true,
        });
    };

    const handleDelete = (id) => {
        if (confirm('¿Estás seguro de eliminar este movimiento?')) {
            router.delete(route('movimientos-caja.destroy', id));
        }
    };

    const formatCurrency = (value) => {
        return new Intl.NumberFormat('es-CO', {
            style: 'currency',
            currency: 'COP',
            minimumFractionDigits: 0,
        }).format(value);
    };

    const formatDateTime = (dateString) => {
        return new Date(dateString).toLocaleString('es-CO', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit',
        });
    };

    const saldoActual = cajaAbierta ?
        parseFloat(cajaAbierta.monto_apertura) + parseFloat(totalIngresos || 0) - parseFloat(totalEgresos || 0) : 0;

    return (
        <AuthenticatedLayout
            header={
                <div className="flex justify-between items-center">
                    <h2 className="text-xl font-semibold text-gray-800">Movimientos de Caja</h2>
                    {cajaAbierta && (
                        <Link
                            href={route('movimientos-caja.create')}
                            className="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition font-semibold shadow-md"
                        >
                            <svg className="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 4v16m8-8H4" />
                            </svg>
                            Nuevo Movimiento
                        </Link>
                    )}
                </div>
            }
        >
            <Head title="Movimientos de Caja" />

            <div className="py-12">
                <div className="px-4 sm:px-6 lg:px-8">
                    {!cajaAbierta ? (
                        <div className="bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded-lg shadow">
                            <div className="flex items-center mb-4">
                                <svg className="w-6 h-6 text-yellow-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <h3 className="text-lg font-semibold text-yellow-800">No hay caja abierta</h3>
                            </div>
                            <p className="text-yellow-700 mb-4">
                                Debe abrir una caja antes de poder registrar movimientos.
                            </p>
                            <Link
                                href={route('cajas.create')}
                                className="inline-flex items-center gap-2 px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition font-semibold"
                            >
                                <svg className="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 4v16m8-8H4" />
                                </svg>
                                Abrir Caja
                            </Link>
                        </div>
                    ) : (
                        <>
                            {/* Resumen de caja */}
                            <div className="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                                <div className="bg-white p-6 rounded-lg shadow">
                                    <div className="flex items-center justify-between">
                                        <div>
                                            <p className="text-sm text-gray-500 font-medium">Apertura</p>
                                            <p className="text-2xl font-bold text-gray-900">{formatCurrency(cajaAbierta.monto_apertura)}</p>
                                        </div>
                                        <div className="p-3 bg-blue-100 rounded-full">
                                            <svg className="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <div className="bg-white p-6 rounded-lg shadow">
                                    <div className="flex items-center justify-between">
                                        <div>
                                            <p className="text-sm text-gray-500 font-medium">Ingresos</p>
                                            <p className="text-2xl font-bold text-green-600">{formatCurrency(totalIngresos || 0)}</p>
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
                                            <p className="text-2xl font-bold text-red-600">{formatCurrency(totalEgresos || 0)}</p>
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
                                            <p className="text-sm text-gray-500 font-medium">Saldo Actual</p>
                                            <p className={`text-2xl font-bold ${saldoActual >= 0 ? 'text-primary-600' : 'text-red-600'}`}>
                                                {formatCurrency(saldoActual)}
                                            </p>
                                        </div>
                                        <div className={`p-3 rounded-full ${saldoActual >= 0 ? 'bg-primary-100' : 'bg-red-100'}`}>
                                            <svg className={`w-6 h-6 ${saldoActual >= 0 ? 'text-primary-600' : 'text-red-600'}`} fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {/* Filtros */}
                            <div className="mb-6 bg-white p-4 rounded-lg shadow">
                                <form onSubmit={handleSearch} className="flex gap-2">
                                    <select
                                        value={tipoFilter}
                                        onChange={(e) => setTipoFilter(e.target.value)}
                                        className="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                    >
                                        <option value="">Todos los tipos</option>
                                        <option value="ingreso">Ingresos</option>
                                        <option value="egreso">Egresos</option>
                                    </select>
                                    <button
                                        type="submit"
                                        className="inline-flex items-center gap-2 px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition font-semibold"
                                    >
                                        Filtrar
                                    </button>
                                </form>
                            </div>

                            {/* Tabla de movimientos */}
                            <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div className="overflow-x-auto">
                                    <table className="min-w-full divide-y divide-gray-200">
                                        <thead className="bg-gray-50">
                                            <tr>
                                                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Fecha/Hora
                                                </th>
                                                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Tipo
                                                </th>
                                                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Categoría
                                                </th>
                                                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Concepto
                                                </th>
                                                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Monto
                                                </th>
                                                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Responsable
                                                </th>
                                                <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Acciones
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody className="bg-white divide-y divide-gray-200">
                                            {movimientos.data && movimientos.data.length > 0 ? (
                                                movimientos.data.map((movimiento) => (
                                                    <tr key={movimiento.id} className="hover:bg-gray-50">
                                                        <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                            {formatDateTime(movimiento.fecha_hora)}
                                                        </td>
                                                        <td className="px-6 py-4 whitespace-nowrap">
                                                            <span className={`inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold ${
                                                                movimiento.tipo === 'ingreso'
                                                                    ? 'bg-green-100 text-green-800'
                                                                    : 'bg-red-100 text-red-800'
                                                            }`}>
                                                                {movimiento.tipo === 'ingreso' ? (
                                                                    <svg className="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M7 11l5-5m0 0l5 5m-5-5v12" />
                                                                    </svg>
                                                                ) : (
                                                                    <svg className="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                                                                    </svg>
                                                                )}
                                                                {movimiento.tipo.charAt(0).toUpperCase() + movimiento.tipo.slice(1)}
                                                            </span>
                                                        </td>
                                                        <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                            {movimiento.categoria.nombre}
                                                        </td>
                                                        <td className="px-6 py-4 text-sm text-gray-900">
                                                            <div className="max-w-xs">
                                                                {movimiento.concepto}
                                                            </div>
                                                        </td>
                                                        <td className="px-6 py-4 whitespace-nowrap">
                                                            <span className={`text-sm font-bold ${
                                                                movimiento.tipo === 'ingreso' ? 'text-green-600' : 'text-red-600'
                                                            }`}>
                                                                {movimiento.tipo === 'ingreso' ? '+' : '-'} {formatCurrency(movimiento.monto)}
                                                            </span>
                                                        </td>
                                                        <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                            {movimiento.responsable.name}
                                                        </td>
                                                        <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                            <div className="flex justify-end gap-2">
                                                                <Link
                                                                    href={route('movimientos-caja.show', movimiento.id)}
                                                                    className="inline-flex items-center gap-1 px-3 py-1.5 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition"
                                                                    title="Ver detalles"
                                                                >
                                                                    <svg className="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                                    </svg>
                                                                    Ver
                                                                </Link>
                                                                <Link
                                                                    href={route('movimientos-caja.edit', movimiento.id)}
                                                                    className="inline-flex items-center gap-1 px-3 py-1.5 text-blue-700 bg-blue-100 hover:bg-blue-200 rounded-lg transition"
                                                                    title="Editar"
                                                                >
                                                                    <svg className="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                    </svg>
                                                                    Editar
                                                                </Link>
                                                                <button
                                                                    onClick={() => handleDelete(movimiento.id)}
                                                                    className="inline-flex items-center gap-1 px-3 py-1.5 text-red-700 bg-red-100 hover:bg-red-200 rounded-lg transition"
                                                                    title="Eliminar"
                                                                >
                                                                    <svg className="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                    </svg>
                                                                    Eliminar
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                ))
                                            ) : (
                                                <tr>
                                                    <td colSpan="7" className="px-6 py-8 text-center text-gray-500">
                                                        <div className="flex flex-col items-center gap-3">
                                                            <svg className="w-12 h-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                            </svg>
                                                            <p className="text-lg font-medium">No hay movimientos registrados</p>
                                                            <p className="text-sm">Comienza agregando un nuevo movimiento de caja</p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            )}
                                        </tbody>
                                    </table>
                                </div>

                                {movimientos.links && movimientos.data && movimientos.data.length > 0 && (
                                    <div className="bg-gray-50 px-4 py-3 border-t border-gray-200 sm:px-6">
                                        <div className="flex justify-between items-center">
                                            <div className="text-sm text-gray-700">
                                                Mostrando <span className="font-medium">{movimientos.from}</span> a{' '}
                                                <span className="font-medium">{movimientos.to}</span> de{' '}
                                                <span className="font-medium">{movimientos.total}</span> resultados
                                            </div>
                                            <div className="flex gap-2">
                                                {movimientos.links.map((link, index) => (
                                                    <Link
                                                        key={index}
                                                        href={link.url || '#'}
                                                        disabled={!link.url}
                                                        className={`px-3 py-1 rounded ${
                                                            link.active
                                                                ? 'bg-blue-600 text-white'
                                                                : 'bg-white text-gray-700 hover:bg-gray-100'
                                                        } ${!link.url ? 'opacity-50 cursor-not-allowed' : ''}`}
                                                        dangerouslySetInnerHTML={{ __html: link.label }}
                                                    />
                                                ))}
                                            </div>
                                        </div>
                                    </div>
                                )}
                            </div>
                        </>
                    )}
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
