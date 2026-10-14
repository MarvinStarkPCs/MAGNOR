import AppLayout from '@/layouts/AppLayout';
import { useForm, Link } from '@inertiajs/react';
import { ArrowLeft, Save, History } from 'lucide-react';

interface Props {
    material: { id: number; nombre: string; codigo_sui: number; precio_actual: number; activo: boolean; familia: { nombre: string }; historial_precios: any[] };
    familias: any[];
}

function fmtCop(n: number) { return '$' + new Intl.NumberFormat('es-CO').format(n); }

export default function MaterialEdit({ material }: Props) {
    const { data, setData, put, processing, errors } = useForm({
        precio_actual: material.precio_actual,
        activo: material.activo,
    });

    return (
        <AppLayout>
            <div className="max-w-lg mx-auto">
                <div className="flex items-center gap-3 mb-6">
                    <Link href="/materiales" className="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-xl transition-colors">
                        <ArrowLeft size={18} />
                    </Link>
                    <div>
                        <h1 className="text-xl font-bold text-gray-900">{material.nombre}</h1>
                        <p className="text-xs text-gray-500">{material.familia.nombre} · SUI {material.codigo_sui}</p>
                    </div>
                </div>

                <form onSubmit={e => { e.preventDefault(); put(`/materiales/${material.id}`); }} className="space-y-5">
                    <div className="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                        <h2 className="text-sm font-semibold text-gray-700 mb-4">Actualizar precio</h2>
                        <div>
                            <label className="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Precio por kilogramo (COP)</label>
                            <div className="relative">
                                <span className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-medium">$</span>
                                <input
                                    type="number" min="0" step="50"
                                    value={data.precio_actual}
                                    onChange={e => setData('precio_actual', parseFloat(e.target.value))}
                                    className={`w-full pl-7 pr-4 py-2.5 text-sm border rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 ${errors.precio_actual ? 'border-red-400' : 'border-gray-200'}`}
                                />
                            </div>
                            {errors.precio_actual && <p className="mt-1 text-xs text-red-500">{errors.precio_actual}</p>}
                            <p className="mt-1 text-xs text-gray-400">Precio actual: {fmtCop(material.precio_actual)}/kg</p>
                        </div>

                        <div className="flex items-center gap-3 mt-4">
                            <input type="checkbox" id="activo" checked={data.activo} onChange={e => setData('activo', e.target.checked)}
                                className="w-4 h-4 rounded border-gray-300 text-green-600 focus:ring-green-500" />
                            <label htmlFor="activo" className="text-sm text-gray-700">Material activo</label>
                        </div>
                    </div>

                    {material.historial_precios?.length > 0 && (
                        <div className="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                            <div className="flex items-center gap-2 mb-3">
                                <History size={15} className="text-gray-400" />
                                <h2 className="text-sm font-semibold text-gray-700">Historial de precios</h2>
                            </div>
                            <div className="space-y-2">
                                {material.historial_precios.slice(0, 5).map((h: any, i: number) => (
                                    <div key={i} className="flex justify-between text-xs text-gray-600 py-1.5 border-b border-gray-50 last:border-0">
                                        <span>{h.vigente_desde}{h.vigente_hasta ? ` → ${h.vigente_hasta}` : ' (vigente)'}</span>
                                        <span className="font-semibold">{fmtCop(h.precio)}/kg</span>
                                    </div>
                                ))}
                            </div>
                        </div>
                    )}

                    <div className="flex gap-3 justify-end">
                        <Link href="/materiales" className="px-4 py-2.5 text-sm text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
                            Cancelar
                        </Link>
                        <button type="submit" disabled={processing}
                            className="inline-flex items-center gap-2 bg-green-700 hover:bg-green-800 disabled:opacity-60 text-white text-sm font-medium px-5 py-2.5 rounded-xl transition-colors">
                            <Save size={16} />
                            {processing ? 'Guardando...' : 'Actualizar precio'}
                        </button>
                    </div>
                </form>
            </div>
        </AppLayout>
    );
}
