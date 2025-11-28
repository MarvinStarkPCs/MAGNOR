import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, usePage } from '@inertiajs/react';
import FacturaCompra from '@/Components/FacturaCompra';
import Modal from '@/Components/Modal';
import { useState, useEffect } from 'react';

export default function Show({ compra }) {
    const { flash } = usePage().props;
    const [mostrarModal, setMostrarModal] = useState(false);

    useEffect(() => {
        if (flash?.mostrarModalFactura) {
            setMostrarModal(true);
        }
    }, [flash]);

    const formatCurrency = (value) => {
        return new Intl.NumberFormat('es-CO', {
            style: 'currency',
            currency: 'COP',
            minimumFractionDigits: 0,
        }).format(value);
    };

    const formatDate = (date) => {
        return new Date(date).toLocaleDateString('es-CO', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    };

    const handlePrint = () => {
        setMostrarModal(false);
        setTimeout(() => window.print(), 100);
    };

    const generarFacturaPDF = () => {
        setMostrarModal(false);
        window.open(route('compras.factura', compra.id), '_blank');
    };

    const cerrarModal = () => {
        setMostrarModal(false);
    };

    return (
        <AuthenticatedLayout
            header={
                <div className="flex justify-between items-center">
                    <h2 className="text-xl font-semibold text-gray-800">Detalle de Compra</h2>
                    <div className="flex gap-2">
                        <button
                            onClick={handlePrint}
                            className="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                        >
                            <svg className="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            Imprimir
                        </button>
                        <button
                            onClick={generarFacturaPDF}
                            className="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition"
                        >
                            <svg className="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Descargar PDF
                        </button>
                        <Link
                            href={route('compras.index')}
                            className="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition"
                        >
                            Volver
                        </Link>
                    </div>
                </div>
            }
        >
            <Head title={`Compra #${compra.id}`} />

            <div className="py-12">
                <div className="mx-auto max-w-6xl sm:px-6 lg:px-8 space-y-6">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 className="text-lg font-semibold text-gray-900 mb-4">Información General</h3>
                        <dl className="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <dt className="text-sm font-medium text-gray-500">Fecha</dt>
                                <dd className="mt-1 text-sm text-gray-900">{formatDate(compra.fecha)}</dd>
                            </div>
                            <div>
                                <dt className="text-sm font-medium text-gray-500">Proveedor</dt>
                                <dd className="mt-1 text-sm text-gray-900">
                                    {compra.proveedor?.nombre || 'N/A'}
                                    {compra.proveedor?.documento && (
                                        <span className="text-gray-500"> - {compra.proveedor.documento}</span>
                                    )}
                                </dd>
                            </div>
                            <div>
                                <dt className="text-sm font-medium text-gray-500">Total</dt>
                                <dd className="mt-1 text-lg font-bold text-green-600">{formatCurrency(compra.total)}</dd>
                            </div>
                        </dl>
                    </div>

                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 className="text-lg font-semibold text-gray-900 mb-4">Detalles de la Compra</h3>
                        <div className="overflow-x-auto">
                            <table className="min-w-full divide-y divide-gray-200">
                                <thead className="bg-gray-50">
                                    <tr>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Material
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Cantidad
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Precio Unitario
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Subtotal
                                        </th>
                                    </tr>
                                </thead>
                                <tbody className="bg-white divide-y divide-gray-200">
                                    {compra.detalles && compra.detalles.length > 0 ? (
                                        compra.detalles.map((detalle) => (
                                            <tr key={detalle.id}>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <div className="text-sm font-medium text-gray-900">
                                                        {detalle.material?.nombre || 'N/A'}
                                                    </div>
                                                    <div className="text-sm text-gray-500">
                                                        {detalle.material?.unidad_medida || ''}
                                                    </div>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {detalle.cantidad}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {formatCurrency(detalle.precio_unitario)}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                                    {formatCurrency(detalle.subtotal)}
                                                </td>
                                            </tr>
                                        ))
                                    ) : (
                                        <tr>
                                            <td colSpan="4" className="px-6 py-4 text-center text-gray-500">
                                                No hay detalles disponibles
                                            </td>
                                        </tr>
                                    )}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {/* Factura para impresión */}
            <FacturaCompra compra={compra} />

            {/* Modal para opciones de factura */}
            <Modal show={mostrarModal} onClose={cerrarModal} maxWidth="md">
                <div className="p-6">
                    <div className="flex items-center justify-center mb-4">
                        <div className="flex h-12 w-12 items-center justify-center rounded-full bg-green-100">
                            <svg className="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>

                    <h3 className="text-lg font-medium text-center text-gray-900 mb-2">
                        ¡Compra registrada exitosamente!
                    </h3>

                    <p className="text-sm text-gray-500 text-center mb-6">
                        ¿Deseas imprimir o descargar la factura?
                    </p>

                    <div className="flex flex-col gap-3">
                        <button
                            onClick={handlePrint}
                            className="inline-flex justify-center items-center gap-2 w-full px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                        >
                            <svg className="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            Imprimir Factura
                        </button>

                        <button
                            onClick={generarFacturaPDF}
                            className="inline-flex justify-center items-center gap-2 w-full px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition"
                        >
                            <svg className="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Descargar PDF
                        </button>

                        <button
                            onClick={cerrarModal}
                            className="inline-flex justify-center items-center gap-2 w-full px-4 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition"
                        >
                            Cerrar
                        </button>
                    </div>
                </div>
            </Modal>
        </AuthenticatedLayout>
    );
}
