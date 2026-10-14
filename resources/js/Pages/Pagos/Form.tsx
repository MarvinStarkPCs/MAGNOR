import AppLayout from '@/layouts/AppLayout';
import { router } from '@inertiajs/react';
import { ArrowLeft, Loader2 } from 'lucide-react';
import { useEffect, useState } from 'react';

interface Reciclador { id: number; nombre: string; documento: string }
interface ReciboPendiente { id: number; numero: number; fecha: string; total: number }

interface Props {
    recicladores: Reciclador[];
    recicladorId: number | null;
    recibosPendientes: ReciboPendiente[];
    fecha: string;
}

function fmtCop(n: number) { return '$' + new Intl.NumberFormat('es-CO').format(Math.round(n ?? 0)); }

export default function PagosForm({ recicladores, recicladorId, recibosPendientes: initialRecibos, fecha }: Props) {
    const [recicladorSeleccionado, setRecicladorSeleccionado] = useState<number | ''>(recicladorId ?? '');
    const [recibos, setRecibos] = useState<ReciboPendiente[]>(initialRecibos);
    const [loadingRecibos, setLoadingRecibos] = useState(false);

    const [form, setForm] = useState({
        reciclador_id: recicladorId ?? '',
        recibo_id: '' as number | '',
        fecha,
        monto: '' as number | '',
        forma_pago: 'efectivo' as 'efectivo' | 'transferencia' | 'cheque',
        referencia: '',
        observaciones: '',
    });
    const [errors, setErrors] = useState<Record<string, string>>({});
    const [submitting, setSubmitting] = useState(false);

    // Cargar recibos cuando cambia el reciclador
    useEffect(() => {
        if (!recicladorSeleccionado) { setRecibos([]); return; }
        setLoadingRecibos(true);
        fetch(`/pagos/recibos-por-reciclador?reciclador_id=${recicladorSeleccionado}`)
            .then(r => r.json())
            .then((data: ReciboPendiente[]) => { setRecibos(data); setLoadingRecibos(false); })
            .catch(() => setLoadingRecibos(false));
    }, [recicladorSeleccionado]);

    // Si se selecciona un recibo, autocompletar el monto
    function onReciboChange(reciboId: number | '') {
        setForm(f => ({
            ...f,
            recibo_id: reciboId,
            monto: reciboId ? (recibos.find(r => r.id === reciboId)?.total ?? '') : f.monto,
        }));
    }

    function set(field: string, value: unknown) {
        setForm(f => ({ ...f, [field]: value }));
        setErrors(e => { const c = { ...e }; delete c[field]; return c; });
    }

    function submit(e: React.FormEvent) {
        e.preventDefault();
        setSubmitting(true);
        router.post('/pagos', { ...form }, {
            onError: (errs) => { setErrors(errs); setSubmitting(false); },
            onFinish: () => setSubmitting(false),
        });
    }

    const inputClass = (field: string) =>
        `w-full px-3 py-2 text-sm border rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 ${errors[field] ? 'border-red-400' : 'border-gray-200'}`;

    return (
        <AppLayout>
            <div className="max-w-xl mx-auto">
                <div className="flex items-center gap-3 mb-6">
                    <button onClick={() => router.visit('/pagos')}
                        className="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-xl transition-colors">
                        <ArrowLeft size={18} />
                    </button>
                    <div>
                        <h1 className="text-xl font-bold text-gray-900">Registrar pago</h1>
                        <p className="text-sm text-gray-500">Pago por compra de materiales reciclables</p>
                    </div>
                </div>

                <form onSubmit={submit} className="bg-white rounded-2xl border border-gray-100 shadow-sm divide-y divide-gray-50">

                    {/* Reciclador */}
                    <div className="p-6 space-y-4">
                        <h2 className="text-sm font-semibold text-gray-700 uppercase tracking-wide">Reciclador</h2>

                        <div>
                            <label className="block text-xs font-medium text-gray-600 mb-1.5">Reciclador <span className="text-red-500">*</span></label>
                            <select value={recicladorSeleccionado}
                                onChange={e => {
                                    const v = e.target.value ? Number(e.target.value) : '';
                                    setRecicladorSeleccionado(v);
                                    set('reciclador_id', v);
                                    set('recibo_id', '');
                                }}
                                className={inputClass('reciclador_id')}>
                                <option value="">— Selecciona un reciclador —</option>
                                {recicladores.map(r => (
                                    <option key={r.id} value={r.id}>{r.nombre} — {r.documento}</option>
                                ))}
                            </select>
                            {errors.reciclador_id && <p className="text-xs text-red-500 mt-1">{errors.reciclador_id}</p>}
                        </div>

                        {/* Recibo asociado (opcional) */}
                        <div>
                            <label className="block text-xs font-medium text-gray-600 mb-1.5">
                                Recibo asociado <span className="text-gray-400 font-normal">(opcional)</span>
                                {loadingRecibos && <Loader2 size={12} className="inline ml-1 animate-spin" />}
                            </label>
                            <select value={form.recibo_id} onChange={e => onReciboChange(e.target.value ? Number(e.target.value) : '')}
                                disabled={!recicladorSeleccionado || loadingRecibos}
                                className={inputClass('recibo_id') + ' disabled:bg-gray-50 disabled:text-gray-400'}>
                                <option value="">— Sin asociar a recibo específico —</option>
                                {recibos.map(r => (
                                    <option key={r.id} value={r.id}>
                                        Recibo #{r.numero} — {r.fecha.substring(0, 10).split('-').reverse().join('/')} — {fmtCop(r.total)}
                                    </option>
                                ))}
                            </select>
                            {recibos.length === 0 && recicladorSeleccionado && !loadingRecibos && (
                                <p className="text-xs text-gray-400 mt-1">Este reciclador no tiene recibos activos.</p>
                            )}
                        </div>
                    </div>

                    {/* Datos del pago */}
                    <div className="p-6 space-y-4">
                        <h2 className="text-sm font-semibold text-gray-700 uppercase tracking-wide">Datos del pago</h2>

                        <div className="grid grid-cols-2 gap-4">
                            <div>
                                <label className="block text-xs font-medium text-gray-600 mb-1.5">Fecha <span className="text-red-500">*</span></label>
                                <input type="date" value={form.fecha} onChange={e => set('fecha', e.target.value)}
                                    className={inputClass('fecha')} />
                                {errors.fecha && <p className="text-xs text-red-500 mt-1">{errors.fecha}</p>}
                            </div>
                            <div>
                                <label className="block text-xs font-medium text-gray-600 mb-1.5">Monto ($) <span className="text-red-500">*</span></label>
                                <input type="number" min="0.01" step="0.01" value={form.monto}
                                    onChange={e => set('monto', e.target.value ? Number(e.target.value) : '')}
                                    placeholder="0.00"
                                    className={inputClass('monto')} />
                                {errors.monto && <p className="text-xs text-red-500 mt-1">{errors.monto}</p>}
                            </div>
                        </div>

                        <div>
                            <label className="block text-xs font-medium text-gray-600 mb-1.5">Forma de pago <span className="text-red-500">*</span></label>
                            <div className="grid grid-cols-3 gap-2">
                                {(['efectivo', 'transferencia', 'cheque'] as const).map(f => (
                                    <button key={f} type="button"
                                        onClick={() => set('forma_pago', f)}
                                        className={`py-2 text-sm rounded-xl border-2 font-medium transition-colors capitalize ${form.forma_pago === f ? 'border-green-600 bg-green-50 text-green-700' : 'border-gray-200 text-gray-500 hover:border-gray-300'}`}>
                                        {f.charAt(0).toUpperCase() + f.slice(1)}
                                    </button>
                                ))}
                            </div>
                        </div>

                        {form.forma_pago !== 'efectivo' && (
                            <div>
                                <label className="block text-xs font-medium text-gray-600 mb-1.5">Referencia / No. comprobante</label>
                                <input type="text" value={form.referencia} onChange={e => set('referencia', e.target.value)}
                                    placeholder="Ej. TXN-20240512-001"
                                    className={inputClass('referencia')} />
                            </div>
                        )}

                        <div>
                            <label className="block text-xs font-medium text-gray-600 mb-1.5">Observaciones</label>
                            <textarea value={form.observaciones} onChange={e => set('observaciones', e.target.value)}
                                rows={2} placeholder="Notas adicionales…"
                                className={inputClass('observaciones') + ' resize-none'} />
                        </div>
                    </div>

                    {/* Botones */}
                    <div className="p-6 flex justify-end gap-3">
                        <button type="button" onClick={() => router.visit('/pagos')}
                            className="px-5 py-2 text-sm text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
                            Cancelar
                        </button>
                        <button type="submit" disabled={submitting}
                            className="inline-flex items-center gap-2 px-6 py-2 text-sm bg-green-700 text-white rounded-xl hover:bg-green-800 disabled:opacity-60 transition-colors font-medium">
                            {submitting && <Loader2 size={14} className="animate-spin" />}
                            Guardar pago
                        </button>
                    </div>
                </form>
            </div>
        </AppLayout>
    );
}
