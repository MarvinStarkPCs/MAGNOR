import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, useForm } from '@inertiajs/react';

export default function Create() {
    const { data, setData, post, processing, errors } = useForm({
        nombre: '',
        descripcion: '',
        unidad_medida: 'kg',
        precio_compra: '',
        precio_venta: '',
        precio_dia_compra: '',
        precio_dia_venta: '',
        stock: '0',
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('materiales.store'));
    };

    return (
        <AuthenticatedLayout
            header={
                <div className="flex justify-between items-center">
                    <h2 className="text-xl font-semibold text-gray-800">Nuevo Material</h2>
                    <Link
                        href={route('materiales.index')}
                        className="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition"
                    >
                        Volver
                    </Link>
                </div>
            }
        >
            <Head title="Nuevo Material" />

            <div className="py-12">
                <div className="px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">
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
                                <label className="block text-sm font-medium text-gray-700">Descripción</label>
                                <textarea
                                    value={data.descripcion}
                                    onChange={(e) => setData('descripcion', e.target.value)}
                                    rows="3"
                                    className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                />
                                {errors.descripcion && <div className="text-red-600 text-sm mt-1">{errors.descripcion}</div>}
                            </div>

                            <div>
                                <label className="block text-sm font-medium text-gray-700">Unidad de Medida *</label>
                                <select
                                    value={data.unidad_medida}
                                    onChange={(e) => setData('unidad_medida', e.target.value)}
                                    className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    required
                                >
                                    <option value="kg">Kilogramos (kg)</option>
                                    <option value="ton">Toneladas (ton)</option>
                                    <option value="lb">Libras (lb)</option>
                                    <option value="unidad">Unidad</option>
                                    <option value="m">Metros (m)</option>
                                </select>
                                {errors.unidad_medida && <div className="text-red-600 text-sm mt-1">{errors.unidad_medida}</div>}
                            </div>

                            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label className="block text-sm font-medium text-gray-700">Precio de Compra *</label>
                                    <input
                                        type="number"
                                        step="0.01"
                                        value={data.precio_compra}
                                        onChange={(e) => setData('precio_compra', e.target.value)}
                                        className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        required
                                    />
                                    {errors.precio_compra && <div className="text-red-600 text-sm mt-1">{errors.precio_compra}</div>}
                                </div>

                                <div>
                                    <label className="block text-sm font-medium text-gray-700">Precio de Venta *</label>
                                    <input
                                        type="number"
                                        step="0.01"
                                        value={data.precio_venta}
                                        onChange={(e) => setData('precio_venta', e.target.value)}
                                        className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        required
                                    />
                                    {errors.precio_venta && <div className="text-red-600 text-sm mt-1">{errors.precio_venta}</div>}
                                </div>
                            </div>

                            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label className="block text-sm font-medium text-gray-700">Precio Día Compra</label>
                                    <input
                                        type="number"
                                        step="0.01"
                                        value={data.precio_dia_compra}
                                        onChange={(e) => setData('precio_dia_compra', e.target.value)}
                                        className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    />
                                    {errors.precio_dia_compra && <div className="text-red-600 text-sm mt-1">{errors.precio_dia_compra}</div>}
                                </div>

                                <div>
                                    <label className="block text-sm font-medium text-gray-700">Precio Día Venta</label>
                                    <input
                                        type="number"
                                        step="0.01"
                                        value={data.precio_dia_venta}
                                        onChange={(e) => setData('precio_dia_venta', e.target.value)}
                                        className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    />
                                    {errors.precio_dia_venta && <div className="text-red-600 text-sm mt-1">{errors.precio_dia_venta}</div>}
                                </div>
                            </div>

                            <div>
                                <label className="block text-sm font-medium text-gray-700">Stock Inicial *</label>
                                <input
                                    type="number"
                                    step="0.01"
                                    value={data.stock}
                                    onChange={(e) => setData('stock', e.target.value)}
                                    className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    required
                                />
                                {errors.stock && <div className="text-red-600 text-sm mt-1">{errors.stock}</div>}
                            </div>

                            <div className="flex justify-end gap-3">
                                <Link
                                    href={route('materiales.index')}
                                    className="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition"
                                >
                                    Cancelar
                                </Link>
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition disabled:opacity-50 font-semibold"
                                >
                                    {processing ? 'Guardando...' : 'Guardar Material'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
