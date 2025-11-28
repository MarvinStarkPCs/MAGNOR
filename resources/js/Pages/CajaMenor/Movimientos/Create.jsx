import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, useForm } from '@inertiajs/react';
import { useState } from 'react';

export default function Create({ cajaAbierta, categorias }) {
    const { data, setData, post, processing, errors } = useForm({
        categoria_id: '',
        tipo: '',
        monto: '',
        concepto: '',
        observaciones: '',
        comprobante: null,
    });

    const [categoriasFiltradas, setCategoriasFiltradas] = useState(categorias);

    const handleTipoChange = (e) => {
        const tipo = e.target.value;
        setData('tipo', tipo);
        setData('categoria_id', '');

        if (tipo) {
            const filtradas = categorias.filter(cat => cat.tipo === tipo);
            setCategoriasFiltradas(filtradas);
        } else {
            setCategoriasFiltradas(categorias);
        }
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('movimientos-caja.store'));
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
                    <h2 className="text-xl font-semibold text-gray-800">Nuevo Movimiento de Caja</h2>
                    <Link
                        href={route('movimientos-caja.index')}
                        className="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition font-semibold"
                    >
                        <svg className="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Volver
                    </Link>
                </div>
            }
        >
            <Head title="Nuevo Movimiento" />

            <div className="py-12">
                <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                    {/* Información de la caja */}
                    <div className="mb-6 bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg shadow">
                        <div className="flex items-center">
                            <svg className="w-6 h-6 text-blue-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <p className="font-semibold text-blue-800">Caja del {new Date(cajaAbierta.fecha).toLocaleDateString('es-CO')}</p>
                                <p className="text-sm text-blue-700">Monto de apertura: {formatCurrency(cajaAbierta.monto_apertura)}</p>
                            </div>
                        </div>
                    </div>

                    {/* Formulario */}
                    <div className="bg-white shadow-sm sm:rounded-lg p-6">
                        <form onSubmit={handleSubmit} className="space-y-6">
                            {/* Tipo de movimiento */}
                            <div>
                                <label htmlFor="tipo" className="block text-sm font-medium text-gray-700 mb-2">
                                    Tipo de Movimiento *
                                </label>
                                <select
                                    id="tipo"
                                    value={data.tipo}
                                    onChange={handleTipoChange}
                                    className={`block w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent ${
                                        errors.tipo ? 'border-red-500' : 'border-gray-300'
                                    }`}
                                    required
                                >
                                    <option value="">Seleccione un tipo</option>
                                    <option value="ingreso">Ingreso</option>
                                    <option value="egreso">Egreso</option>
                                </select>
                                {errors.tipo && <p className="mt-1 text-sm text-red-600">{errors.tipo}</p>}
                            </div>

                            {/* Categoría */}
                            <div>
                                <label htmlFor="categoria_id" className="block text-sm font-medium text-gray-700 mb-2">
                                    Categoría *
                                </label>
                                <select
                                    id="categoria_id"
                                    value={data.categoria_id}
                                    onChange={(e) => setData('categoria_id', e.target.value)}
                                    className={`block w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent ${
                                        errors.categoria_id ? 'border-red-500' : 'border-gray-300'
                                    }`}
                                    required
                                    disabled={!data.tipo}
                                >
                                    <option value="">Seleccione una categoría</option>
                                    {categoriasFiltradas.map((categoria) => (
                                        <option key={categoria.id} value={categoria.id}>
                                            {categoria.nombre}
                                        </option>
                                    ))}
                                </select>
                                {errors.categoria_id && <p className="mt-1 text-sm text-red-600">{errors.categoria_id}</p>}
                                {!data.tipo && (
                                    <p className="mt-1 text-sm text-gray-500">Primero seleccione el tipo de movimiento</p>
                                )}
                            </div>

                            {/* Monto */}
                            <div>
                                <label htmlFor="monto" className="block text-sm font-medium text-gray-700 mb-2">
                                    Monto *
                                </label>
                                <div className="relative">
                                    <div className="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <span className="text-gray-500 font-medium">$</span>
                                    </div>
                                    <input
                                        type="number"
                                        id="monto"
                                        value={data.monto}
                                        onChange={(e) => setData('monto', e.target.value)}
                                        step="0.01"
                                        min="0.01"
                                        className={`block w-full pl-8 pr-4 py-3 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent ${
                                            errors.monto ? 'border-red-500' : 'border-gray-300'
                                        }`}
                                        placeholder="0.00"
                                        required
                                    />
                                </div>
                                {errors.monto && <p className="mt-1 text-sm text-red-600">{errors.monto}</p>}
                            </div>

                            {/* Concepto */}
                            <div>
                                <label htmlFor="concepto" className="block text-sm font-medium text-gray-700 mb-2">
                                    Concepto *
                                </label>
                                <input
                                    type="text"
                                    id="concepto"
                                    value={data.concepto}
                                    onChange={(e) => setData('concepto', e.target.value)}
                                    className={`block w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent ${
                                        errors.concepto ? 'border-red-500' : 'border-gray-300'
                                    }`}
                                    placeholder="Descripción del movimiento"
                                    required
                                />
                                {errors.concepto && <p className="mt-1 text-sm text-red-600">{errors.concepto}</p>}
                            </div>

                            {/* Observaciones */}
                            <div>
                                <label htmlFor="observaciones" className="block text-sm font-medium text-gray-700 mb-2">
                                    Observaciones
                                </label>
                                <textarea
                                    id="observaciones"
                                    value={data.observaciones}
                                    onChange={(e) => setData('observaciones', e.target.value)}
                                    rows="3"
                                    className={`block w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent ${
                                        errors.observaciones ? 'border-red-500' : 'border-gray-300'
                                    }`}
                                    placeholder="Información adicional (opcional)"
                                ></textarea>
                                {errors.observaciones && <p className="mt-1 text-sm text-red-600">{errors.observaciones}</p>}
                            </div>

                            {/* Comprobante */}
                            <div>
                                <label htmlFor="comprobante" className="block text-sm font-medium text-gray-700 mb-2">
                                    Comprobante/Factura
                                </label>
                                <div className="flex items-center gap-4">
                                    <label className="flex-1 flex items-center justify-center px-4 py-6 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer hover:border-primary-500 transition">
                                        <div className="text-center">
                                            <svg className="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                            </svg>
                                            <div className="mt-2">
                                                <span className="text-sm font-medium text-primary-600">Subir archivo</span>
                                                <p className="text-xs text-gray-500">PDF, JPG, PNG hasta 2MB</p>
                                            </div>
                                        </div>
                                        <input
                                            id="comprobante"
                                            type="file"
                                            className="hidden"
                                            accept=".pdf,.jpg,.jpeg,.png"
                                            onChange={(e) => setData('comprobante', e.target.files[0])}
                                        />
                                    </label>
                                    {data.comprobante && (
                                        <div className="flex items-center gap-2 px-4 py-2 bg-green-50 border border-green-200 rounded-lg">
                                            <svg className="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span className="text-sm text-green-800">{data.comprobante.name}</span>
                                            <button
                                                type="button"
                                                onClick={() => setData('comprobante', null)}
                                                className="text-green-600 hover:text-green-800"
                                            >
                                                <svg className="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    )}
                                </div>
                                {errors.comprobante && <p className="mt-1 text-sm text-red-600">{errors.comprobante}</p>}
                            </div>

                            {/* Botones */}
                            <div className="flex items-center justify-end gap-4 pt-6 border-t">
                                <Link
                                    href={route('movimientos-caja.index')}
                                    className="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-semibold"
                                >
                                    Cancelar
                                </Link>
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="inline-flex items-center gap-2 px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition font-semibold disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    {processing ? (
                                        <>
                                            <svg className="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                                                <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                                <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Guardando...
                                        </>
                                    ) : (
                                        <>
                                            <svg className="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                            </svg>
                                            Guardar Movimiento
                                        </>
                                    )}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
