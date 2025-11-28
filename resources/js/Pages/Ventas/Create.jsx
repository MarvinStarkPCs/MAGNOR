import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, useForm } from '@inertiajs/react';
import { useState } from 'react';

export default function Create({ clientes, materiales }) {
    const { data, setData, post, processing, errors } = useForm({
        cliente_id: '',
        fecha: new Date().toISOString().split('T')[0],
        detalles: [],
    });

    const [detalles, setDetalles] = useState([
        { material_id: '', cantidad: '', precio_unitario: '' }
    ]);

    const handleSubmit = (e) => {
        e.preventDefault();
        data.detalles = detalles;
        post(route('ventas.store'));
    };

    const agregarDetalle = () => {
        setDetalles([...detalles, { material_id: '', cantidad: '', precio_unitario: '' }]);
    };

    const removerDetalle = (index) => {
        const nuevosDetalles = detalles.filter((_, i) => i !== index);
        setDetalles(nuevosDetalles);
    };

    const actualizarDetalle = (index, campo, valor) => {
        const nuevosDetalles = [...detalles];
        nuevosDetalles[index][campo] = valor;
        setDetalles(nuevosDetalles);
    };

    const obtenerStockDisponible = (materialId) => {
        const material = materiales.find(m => m.id == materialId);
        return material ? material.stock : 0;
    };

    const calcularTotal = () => {
        return detalles.reduce((total, detalle) => {
            const cantidad = parseFloat(detalle.cantidad) || 0;
            const precio = parseFloat(detalle.precio_unitario) || 0;
            return total + (cantidad * precio);
        }, 0);
    };

    const formatCurrency = (value) => {
        return new Intl.NumberFormat('es-CO', {
            style: 'currency',
            currency: 'COP',
            minimumFractionDigits: 0,
        }).format(value);
    };

    const verificarStock = (index) => {
        const detalle = detalles[index];
        if (detalle.material_id && detalle.cantidad) {
            const stockDisponible = obtenerStockDisponible(detalle.material_id);
            return parseFloat(detalle.cantidad) <= stockDisponible;
        }
        return true;
    };

    return (
        <AuthenticatedLayout
            header={
                <div className="flex justify-between items-center">
                    <h2 className="text-xl font-semibold text-gray-800">Nueva Venta</h2>
                    <Link
                        href={route('ventas.index')}
                        className="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition"
                    >
                        Volver
                    </Link>
                </div>
            }
        >
            <Head title="Nueva Venta" />

            <div className="py-12">
                <div className="mx-auto max-w-6xl sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <form onSubmit={handleSubmit} className="p-6 space-y-6">
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label className="block text-sm font-medium text-gray-700">Cliente *</label>
                                    <select
                                        value={data.cliente_id}
                                        onChange={(e) => setData('cliente_id', e.target.value)}
                                        className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        required
                                    >
                                        <option value="">Seleccionar cliente</option>
                                        {clientes.map((cliente) => (
                                            <option key={cliente.id} value={cliente.id}>
                                                {cliente.nombre} - {cliente.documento}
                                            </option>
                                        ))}
                                    </select>
                                    {errors.cliente_id && <div className="text-red-600 text-sm mt-1">{errors.cliente_id}</div>}
                                </div>

                                <div>
                                    <label className="block text-sm font-medium text-gray-700">Fecha *</label>
                                    <input
                                        type="date"
                                        value={data.fecha}
                                        onChange={(e) => setData('fecha', e.target.value)}
                                        className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        required
                                    />
                                    {errors.fecha && <div className="text-red-600 text-sm mt-1">{errors.fecha}</div>}
                                </div>
                            </div>

                            <div>
                                <div className="flex justify-between items-center mb-3">
                                    <h3 className="text-lg font-semibold text-gray-900">Detalles de la Venta</h3>
                                    <button
                                        type="button"
                                        onClick={agregarDetalle}
                                        className="px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700 transition"
                                    >
                                        + Agregar Material
                                    </button>
                                </div>

                                <div className="overflow-x-auto">
                                    <table className="min-w-full divide-y divide-gray-200">
                                        <thead className="bg-gray-50">
                                            <tr>
                                                <th className="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Material</th>
                                                <th className="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                                                <th className="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Cantidad</th>
                                                <th className="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Precio Unitario</th>
                                                <th className="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                                                <th className="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody className="bg-white divide-y divide-gray-200">
                                            {detalles.map((detalle, index) => (
                                                <tr key={index}>
                                                    <td className="px-4 py-2">
                                                        <select
                                                            value={detalle.material_id}
                                                            onChange={(e) => actualizarDetalle(index, 'material_id', e.target.value)}
                                                            className="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                            required
                                                        >
                                                            <option value="">Seleccionar</option>
                                                            {materiales.map((material) => (
                                                                <option key={material.id} value={material.id}>
                                                                    {material.nombre}
                                                                </option>
                                                            ))}
                                                        </select>
                                                    </td>
                                                    <td className="px-4 py-2">
                                                        {detalle.material_id && (
                                                            <span className={`text-sm font-semibold ${obtenerStockDisponible(detalle.material_id) < 10 ? 'text-red-600' : 'text-gray-900'}`}>
                                                                {obtenerStockDisponible(detalle.material_id)}
                                                            </span>
                                                        )}
                                                    </td>
                                                    <td className="px-4 py-2">
                                                        <input
                                                            type="number"
                                                            step="0.01"
                                                            value={detalle.cantidad}
                                                            onChange={(e) => actualizarDetalle(index, 'cantidad', e.target.value)}
                                                            className={`block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 ${!verificarStock(index) ? 'border-red-500' : ''}`}
                                                            required
                                                        />
                                                        {!verificarStock(index) && (
                                                            <span className="text-xs text-red-600">Stock insuficiente</span>
                                                        )}
                                                    </td>
                                                    <td className="px-4 py-2">
                                                        <input
                                                            type="number"
                                                            step="0.01"
                                                            value={detalle.precio_unitario}
                                                            onChange={(e) => actualizarDetalle(index, 'precio_unitario', e.target.value)}
                                                            className="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                            required
                                                        />
                                                    </td>
                                                    <td className="px-4 py-2 text-sm font-semibold">
                                                        {formatCurrency((parseFloat(detalle.cantidad) || 0) * (parseFloat(detalle.precio_unitario) || 0))}
                                                    </td>
                                                    <td className="px-4 py-2">
                                                        <button
                                                            type="button"
                                                            onClick={() => removerDetalle(index)}
                                                            className="text-red-600 hover:text-red-900"
                                                            disabled={detalles.length === 1}
                                                        >
                                                            Eliminar
                                                        </button>
                                                    </td>
                                                </tr>
                                            ))}
                                        </tbody>
                                        <tfoot className="bg-gray-50">
                                            <tr>
                                                <td colSpan="4" className="px-4 py-3 text-right font-semibold">Total:</td>
                                                <td className="px-4 py-3 text-lg font-bold text-green-600">{formatCurrency(calcularTotal())}</td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                {errors.detalles && <div className="text-red-600 text-sm mt-1">{errors.detalles}</div>}
                            </div>

                            <div className="flex justify-end gap-3">
                                <Link
                                    href={route('ventas.index')}
                                    className="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition"
                                >
                                    Cancelar
                                </Link>
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition disabled:opacity-50"
                                >
                                    {processing ? 'Guardando...' : 'Guardar Venta'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
