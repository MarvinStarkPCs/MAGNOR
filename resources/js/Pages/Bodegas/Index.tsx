import AppLayout from '@/layouts/AppLayout';
import { router } from '@inertiajs/react';
import { Warehouse, Plus, Pencil, Trash2, X, Save } from 'lucide-react';
import { useState } from 'react';

interface Bodega {
    id: number; nombre: string; direccion: string | null;
    activa: boolean; recibos_count: number; inventarios_count: number;
}
interface Props { bodegas: Bodega[] }

export default function BodegasIndex({ bodegas }: Props) {
    const [showForm, setShowForm]   = useState(false);
    const [editing, setEditing]     = useState<Bodega | null>(null);
    const [form, setForm]           = useState({ nombre: '', direccion: '', activa: true });
    const [errors, setErrors]       = useState<Record<string, string>>({});

    function abrirCrear() {
        setEditing(null);
        setForm({ nombre: '', direccion: '', activa: true });
        setErrors({});
        setShowForm(true);
    }

    function abrirEditar(b: Bodega) {
        setEditing(b);
        setForm({ nombre: b.nombre, direccion: b.direccion ?? '', activa: b.activa });
        setErrors({});
        setShowForm(true);
    }

    function guardar(e: React.FormEvent) {
        e.preventDefault();
        const payload = { nombre: form.nombre.trim(), direccion: form.direccion.trim() || null, activa: form.activa };
        if (editing) {
            router.patch(`/bodegas/${editing.id}`, payload, {
                onSuccess: () => setShowForm(false),
                onError: setErrors,
            });
        } else {
            router.post('/bodegas', payload, {
                onSuccess: () => setShowForm(false),
                onError: setErrors,
            });
        }
    }

    function eliminar(b: Bodega) {
        if (!confirm(`¿Eliminar la bodega "${b.nombre}"?`)) return;
        router.delete(`/bodegas/${b.id}`);
    }

    return (
        <AppLayout>
            <div className="max-w-4xl mx-auto">
                <div className="flex items-center justify-between mb-6">
                    <div>
                        <h1 className="text-2xl font-bold text-gray-900">Bodegas</h1>
                        <p className="text-sm text-gray-500 mt-0.5">Gestión de bodegas de almacenamiento</p>
                    </div>
                    <button onClick={abrirCrear}
                        className="inline-flex items-center gap-2 bg-green-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-green-800 transition-colors">
                        <Plus size={16} /> Nueva bodega
                    </button>
                </div>

                {/* KPIs */}
                <div className="grid grid-cols-3 gap-4 mb-6">
                    <div className="bg-green-700 text-white rounded-2xl p-5 shadow-sm">
                        <p className="text-xs font-semibold text-green-200 uppercase tracking-wide">Total bodegas</p>
                        <p className="text-3xl font-bold mt-1">{bodegas.length}</p>
                    </div>
                    <div className="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                        <p className="text-xs font-semibold text-gray-400 uppercase tracking-wide">Activas</p>
                        <p className="text-2xl font-bold text-gray-800 mt-1">{bodegas.filter(b => b.activa).length}</p>
                    </div>
                    <div className="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                        <p className="text-xs font-semibold text-gray-400 uppercase tracking-wide">Inactivas</p>
                        <p className="text-2xl font-bold text-red-600 mt-1">{bodegas.filter(b => !b.activa).length}</p>
                    </div>
                </div>

                {/* Lista */}
                <div className="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    {bodegas.length === 0 ? (
                        <div className="text-center py-16 text-gray-400">
                            <Warehouse size={40} className="mx-auto mb-3 opacity-30" />
                            <p className="text-lg font-medium">Sin bodegas registradas</p>
                            <p className="text-sm mt-1">Crea la primera bodega para comenzar.</p>
                        </div>
                    ) : (
                        <table className="w-full text-sm">
                            <thead>
                                <tr className="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                    <th className="text-left px-5 py-3">Bodega</th>
                                    <th className="text-left px-4 py-3">Dirección</th>
                                    <th className="text-center px-4 py-3">Recibos</th>
                                    <th className="text-center px-4 py-3">Materiales</th>
                                    <th className="text-center px-4 py-3">Estado</th>
                                    <th className="px-4 py-3" />
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-gray-50">
                                {bodegas.map(b => (
                                    <tr key={b.id} className="hover:bg-gray-50">
                                        <td className="px-5 py-3 font-medium text-gray-800 flex items-center gap-2">
                                            <Warehouse size={16} className="text-green-600 shrink-0" />
                                            {b.nombre}
                                        </td>
                                        <td className="px-4 py-3 text-gray-500 text-xs">{b.direccion || '—'}</td>
                                        <td className="px-4 py-3 text-center text-gray-700">{b.recibos_count}</td>
                                        <td className="px-4 py-3 text-center text-gray-700">{b.inventarios_count}</td>
                                        <td className="px-4 py-3 text-center">
                                            <span className={`inline-flex px-2 py-0.5 rounded-full text-xs font-medium ${b.activa ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'}`}>
                                                {b.activa ? 'Activa' : 'Inactiva'}
                                            </span>
                                        </td>
                                        <td className="px-4 py-3 text-right">
                                            <div className="flex items-center justify-end gap-1">
                                                <button onClick={() => abrirEditar(b)}
                                                    className="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                                    <Pencil size={14} />
                                                </button>
                                                <button onClick={() => eliminar(b)}
                                                    className="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                                    <Trash2 size={14} />
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    )}
                </div>
            </div>

            {/* Modal crear / editar */}
            {showForm && (
                <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4" onClick={() => setShowForm(false)}>
                    <div className="bg-white rounded-2xl shadow-xl w-full max-w-md p-6" onClick={e => e.stopPropagation()}>
                        <div className="flex items-center justify-between mb-4">
                            <h2 className="text-base font-bold text-gray-900">
                                {editing ? 'Editar bodega' : 'Nueva bodega'}
                            </h2>
                            <button onClick={() => setShowForm(false)} className="text-gray-400 hover:text-gray-600"><X size={18} /></button>
                        </div>
                        <form onSubmit={guardar} className="space-y-4">
                            <div>
                                <label className="block text-xs font-medium text-gray-600 mb-1.5">Nombre <span className="text-red-500">*</span></label>
                                <input type="text" value={form.nombre} onChange={e => setForm(f => ({ ...f, nombre: e.target.value }))}
                                    placeholder="Ej. Bodega Norte"
                                    className={`w-full px-3 py-2 text-sm border rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 ${errors.nombre ? 'border-red-400' : 'border-gray-200'}`} />
                                {errors.nombre && <p className="text-xs text-red-500 mt-1">{errors.nombre}</p>}
                            </div>
                            <div>
                                <label className="block text-xs font-medium text-gray-600 mb-1.5">Dirección</label>
                                <input type="text" value={form.direccion} onChange={e => setForm(f => ({ ...f, direccion: e.target.value }))}
                                    placeholder="Dirección opcional"
                                    className="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500" />
                            </div>
                            {editing && (
                                <div className="flex items-center gap-2">
                                    <input type="checkbox" id="activa" checked={form.activa}
                                        onChange={e => setForm(f => ({ ...f, activa: e.target.checked }))}
                                        className="rounded" />
                                    <label htmlFor="activa" className="text-sm text-gray-700">Bodega activa</label>
                                </div>
                            )}
                            <div className="flex justify-end gap-3 pt-2">
                                <button type="button" onClick={() => setShowForm(false)}
                                    className="px-4 py-2 text-sm text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-50">
                                    Cancelar
                                </button>
                                <button type="submit"
                                    className="inline-flex items-center gap-2 px-5 py-2 text-sm bg-green-700 text-white rounded-xl hover:bg-green-800 font-medium">
                                    <Save size={14} /> Guardar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </AppLayout>
    );
}
