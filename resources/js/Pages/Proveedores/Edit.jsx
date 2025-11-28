import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, useForm } from '@inertiajs/react';

export default function Edit({ proveedor }) {
    const { data, setData, put, processing, errors } = useForm({
        nombre: proveedor.nombre || '',
        documento: proveedor.documento || '',
        telefono: proveedor.telefono || '',
        direccion: proveedor.direccion || '',
        es_reciclador: proveedor.es_reciclador || false,
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        put(route('proveedores.update', proveedor.id));
    };

    return (
        <AuthenticatedLayout
            header={
                <div className="flex justify-between items-center">
                    <h2 className="text-xl font-semibold text-gray-800">Editar Proveedor</h2>
                    <Link
                        href={route('proveedores.index')}
                        className="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition"
                    >
                        Volver
                    </Link>
                </div>
            }
        >
            <Head title="Editar Proveedor" />

            <div className="py-12">
                <div className="mx-auto max-w-3xl sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <form onSubmit={handleSubmit} className="p-6 space-y-6">
                            <div>
                                <label className="block text-sm font-medium text-gray-700">Nombre *</label>
                                <input
                                    type="text"
                                    value={data.nombre}
                                    onChange={(e) => setData('nombre', e.target.value)}
                                    className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    required
                                />
                                {errors.nombre && <div className="text-red-600 text-sm mt-1">{errors.nombre}</div>}
                            </div>

                            <div>
                                <label className="block text-sm font-medium text-gray-700">Documento *</label>
                                <input
                                    type="text"
                                    value={data.documento}
                                    onChange={(e) => setData('documento', e.target.value)}
                                    className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    required
                                />
                                {errors.documento && <div className="text-red-600 text-sm mt-1">{errors.documento}</div>}
                            </div>

                            <div>
                                <label className="block text-sm font-medium text-gray-700">Teléfono</label>
                                <input
                                    type="text"
                                    value={data.telefono}
                                    onChange={(e) => setData('telefono', e.target.value)}
                                    className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                />
                                {errors.telefono && <div className="text-red-600 text-sm mt-1">{errors.telefono}</div>}
                            </div>

                            <div>
                                <label className="block text-sm font-medium text-gray-700">Dirección</label>
                                <textarea
                                    value={data.direccion}
                                    onChange={(e) => setData('direccion', e.target.value)}
                                    rows="3"
                                    className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                />
                                {errors.direccion && <div className="text-red-600 text-sm mt-1">{errors.direccion}</div>}
                            </div>

                            <div className="flex items-center">
                                <input
                                    type="checkbox"
                                    checked={data.es_reciclador}
                                    onChange={(e) => setData('es_reciclador', e.target.checked)}
                                    className="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                />
                                <label className="ml-2 block text-sm text-gray-700">
                                    Es reciclador
                                </label>
                            </div>
                            {errors.es_reciclador && <div className="text-red-600 text-sm mt-1">{errors.es_reciclador}</div>}

                            <div className="flex justify-end gap-3">
                                <Link
                                    href={route('proveedores.index')}
                                    className="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition"
                                >
                                    Cancelar
                                </Link>
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition disabled:opacity-50"
                                >
                                    {processing ? 'Actualizando...' : 'Actualizar Proveedor'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
