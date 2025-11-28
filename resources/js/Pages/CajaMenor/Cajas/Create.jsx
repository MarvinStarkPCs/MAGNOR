import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, useForm } from '@inertiajs/react';

export default function Create() {
    const { data, setData, post, processing, errors } = useForm({
        monto_apertura: '',
        observaciones: '',
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('cajas.store'));
    };

    const today = new Date().toLocaleDateString('es-CO', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });

    return (
        <AuthenticatedLayout
            header={
                <div className="flex justify-between items-center">
                    <h2 className="text-xl font-semibold text-gray-800">Abrir Caja</h2>
                    <Link
                        href={route('cajas.index')}
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
            <Head title="Abrir Caja" />

            <div className="py-12">
                <div className="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
                    {/* Información del día */}
                    <div className="mb-6 bg-blue-50 border-l-4 border-blue-400 p-6 rounded-lg shadow">
                        <div className="flex items-center">
                            <svg className="w-8 h-8 text-blue-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <div>
                                <h3 className="text-lg font-semibold text-blue-800 capitalize">{today}</h3>
                                <p className="text-sm text-blue-700">Apertura de caja diaria</p>
                            </div>
                        </div>
                    </div>

                    {/* Formulario */}
                    <div className="bg-white shadow-sm sm:rounded-lg p-6">
                        <form onSubmit={handleSubmit} className="space-y-6">
                            {/* Monto de apertura */}
                            <div>
                                <label htmlFor="monto_apertura" className="block text-sm font-medium text-gray-700 mb-2">
                                    Monto de Apertura *
                                </label>
                                <div className="relative">
                                    <div className="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg className="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <input
                                        type="number"
                                        id="monto_apertura"
                                        value={data.monto_apertura}
                                        onChange={(e) => setData('monto_apertura', e.target.value)}
                                        step="0.01"
                                        min="0"
                                        className={`block w-full pl-12 pr-4 py-3 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent text-lg ${
                                            errors.monto_apertura ? 'border-red-500' : 'border-gray-300'
                                        }`}
                                        placeholder="0.00"
                                        required
                                        autoFocus
                                    />
                                </div>
                                {errors.monto_apertura && <p className="mt-1 text-sm text-red-600">{errors.monto_apertura}</p>}
                                <p className="mt-2 text-sm text-gray-500">
                                    Ingrese el efectivo con el que se abre la caja del día
                                </p>
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
                                    rows="4"
                                    className={`block w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent ${
                                        errors.observaciones ? 'border-red-500' : 'border-gray-300'
                                    }`}
                                    placeholder="Notas adicionales sobre la apertura de caja (opcional)"
                                ></textarea>
                                {errors.observaciones && <p className="mt-1 text-sm text-red-600">{errors.observaciones}</p>}
                            </div>

                            {/* Info sobre qué pasa después */}
                            <div className="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <div className="flex items-start">
                                    <svg className="w-5 h-5 text-gray-500 mt-0.5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div>
                                        <h4 className="text-sm font-medium text-gray-900 mb-1">¿Qué sucede después?</h4>
                                        <ul className="text-sm text-gray-600 space-y-1 list-disc list-inside">
                                            <li>Se registrará la apertura de caja con el usuario actual</li>
                                            <li>Podrá comenzar a registrar ingresos y egresos</li>
                                            <li>Al final del día, deberá cerrar la caja con el monto final</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            {/* Botones */}
                            <div className="flex items-center justify-end gap-4 pt-6 border-t">
                                <Link
                                    href={route('cajas.index')}
                                    className="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-semibold"
                                >
                                    Cancelar
                                </Link>
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="inline-flex items-center gap-2 px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition font-semibold disabled:opacity-50 disabled:cursor-not-allowed shadow-md"
                                >
                                    {processing ? (
                                        <>
                                            <svg className="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                                                <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                                <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Abriendo caja...
                                        </>
                                    ) : (
                                        <>
                                            <svg className="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                            </svg>
                                            Abrir Caja
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
