import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';

export default function Show({ material }) {
    const formatCurrency = (value) => {
        return new Intl.NumberFormat('es-CO', {
            style: 'currency',
            currency: 'COP',
            minimumFractionDigits: 0,
        }).format(value);
    };

    return (
        <AuthenticatedLayout
            header={
                <div className="flex justify-between items-center">
                    <h2 className="text-xl font-semibold text-gray-800">Detalle del Material</h2>
                    <div className="flex gap-2">
                        <Link
                            href={route('materiales.edit', material.id)}
                            className="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition font-semibold"
                        >
                            Editar
                        </Link>
                        <Link
                            href={route('materiales.index')}
                            className="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition"
                        >
                            Volver
                        </Link>
                    </div>
                </div>
            }
        >
            <Head title={`Material: ${material.nombre}`} />

            <div className="py-12">
                <div className="px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto space-y-6">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 className="text-lg font-semibold text-gray-900 mb-4">Información General</h3>
                        <dl className="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <dt className="text-sm font-medium text-gray-500">Nombre</dt>
                                <dd className="mt-1 text-sm text-gray-900">{material.nombre}</dd>
                            </div>
                            <div>
                                <dt className="text-sm font-medium text-gray-500">Unidad de Medida</dt>
                                <dd className="mt-1 text-sm text-gray-900">{material.unidad_medida}</dd>
                            </div>
                            {material.descripcion && (
                                <div className="md:col-span-2">
                                    <dt className="text-sm font-medium text-gray-500">Descripción</dt>
                                    <dd className="mt-1 text-sm text-gray-900">{material.descripcion}</dd>
                                </div>
                            )}
                            <div>
                                <dt className="text-sm font-medium text-gray-500">Stock Actual</dt>
                                <dd className="mt-1 text-lg font-semibold text-gray-900">{material.stock} {material.unidad_medida}</dd>
                            </div>
                            <div>
                                <dt className="text-sm font-medium text-gray-500">Valor en Inventario</dt>
                                <dd className="mt-1 text-lg font-semibold text-green-600">{formatCurrency(material.stock * material.precio_compra)}</dd>
                            </div>
                        </dl>
                    </div>

                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 className="text-lg font-semibold text-gray-900 mb-4">Precios</h3>
                        <dl className="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <dt className="text-sm font-medium text-gray-500">Precio de Compra</dt>
                                <dd className="mt-1 text-sm text-gray-900">{formatCurrency(material.precio_compra)}</dd>
                            </div>
                            <div>
                                <dt className="text-sm font-medium text-gray-500">Precio de Venta</dt>
                                <dd className="mt-1 text-sm font-semibold text-green-600">{formatCurrency(material.precio_venta)}</dd>
                            </div>
                            {material.precio_dia_compra && (
                                <div>
                                    <dt className="text-sm font-medium text-gray-500">Precio Día Compra</dt>
                                    <dd className="mt-1 text-sm text-gray-900">{formatCurrency(material.precio_dia_compra)}</dd>
                                </div>
                            )}
                            {material.precio_dia_venta && (
                                <div>
                                    <dt className="text-sm font-medium text-gray-500">Precio Día Venta</dt>
                                    <dd className="mt-1 text-sm text-green-600">{formatCurrency(material.precio_dia_venta)}</dd>
                                </div>
                            )}
                        </dl>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
