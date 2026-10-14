import AppLayout from '@/layouts/AppLayout';
import { Link } from '@inertiajs/react';
import { ArrowLeft, Download, Pencil, Printer } from 'lucide-react';

interface Props {
    recibo: {
        id: number; numero: number; fecha: string; subtotal: number; total: number; retefuente: number;
        anulado: boolean; anulado_motivo: string | null; observaciones: string | null;
        reciclador: { nombre: string; documento: string; telefono: string | null; direccion: string | null; municipio: { nombre: string } | null };
        detalles: { id: number; kg_bruto: number; kg_rechazado: number; kg_neto: number; precio_kg: number; subtotal: number; material: { nombre: string; familia: { nombre: string } } }[];
    };
}

function fmtCop(n: number) { return '$' + new Intl.NumberFormat('es-CO').format(Math.round(n ?? 0)); }
function fmtFecha(s: string) { return s ? s.substring(0, 10).split('-').reverse().join('/') : '—'; }

export default function ReciboShow({ recibo }: Props) {
    const totalKgBruto = recibo.detalles.reduce((s, d) => s + +d.kg_bruto, 0);
    const totalKgNeto  = recibo.detalles.reduce((s, d) => s + +d.kg_neto, 0);

    return (
        <AppLayout>
            <div className="max-w-3xl mx-auto">
                <div className="flex items-center justify-between mb-6">
                    <div className="flex items-center gap-3">
                        <Link href="/recibos" className="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-xl transition-colors">
                            <ArrowLeft size={18} />
                        </Link>
                        <div>
                            <h1 className="text-xl font-bold text-gray-900">Recibo #{recibo.numero}</h1>
                            <p className="text-sm text-gray-500">{fmtFecha(recibo.fecha)}</p>
                        </div>
                    </div>
                    <div className="flex items-center gap-2">
                        {!recibo.anulado && (
                            <Link href={`/recibos/${recibo.id}/edit`}
                                className="inline-flex items-center gap-2 text-sm text-white bg-green-700 px-4 py-2 rounded-xl hover:bg-green-800 transition-colors">
                                <Pencil size={15} /> Editar
                            </Link>
                        )}
                        <a href={`/recibos/${recibo.id}/pdf`} target="_blank"
                            className="inline-flex items-center gap-2 text-sm text-gray-600 border border-gray-200 px-4 py-2 rounded-xl hover:bg-gray-50 transition-colors">
                            <Download size={15} /> PDF
                        </a>
                        <button onClick={() => window.print()}
                            className="inline-flex items-center gap-2 text-sm text-gray-600 border border-gray-200 px-4 py-2 rounded-xl hover:bg-gray-50 transition-colors">
                            <Printer size={15} /> Imprimir
                        </button>
                    </div>
                </div>

                {recibo.anulado && (
                    <div className="bg-red-50 border border-red-200 rounded-xl p-4 mb-5 text-sm text-red-700">
                        <strong>Recibo anulado.</strong>{recibo.anulado_motivo && ` Motivo: ${recibo.anulado_motivo}`}
                    </div>
                )}

                <div className="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    {/* Encabezado */}
                    <div className="bg-green-800 text-white px-6 py-5">
                        <div className="flex justify-between items-start">
                            <div>
                                <h2 className="font-bold text-lg">ARECUPERAR</h2>
                                <p className="text-green-200 text-sm">Recibo de materiales reciclables</p>
                            </div>
                            <div className="text-right">
                                <p className="text-2xl font-bold">#{recibo.numero}</p>
                                <p className="text-green-200 text-sm">{fmtFecha(recibo.fecha)}</p>
                            </div>
                        </div>
                    </div>

                    {/* Datos del reciclador */}
                    <div className="px-6 py-4 bg-gray-50 border-b border-gray-100">
                        <div className="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p className="text-xs text-gray-500 font-semibold uppercase tracking-wide">Reciclador</p>
                                <p className="font-medium text-gray-800 mt-0.5">{recibo.reciclador.nombre}</p>
                                <p className="text-gray-500">CC {recibo.reciclador.documento}</p>
                            </div>
                            <div>
                                <p className="text-xs text-gray-500 font-semibold uppercase tracking-wide">Contacto</p>
                                <p className="text-gray-700 mt-0.5">{recibo.reciclador.telefono ?? '—'}</p>
                                <p className="text-gray-500">{recibo.reciclador.municipio?.nombre ?? '—'}</p>
                            </div>
                        </div>
                    </div>

                    {/* Tabla de materiales */}
                    <div className="overflow-x-auto">
                        <table className="w-full text-sm">
                            <thead>
                                <tr className="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                    <th className="text-left px-6 py-3">Material</th>
                                    <th className="text-right px-4 py-3">KG Bruto</th>
                                    <th className="text-right px-4 py-3">KG Rechazo</th>
                                    <th className="text-right px-4 py-3">KG Neto</th>
                                    <th className="text-right px-4 py-3">$/kg</th>
                                    <th className="text-right px-6 py-3">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-gray-50">
                                {recibo.detalles.map(d => (
                                    <tr key={d.id} className="hover:bg-gray-50">
                                        <td className="px-6 py-3">
                                            <p className="font-medium text-gray-800">{d.material.nombre}</p>
                                            <p className="text-xs text-gray-400">{d.material.familia.nombre}</p>
                                        </td>
                                        <td className="px-4 py-3 text-right text-gray-600">{(+d.kg_bruto).toFixed(2)}</td>
                                        <td className="px-4 py-3 text-right text-gray-500">{(+d.kg_rechazado).toFixed(2)}</td>
                                        <td className="px-4 py-3 text-right font-medium text-gray-700">{(+d.kg_neto).toFixed(2)}</td>
                                        <td className="px-4 py-3 text-right text-gray-600">{fmtCop(+d.precio_kg)}</td>
                                        <td className="px-6 py-3 text-right font-bold text-gray-900">{fmtCop(+d.subtotal)}</td>
                                    </tr>
                                ))}
                            </tbody>
                            <tfoot className="bg-gray-50 border-t-2 border-gray-200">
                                <tr>
                                    <td className="px-6 py-3 font-semibold text-gray-700">Totales</td>
                                    <td className="px-4 py-3 text-right font-semibold text-gray-700">{totalKgBruto.toFixed(2)}</td>
                                    <td className="px-4 py-3 text-right text-gray-500">—</td>
                                    <td className="px-4 py-3 text-right font-semibold text-gray-700">{totalKgNeto.toFixed(2)}</td>
                                    <td className="px-4 py-3" />
                                    <td className="px-6 py-3 text-right">
                                        <span className="text-xl font-bold text-green-700">{fmtCop(+recibo.total)}</span>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    {recibo.observaciones && (
                        <div className="px-6 py-4 border-t border-gray-100">
                            <p className="text-xs text-gray-500 font-semibold uppercase tracking-wide mb-1">Observaciones</p>
                            <p className="text-sm text-gray-700">{recibo.observaciones}</p>
                        </div>
                    )}
                </div>
            </div>
        </AppLayout>
    );
}
