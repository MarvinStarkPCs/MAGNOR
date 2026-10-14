import AppLayout from '@/layouts/AppLayout';
import { useForm, router } from '@inertiajs/react';
import { ArrowLeft, Plus, Trash2, Save, Search } from 'lucide-react';
import { useState, useRef, useEffect } from 'react';
import { Link } from '@inertiajs/react';

interface Material { id: number; nombre: string; precio_actual: number; familia: { nombre: string }; familia_id: number }
interface Bodega   { id: number; nombre: string }
interface Props { numero: number; fecha: string; materiales: Material[]; bodegas: Bodega[]; pctRechazo: number }

interface Item { material_id: number; nombre: string; kg_bruto: number; kg_rechazado: number; precio_kg: number }

function fmtCop(n: number) { return '$' + new Intl.NumberFormat('es-CO').format(Math.round(n)); }

function BuscadorReciclador({ onSelect }: { onSelect: (r: any) => void }) {
    const [q, setQ] = useState('');
    const [resultados, setResultados] = useState<any[]>([]);
    const [abierto, setAbierto] = useState(false);
    const ref = useRef<HTMLDivElement>(null);

    useEffect(() => {
        const handler = (e: MouseEvent) => { if (!ref.current?.contains(e.target as Node)) setAbierto(false); };
        document.addEventListener('mousedown', handler);
        return () => document.removeEventListener('mousedown', handler);
    }, []);

    const buscar = async (val: string) => {
        setQ(val);
        if (val.length < 2) { setResultados([]); setAbierto(false); return; }
        const res = await fetch(`/recicladores/buscar?q=${encodeURIComponent(val)}`);
        const data = await res.json();
        setResultados(data);
        setAbierto(true);
    };

    return (
        <div ref={ref} className="relative">
            <div className="relative">
                <Search size={16} className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
                <input value={q} onChange={e => buscar(e.target.value)} placeholder="Buscar reciclador por nombre o cédula..."
                    className="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500" />
            </div>
            {abierto && resultados.length > 0 && (
                <div className="absolute top-full left-0 right-0 z-20 mt-1 bg-white border border-gray-200 rounded-xl shadow-lg overflow-hidden">
                    {resultados.map(r => (
                        <button key={r.id} onClick={() => { onSelect(r); setQ(r.nombre); setAbierto(false); }}
                            className="flex flex-col items-start w-full px-4 py-2.5 hover:bg-green-50 text-left transition-colors border-b border-gray-50 last:border-0">
                            <span className="text-sm font-medium text-gray-800">{r.nombre}</span>
                            <span className="text-xs text-gray-500">{r.documento}{r.telefono ? ` · ${r.telefono}` : ''}</span>
                        </button>
                    ))}
                </div>
            )}
        </div>
    );
}

export default function ReciboForm({ numero, fecha, materiales, bodegas, pctRechazo }: Props) {
    const [reciclador, setReciclador] = useState<any>(null);
    const [bodegaId, setBodegaId]     = useState(bodegas[0]?.id?.toString() ?? '');
    const [items, setItems] = useState<Item[]>([]);
    const [processing, setProcessing] = useState(false);
    const [errors, setErrors] = useState<any>({});
    const [fechaVal, setFechaVal] = useState(fecha);
    const [obs, setObs] = useState('');

    const materialesPorFamilia = materiales.reduce((acc, m) => {
        const f = m.familia.nombre;
        if (!acc[f]) acc[f] = [];
        acc[f].push(m);
        return acc;
    }, {} as Record<string, Material[]>);

    const agregarItem = () => {
        setItems(prev => [...prev, { material_id: 0, nombre: '', kg_bruto: 0, kg_rechazado: 0, precio_kg: 0 }]);
    };

    const actualizarItem = (i: number, campo: keyof Item, valor: any) => {
        setItems(prev => {
            const next = [...prev];
            next[i] = { ...next[i], [campo]: valor };
            if (campo === 'material_id') {
                const mat = materiales.find(m => m.id === Number(valor));
                if (mat) {
                    next[i].precio_kg = mat.precio_actual;
                    next[i].nombre = mat.nombre;
                    if (next[i].kg_bruto > 0) {
                        next[i].kg_rechazado = parseFloat((next[i].kg_bruto * pctRechazo / 100).toFixed(3));
                    }
                }
            }
            if (campo === 'kg_bruto') {
                next[i].kg_rechazado = parseFloat((Number(valor) * pctRechazo / 100).toFixed(3));
            }
            return next;
        });
    };

    const eliminarItem = (i: number) => setItems(prev => prev.filter((_, idx) => idx !== i));

    const subtotal     = items.reduce((s, it) => s + (it.kg_bruto * it.precio_kg), 0);
    const totalKgBruto = items.reduce((s, it) => s + it.kg_bruto, 0);
    const totalKgNeto  = items.reduce((s, it) => s + (it.kg_bruto - it.kg_rechazado), 0);

    const guardar = async () => {
        setErrors({});
        if (!reciclador) { setErrors({ reciclador: 'Seleccione un reciclador' }); return; }
        if (!bodegaId)   { setErrors({ bodega_id: 'Seleccione una bodega' }); return; }
        if (items.length === 0) { setErrors({ items: 'Agregue al menos un material' }); return; }
        const itemsInvalidos = items.some(it => !it.material_id || it.kg_bruto <= 0);
        if (itemsInvalidos) { setErrors({ items: 'Verifique que todos los materiales tienen KG mayor a 0' }); return; }

        setProcessing(true);
        router.post('/recibos', {
            reciclador_id: reciclador.id,
            bodega_id:     bodegaId,
            fecha: fechaVal,
            observaciones: obs,
            items: items.map(it => ({
                material_id:  it.material_id,
                kg_bruto:     it.kg_bruto,
                kg_rechazado: it.kg_rechazado,
                precio_kg:    it.precio_kg,
            })),
        }, { onFinish: () => setProcessing(false) });
    };

    return (
        <AppLayout>
            <div className="max-w-4xl mx-auto">
                <div className="flex items-center gap-3 mb-6">
                    <Link href="/recibos" className="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-xl transition-colors">
                        <ArrowLeft size={18} />
                    </Link>
                    <div>
                        <h1 className="text-xl font-bold text-gray-900">Nuevo Recibo</h1>
                        <p className="text-xs text-gray-500 font-mono">N° {numero}</p>
                    </div>
                </div>

                <div className="space-y-5">
                    {/* Cabecera */}
                    <div className="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                        <h2 className="text-sm font-semibold text-gray-700 mb-4">Información del recibo</h2>
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div className="md:col-span-2">
                                <label className="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Reciclador</label>
                                <BuscadorReciclador onSelect={setReciclador} />
                                {reciclador && (
                                    <div className="mt-2 flex items-center gap-2 bg-green-50 rounded-lg px-3 py-2">
                                        <div className="w-7 h-7 rounded-full bg-green-700 text-white text-xs flex items-center justify-center font-bold shrink-0">
                                            {reciclador.nombre.charAt(0)}
                                        </div>
                                        <div>
                                            <p className="text-sm font-medium text-green-800">{reciclador.nombre}</p>
                                            <p className="text-xs text-green-600">CC {reciclador.documento}</p>
                                        </div>
                                    </div>
                                )}
                                {errors.reciclador && <p className="mt-1 text-xs text-red-500">{errors.reciclador}</p>}
                            </div>
                            <div>
                                <label className="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Fecha</label>
                                <input type="date" value={fechaVal} onChange={e => setFechaVal(e.target.value)}
                                    className="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500" />
                            </div>
                        </div>
                        <div className="mt-4">
                            <label className="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Bodega de destino</label>
                            <select value={bodegaId} onChange={e => setBodegaId(e.target.value)}
                                className={`w-full md:w-1/2 px-3 py-2.5 text-sm border rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 ${errors.bodega_id ? 'border-red-400' : 'border-gray-200'}`}>
                                <option value="">— Selecciona bodega —</option>
                                {bodegas.map(b => <option key={b.id} value={b.id}>{b.nombre}</option>)}
                            </select>
                            {errors.bodega_id && <p className="mt-1 text-xs text-red-500">{errors.bodega_id}</p>}
                        </div>
                    </div>

                    {/* Materiales */}
                    <div className="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                        <div className="flex items-center justify-between mb-4">
                            <h2 className="text-sm font-semibold text-gray-700">Detalle de materiales</h2>
                            <button onClick={agregarItem}
                                className="inline-flex items-center gap-1.5 text-sm text-green-700 hover:text-green-800 font-medium bg-green-50 hover:bg-green-100 px-3 py-1.5 rounded-lg transition-colors">
                                <Plus size={15} /> Agregar material
                            </button>
                        </div>

                        {items.length === 0 ? (
                            <div className="text-center py-10 text-gray-400">
                                <p className="text-sm">Sin materiales. Haz clic en "Agregar material" para comenzar.</p>
                            </div>
                        ) : (
                            <div className="space-y-3">
                                {/* Cabecera tabla */}
                                <div className="hidden md:grid grid-cols-[2fr_1fr_1fr_1fr_auto] gap-3 px-1 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                    <span>Material</span><span>KG bruto</span><span>KG rechazo</span><span>Precio/kg</span><span />
                                </div>

                                {items.map((item, i) => (
                                    <div key={i} className="grid grid-cols-1 md:grid-cols-[2fr_1fr_1fr_1fr_auto] gap-3 p-3 bg-gray-50 rounded-xl">
                                        <select value={item.material_id} onChange={e => actualizarItem(i, 'material_id', e.target.value)}
                                            className="px-3 py-2 text-sm border border-gray-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-green-500">
                                            <option value="0">Seleccionar material...</option>
                                            {Object.entries(materialesPorFamilia).map(([familia, mats]) => (
                                                <optgroup key={familia} label={familia}>
                                                    {mats.map(m => <option key={m.id} value={m.id}>{m.nombre}</option>)}
                                                </optgroup>
                                            ))}
                                        </select>

                                        <div>
                                            <label className="md:hidden text-xs text-gray-500 mb-1 block">KG Bruto</label>
                                            <input type="number" step="0.1" min="0" value={item.kg_bruto || ''}
                                                onChange={e => actualizarItem(i, 'kg_bruto', parseFloat(e.target.value) || 0)}
                                                placeholder="0.0"
                                                className="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" />
                                        </div>

                                        <div>
                                            <label className="md:hidden text-xs text-gray-500 mb-1 block">KG Rechazo</label>
                                            <input type="number" step="0.01" min="0" value={item.kg_rechazado || ''}
                                                onChange={e => actualizarItem(i, 'kg_rechazado', parseFloat(e.target.value) || 0)}
                                                placeholder="0.0"
                                                className="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" />
                                        </div>

                                        <div>
                                            <label className="md:hidden text-xs text-gray-500 mb-1 block">Precio/kg</label>
                                            <div className="relative">
                                                <span className="absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs">$</span>
                                                <input type="number" step="50" min="0" value={item.precio_kg || ''}
                                                    onChange={e => actualizarItem(i, 'precio_kg', parseFloat(e.target.value) || 0)}
                                                    placeholder="0"
                                                    className="w-full pl-6 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" />
                                            </div>
                                            {item.kg_bruto > 0 && item.precio_kg > 0 && (
                                                <p className="text-xs text-green-700 font-medium mt-0.5 text-right">
                                                    = {fmtCop(item.kg_bruto * item.precio_kg)}
                                                </p>
                                            )}
                                        </div>

                                        <button onClick={() => eliminarItem(i)} className="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors self-start mt-0.5">
                                            <Trash2 size={15} />
                                        </button>
                                    </div>
                                ))}
                            </div>
                        )}

                        {errors.items && <p className="mt-3 text-xs text-red-500">{errors.items}</p>}
                    </div>

                    {/* Observaciones + Totales */}
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div className="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                            <label className="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Observaciones</label>
                            <textarea value={obs} onChange={e => setObs(e.target.value)} rows={3}
                                placeholder="Notas adicionales del recibo..."
                                className="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl resize-none focus:outline-none focus:ring-2 focus:ring-green-500" />
                        </div>

                        <div className="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                            <h2 className="text-sm font-semibold text-gray-700 mb-4">Resumen</h2>
                            <div className="space-y-2 text-sm">
                                <div className="flex justify-between text-gray-600">
                                    <span>KG brutos:</span>
                                    <span className="font-medium">{totalKgBruto.toFixed(2)} kg</span>
                                </div>
                                <div className="flex justify-between text-gray-600">
                                    <span>KG netos:</span>
                                    <span className="font-medium">{totalKgNeto.toFixed(2)} kg</span>
                                </div>
                                <div className="flex justify-between text-gray-600">
                                    <span>Materiales:</span>
                                    <span className="font-medium">{items.length}</span>
                                </div>
                                <div className="border-t border-gray-100 pt-2 flex justify-between items-center">
                                    <span className="font-semibold text-gray-800">Total a pagar:</span>
                                    <span className="text-xl font-bold text-green-700">{fmtCop(subtotal)}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div className="flex gap-3 justify-end">
                        <Link href="/recibos" className="px-4 py-2.5 text-sm text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
                            Cancelar
                        </Link>
                        <button onClick={guardar} disabled={processing}
                            className="inline-flex items-center gap-2 bg-green-700 hover:bg-green-800 disabled:opacity-60 text-white text-sm font-medium px-6 py-2.5 rounded-xl transition-colors">
                            <Save size={16} />
                            {processing ? 'Guardando...' : 'Guardar recibo'}
                        </button>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
