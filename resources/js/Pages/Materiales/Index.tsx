import AppLayout from '@/layouts/AppLayout';
import Flash from '@/components/ui/flash';
import { Link, router } from '@inertiajs/react';
import { Search, Pencil } from 'lucide-react';
import { useState } from 'react';

interface Material { id: number; nombre: string; codigo_sui: number; precio_actual: number; activo: boolean; familia: { nombre: string } }
interface Props {
    materiales: { data: Material[]; links: any[]; meta: any };
    familias: { id: number; nombre: string }[];
    filtros: { search?: string; familia_id?: string };
}

function fmtCop(n: number) { return '$' + new Intl.NumberFormat('es-CO').format(n); }

const FAMILIA_COLOR: Record<string, string> = {
    'METALES':        'bg-yellow-100 text-yellow-700',
    'PAPEL Y CARTÓN': 'bg-blue-100   text-blue-700',
    'PLÁSTICOS':      'bg-purple-100 text-purple-700',
    'VIDRIO':         'bg-cyan-100   text-cyan-700',
    'RECHAZO':        'bg-gray-100   text-gray-600',
};

export default function MaterialesIndex({ materiales, familias, filtros }: Props) {
    const [search, setSearch] = useState(filtros.search ?? '');

    return (
        <AppLayout>
            <Flash />

            <div className="flex items-center justify-between mb-6">
                <h1 className="text-xl font-bold text-gray-900">Materiales</h1>
                <p className="text-sm text-gray-500">{materiales.meta?.total ?? 0} materiales</p>
            </div>

            <div className="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-5 flex flex-col sm:flex-row gap-3">
                <div className="relative flex-1">
                    <Search size={16} className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
                    <input value={search} onChange={e => setSearch(e.target.value)}
                        onKeyDown={e => e.key === 'Enter' && router.get('/materiales', { search, familia_id: filtros.familia_id })}
                        placeholder="Buscar material..." className="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" />
                </div>
                <select value={filtros.familia_id ?? ''} onChange={e => router.get('/materiales', { search, familia_id: e.target.value })}
                    className="text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">Todas las familias</option>
                    {familias.map(f => <option key={f.id} value={f.id}>{f.nombre}</option>)}
                </select>
            </div>

            <div className="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div className="overflow-x-auto">
                    <table className="w-full text-sm">
                        <thead className="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th className="text-left px-4 py-3 font-semibold text-gray-600">Material</th>
                                <th className="text-left px-4 py-3 font-semibold text-gray-600 hidden sm:table-cell">Familia</th>
                                <th className="text-left px-4 py-3 font-semibold text-gray-600 hidden md:table-cell">Cód. SUI</th>
                                <th className="text-right px-4 py-3 font-semibold text-gray-600">Precio/kg</th>
                                <th className="text-left px-4 py-3 font-semibold text-gray-600">Estado</th>
                                <th className="px-4 py-3" />
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-50">
                            {materiales.data.map(m => (
                                <tr key={m.id} className="hover:bg-gray-50 transition-colors">
                                    <td className="px-4 py-3 font-medium text-gray-800">{m.nombre}</td>
                                    <td className="px-4 py-3 hidden sm:table-cell">
                                        <span className={`inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium ${FAMILIA_COLOR[m.familia.nombre] ?? 'bg-gray-100 text-gray-600'}`}>
                                            {m.familia.nombre}
                                        </span>
                                    </td>
                                    <td className="px-4 py-3 text-gray-500 font-mono text-xs hidden md:table-cell">{m.codigo_sui}</td>
                                    <td className="px-4 py-3 text-right font-bold text-gray-900">{fmtCop(m.precio_actual)}</td>
                                    <td className="px-4 py-3">
                                        <span className={`inline-flex px-2 py-0.5 rounded-full text-xs font-medium ${m.activo ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'}`}>
                                            {m.activo ? 'Activo' : 'Inactivo'}
                                        </span>
                                    </td>
                                    <td className="px-4 py-3">
                                        <Link href={`/materiales/${m.id}/edit`}
                                            className="p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors inline-flex">
                                            <Pencil size={15} />
                                        </Link>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
                {materiales.meta?.last_page > 1 && (
                    <div className="flex items-center justify-between px-4 py-3 border-t border-gray-100">
                        <p className="text-xs text-gray-500">{materiales.meta.from}–{materiales.meta.to} de {materiales.meta.total}</p>
                        <div className="flex gap-1">
                            {materiales.links.map((l, i) => (
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
