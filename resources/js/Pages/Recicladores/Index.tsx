import AppLayout from '@/layouts/AppLayout';
import Flash from '@/components/ui/flash';
import { Link, router } from '@inertiajs/react';
import { Plus, Search, Pencil, UserX, Phone, MapPin } from 'lucide-react';
import { useState } from 'react';

interface Reciclador {
    id: number; nombre: string; documento: string; telefono: string | null;
    direccion: string | null; activo: boolean;
    municipio: { nombre: string } | null;
}
interface PaginaLink { url: string | null; label: string; active: boolean }
interface Props {
    recicladores: { data: Reciclador[]; links: PaginaLink[]; from: number; to: number; total: number; last_page: number; current_page: number };
    filtros: { search?: string; activo?: string };
}

export default function RecicladoresIndex({ recicladores, filtros }: Props) {
    const [search, setSearch] = useState(filtros.search ?? '');

    const buscar = () => router.get('/recicladores', { search, activo: filtros.activo }, { preserveState: true });

    return (
        <AppLayout>
            <Flash />

            <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
                <h1 className="text-xl font-bold text-gray-900">Recicladores</h1>
                <Link href="/recicladores/create"
                    className="inline-flex items-center gap-2 bg-green-700 hover:bg-green-800 text-white text-sm font-medium px-4 py-2.5 rounded-xl transition-colors">
                    <Plus size={16} /> Nuevo reciclador
                </Link>
            </div>

            {/* Filtros */}
            <div className="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-5 flex flex-col sm:flex-row gap-3">
                <div className="relative flex-1">
                    <Search size={16} className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
                    <input
                        value={search}
                        onChange={e => setSearch(e.target.value)}
                        onKeyDown={e => e.key === 'Enter' && buscar()}
                        placeholder="Buscar por nombre o cédula..."
                        className="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                    />
                </div>
                <select value={filtros.activo ?? ''} onChange={e => router.get('/recicladores', { search, activo: e.target.value })}
                    className="text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">Todos</option>
                    <option value="1">Activos</option>
                    <option value="0">Inactivos</option>
                </select>
                <button onClick={buscar} className="bg-green-700 text-white text-sm px-4 py-2 rounded-lg hover:bg-green-800 transition-colors">
                    Buscar
                </button>
            </div>

            {/* Tabla */}
            <div className="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div className="overflow-x-auto">
                    <table className="w-full text-sm">
                        <thead className="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th className="text-left px-4 py-3 font-semibold text-gray-600">Nombre</th>
                                <th className="text-left px-4 py-3 font-semibold text-gray-600 hidden md:table-cell">Cédula</th>
                                <th className="text-left px-4 py-3 font-semibold text-gray-600 hidden lg:table-cell">Teléfono</th>
                                <th className="text-left px-4 py-3 font-semibold text-gray-600 hidden lg:table-cell">Municipio</th>
                                <th className="text-left px-4 py-3 font-semibold text-gray-600">Estado</th>
                                <th className="px-4 py-3" />
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-50">
                            {recicladores.data.length === 0 && (
                                <tr><td colSpan={6} className="text-center py-12 text-gray-400">No se encontraron recicladores</td></tr>
                            )}
                            {recicladores.data.map(r => (
                                <tr key={r.id} className="hover:bg-gray-50 transition-colors">
                                    <td className="px-4 py-3">
                                        <p className="font-medium text-gray-800">{r.nombre}</p>
                                        <p className="text-xs text-gray-400 md:hidden">{r.documento}</p>
                                    </td>
                                    <td className="px-4 py-3 text-gray-600 hidden md:table-cell">{r.documento}</td>
                                    <td className="px-4 py-3 text-gray-600 hidden lg:table-cell">
                                        {r.telefono ? <span className="flex items-center gap-1"><Phone size={12} />{r.telefono}</span> : <span className="text-gray-300">—</span>}
                                    </td>
                                    <td className="px-4 py-3 text-gray-600 hidden lg:table-cell">
                                        {r.municipio ? <span className="flex items-center gap-1"><MapPin size={12} />{r.municipio.nombre}</span> : <span className="text-gray-300">—</span>}
                                    </td>
                                    <td className="px-4 py-3">
                                        <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${r.activo ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'}`}>
                                            {r.activo ? 'Activo' : 'Inactivo'}
                                        </span>
                                    </td>
                                    <td className="px-4 py-3">
                                        <div className="flex items-center gap-2 justify-end">
                                            <Link href={`/recicladores/${r.id}/edit`} className="p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors">
                                                <Pencil size={15} />
                                            </Link>
                                            {r.activo && (
                                                <button
                                                    onClick={() => { if (confirm('¿Desactivar este reciclador?')) router.delete(`/recicladores/${r.id}/desactivar`); }}
                                                    className="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                                    <UserX size={15} />
                                                </button>
                                            )}
                                        </div>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>

                {/* Paginación */}
                {recicladores.last_page > 1 && (
                    <div className="flex items-center justify-between px-4 py-3 border-t border-gray-100">
                        <p className="text-xs text-gray-500">
                            {recicladores.from}–{recicladores.to} de {recicladores.total} recicladores
                        </p>
                        <div className="flex gap-1">
                            {recicladores.links.map((l, i) => (
                                <button key={i} disabled={!l.url}
                                    onClick={() => l.url && router.get(l.url, {}, { preserveState: true })}
                                    className={`px-3 py-1.5 text-xs rounded-lg font-medium transition-colors ${
                                        l.active
                                            ? 'bg-green-700 text-white'
                                            : l.url
                                                ? 'text-gray-600 hover:bg-gray-100 border border-gray-200'
                                                : 'text-gray-300 cursor-not-allowed'
                                    }`}
                                    dangerouslySetInnerHTML={{ __html: l.label }}
                                />
                            ))}
                        </div>
                    </div>
                )}
            </div>
        </AppLayout>
    );
}
