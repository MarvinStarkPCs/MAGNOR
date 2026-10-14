import AppLayout from '@/layouts/AppLayout';
import Flash from '@/components/ui/flash';
import { router } from '@inertiajs/react';
import { Weight, DollarSign, FileText, Users, TrendingUp, Warehouse } from 'lucide-react';

interface Props {
    resumen: { total_kg_bruto: number; total_kg_neto: number; total_pagado: number; total_recibos: number; total_recicladores: number };
    topRecicladores: { nombre: string; total_kg: number; total_pagado: number }[];
    topMateriales: { nombre: string; total_kg: number }[];
    actividadSemanal: { semana: number; total_kg: number; total_pagado: number }[];
    stockTotal: number;
    stockMateriales: { nombre: string; kg: number }[];
    mes: number;
    anio: number;
}

const MESES = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

function fmt(n: number) { return new Intl.NumberFormat('es-CO').format(Math.round(n ?? 0)); }
function fmtCop(n: number) { return '$' + new Intl.NumberFormat('es-CO').format(Math.round(n ?? 0)); }

export default function Dashboard({ resumen, topRecicladores, topMateriales, actividadSemanal, stockTotal, stockMateriales, mes, anio }: Props) {
    const kpis = [
        { label: 'Total pagado',     value: fmtCop(resumen.total_pagado),       icon: DollarSign, color: 'text-green-600',  bg: 'bg-green-50'  },
        { label: 'KG netos',         value: fmt(resumen.total_kg_neto) + ' kg',  icon: Weight,     color: 'text-blue-600',   bg: 'bg-blue-50'   },
        { label: 'Recibos emitidos', value: fmt(resumen.total_recibos),           icon: FileText,   color: 'text-purple-600', bg: 'bg-purple-50' },
        { label: 'Recicladores',     value: fmt(resumen.total_recicladores),      icon: Users,      color: 'text-orange-600', bg: 'bg-orange-50' },
        { label: 'Stock en bodega',  value: fmt(stockTotal) + ' kg',             icon: Warehouse,  color: 'text-teal-600',   bg: 'bg-teal-50'   },
    ];

    const maxKgR = Math.max(...(topRecicladores ?? []).map(r => r.total_kg), 1);
    const maxKgM = Math.max(...(topMateriales ?? []).map(m => m.total_kg), 1);

    return (
        <AppLayout>
            <Flash />

            <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
                <div>
                    <h1 className="text-xl font-bold text-gray-900">Dashboard</h1>
                    <p className="text-sm text-gray-500 mt-0.5">{MESES[mes]} {anio}</p>
                </div>
                <div className="flex gap-2">
                    <select value={mes} onChange={e => router.get('/dashboard', { mes: e.target.value, anio })}
                        className="text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none">
                        {MESES.slice(1).map((m, i) => <option key={i+1} value={i+1}>{m}</option>)}
                    </select>
                    <select value={anio} onChange={e => router.get('/dashboard', { mes, anio: e.target.value })}
                        className="text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none">
                        {[2024, 2025, 2026].map(y => <option key={y} value={y}>{y}</option>)}
                    </select>
                </div>
            </div>

            <div className="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
                {kpis.map(({ label, value, icon: Icon, color, bg }) => (
                    <div key={label} className="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                        <div className="flex items-start justify-between">
                            <div>
                                <p className="text-xs font-medium text-gray-500 uppercase tracking-wide">{label}</p>
                                <p className="text-2xl font-bold text-gray-900 mt-1">{value}</p>
                            </div>
                            <div className={`${bg} ${color} p-2.5 rounded-xl`}><Icon size={20} /></div>
                        </div>
                    </div>
                ))}
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div className="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <div className="flex items-center gap-2 mb-4">
                        <TrendingUp size={16} className="text-green-600" />
                        <h2 className="font-semibold text-gray-800 text-sm">Top Recicladores</h2>
                    </div>
                    {!topRecicladores?.length ? (
                        <p className="text-sm text-gray-400 text-center py-8">Sin datos este mes</p>
                    ) : (
                        <div className="space-y-3">
                            {topRecicladores.map((r, i) => (
                                <div key={i}>
                                    <div className="flex justify-between text-sm mb-1">
                                        <span className="text-gray-700 font-medium truncate flex-1 pr-2">{r.nombre}</span>
                                        <span className="text-gray-500 shrink-0">{fmtCop(r.total_pagado)}</span>
                                    </div>
                                    <div className="h-2 bg-gray-100 rounded-full overflow-hidden">
                                        <div className="h-full bg-green-500 rounded-full" style={{ width: `${(r.total_kg / maxKgR) * 100}%` }} />
                                    </div>
                                    <p className="text-xs text-gray-400 mt-0.5">{fmt(r.total_kg)} kg netos</p>
                                </div>
                            ))}
                        </div>
                    )}
                </div>

                <div className="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <div className="flex items-center gap-2 mb-4">
                        <TrendingUp size={16} className="text-blue-600" />
                        <h2 className="font-semibold text-gray-800 text-sm">Top Materiales</h2>
                    </div>
                    {!topMateriales?.length ? (
                        <p className="text-sm text-gray-400 text-center py-8">Sin datos este mes</p>
                    ) : (
                        <div className="space-y-3">
                            {topMateriales.map((m, i) => (
                                <div key={i}>
                                    <div className="flex justify-between text-sm mb-1">
                                        <span className="text-gray-700 font-medium">{m.nombre}</span>
                                        <span className="text-gray-500">{fmt(m.total_kg)} kg</span>
                                    </div>
                                    <div className="h-2 bg-gray-100 rounded-full overflow-hidden">
                                        <div className="h-full bg-blue-500 rounded-full" style={{ width: `${(m.total_kg / maxKgM) * 100}%` }} />
                                    </div>
                                </div>
                            ))}
                        </div>
                    )}
                </div>
            </div>

            {/* Stock actual en bodega */}
            {stockMateriales?.length > 0 && (
                <div className="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6">
                    <div className="flex items-center justify-between mb-4">
                        <div className="flex items-center gap-2">
                            <Warehouse size={16} className="text-teal-600" />
                            <h2 className="font-semibold text-gray-800 text-sm">Stock en bodega — Top 5</h2>
                        </div>
                        <a href="/inventario" className="text-xs text-green-700 hover:underline font-medium">Ver todo →</a>
                    </div>
                    <div className="space-y-3">
                        {stockMateriales.map((m, i) => (
                            <div key={i}>
                                <div className="flex justify-between text-sm mb-1">
                                    <span className="text-gray-700 font-medium">{m.nombre}</span>
                                    <span className="text-teal-700 font-semibold">{fmt(m.kg)} kg</span>
                                </div>
                                <div className="h-2 bg-gray-100 rounded-full overflow-hidden">
                                    <div className="h-full bg-teal-500 rounded-full"
                                        style={{ width: `${(m.kg / (stockMateriales[0]?.kg || 1)) * 100}%` }} />
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            )}

            {!!actividadSemanal?.length && (
                <div className="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h2 className="font-semibold text-gray-800 text-sm mb-4">Actividad semanal</h2>
                    <div className="overflow-x-auto">
                        <table className="w-full text-sm">
                            <thead>
                                <tr className="text-xs text-gray-500 uppercase tracking-wide border-b border-gray-100">
                                    <th className="text-left pb-3">Semana</th>
                                    <th className="text-right pb-3">KG netos</th>
                                    <th className="text-right pb-3">Total pagado</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-gray-50">
                                {actividadSemanal.map(s => (
                                    <tr key={s.semana} className="hover:bg-gray-50">
                                        <td className="py-2.5 font-medium text-gray-700">Semana {s.semana}</td>
                                        <td className="py-2.5 text-right text-gray-600">{fmt(s.total_kg)} kg</td>
                                        <td className="py-2.5 text-right text-green-700 font-medium">{fmtCop(s.total_pagado)}</td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                </div>
            )}
        </AppLayout>
    );
}
