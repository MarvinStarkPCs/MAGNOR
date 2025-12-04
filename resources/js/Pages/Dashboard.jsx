import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';
import { formatDate, formatCurrency } from '@/Utils/dateFormatter';

export default function Dashboard({ stats, materialesBajoStock, ultimasVentas, ultimasCompras, ventasPorMes, comprasPorMes, materialesMasVendidos }) {

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Dashboard - MAGNOR
                </h2>
            }
        >
            <Head title="Dashboard" />

            <div className="py-12">
                <div className="px-4 sm:px-6 lg:px-8 space-y-6">
                    {/* Estadísticas Principales */}
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-primary-500">
                            <div className="text-sm font-medium text-gray-500">Total Materiales</div>
                            <div className="mt-1 text-3xl font-semibold text-primary-600">{stats.totalMateriales}</div>
                        </div>
                        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-secondary-500">
                            <div className="text-sm font-medium text-gray-500">Total Clientes</div>
                            <div className="mt-1 text-3xl font-semibold text-secondary-600">{stats.totalClientes}</div>
                        </div>
                        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-accent-500">
                            <div className="text-sm font-medium text-gray-500">Total Proveedores</div>
                            <div className="mt-1 text-3xl font-semibold text-accent-600">{stats.totalProveedores}</div>
                        </div>
                        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-primary-500">
                            <div className="text-sm font-medium text-gray-500">Ventas del Mes</div>
                            <div className="mt-1 text-2xl font-semibold text-primary-600">{formatCurrency(stats.ventasMesActual)}</div>
                        </div>
                    </div>

                    {/* Materiales con Bajo Stock */}
                    {materialesBajoStock && materialesBajoStock.length > 0 && (
                        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div className="p-6">
                                <h3 className="text-lg font-semibold text-gray-900 mb-4">⚠️ Materiales con Bajo Stock</h3>
                                <div className="space-y-2">
                                    {materialesBajoStock.map((material) => (
                                        <div key={material.id} className="flex justify-between items-center py-2 border-b border-gray-200">
                                            <span className="font-medium">{material.nombre}</span>
                                            <span className="text-red-600 font-semibold">{material.stock} {material.unidad_medida}</span>
                                        </div>
                                    ))}
                                </div>
                                <Link
                                    href={route('inventario.index')}
                                    className="mt-4 inline-block text-blue-600 hover:text-blue-800"
                                >
                                    Ver Inventario Completo →
                                </Link>
                            </div>
                        </div>
                    )}

                    {/* Últimas Transacciones */}
                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        {/* Últimas Ventas */}
                        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div className="p-6">
                                <h3 className="text-lg font-semibold text-gray-900 mb-4">Últimas Ventas</h3>
                                <div className="space-y-3">
                                    {ultimasVentas && ultimasVentas.length > 0 ? (
                                        ultimasVentas.map((venta) => (
                                            <div key={venta.id} className="flex justify-between items-center py-2 border-b border-gray-200">
                                                <div>
                                                    <div className="font-medium">{venta.cliente?.nombre || 'Sin cliente'}</div>
                                                    <div className="text-sm text-gray-500">{formatDate(venta.fecha)}</div>
                                                </div>
                                                <span className="font-semibold text-green-600">{formatCurrency(venta.total)}</span>
                                            </div>
                                        ))
                                    ) : (
                                        <p className="text-gray-500">No hay ventas registradas</p>
                                    )}
                                </div>
                                <Link
                                    href={route('ventas.index')}
                                    className="mt-4 inline-block text-blue-600 hover:text-blue-800"
                                >
                                    Ver Todas las Ventas →
                                </Link>
                            </div>
                        </div>

                        {/* Últimas Compras */}
                        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div className="p-6">
                                <h3 className="text-lg font-semibold text-gray-900 mb-4">Últimas Compras</h3>
                                <div className="space-y-3">
                                    {ultimasCompras && ultimasCompras.length > 0 ? (
                                        ultimasCompras.map((compra) => (
                                            <div key={compra.id} className="flex justify-between items-center py-2 border-b border-gray-200">
                                                <div>
                                                    <div className="font-medium">{compra.proveedor?.nombre || 'Sin proveedor'}</div>
                                                    <div className="text-sm text-gray-500">{formatDate(compra.fecha_compra)}</div>
                                                </div>
                                                <span className="font-semibold text-blue-600">{formatCurrency(compra.total)}</span>
                                            </div>
                                        ))
                                    ) : (
                                        <p className="text-gray-500">No hay compras registradas</p>
                                    )}
                                </div>
                                <Link
                                    href={route('compras.index')}
                                    className="mt-4 inline-block text-blue-600 hover:text-blue-800"
                                >
                                    Ver Todas las Compras →
                                </Link>
                            </div>
                        </div>
                    </div>

                    {/* Materiales Más Vendidos */}
                    {materialesMasVendidos && materialesMasVendidos.length > 0 && (
                        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div className="p-6">
                                <h3 className="text-lg font-semibold text-gray-900 mb-4">Materiales Más Vendidos</h3>
                                <div className="space-y-2">
                                    {materialesMasVendidos.map((material) => (
                                        <div key={material.id} className="flex justify-between items-center py-2 border-b border-gray-200">
                                            <span className="font-medium">{material.nombre}</span>
                                            <span className="text-green-600 font-semibold">
                                                {material.total_vendido} {material.unidad_medida}
                                            </span>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        </div>
                    )}

                    {/* Accesos Rápidos */}
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6">
                            <h3 className="text-lg font-semibold text-gray-900 mb-4">Accesos Rápidos</h3>
                            <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <Link
                                    href={route('ventas.create')}
                                    className="px-4 py-3 bg-primary-600 text-white rounded-lg text-center hover:bg-primary-700 transition font-semibold shadow-md flex flex-col items-center gap-2"
                                >
                                    <svg className="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Nueva Venta
                                </Link>
                                <Link
                                    href={route('compras.create')}
                                    className="px-4 py-3 bg-secondary-600 text-white rounded-lg text-center hover:bg-secondary-700 transition font-semibold shadow-md flex flex-col items-center gap-2"
                                >
                                    <svg className="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    Nueva Compra
                                </Link>
                                <Link
                                    href={route('inventario.index')}
                                    className="px-4 py-3 bg-dark-700 text-white rounded-lg text-center hover:bg-dark-800 transition font-semibold shadow-md flex flex-col items-center gap-2"
                                >
                                    <svg className="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                    Inventario
                                </Link>
                                <Link
                                    href={route('precios.index')}
                                    className="px-4 py-3 bg-accent-600 text-white rounded-lg text-center hover:bg-accent-700 transition font-semibold shadow-md flex flex-col items-center gap-2"
                                >
                                    <svg className="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                    Precios del Día
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
