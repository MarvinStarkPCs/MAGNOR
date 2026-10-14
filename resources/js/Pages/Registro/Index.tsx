import AppLayout from '@/layouts/AppLayout';
import { router } from '@inertiajs/react';
import { Download, FileSpreadsheet, Weight, DollarSign, List } from 'lucide-react';
import { useState } from 'react';

interface Registro {
    id: number;
    numero_recibo: number;
    fecha: string;
    semana: number;
    reciclador: string;
    familia: string;
    material: string;
    kg_bruto: number;
    kg_rechazado: number;
    kg_neto: number;
    precio_kg: number;
    subtotal: number;
}

interface Totales {
    total_kg_bruto: number;
    total_kg_neto: number;
    total_pagado: number;
    total_filas: number;
}

interface Familia { id: number; nombre: string }

interface Props {
    registros: { data: Registro[]; links: any[]; meta: any };
    totales: Totales;
    familias: Familia[];
    filtros: { desde?: string; hasta?: string; familia_id?: string; semana?: string };
}

function fmt(n: number) { return new Intl.NumberFormat('es-CO').format(Math.round(n ?? 0)); }
function fmtCop(n: number) { return '$' + new Intl.NumberFormat('es-CO').format(Math.round(n ?? 0)); }
function fmtFecha(s: string) { return s ? s.substring(0, 10).split('-').reverse().join('/') : '—'; }

function buildExportUrl(base: string, filtros: Props['filtros']) {
    const params = new URLSearchParams();
    if (filtros.desde) params.set('desde', filtros.desde);
    if (filtros.hasta) params.set('hasta', filtros.hasta);
    const qs = params.toString();
    return qs ? `${base}?${qs}` : base;
}

export default function RegistroIndex({ registros, totales, familias, filtros }: Props) {
    const [desde, setDesde]       = useState(filtros.desde ?? '');
    const [hasta, setHasta]       = useState(filtros.hasta ?? '');
    const [familiaId, setFamilia] = useState(filtros.familia_id ?? '');
    const [semana, setSemana]     = useState(filtros.semana ?? '');

    const filtrar = () => router.get('/registro', { desde, hasta, familia_id: familiaId, semana }, { preserveState: true });

    const limpiar = () => {
        setDesde(''); setHasta(''); setFamilia(''); setSemana('');
        router.get('/registro');
    };

    const urlSui = buildExportUrl('/registro/exportar-sui', filtros);
    const urlCsv = buildExportUrl('/registro/exportar-csv', filtros);

    const kpis = [
        { label: 'Líneas filtradas', value: fmt(totales.total_filas), icon: List,       color: 'text-purple-600', bg: 'bg-purple-50' },
        { label: 'KG brutos',        value: fmt(totales.total_kg_bruto) + ' kg', icon: Weight, color: 'text-blue-600',   bg: 'bg-blue-50'   },
        { label: 'KG netos',         value: fmt(totales.total_kg_neto) + ' kg',  icon: Weight, color: 'text-green-600',  bg: 'bg-green-50'  },
        { label: 'Total pagado',     value: fmtCop(totales.total_pagado),         icon: DollarSign, color: 'text-orange-600', bg: 'bg-orange-50' },
    ];

    return (
        <AppLayout>
            <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
                <div>
                    <h1 className="text-xl font-bold text-gray-900">Registro / Balance de Masa</h1>
                    <p className="text-sm text-gray-500 mt-0.5">Todas las líneas de materiales recibidos</p>
                </div>
                <div className="flex gap-2 flex-wrap">
                    <a href={urlSui}
                        className="inline-flex items-center gap-1.5 text-sm bg-green-700 hover:bg-green-800 text-white px-3 py-2 rounded-xl transition-colors">
                        <Download size={14} /> Exportar SUI
                    </a>
                    <a href={urlCsv}
                        className="inline-flex items-center gap-1.5 text-sm bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-xl transition-colors">
                        <FileSpreadsheet size={14} /> Exportar CSV
                    </a>
                </div>
            </div>

            {/* KPIs */}
            <div className="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
                {kpis.map(({ label, value, icon: Icon, color, bg }) => (
                    <div key={label} className="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                        <div className="flex items-start justify-between">
                            <div>
                                <p className="text-xs font-medium text-gray-500 uppercase tracking-wide">{label}</p>
                                <p className="text-xl font-bold text-gray-900 mt-1">{value}</p>
                            </div>
                            <div className={`${bg} ${color} p-2 rounded-xl`}><Icon size={18} /></div>
                        </div>
                    </div>
                ))}
            </div>

            {/* Filtros */}
            <div className="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-5">
                <div className="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <div>
                        <label className="text-xs font-medium text-gray-500 uppercase tracking-wide block mb-1">Desde</label>
                        <input type="date" value={desde} onChange={e => setDesde(e.target.value)}
                            className="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none" />
                    </div>
                    <div>
                        <label className="text-xs font-medium text-gray-500 uppercase tracking-wide block mb-1">Hasta</label>
                        <input type="date" value={hasta} onChange={e => setHasta(e.target.value)}
                            className="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none" />
                    </div>
                    <div>
                        <label className="text-xs font-medium text-gray-500 uppercase tracking-wide block mb-1">Familia</label>
                        <select value={familiaId} onChange={e => setFamilia(e.target.value)}
                            className="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none bg-white">
                            <option value="">Todas</option>
                            {familias.map(f => <option key={f.id} value={f.id}>{f.nombre}</option>)}
                        </select>
                    </div>
                    <div>
                        <label className="text-xs font-medium text-gray-500 uppercase tracking-wide block mb-1">Semana</label>
                        <input type="number" min="1" max="53" value={semana} onChange={e => setSemana(e.target.value)}
                            placeholder="Ej: 20"
                            className="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none" />
                    </div>
                </div>
                <div className="flex gap-2 mt-3 justify-end">
                    <button onClick={limpiar} className="text-sm text-gray-500 hover:text-gray-700 px-4 py-2 rounded-xl border border-gray-200 hover:bg-gray-50 transition-colors">
                        Limpiar
                    </button>
                    <button onClick={filtrar} className="text-sm bg-green-700 hover:bg-green-800 text-white px-4 py-2 rounded-xl transition-colors">
                        Filtrar
                    </button>
                </div>
            </div>

            {/* Tabla */}
            <div className="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div className="overflow-x-auto">
                    <table className="w-full text-sm">
                        <thead>
                            <tr className="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                <th className="text-left px-4 py-3">Recibo</th>
                                <th className="text-left px-4 py-3">Fecha</th>
                                <th className="text-left px-4 py-3 hidden sm:table-cell">Sem.</th>
                                <th className="text-left px-4 py-3">Reciclador</th>
                                <th className="text-left px-4 py-3 hidden md:table-cell">Material</th>
                                <th className="text-right px-4 py-3">KG Bruto</th>
                                <th className="text-right px-4 py-3 hidden lg:table-cell">KG Rec.</th>
                                <th className="text-right px-4 py-3">KG Neto</th>
                                <th className="text-right px-4 py-3 hidden xl:table-cell">$/kg</th>
                                <th className="text-right px-4 py-3">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-50">
                            {registros.data.length === 0 ? (
                                <tr>
                                    <td colSpan={10} className="text-center py-12 text-gray-400 text-sm">
                                        No hay registros para los filtros seleccionados
                                    </td>
                                </tr>
                            ) : registros.data.map(r => (
                                <tr key={r.id} className="hover:bg-gray-50 transition-colors">
                                    <td className="px-4 py-2.5 font-medium text-gray-700">#{r.numero_recibo}</td>
                                    <td className="px-4 py-2.5 text-gray-600">{fmtFecha(r.fecha)}</td>
                                    <td className="px-4 py-2.5 text-gray-500 hidden sm:table-cell">{r.semana}</td>
                                    <td className="px-4 py-2.5 text-gray-700 max-w-[140px] truncate">{r.reciclador}</td>
                                    <td className="px-4 py-2.5 hidden md:table-cell">
                                        <p className="font-medium text-gray-700">{r.material}</p>
                                        <p className="text-xs text-gray-400">{r.familia}</p>
                                    </td>
                                    <td className="px-4 py-2.5 text-right text-gray-600">{(+r.kg_bruto).toFixed(2)}</td>
                                    <td className="px-4 py-2.5 text-right text-gray-400 hidden lg:table-cell">{(+r.kg_rechazado).toFixed(2)}</td>
                                    <td className="px-4 py-2.5 text-right font-medium text-gray-700">{(+r.kg_neto).toFixed(2)}</td>
                                    <td className="px-4 py-2.5 text-right text-gray-500 hidden xl:table-cell">{fmtCop(+r.precio_kg)}</td>
                                    <td className="px-4 py-2.5 text-right font-bold text-gray-900">{fmtCop(+r.subtotal)}</td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>

                {/* Paginación */}
                {registros.meta?.last_page > 1 && (
                    <div className="px-4 py-3 border-t border-gray-100 flex items-center justify-between">
                        <p className="text-xs text-gray-500">
                            Página {registros.meta.current_page} de {registros.meta.last_page}
                            {' '}· {fmt(registros.meta.total)} filas total
                        </p>
                        <div className="flex gap-1">
                            {registros.links.map((link: any, i: number) => (
                                <button key={i} disabled={!link.url}
                                    onClick={() => link.url && router.get(link.url)}
                                    className={`px-3 py-1.5 text-xs rounded-lg transition-colors ${
                                        link.active
                                            ? 'bg-green-700 text-white'
                                            : link.url
                                                ? 'text-gray-600 hover:bg-gray-100 border border-gray-200'
                                                : 'text-gray-300 cursor-not-allowed border border-gray-100'
                                    }`}
                                    dangerouslySetInnerHTML={{ __html: link.label }}
                                />
                            ))}
                        </div>
                    </div>
                )}
            </div>
        </AppLayout>
    );
}
