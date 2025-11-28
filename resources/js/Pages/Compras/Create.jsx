import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, useForm } from '@inertiajs/react';
import { useState } from 'react';

export default function Create({ proveedores, materiales }) {
    const { data, setData, post, processing, errors } = useForm({
        proveedor_id: '',
        fecha_compra: new Date().toISOString().split('T')[0],
        detalles: [],
    });

    const [detalles, setDetalles] = useState([
        { material_id: '', cantidad: '', precio_unitario: '' }
    ]);

    const handleSubmit = (e) => {
        e.preventDefault();

        // Convertir comas a puntos antes de enviar
        const detallesConvertidos = detalles.map(detalle => ({
            ...detalle,
            cantidad: parseNumero(detalle.cantidad),
            precio_unitario: parseNumero(detalle.precio_unitario)
        }));

        data.detalles = detallesConvertidos;
        post(route('compras.store'));
    };

    const agregarDetalle = () => {
        setDetalles([...detalles, { material_id: '', cantidad: '', precio_unitario: '' }]);
    };

    const removerDetalle = (index) => {
        const nuevosDetalles = detalles.filter((_, i) => i !== index);
        setDetalles(nuevosDetalles);
    };

    // Función para formatear números con coma decimal
    const formatearNumero = (valor) => {
        if (!valor) return '';
        return valor.toString().replace('.', ',');
    };

    // Función para convertir coma a punto para cálculos
    const parseNumero = (valor) => {
        if (!valor) return 0;
        return parseFloat(valor.toString().replace(',', '.')) || 0;
    };

    const actualizarDetalle = (index, campo, valor) => {
        const nuevosDetalles = [...detalles];

        // Si es un campo numérico, permitir solo números y coma
        if (campo === 'cantidad' || campo === 'precio_unitario') {
            // Validar entrada: solo números, coma y un solo separador decimal
            const regex = /^[0-9]*,?[0-9]*$/;
            if (!regex.test(valor) && valor !== '') {
                return; // No actualizar si no es válido
            }
        }

        nuevosDetalles[index][campo] = valor;

        // Si se seleccionó un material, auto-completar el precio del día
        if (campo === 'material_id' && valor) {
            const material = materiales.find(m => m.id == valor);
            if (material && material.precio_dia_compra) {
                nuevosDetalles[index]['precio_unitario'] = formatearNumero(material.precio_dia_compra);
            }
        }

        setDetalles(nuevosDetalles);
    };

    const calcularTotal = () => {
        return detalles.reduce((total, detalle) => {
            const cantidad = parseNumero(detalle.cantidad);
            const precio = parseNumero(detalle.precio_unitario);
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

    return (
        <AuthenticatedLayout
            header={
                <div className="flex justify-between items-center">
                    <h2 className="text-xl font-semibold text-gray-800">Nueva Compra</h2>
                    <Link
                        href={route('compras.index')}
                        className="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition"
                    >
                        Volver
                    </Link>
                </div>
            }
        >
            <Head title="Nueva Compra" />

            <div className="py-12">
                <div className="mx-auto max-w-6xl sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <form onSubmit={handleSubmit} className="p-6 space-y-6">
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label className="block text-sm font-medium text-gray-700">Proveedor *</label>
                                    <select
                                        value={data.proveedor_id}
                                        onChange={(e) => setData('proveedor_id', e.target.value)}
                                        className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        required
                                    >
                                        <option value="">Seleccionar proveedor</option>
                                        {proveedores.map((proveedor) => (
                                            <option key={proveedor.id} value={proveedor.id}>
                                                {proveedor.nombre} - {proveedor.documento}
                                            </option>
                                        ))}
                                    </select>
                                    {errors.proveedor_id && <div className="text-red-600 text-sm mt-1">{errors.proveedor_id}</div>}
                                </div>

                                <div>
                                    <label className="block text-sm font-medium text-gray-700">Fecha de Compra *</label>
                                    <input
                                        type="date"
                                        value={data.fecha_compra}
                                        onChange={(e) => setData('fecha_compra', e.target.value)}
                                        className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        required
                                    />
                                    <p className="mt-1 text-xs text-gray-500">Se establece automáticamente con la fecha actual</p>
                                    {errors.fecha_compra && <div className="text-red-600 text-sm mt-1">{errors.fecha_compra}</div>}
                                </div>
                            </div>

                            <div>
                                <div className="mb-3">
                                    <div className="flex justify-between items-center">
                                        <h3 className="text-lg font-semibold text-gray-900">Detalles de la Compra</h3>
                                        <button
                                            type="button"
                                            onClick={agregarDetalle}
                                            className="px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700 transition"
                                        >
                                            + Agregar Material
                                        </button>
                                    </div>
                                    <p className="mt-2 text-xs text-blue-600 bg-blue-50 p-2 rounded">
                                        💡 Los precios unitarios se completarán automáticamente con el precio del día al seleccionar un material.
                                        Use coma (,) como separador decimal, ejemplo: 10,50
                                    </p>
                                </div>

                                <div className="overflow-x-auto">
                                    <table className="min-w-full divide-y divide-gray-200">
                                        <thead className="bg-gray-50">
                                            <tr>
                                                <th className="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Material</th>
                                                <th className="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Unidad</th>
                                                <th className="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Cantidad</th>
                                                <th className="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Precio Unitario</th>
                                                <th className="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                                                <th className="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody className="bg-white divide-y divide-gray-200">
                                            {detalles.map((detalle, index) => {
                                                const materialSeleccionado = materiales.find(m => m.id == detalle.material_id);
                                                return (
                                                    <tr key={index}>
                                                        <td className="px-4 py-2">
                                                            <select
                                                                value={detalle.material_id}
                                                                onChange={(e) => actualizarDetalle(index, 'material_id', e.target.value)}
                                                                className="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                                                                required
                                                            >
                                                                <option value="">Seleccionar material...</option>
                                                                {materiales.map((material) => (
                                                                    <option key={material.id} value={material.id}>
                                                                        {material.nombre} ({material.unidad_medida})
                                                                        {material.precio_dia_compra ? ` - ${formatCurrency(material.precio_dia_compra)}` : ''}
                                                                    </option>
                                                                ))}
                                                            </select>
                                                        </td>
                                                        <td className="px-4 py-2 text-sm text-gray-600">
                                                            {materialSeleccionado ? materialSeleccionado.unidad_medida : '-'}
                                                        </td>
                                                        <td className="px-4 py-2">
                                                            <input
                                                                type="text"
                                                                value={detalle.cantidad}
                                                                onChange={(e) => actualizarDetalle(index, 'cantidad', e.target.value)}
                                                                className="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                                placeholder={materialSeleccionado ? `Ej: 10,50 ${materialSeleccionado.unidad_medida}` : 'Ej: 10,50'}
                                                                required
                                                            />
                                                        </td>
                                                    <td className="px-4 py-2">
                                                        <input
                                                            type="text"
                                                            value={detalle.precio_unitario}
                                                            onChange={(e) => actualizarDetalle(index, 'precio_unitario', e.target.value)}
                                                            className="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                            placeholder={materialSeleccionado?.precio_dia_compra ? 'Precio del día cargado' : 'Ej: 15000,50'}
                                                            required
                                                        />
                                                    </td>
                                                    <td className="px-4 py-2 text-sm font-semibold">
                                                        {formatCurrency(parseNumero(detalle.cantidad) * parseNumero(detalle.precio_unitario))}
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
                                                );
                                            })}
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
                                    href={route('compras.index')}
                                    className="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition"
                                >
                                    Cancelar
                                </Link>
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition disabled:opacity-50"
                                >
                                    {processing ? 'Guardando...' : 'Guardar Compra'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
