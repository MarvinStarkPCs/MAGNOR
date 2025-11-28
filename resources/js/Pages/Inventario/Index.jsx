import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, router } from '@inertiajs/react';
import { useState } from 'react';

export default function Index({ materiales, filters }) {
    const [search, setSearch] = useState(filters.search || '');
    const [filtro, setFiltro] = useState(filters.filtro || 'todos');

    const handleSearch = (e) => {
        e.preventDefault();
        router.get(route('inventario.index'), { search, filtro }, {
            preserveState: true,
        });
    };

    const formatCurrency = (value) => {
        return new Intl.NumberFormat('es-CO', {
            style: 'currency',
            currency: 'COP',
            minimumFractionDigits: 0,
        }).format(value);
    };

    const calcularValorInventario = (material) => {
        return material.stock * material.precio_compra;
    };

    const calcularTotalInventario = () => {
        return materiales.data.reduce((total, material) => {
            return total + calcularValorInventario(material);
        }, 0);
    };

    const getStockClass = (stock) => {
        if (stock === 0) return 'text-red-600 font-bold';
        if (stock < 10) return 'text-orange-600 font-semibold';
        return 'text-gray-900';
    };

    return (
        <AuthenticatedLayout
            header={
                <div className="flex justify-between items-center">
                    <h2 className="text-xl font-semibold text-gray-800">Inventario</h2>
                    <div className="text-lg font-bold text-green-600">
                        Total Inventario: {formatCurrency(calcularTotalInventario())}
                    </div>
                </div>
            }
        >
            <Head title="Inventario" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="mb-6 bg-white p-4 rounded-lg shadow">
                        <form onSubmit={handleSearch} className="space-y-4">
                            <div className="flex gap-2">
                                <div className="relative flex-1">
                                    <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg className="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                    <input
                                        type="text"
                                        value={search}
                                        onChange={(e) => setSearch(e.target.value)}
                                        placeholder="Buscar material..."
                                        className="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                    />
                                </div>
                                <select
                                    value={filtro}
                                    onChange={(e) => setFiltro(e.target.value)}
                                    className="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                >
                                    <option value="todos">Todos</option>
                                    <option value="bajo_stock">Stock Bajo (&lt; 10)</option>
                                    <option value="sin_stock">Sin Stock</option>
                                </select>
                                <button
                                    type="submit"
                                    className="inline-flex items-center gap-2 px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition font-semibold"
                                >
                                    <svg className="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    Filtrar
                                </button>
                            </div>
                        </form>
                    </div>

                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="overflow-x-auto">
                            <table className="min-w-full divide-y divide-gray-200">
                                <thead className="bg-gray-50">
                                    <tr>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Material
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Unidad
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Stock Actual
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Precio Compra
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Precio Venta
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Valor en Inventario
                                        </th>
                                    </tr>
                                </thead>
                                <tbody className="bg-white divide-y divide-gray-200">
                                    {materiales.data && materiales.data.length > 0 ? (
                                        materiales.data.map((material) => (
                                            <tr key={material.id} className="hover:bg-gray-50">
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <div className="text-sm font-medium text-gray-900">{material.nombre}</div>
                                                    {material.descripcion && (
                                                        <div className="text-sm text-gray-500">{material.descripcion}</div>
                                                    )}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {material.unidad_medida}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <span className={`text-sm ${getStockClass(material.stock)}`}>
                                                        {material.stock}
                                                    </span>
                                                    {material.stock === 0 && (
                                                        <span className="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                            Agotado
                                                        </span>
                                                    )}
                                                    {material.stock > 0 && material.stock < 10 && (
                                                        <span className="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                                            Bajo
                                                        </span>
                                                    )}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {formatCurrency(material.precio_compra)}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-semibold">
                                                    {formatCurrency(material.precio_venta)}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                                    {formatCurrency(calcularValorInventario(material))}
                                                </td>
                                            </tr>
                                        ))
                                    ) : (
                                        <tr>
                                            <td colSpan="6" className="px-6 py-4 text-center text-gray-500">
                                                No hay materiales en inventario
                                            </td>
                                        </tr>
                                    )}
                                </tbody>
                                <tfoot className="bg-gray-50">
                                    <tr>
                                        <td colSpan="5" className="px-6 py-3 text-right font-bold text-gray-900">
                                            Total Valor Inventario:
                                        </td>
                                        <td className="px-6 py-3 text-xl font-bold text-green-600">
                                            {formatCurrency(calcularTotalInventario())}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        {materiales.links && (
                            <div className="bg-gray-50 px-4 py-3 border-t border-gray-200 sm:px-6">
                                <div className="flex justify-between items-center">
                                    <div className="text-sm text-gray-700">
                                        Mostrando <span className="font-medium">{materiales.from}</span> a{' '}
                                        <span className="font-medium">{materiales.to}</span> de{' '}
                                        <span className="font-medium">{materiales.total}</span> resultados
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
