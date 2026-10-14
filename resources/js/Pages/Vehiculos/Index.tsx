import AppLayout from '@/layouts/AppLayout';
import Flash from '@/components/ui/flash';
import { router } from '@inertiajs/react';
import { Check, Pencil, Plus, Truck, X } from 'lucide-react';
import { useState } from 'react';

interface Vehiculo { id: number; placa: string; tipo: string | null; activo: boolean }

interface Props { vehiculos: Vehiculo[] }

export default function VehiculosIndex({ vehiculos }: Props) {
    // Formulario de creación
    const [showNuevo, setShowNuevo] = useState(false);
    const [nuevo, setNuevo] = useState({ placa: '', tipo: '' });
    const [nuevoErrors, setNuevoErrors] = useState<Record<string, string>>({});

    // Edición inline
    const [editId, setEditId]   = useState<number | null>(null);
    const [editData, setEditData] = useState({ placa: '', tipo: '', activo: true });
    const [editErrors, setEditErrors] = useState<Record<string, string>>({});

    function crearVehiculo(e: React.FormEvent) {
        e.preventDefault();
        router.post('/vehiculos', nuevo, {
            onSuccess: () => { setShowNuevo(false); setNuevo({ placa: '', tipo: '' }); },
            onError: (errs) => setNuevoErrors(errs),
        });
    }

    function iniciarEdicion(v: Vehiculo) {
        setEditId(v.id);
        setEditData({ placa: v.placa, tipo: v.tipo ?? '', activo: v.activo });
        setEditErrors({});
    }

    function guardarEdicion(e: React.FormEvent) {
        e.preventDefault();
        router.put(`/vehiculos/${editId}`, editData, {
            onSuccess: () => setEditId(null),
            onError: (errs) => setEditErrors(errs),
        });
    }

    function desactivar(id: number) {
        if (!confirm('¿Desactivar este vehículo?')) return;
        router.delete(`/vehiculos/${id}`, { preserveScroll: true });
    }

    const inputCls = (err?: string) =>
        `px-3 py-1.5 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 ${err ? 'border-red-400' : 'border-gray-200'}`;

    return (
        <AppLayout>
            <div className="max-w-3xl mx-auto">
                <Flash />
                <div className="flex items-center justify-between mb-6">
                    <div>
                        <h1 className="text-2xl font-bold text-gray-900">Vehículos</h1>
                        <p className="text-sm text-gray-500 mt-0.5">Flota para recolección y despacho</p>
                    </div>
                    <button onClick={() => { setShowNuevo(true); setNuevoErrors({}); }}
                        className="inline-flex items-center gap-2 bg-green-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-green-800 transition-colors">
                        <Plus size={16} /> Nuevo vehículo
                    </button>
                </div>

                {/* Formulario nuevo */}
                {showNuevo && (
                    <form onSubmit={crearVehiculo}
                        className="bg-green-50 border border-green-200 rounded-2xl p-4 mb-4 flex flex-wrap items-end gap-3">
                        <div>
                            <label className="block text-xs font-medium text-gray-600 mb-1">Placa *</label>
                            <input value={nuevo.placa} onChange={e => setNuevo(n => ({ ...n, placa: e.target.value.toUpperCase() }))}
                                placeholder="ABC-123" maxLength={10}
                                className={inputCls(nuevoErrors.placa)} />
                            {nuevoErrors.placa && <p className="text-xs text-red-500 mt-0.5">{nuevoErrors.placa}</p>}
                        </div>
                        <div className="flex-1 min-w-[160px]">
                            <label className="block text-xs font-medium text-gray-600 mb-1">Tipo</label>
                            <input value={nuevo.tipo} onChange={e => setNuevo(n => ({ ...n, tipo: e.target.value }))}
                                placeholder="Ej. Camión, Moto, Carreta…"
                                className={inputCls(nuevoErrors.tipo) + ' w-full'} />
                        </div>
                        <div className="flex gap-2">
                            <button type="submit"
                                className="px-4 py-2 text-sm bg-green-700 text-white rounded-lg hover:bg-green-800 font-medium">
                                Guardar
                            </button>
                            <button type="button" onClick={() => setShowNuevo(false)}
                                className="px-3 py-2 text-sm text-gray-500 hover:bg-gray-100 rounded-lg">
                                Cancelar
                            </button>
                        </div>
                    </form>
                )}

                {/* Tabla */}
                <div className="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    {vehiculos.length === 0 ? (
                        <div className="text-center py-16 text-gray-400">
                            <Truck size={32} className="mx-auto mb-3 opacity-40" />
                            <p className="font-medium">Sin vehículos registrados</p>
                        </div>
                    ) : (
                        <table className="w-full text-sm">
                            <thead>
                                <tr className="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                    <th className="text-left px-5 py-3">Placa</th>
                                    <th className="text-left px-4 py-3">Tipo</th>
                                    <th className="text-center px-4 py-3">Estado</th>
                                    <th className="px-4 py-3 w-32"></th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-gray-50">
                                {vehiculos.map(v => (
                                    <tr key={v.id} className="hover:bg-gray-50">
                                        {editId === v.id ? (
                                            /* Fila edición */
                                            <>
                                                <td className="px-4 py-2">
                                                    <input value={editData.placa}
                                                        onChange={e => setEditData(d => ({ ...d, placa: e.target.value.toUpperCase() }))}
                                                        className={inputCls(editErrors.placa) + ' w-28'} maxLength={10} />
                                                    {editErrors.placa && <p className="text-xs text-red-500">{editErrors.placa}</p>}
                                                </td>
                                                <td className="px-4 py-2">
                                                    <input value={editData.tipo}
                                                        onChange={e => setEditData(d => ({ ...d, tipo: e.target.value }))}
                                                        className={inputCls() + ' w-40'} />
                                                </td>
                                                <td className="px-4 py-2 text-center">
                                                    <select value={editData.activo ? '1' : '0'}
                                                        onChange={e => setEditData(d => ({ ...d, activo: e.target.value === '1' }))}
                                                        className="px-2 py-1 text-xs border border-gray-200 rounded-lg focus:outline-none">
                                                        <option value="1">Activo</option>
                                                        <option value="0">Inactivo</option>
                                                    </select>
                                                </td>
                                                <td className="px-4 py-2">
                                                    <form onSubmit={guardarEdicion} className="flex gap-1">
                                                        <button type="submit"
                                                            className="p-1.5 text-green-700 hover:bg-green-50 rounded-lg"><Check size={14} /></button>
                                                        <button type="button" onClick={() => setEditId(null)}
                                                            className="p-1.5 text-gray-400 hover:bg-gray-100 rounded-lg"><X size={14} /></button>
                                                    </form>
                                                </td>
                                            </>
                                        ) : (
                                            /* Fila normal */
                                            <>
                                                <td className="px-5 py-3 font-mono font-medium text-gray-800">{v.placa}</td>
                                                <td className="px-4 py-3 text-gray-600">{v.tipo ?? <span className="text-gray-300">—</span>}</td>
                                                <td className="px-4 py-3 text-center">
                                                    <span className={`inline-flex px-2 py-0.5 rounded-full text-xs font-medium ${v.activo ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'}`}>
                                                        {v.activo ? 'Activo' : 'Inactivo'}
                                                    </span>
                                                </td>
                                                <td className="px-4 py-3">
                                                    <div className="flex gap-1 justify-end">
                                                        <button onClick={() => iniciarEdicion(v)}
                                                            className="p-1.5 text-gray-400 hover:text-green-700 hover:bg-green-50 rounded-lg transition-colors">
                                                            <Pencil size={14} />
                                                        </button>
                                                        {v.activo && (
                                                            <button onClick={() => desactivar(v.id)}
                                                                className="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                                                <X size={14} />
                                                            </button>
                                                        )}
                                                    </div>
                                                </td>
                                            </>
                                        )}
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    )}
                </div>
            </div>
        </AppLayout>
    );
}
