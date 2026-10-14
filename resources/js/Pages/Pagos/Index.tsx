import AppLayout from '@/layouts/AppLayout';
import { Link, router } from '@inertiajs/react';
import { Plus, Search, Trash2, X } from 'lucide-react';
import { useState } from 'react';

interface Pago {
    id: number;
    fecha: string;
    monto: number;
    forma_pago: 'efectivo' | 'transferencia' | 'cheque';
    referencia: string | null;
    observaciones: string | null;
    reciclador: { id: number; nombre: string; documento: string };
    recibo: { id: number; numero: number } | null;
}

interface Props {
    pagos: {
        data: Pago[];
        links: { url: string | null; label: string; active: boolean }[];
        from: number; to: number; total: number;
    };
    filtros: { search?: string; fecha_desde?: string; fecha_hasta?: string; forma_pago?: string };
    saldoPendiente: number;
    totalPagado: number;
    totalRecibos: number;
}

const FORMAS: Record<string, string> = {
    efectivo: 'Efectivo',
    transferencia: 'Transferencia',
    cheque: 'Cheque',
};

const FORMA_COLORS: Record<string, string> = {
    efectivo: 'bg-green-50 text-green-700',
    transferencia: 'bg-blue-50 text-blue-700',
    cheque: 'bg-purple-50 text-purple-700',
};

function fmtCop(n: number) { return '$' + new Intl.NumberFormat('es-CO').format(Math.round(n ?? 0)); }
function fmtFecha(s: string) { return s ? s.substring(0, 10).split('-').reverse().join('/') : '—'; }

export default function PagosIndex({ pagos, filtros, saldoPendiente, totalPagado, totalRecibos }: Props) {
    const [search, setSearch]       = useState(filtros.search ?? '');
    const [fechaDesde, setFechaDesde] = useState(filtros.fecha_desde ?? '');
    const [fechaHasta, setFechaHasta] = useState(filtros.fecha_hasta ?? '');
    const [formaPago, setFormaPago]   = useState(filtros.forma_pago ?? '');

    function aplicarFiltros() {
        router.get('/pagos', { search, fecha_desde: fechaDesde, fecha_hasta: fechaHasta, forma_pago: formaPago }, { preserveState: true });
    }

    function limpiarFiltros() {
        setSearch(''); setFechaDesde(''); setFechaHasta(''); setFormaPago('');
        router.get('/pagos', {});
    }

    function eliminar(id: number) {
        if (!confirm('¿Eliminar este pago?')) return;
        router.delete(`/pagos/${id}`, { preserveScroll: true });
    }

    const hayFiltros = search || fechaDesde || fechaHasta || formaPago;

    return (
        <AppLayout>
            <div className="max-w-5xl mx-auto">
                {/* Título */}
                <div className="flex items-center justify-between mb-6">
                    <div>
                        <h1 className="text-2xl font-bold text-gray-900">Pagos a recicladores</h1>
                        <p className="text-sm text-gray-500 mt-0.5">Registro de pagos por compra de materiales</p>
                    </div>
                    <Link href="/pagos/create"
                        className="inline-flex items-center gap-2 bg-green-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-green-800 transition-colors">
                        <Plus size={16} /> Registrar pago
                    </Link>
                </div>

                {/* KPI cards */}
                <div className="grid grid-cols-3 gap-4 mb-6">
                    <div className="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                        <p className="text-xs font-semibold text-gray-400 uppercase tracking-wide">Total recibos</p>
                        <p className="text-2xl font-bold text-gray-800 mt-1">{fmtCop(totalRecibos)}</p>
                    </div>
                    <div className="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                        <p className="text-xs font-semibold text-gray-400 uppercase tracking-wide">Total pagado</p>
                        <p className="text-2xl font-bold text-green-700 mt-1">{fmtCop(totalPagado)}</p>
                    </div>
                    <div className="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                        <p className="text-xs font-semibold text-gray-400 uppercase tracking-wide">Saldo pendiente</p>
                        <p className={`text-2xl font-bold mt-1 ${saldoPendiente > 0 ? 'text-amber-600' : 'text-gray-400'}`}>
                            {fmtCop(saldoPendiente)}
                        </p>
                    </div>
                </div>

                {/* Filtros */}
                <div className="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-4">
                    <div className="flex flex-wrap gap-3">
                        <div className="relative flex-1 min-w-[180px]">
                            <Search size={14} className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
                            <input value={search} onChange={e => setSearch(e.target.value)}
                                onKeyDown={e => e.key === 'Enter' && aplicarFiltros()}
                                placeholder="Buscar reciclador…"
                                className="w-full pl-8 pr-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500" />
                        </div>
                        <input type="date" value={fechaDesde} onChange={e => setFechaDesde(e.target.value)}
                            className="px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500" />
                        <input type="date" value={fechaHasta} onChange={e => setFechaHasta(e.target.value)}
                            className="px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500" />
                        <select value={formaPago} onChange={e => setFormaPago(e.target.value)}
                            className="px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">Todas las formas</option>
                            <option value="efectivo">Efectivo</option>
                            <option value="transferencia">Transferencia</option>
                            <option value="cheque">Cheque</option>
                        </select>
                        <button onClick={aplicarFiltros}
                            className="px-4 py-2 text-sm bg-green-700 text-white rounded-xl hover:bg-green-800 transition-colors">
                            Filtrar
                        </button>
                        {hayFiltros && (
                            <button onClick={limpiarFiltros}
                                className="px-3 py-2 text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1">
                                <X size={14} /> Limpiar
                            </button>
                        )}
                    </div>
                </div>

                {/* Tabla */}
                <div className="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    {pagos.data.length === 0 ? (
                        <div className="text-center py-16 text-gray-400">
                            <p className="text-lg font-medium">Sin pagos registrados</p>
                            <p className="text-sm mt-1">Registra el primer pago usando el botón superior.</p>
                        </div>
                    ) : (
                        <table className="w-full text-sm">
                            <thead>
                                <tr className="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                    <th className="text-left px-5 py-3">Fecha</th>
                                    <th className="text-left px-4 py-3">Reciclador</th>
                                    <th className="text-left px-4 py-3">Recibo</th>
                                    <th className="text-left px-4 py-3">Forma</th>
                                    <th className="text-right px-5 py-3">Monto</th>
                                    <th className="px-4 py-3"></th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-gray-50">
                                {pagos.data.map(p => (
                                    <tr key={p.id} className="hover:bg-gray-50">
                                        <td className="px-5 py-3 text-gray-700 whitespace-nowrap">{fmtFecha(p.fecha)}</td>
                                        <td className="px-4 py-3">
                                            <p className="font-medium text-gray-800">{p.reciclador.nombre}</p>
                                            <p className="text-xs text-gray-400">CC {p.reciclador.documento}</p>
                                        </td>
                                        <td className="px-4 py-3">
                                            {p.recibo
                                                ? <Link href={`/recibos/${p.recibo.id}`} className="text-green-700 hover:underline">#{p.recibo.numero}</Link>
                                                : <span className="text-gray-400 text-xs">Sin asociar</span>
                                            }
                                        </td>
                                        <td className="px-4 py-3">
                                            <span className={`inline-flex px-2 py-0.5 rounded-full text-xs font-medium ${FORMA_COLORS[p.forma_pago]}`}>
                                                {FORMAS[p.forma_pago]}
                                            </span>
                                        </td>
                                        <td className="px-5 py-3 text-right font-bold text-gray-900">{fmtCop(p.monto)}</td>
                                        <td className="px-4 py-3 text-right">
                                            <button onClick={() => eliminar(p.id)}
                                                className="p-1.5 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                                <Trash2 size={14} />
                                            </button>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    )}

                    {/* Paginación */}
                    {pagos.links.length > 3 && (
                        <div className="flex justify-center gap-1 px-4 py-3 border-t border-gray-100">
                            {pagos.links.map((l, i) => (
                                <button key={i} disabled={!l.url}
                                    onClick={() => l.url && router.get(l.url, {}, { preserveState: true })}
                                    className={`px-3 py-1.5 text-xs rounded-lg transition-colors ${l.active ? 'bg-green-700 text-white' : 'text-gray-500 hover:bg-gray-100 disabled:opacity-30'}`}
                                    dangerouslySetInnerHTML={{ __html: l.label }} />
                            ))}
                        </div>
                    )}
                </div>
            </div>
        </AppLayout>
    );
}
