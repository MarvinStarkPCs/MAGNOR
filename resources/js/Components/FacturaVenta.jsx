export default function FacturaVenta({ venta }) {
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

    return (
        <div className="factura-container hidden print:block">
            {/* Header */}
            <div className="text-center mb-4 border-b-2 border-green-700 pb-3">
                <img
                    src="/images/logo_factura.png"
                    alt="MAGNOR"
                    className="mx-auto mb-2"
                    style={{ maxWidth: '120px', height: 'auto' }}
                    onError={(e) => {
                        e.target.style.display = 'none';
                        e.target.nextElementSibling.style.display = 'block';
                    }}
                />
                <h1 className="text-2xl font-bold text-green-700" style={{ display: 'none' }}>MAGNOR</h1>
                <p className="text-xs text-gray-600">
                    Sistema de Gestión de Chatarrería | Soluciones en Reciclaje y Materiales
                </p>
                <h2 className="text-lg font-bold text-green-700 mt-2">FACTURA DE VENTA</h2>
            </div>

            {/* Invoice Info */}
            <div className="flex justify-between mb-3 text-xs">
                <div className="w-1/2">
                    <div className="bg-gray-50 p-2 rounded mb-2">
                        <h3 className="text-sm font-bold text-green-700 border-b border-gray-300 mb-1">Información de la Venta</h3>
                        <p><strong>No. Factura:</strong> #{String(venta.id).padStart(6, '0')}</p>
                        <p><strong>Fecha:</strong> {formatDate(venta.fecha)}</p>
                        <p><strong>Emisión:</strong> {new Date().toLocaleString('es-CO')}</p>
                    </div>
                </div>

                <div className="w-1/2 pl-2">
                    {venta.cliente ? (
                        <div className="bg-gray-50 p-2 rounded">
                            <h3 className="text-sm font-bold text-green-700 border-b border-gray-300 mb-1">Cliente</h3>
                            <p><strong>Nombre:</strong> {venta.cliente.nombre}</p>
                            {venta.cliente.documento && <p><strong>Documento:</strong> {venta.cliente.documento}</p>}
                            {venta.cliente.telefono && <p><strong>Teléfono:</strong> {venta.cliente.telefono}</p>}
                            {venta.cliente.direccion && <p><strong>Dirección:</strong> {venta.cliente.direccion}</p>}
                        </div>
                    ) : (
                        <div className="bg-gray-50 p-2 rounded">
                            <h3 className="text-sm font-bold text-green-700 border-b border-gray-300 mb-1">Cliente</h3>
                            <p className="text-gray-500 italic">Cliente General</p>
                        </div>
                    )}
                </div>
            </div>

            {/* Items Table */}
            <div className="mb-3">
                <h3 className="text-sm font-bold text-green-700 border-b border-green-700 mb-2">Detalle de Materiales</h3>
                <table className="w-full text-xs border-collapse">
                    <thead className="bg-green-700 text-white">
                        <tr>
                            <th className="p-2 text-left">#</th>
                            <th className="p-2 text-left">Material</th>
                            <th className="p-2 text-center">Cantidad</th>
                            <th className="p-2 text-right">Precio Unitario</th>
                            <th className="p-2 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        {venta.detalles && venta.detalles.map((detalle, index) => (
                            <tr key={detalle.id} className={index % 2 === 0 ? 'bg-gray-50' : ''}>
                                <td className="p-2 text-center border-b">{index + 1}</td>
                                <td className="p-2 border-b">
                                    <strong>{detalle.material?.nombre || 'Material'}</strong>
                                </td>
                                <td className="p-2 text-center border-b">
                                    {detalle.cantidad} {detalle.material?.unidad_medida || 'kg'}
                                </td>
                                <td className="p-2 text-right border-b">{formatCurrency(detalle.precio_unitario)}</td>
                                <td className="p-2 text-right border-b font-semibold">{formatCurrency(detalle.subtotal)}</td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>

            {/* Totals */}
            <div className="flex justify-end mb-3">
                <div className="w-64">
                    <table className="w-full text-xs">
                        <tbody>
                            <tr>
                                <td className="p-2 font-bold">Subtotal:</td>
                                <td className="p-2 text-right">{formatCurrency(venta.total * 0.85)}</td>
                            </tr>
                            <tr>
                                <td className="p-2 font-bold">IVA (15%):</td>
                                <td className="p-2 text-right">{formatCurrency(venta.total * 0.15)}</td>
                            </tr>
                            <tr className="bg-green-700 text-white font-bold">
                                <td className="p-2">TOTAL:</td>
                                <td className="p-2 text-right text-lg">{formatCurrency(venta.total)} COP</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {/* Observations */}
            {venta.observaciones && (
                <div className="mb-3 bg-gray-50 p-2 rounded">
                    <h3 className="text-sm font-bold text-green-700 mb-1">Observaciones</h3>
                    <p className="text-xs">{venta.observaciones}</p>
                </div>
            )}

            {/* Footer */}
            <div className="mt-4 pt-2 border-t border-green-700 text-center text-xs text-gray-500">
                <p>Documento generado el {new Date().toLocaleString('es-CO')}</p>
                <p className="font-semibold">MAGNOR - Sistema de Gestión de Chatarrería</p>
            </div>
        </div>
    );
}
