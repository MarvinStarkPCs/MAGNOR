import AppLayout from '@/layouts/AppLayout';
import { Link, router } from '@inertiajs/react';
import { ArrowLeft, ArrowDownCircle, ArrowUpCircle, SlidersHorizontal, X } from 'lucide-react';
import { useState } from 'react';

interface Movimiento {
    id: number; tipo: 'entrada' | 'salida' | 'ajuste'; kg: number;
    kg_antes: number; kg_despues: number; fecha: string;
    observaciones: string | null;
    material: { nombre: string; familia: { nombre: string } };
    user: { name: string } | null;
}
interface Material { id: number; nombre: string }

interface Props {
    movimientos: {
        data: Movimiento[];
        links: { url: string | null; label: string; active: boolean }[];
        from: number; to: number; total: number;
    };
    materiales: Material[];
    filtros: { material_id?: string; tipo?: string; fecha_desde?: string; fecha_hasta?: string };
}

const TIPO_ICON = {
    entrada: <ArrowUpCircle size={15} className="text-green-600" />,
    salida:  <ArrowDownCircle size={15} className="text-red-500" />,
    ajuste:  <SlidersHorizontal size={15} className="text-blue-500" />,
};
const TIPO_COLOR = {
    entrada: 'bg-green-50 text-green-700',
    salida:  'bg-red-50 text-red-700',
    ajuste:  'bg-blue-50 text-blue-700',
};

function fmtKg(n: number) { return new Intl.NumberFormat('es-CO', { maximumFractionDigits: 3 }).format(n) + ' kg'; }

export default function InventarioMovimientos({ movimientos, materiales, filtros }: Props) {
    const [materialId, setMaterialId] = useState(filtros.material_id ?? '');
    const [tipo, setTipo]             = useState(filtros.tipo ?? '');
    const [fechaDesde, setFechaDesde] = useState(filtros.fecha_desde ?? '');
    const [fechaHasta, setFechaHasta] = useState(filtros.fecha_hasta ?? '');

    function filtrar() {
        router.get('/inventario/movimientos', { material_id: materialId, tipo, fecha_desde: fechaDesde, fecha_hasta: fechaHasta }, { preserveState: true });
    }
    function limpiar() {
        setMaterialId(''); setTipo(''); setFechaDesde(''); setFechaHasta('');
        router.get('/inventario/movimientos', {});
    }

    const hayFiltros = materialId || tipo || fechaDesde || fechaHasta;

    return (
        <AppLayout>
            <div className="max-w-5xl mx-auto">
                <div className="flex items-center gap-3 mb-6">
                    <Link href="/inventario"
                        className="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-xl transition-colors">
                        <ArrowLeft size={18} />
                    </Link>
                    <div>
                        <h1 className="text-2xl font-bold text-gray-900">Movimientos de Inventario</h1>
                        <p className="text-sm text-gray-500 mt-0.5">Historial completo de entradas, salidas y ajustes</p>
                    </div>
                </div>

                {/* Filtros */}
                <div className="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-4">
                    <div className="flex flex-wrap gap-3">
                        <select value={materialId} onChange={e => setMaterialId(e.target.value)}
                            className="flex-1 min-w-[180px] px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">Todos los materiales</option>
                            {materiales.map(m => <option key={m.id} value={m.id}>{m.nombre}</option>)}
                        </select>
                        <select value={tipo} onChange={e => setTipo(e.target.value)}
                            className="px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">Todos los tipos</option>
                            <option value="entrada">Entrada</option>
                            <option value="salida">Salida</option>
                            <option value="ajuste">Ajuste</option>
                        </select>
                        <input type="date" value={fechaDesde} onChange={e => setFechaDesde(e.target.value)}
                            className="px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500" />
                        <input type="date" value={fechaHasta} onChange={e => setFechaHasta(e.target.value)}
                            className="px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500" />
                        <button onClick={filtrar}
                            className="px-4 py-2 text-sm bg-green-700 text-white rounded-xl hover:bg-green-800 transition-colors">
                            Filtrar
                        </button>
                        {hayFiltros && (
                            <button onClick={limpiar} className="px-3 py-2 text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1">
                                <X size={14} /> Limpiar
                            </button>
                        )}
                    </div>
                </div>

                <div className="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    {movimientos.data.length === 0 ? (
                        <div className="text-center py-16 text-gray-400">
                            <p className="text-lg font-medium">Sin movimientos</p>
                        </div>
                    ) : (
                        <table className="w-full text-sm">
                            <thead>
                                <tr className="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                    <th className="text-left px-5 py-3">Fecha</th>
                                    <th className="text-left px-4 py-3">Tipo</th>
                                    <th className="text-left px-4 py-3">Material</th>
                                    <th className="text-right px-4 py-3">KG mov.</th>
                                    <th className="text-right px-4 py-3">Antes</th>
                                    <th className="text-right px-5 py-3">Después</th>
                                    <th className="text-left px-4 py-3">Detalle</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-gray-50">
                                {movimientos.data.map(m => (
                                    <tr key={m.id} className="hover:bg-gray-50">
                                        <td className="px-5 py-3 text-gray-600 whitespace-nowrap">
                                            {m.fecha ? m.fecha.substring(0, 10).split('-').reverse().join('/') : '—'}
                                        </td>
                                        <td className="px-4 py-3">
                                            <span className={`inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium ${TIPO_COLOR[m.tipo]}`}>
                                                {TIPO_ICON[m.tipo]}
                                                {m.tipo.charAt(0).toUpperCase() + m.tipo.slice(1)}
                                            </span>
                                        </td>
                                        <td className="px-4 py-3">
                                            <p className="font-medium text-gray-800">{m.material.nombre}</p>
                                            <p className="text-xs text-gray-400">{m.material.familia.nombre}</p>
                                        </td>
                                        <td className={`px-4 py-3 text-right font-bold ${m.tipo === 'salida' ? 'text-red-600' : 'text-green-700'}`}>
                                            {m.tipo === 'salida' ? '−' : '+'}{fmtKg(m.kg)}
                                        </td>
                                        <td className="px-4 py-3 text-right text-gray-400">{fmtKg(m.kg_antes)}</td>
                                        <td className="px-5 py-3 text-right text-gray-700 font-medium">{fmtKg(m.kg_despues)}</td>
                                        <td className="px-4 py-3 text-gray-500 text-xs max-w-[180px] truncate">
                                            {m.observaciones ?? '—'}
                                            {m.user && <span className="block text-gray-400">{m.user.name}</span>}
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    )}

                    {/* Paginación */}
                    {movimientos.links.length > 3 && (
                        <div className="flex justify-center gap-1 px-4 py-3 border-t border-gray-100">
                            {movimientos.links.map((l, i) => (
                                <button key={i} disabled={!l.url}
                                    onClick={() => l.url && router.get(l.url, {}, { preserveState: true })}
                                    className={`px-3 py-1.5 text-xs rounded-lg transition-colors ${l.active ? 'bg-green-700 text-white' : 'text-gray-500 hover:bg-gray-100 disabled:opacity-30'}`}
                                    dangerouslySetInnerHTML={{ __html: l.label }} />
                            ))}
                        </div>
                    )}
                </div>

                <p className="text-xs text-gray-400 mt-3 text-right">
                    {movimientos.from}–{movimientos.to} de {movimientos.total} registros
                </p>
            </div>
        </AppLayout>
    );
}
