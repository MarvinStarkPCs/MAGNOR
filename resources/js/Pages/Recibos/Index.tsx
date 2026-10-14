import AppLayout from '@/layouts/AppLayout';
import Flash from '@/components/ui/flash';
import { Link, router, useForm } from '@inertiajs/react';
import { Plus, Search, Eye, Ban } from 'lucide-react';
import { useState } from 'react';

interface Recibo {
    id: number; numero: number; fecha: string; anulado: boolean;
    subtotal: number; total: number; detalles_count: number; total_kg: number;
    reciclador: { nombre: string };
}
interface Props {
    recibos: { data: Recibo[]; links: any[]; meta: any };
    filtros: { search?: string; fecha_desde?: string; fecha_hasta?: string; anulado?: string };
}

function fmtCop(n: number) { return '$' + new Intl.NumberFormat('es-CO').format(Math.round(n ?? 0)); }
function fmtFecha(s: string) { return s ? s.substring(0, 10).split('-').reverse().join('/') : '—'; }

function AnularModal({ recibo, onClose }: { recibo: Recibo; onClose: () => void }) {
    const { data, setData, patch, processing } = useForm({ motivo: '' });
    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
            <div className="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
                <h3 className="text-lg font-bold text-gray-900 mb-1">Anular recibo #{recibo.numero}</h3>
                <p className="text-sm text-gray-500 mb-4">Esta acción no se puede deshacer. Ingresa el motivo:</p>
                <textarea value={data.motivo} onChange={e => setData('motivo', e.target.value)} rows={3}
                    placeholder="Motivo de anulación (mínimo 5 caracteres)..."
                    className="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-400 resize-none mb-4" />
                <div className="flex gap-3 justify-end">
                    <button onClick={onClose} className="px-4 py-2 text-sm text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-50">Cancelar</button>
                    <button disabled={processing || data.motivo.length < 5}
                        onClick={() => patch(`/recibos/${recibo.id}/anular`, { onSuccess: onClose })}
                        className="px-4 py-2 text-sm bg-red-600 hover:bg-red-700 disabled:opacity-50 text-white rounded-xl transition-colors">
                        {processing ? 'Anulando...' : 'Confirmar anulación'}
                    </button>
                </div>
            </div>
        </div>
    );
}

export default function RecibosIndex({ recibos, filtros }: Props) {
    const [search, setSearch] = useState(filtros.search ?? '');
    const [anular, setAnular] = useState<Recibo | null>(null);

    const filtrar = (extra = {}) => router.get('/recibos', { search, ...filtros, ...extra }, { preserveState: true });

    return (
        <AppLayout>
            <Flash />
            {anular && <AnularModal recibo={anular} onClose={() => setAnular(null)} />}

            <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
                <h1 className="text-xl font-bold text-gray-900">Recibos</h1>
                <Link href="/recibos/create"
                    className="inline-flex items-center gap-2 bg-green-700 hover:bg-green-800 text-white text-sm font-medium px-4 py-2.5 rounded-xl transition-colors">
                    <Plus size={16} /> Nuevo recibo
                </Link>
            </div>

            <div className="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-5 flex flex-wrap gap-3">
                <div className="relative flex-1 min-w-[180px]">
                    <Search size={16} className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
                    <input value={search} onChange={e => setSearch(e.target.value)} onKeyDown={e => e.key === 'Enter' && filtrar()}
                        placeholder="N° recibo o reciclador..." className="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" />
                </div>
                <input type="date" value={filtros.fecha_desde ?? ''} onChange={e => filtrar({ fecha_desde: e.target.value })}
                    className="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" />
                <input type="date" value={filtros.fecha_hasta ?? ''} onChange={e => filtrar({ fecha_hasta: e.target.value })}
                    className="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" />
                <select value={filtros.anulado ?? ''} onChange={e => filtrar({ anulado: e.target.value })}
                    className="text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">Todos</option>
                    <option value="0">Vigentes</option>
                    <option value="1">Anulados</option>
                </select>
            </div>

            <div className="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div className="overflow-x-auto">
                    <table className="w-full text-sm">
                        <thead className="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th className="text-left px-4 py-3 font-semibold text-gray-600"># Recibo</th>
                                <th className="text-left px-4 py-3 font-semibold text-gray-600">Fecha</th>
                                <th className="text-left px-4 py-3 font-semibold text-gray-600">Reciclador</th>
                                <th className="text-right px-4 py-3 font-semibold text-gray-600 hidden sm:table-cell">KG</th>
                                <th className="text-right px-4 py-3 font-semibold text-gray-600">Total</th>
                                <th className="text-left px-4 py-3 font-semibold text-gray-600">Estado</th>
                                <th className="px-4 py-3" />
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-50">
                            {recibos.data.length === 0 && (
                                <tr><td colSpan={7} className="text-center py-12 text-gray-400">No hay recibos registrados</td></tr>
                            )}
                            {recibos.data.map(r => (
                                <tr key={r.id} className={`hover:bg-gray-50 transition-colors ${r.anulado ? 'opacity-60' : ''}`}>
                                    <td className="px-4 py-3 font-bold text-green-700">#{r.numero}</td>
                                    <td className="px-4 py-3 text-gray-600">{fmtFecha(r.fecha)}</td>
                                    <td className="px-4 py-3 font-medium text-gray-800 max-w-[180px] truncate">{r.reciclador?.nombre}</td>
                                    <td className="px-4 py-3 text-right text-gray-600 hidden sm:table-cell">
                                        {r.total_kg ? (+r.total_kg).toFixed(1) + ' kg' : '—'}
                                    </td>
                                    <td className="px-4 py-3 text-right font-bold text-gray-900">{fmtCop(r.total)}</td>
                                    <td className="px-4 py-3">
                                        <span className={`inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium ${r.anulado ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-700'}`}>
                                            {r.anulado ? 'Anulado' : 'Vigente'}
                                        </span>
                                    </td>
                                    <td className="px-4 py-3">
                                        <div className="flex items-center gap-1 justify-end">
                                            <Link href={`/recibos/${r.id}`} className="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                                <Eye size={15} />
                                            </Link>
                                            {!r.anulado && (
                                                <button onClick={() => setAnular(r)} className="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                                    <Ban size={15} />
                                                </button>
                                            )}
                                        </div>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
                {recibos.meta?.last_page > 1 && (
                    <div className="flex items-center justify-between px-4 py-3 border-t border-gray-100">
                        <p className="text-xs text-gray-500">{recibos.meta.from}–{recibos.meta.to} de {recibos.meta.total}</p>
                        <div className="flex gap-1">
                            {recibos.links.map((l, i) => (
                                <button key={i} disabled={!l.url} onClick={() => l.url && router.get(l.url)}
                                    className={`px-3 py-1 text-xs rounded-lg ${l.active ? 'bg-green-700 text-white' : l.url ? 'text-gray-600 hover:bg-gray-100' : 'text-gray-300 cursor-not-allowed'}`}
                                    dangerouslySetInnerHTML={{ __html: l.label }} />
                            ))}
                        </div>
                    </div>
                )}
            </div>
        </AppLayout>
    );
}
