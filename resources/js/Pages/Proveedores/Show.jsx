import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';

export default function Show({ proveedor }) {
    return (
        <AuthenticatedLayout
            header={
                <div className="flex justify-between items-center">
                    <h2 className="text-xl font-semibold text-gray-800">Detalle del Proveedor</h2>
                    <div className="flex gap-2">
                        <Link
                            href={route('proveedores.edit', proveedor.id)}
                            className="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                        >
                            Editar
                        </Link>
                        <Link
                            href={route('proveedores.index')}
                            className="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition"
                        >
                            Volver
                        </Link>
                    </div>
                </div>
            }
        >
            <Head title={`Proveedor: ${proveedor.nombre}`} />

            <div className="py-12">
                <div className="mx-auto max-w-4xl sm:px-6 lg:px-8 space-y-6">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 className="text-lg font-semibold text-gray-900 mb-4">Información General</h3>
                        <dl className="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <dt className="text-sm font-medium text-gray-500">Nombre</dt>
                                <dd className="mt-1 text-sm text-gray-900">{proveedor.nombre}</dd>
                            </div>
                            <div>
                                <dt className="text-sm font-medium text-gray-500">Documento</dt>
                                <dd className="mt-1 text-sm text-gray-900">{proveedor.documento}</dd>
                            </div>
                            {proveedor.telefono && (
                                <div>
                                    <dt className="text-sm font-medium text-gray-500">Teléfono</dt>
                                    <dd className="mt-1 text-sm text-gray-900">{proveedor.telefono}</dd>
                                </div>
                            )}
                            <div>
                                <dt className="text-sm font-medium text-gray-500">Tipo</dt>
                                <dd className="mt-1">
                                    {proveedor.es_reciclador ? (
                                        <span className="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Reciclador
                                        </span>
                                    ) : (
                                        <span className="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Regular
                                        </span>
                                    )}
                                </dd>
                            </div>
                            {proveedor.direccion && (
                                <div className="md:col-span-2">
                                    <dt className="text-sm font-medium text-gray-500">Dirección</dt>
                                    <dd className="mt-1 text-sm text-gray-900">{proveedor.direccion}</dd>
                                </div>
                            )}
                        </dl>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
