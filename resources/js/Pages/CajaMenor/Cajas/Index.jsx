import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, router } from '@inertiajs/react';

export default function Index({ cajas, cajaAbierta, filters }) {
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
        return new Date(dateString).toLocaleString('es-CO', {
            hour: '2-digit',
            minute: '2-digit',
        });
    };

    return (
        <AuthenticatedLayout
            header={
                <div className="flex justify-between items-center">
                    <h2 className="text-xl font-semibold text-gray-800">Gestión de Cajas</h2>
                    {!cajaAbierta && (
                        <Link
                            href={route('cajas.create')}
                            className="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition font-semibold shadow-md"
                        >
                            <svg className="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 4v16m8-8H4" />
                            </svg>
                            Abrir Caja
                        </Link>
                    )}
                </div>
            }
        >
            <Head title="Gestión de Cajas" />

            <div className="py-12">
                <div className="px-4 sm:px-6 lg:px-8">
                    {/* Caja abierta actual */}
                    {cajaAbierta && (
                        <div className="mb-6 bg-green-50 border-l-4 border-green-400 p-6 rounded-lg shadow">
                            <div className="flex items-center justify-between mb-4">
                                <div className="flex items-center">
                                    <svg className="w-8 h-8 text-green-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div>
                                        <h3 className="text-lg font-semibold text-green-800">Caja Abierta</h3>
                                        <p className="text-sm text-green-700">
                                            Fecha: {formatDate(cajaAbierta.fecha)} - Hora apertura: {formatDateTime(cajaAbierta.hora_apertura)}
                                        </p>
                                    </div>
                                </div>
                                <Link
                                    href={route('cajas.show', cajaAbierta.id)}
                                    className="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold"
                                >
                                    Ver Detalles
                                </Link>
                            </div>
                            <div className="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                                <div className="bg-white p-4 rounded-lg">
                                    <p className="text-sm text-gray-500">Monto Apertura</p>
                                    <p className="text-xl font-bold text-gray-900">{formatCurrency(cajaAbierta.monto_apertura)}</p>
                                </div>
                                <div className="bg-white p-4 rounded-lg">
                                    <p className="text-sm text-gray-500">Usuario</p>
                                    <p className="text-xl font-bold text-gray-900">{cajaAbierta.usuario_apertura?.name || 'N/A'}</p>
                                </div>
                                <div className="bg-white p-4 rounded-lg">
                                    <p className="text-sm text-gray-500">Estado</p>
                                    <span className="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                        Abierta
                                    </span>
                                </div>
                            </div>
                        </div>
                    )}

                    {/* Tabla de cajas */}
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 border-b">
                            <h3 className="text-lg font-semibold text-gray-800">Historial de Cajas</h3>
                        </div>
                        <div className="overflow-x-auto">
                            <table className="min-w-full divide-y divide-gray-200">
                                <thead className="bg-gray-50">
                                    <tr>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Fecha
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Usuario Apertura
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Monto Apertura
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Monto Cierre
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Diferencia
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Estado
                                        </th>
                                        <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody className="bg-white divide-y divide-gray-200">
                                    {cajas.data && cajas.data.length > 0 ? (
                                        cajas.data.map((caja) => (
                                            <tr key={caja.id} className="hover:bg-gray-50">
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {formatDate(caja.fecha)}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {caja.usuario_apertura?.name || 'N/A'}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {formatCurrency(caja.monto_apertura)}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {caja.monto_cierre ? formatCurrency(caja.monto_cierre) : '-'}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    {caja.diferencia !== null ? (
                                                        <span className={`font-semibold ${
                                                            parseFloat(caja.diferencia) === 0
                                                                ? 'text-green-600'
                                                                : parseFloat(caja.diferencia) > 0
                                                                ? 'text-blue-600'
                                                                : 'text-red-600'
                                                        }`}>
                                                            {formatCurrency(caja.diferencia)}
                                                        </span>
                                                    ) : (
                                                        <span className="text-gray-400">-</span>
                                                    )}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <span className={`inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold ${
                                                        caja.estado === 'abierta'
                                                            ? 'bg-green-100 text-green-800'
                                                            : 'bg-gray-100 text-gray-800'
                                                    }`}>
                                                        {caja.estado === 'abierta' ? (
                                                            <>
                                                                <svg className="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                                                    <circle cx="4" cy="4" r="3" />
                                                                </svg>
                                                                Abierta
                                                            </>
                                                        ) : (
                                                            'Cerrada'
                                                        )}
                                                    </span>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <Link
                                                        href={route('cajas.show', caja.id)}
                                                        className="inline-flex items-center gap-1 px-3 py-1.5 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition"
                                                    >
                                                        <svg className="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                        Ver Detalles
                                                    </Link>
                                                </td>
                                            </tr>
                                        ))
                                    ) : (
                                        <tr>
                                            <td colSpan="7" className="px-6 py-8 text-center text-gray-500">
                                                <div className="flex flex-col items-center gap-3">
                                                    <svg className="w-12 h-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                    </svg>
                                                    <p className="text-lg font-medium">No hay cajas registradas</p>
                                                </div>
                                            </td>
                                        </tr>
                                    )}
                                </tbody>
                            </table>
                        </div>

                        {cajas.links && cajas.data && cajas.data.length > 0 && (
                            <div className="bg-gray-50 px-4 py-3 border-t border-gray-200 sm:px-6">
                                <div className="flex justify-between items-center">
                                    <div className="text-sm text-gray-700">
                                        Mostrando <span className="font-medium">{cajas.from}</span> a{' '}
                                        <span className="font-medium">{cajas.to}</span> de{' '}
                                        <span className="font-medium">{cajas.total}</span> resultados
                                    </div>
                                    <div className="flex gap-2">
                                        {cajas.links.map((link, index) => (
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
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
