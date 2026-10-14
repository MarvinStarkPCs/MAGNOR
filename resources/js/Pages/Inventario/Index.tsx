import AppLayout from '@/layouts/AppLayout';
import { router } from '@inertiajs/react';
import { ClipboardList, Plus, Search, SlidersHorizontal, X, ArrowRightLeft } from 'lucide-react';
import { useState } from 'react';

interface Stock {
    id: number; material_id: number; bodega_id: number;
    material_nombre: string; familia_nombre: string; bodega_nombre: string;
    cantidad_kg: number; ultima_actualizacion: string | null;
}
interface Familia { id: number; nombre: string }
interface Bodega  { id: number; nombre: string }

interface Props {
    stocks: Stock[];
    familias: Familia[];
    bodegas: Bodega[];
    totalKg: number;
    filtros: { search?: string; familia_id?: string; bodega_id?: string };
}

function fmtKg(n: number) { return new Intl.NumberFormat('es-CO', { maximumFractionDigits: 2 }).format(n) + ' kg'; }

function nivelColor(kg: number) {
    if (kg <= 0)  return 'bg-red-100 text-red-700';
    if (kg < 100) return 'bg-amber-100 text-amber-700';
    return 'bg-green-100 text-green-700';
}
function nivelLabel(kg: number) {
    if (kg <= 0) return 'Sin stock';
    if (kg < 100) return 'Bajo';
    return 'Disponible';
}

export default function InventarioIndex({ stocks, familias, bodegas, totalKg, filtros }: Props) {
    const [search, setSearch]       = useState(filtros.search ?? '');
    const [familiaId, setFamiliaId] = useState(filtros.familia_id ?? '');
    const [bodegaId, setBodegaId]   = useState(filtros.bodega_id ?? '');

    const [showAjuste, setShowAjuste]           = useState(false);
    const [showTransferencia, setShowTransferencia] = useState(false);

    const [ajuste, setAjuste] = useState({
        material_id: '', bodega_id: '', kg: '', tipo: 'ajuste', direccion: 'sumar', observaciones: '',
    });
    const [ajusteErrors, setAjusteErrors] = useState<Record<string, string>>({});

    const [transferencia, setTransferencia] = useState({
        material_id: '', bodega_origen_id: '', bodega_destino_id: '', kg: '', observaciones: '',
    });
    const [transErrors, setTransErrors] = useState<Record<string, string>>({});

    function filtrar() {
        router.get('/inventario', { search, familia_id: familiaId, bodega_id: bodegaId }, { preserveState: true });
    }
    function limpiar() {
        setSearch(''); setFamiliaId(''); setBodegaId('');
        router.get('/inventario', {});
    }

    function submitAjuste(e: React.FormEvent) {
        e.preventDefault();
        router.post('/inventario/ajustar', ajuste, {
            onSuccess: () => {
                setShowAjuste(false);
                setAjuste({ material_id: '', bodega_id: '', kg: '', tipo: 'ajuste', direccion: 'sumar', observaciones: '' });
            },
            onError: setAjusteErrors,
        });
    }

    function submitTransferencia(e: React.FormEvent) {
        e.preventDefault();
        router.post('/inventario/transferir', transferencia, {
            onSuccess: () => {
                setShowTransferencia(false);
                setTransferencia({ material_id: '', bodega_origen_id: '', bodega_destino_id: '', kg: '', observaciones: '' });
            },
            onError: setTransErrors,
        });
    }

    const hayFiltros = search || familiaId || bodegaId;

    // Materiales únicos para los modales
    const materialesUnicos = Array.from(new Map(stocks.map(s => [s.material_id, s.material_nombre])).entries());

    return (
        <AppLayout>
            <div className="max-w-5xl mx-auto">
                <div className="flex items-center justify-between mb-6">
                    <div>
                        <h1 className="text-2xl font-bold text-gray-900">Inventario</h1>
                        <p className="text-sm text-gray-500 mt-0.5">Stock actual de materiales por bodega</p>
                    </div>
                    <div className="flex items-center gap-2">
                        <a href="/inventario/movimientos"
                            className="inline-flex items-center gap-2 text-sm text-gray-600 border border-gray-200 px-4 py-2 rounded-xl hover:bg-gray-50 transition-colors">
                            <ClipboardList size={15} /> Movimientos
                        </a>
                        <button onClick={() => setShowTransferencia(true)}
                            className="inline-flex items-center gap-2 text-sm text-blue-700 border border-blue-200 px-4 py-2 rounded-xl hover:bg-blue-50 transition-colors">
                            <ArrowRightLeft size={15} /> Transferir
                        </button>
                        <button onClick={() => setShowAjuste(true)}
                            className="inline-flex items-center gap-2 bg-green-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-green-800 transition-colors">
                            <SlidersHorizontal size={16} /> Ajuste manual
                        </button>
                    </div>
                </div>

                {/* KPIs */}
                <div className="grid grid-cols-3 gap-4 mb-6">
                    <div className="col-span-1 bg-green-700 text-white rounded-2xl p-5 shadow-sm">
                        <p className="text-xs font-semibold text-green-200 uppercase tracking-wide">Total en bodegas</p>
                        <p className="text-3xl font-bold mt-1">{fmtKg(totalKg)}</p>
                    </div>
                    <div className="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                        <p className="text-xs font-semibold text-gray-400 uppercase tracking-wide">Con stock</p>
                        <p className="text-2xl font-bold text-gray-800 mt-1">{stocks.filter(s => s.cantidad_kg > 0).length}</p>
                    </div>
                    <div className="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                        <p className="text-xs font-semibold text-gray-400 uppercase tracking-wide">Sin stock</p>
                        <p className="text-2xl font-bold text-red-600 mt-1">{stocks.filter(s => s.cantidad_kg <= 0).length}</p>
                    </div>
                </div>

                {/* Filtros */}
                <div className="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-4">
                    <div className="flex flex-wrap gap-3">
                        <div className="relative flex-1 min-w-[180px]">
                            <Search size={14} className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
                            <input value={search} onChange={e => setSearch(e.target.value)}
                                onKeyDown={e => e.key === 'Enter' && filtrar()}
                                placeholder="Buscar material…"
                                className="w-full pl-8 pr-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500" />
                        </div>
                        <select value={familiaId} onChange={e => setFamiliaId(e.target.value)}
                            className="px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">Todas las familias</option>
                            {familias.map(f => <option key={f.id} value={f.id}>{f.nombre}</option>)}
                        </select>
                        <select value={bodegaId} onChange={e => setBodegaId(e.target.value)}
                            className="px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">Todas las bodegas</option>
                            {bodegas.map(b => <option key={b.id} value={b.id}>{b.nombre}</option>)}
                        </select>
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

                {/* Tabla */}
                <div className="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    {stocks.length === 0 ? (
                        <div className="text-center py-16 text-gray-400">
                            <p className="text-lg font-medium">Sin datos de inventario</p>
                            <p className="text-sm mt-1">Los materiales aparecerán aquí cuando se registren recibos.</p>
                        </div>
                    ) : (
                        <table className="w-full text-sm">
                            <thead>
                                <tr className="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                    <th className="text-left px-5 py-3">Material</th>
                                    <th className="text-left px-4 py-3">Familia</th>
                                    <th className="text-left px-4 py-3">Bodega</th>
                                    <th className="text-right px-5 py-3">KG disponibles</th>
                                    <th className="text-center px-4 py-3">Estado</th>
                                    <th className="text-right px-5 py-3">Última entrada</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-gray-50">
                                {stocks.map(s => (
                                    <tr key={s.id} className="hover:bg-gray-50">
                                        <td className="px-5 py-3 font-medium text-gray-800">{s.material_nombre}</td>
                                        <td className="px-4 py-3 text-gray-500 text-xs">{s.familia_nombre}</td>
                                        <td className="px-4 py-3 text-xs">
                                            <span className="bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full font-medium">{s.bodega_nombre}</span>
                                        </td>
                                        <td className="px-5 py-3 text-right font-bold text-gray-900">{fmtKg(s.cantidad_kg)}</td>
                                        <td className="px-4 py-3 text-center">
                                            <span className={`inline-flex px-2 py-0.5 rounded-full text-xs font-medium ${nivelColor(s.cantidad_kg)}`}>
                                                {nivelLabel(s.cantidad_kg)}
                                            </span>
                                        </td>
                                        <td className="px-5 py-3 text-right text-gray-400 text-xs">
                                            {s.ultima_actualizacion
                                                ? new Date(s.ultima_actualizacion).toLocaleDateString('es-CO')
                                                : '—'}
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    )}
                </div>
            </div>

            {/* Modal ajuste manual */}
            {showAjuste && (
                <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4" onClick={() => setShowAjuste(false)}>
                    <div className="bg-white rounded-2xl shadow-xl w-full max-w-md p-6" onClick={e => e.stopPropagation()}>
                        <div className="flex items-center justify-between mb-4">
                            <h2 className="text-base font-bold text-gray-900">Ajuste manual de inventario</h2>
                            <button onClick={() => setShowAjuste(false)} className="text-gray-400 hover:text-gray-600"><X size={18} /></button>
                        </div>
                        <form onSubmit={submitAjuste} className="space-y-4">
                            <div>
                                <label className="block text-xs font-medium text-gray-600 mb-1.5">Bodega</label>
                                <select value={ajuste.bodega_id} onChange={e => setAjuste(a => ({ ...a, bodega_id: e.target.value }))}
                                    className={`w-full px-3 py-2 text-sm border rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 ${ajusteErrors.bodega_id ? 'border-red-400' : 'border-gray-200'}`}>
                                    <option value="">— Selecciona bodega —</option>
                                    {bodegas.map(b => <option key={b.id} value={b.id}>{b.nombre}</option>)}
                                </select>
                                {ajusteErrors.bodega_id && <p className="text-xs text-red-500 mt-1">{ajusteErrors.bodega_id}</p>}
                            </div>
                            <div>
                                <label className="block text-xs font-medium text-gray-600 mb-1.5">Material</label>
                                <select value={ajuste.material_id} onChange={e => setAjuste(a => ({ ...a, material_id: e.target.value }))}
                                    className={`w-full px-3 py-2 text-sm border rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 ${ajusteErrors.material_id ? 'border-red-400' : 'border-gray-200'}`}>
                                    <option value="">— Selecciona material —</option>
                                    {materialesUnicos.map(([id, nombre]) => (
                                        <option key={id} value={id}>{nombre}</option>
                                    ))}
                                </select>
                                {ajusteErrors.material_id && <p className="text-xs text-red-500 mt-1">{ajusteErrors.material_id}</p>}
                            </div>
                            <div className="grid grid-cols-2 gap-3">
                                <div>
                                    <label className="block text-xs font-medium text-gray-600 mb-1.5">Cantidad (kg)</label>
                                    <input type="number" min="0.001" step="0.001" value={ajuste.kg}
                                        onChange={e => setAjuste(a => ({ ...a, kg: e.target.value }))}
                                        className={`w-full px-3 py-2 text-sm border rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 ${ajusteErrors.kg ? 'border-red-400' : 'border-gray-200'}`} />
                                </div>
                                <div>
                                    <label className="block text-xs font-medium text-gray-600 mb-1.5">Operación</label>
                                    <div className="grid grid-cols-2 gap-1">
                                        {[['sumar', '+ Sumar'], ['restar', '− Restar']].map(([val, lbl]) => (
                                            <button key={val} type="button"
                                                onClick={() => setAjuste(a => ({ ...a, direccion: val }))}
                                                className={`py-2 text-xs rounded-xl border-2 font-medium transition-colors ${ajuste.direccion === val ? (val === 'sumar' ? 'border-green-600 bg-green-50 text-green-700' : 'border-red-500 bg-red-50 text-red-600') : 'border-gray-200 text-gray-500'}`}>
                                                {lbl}
                                            </button>
                                        ))}
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label className="block text-xs font-medium text-gray-600 mb-1.5">Motivo del ajuste <span className="text-red-500">*</span></label>
                                <input type="text" value={ajuste.observaciones}
                                    onChange={e => setAjuste(a => ({ ...a, observaciones: e.target.value }))}
                                    placeholder="Ej. Conteo físico, merma…"
                                    className={`w-full px-3 py-2 text-sm border rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 ${ajusteErrors.observaciones ? 'border-red-400' : 'border-gray-200'}`} />
                                {ajusteErrors.observaciones && <p className="text-xs text-red-500 mt-1">{ajusteErrors.observaciones}</p>}
                            </div>
                            <div className="flex justify-end gap-3 pt-2">
                                <button type="button" onClick={() => setShowAjuste(false)}
                                    className="px-4 py-2 text-sm text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-50">Cancelar</button>
                                <button type="submit"
                                    className="px-5 py-2 text-sm bg-green-700 text-white rounded-xl hover:bg-green-800 font-medium">Guardar ajuste</button>
                            </div>
                        </form>
                    </div>
                </div>
            )}

            {/* Modal transferencia */}
            {showTransferencia && (
                <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4" onClick={() => setShowTransferencia(false)}>
                    <div className="bg-white rounded-2xl shadow-xl w-full max-w-md p-6" onClick={e => e.stopPropagation()}>
                        <div className="flex items-center justify-between mb-4">
                            <h2 className="text-base font-bold text-gray-900 flex items-center gap-2">
                                <ArrowRightLeft size={18} className="text-blue-600" /> Transferir entre bodegas
                            </h2>
                            <button onClick={() => setShowTransferencia(false)} className="text-gray-400 hover:text-gray-600"><X size={18} /></button>
                        </div>
                        <form onSubmit={submitTransferencia} className="space-y-4">
                            <div>
                                <label className="block text-xs font-medium text-gray-600 mb-1.5">Material</label>
                                <select value={transferencia.material_id} onChange={e => setTransferencia(t => ({ ...t, material_id: e.target.value }))}
                                    className={`w-full px-3 py-2 text-sm border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 ${transErrors.material_id ? 'border-red-400' : 'border-gray-200'}`}>
                                    <option value="">— Selecciona material —</option>
                                    {materialesUnicos.map(([id, nombre]) => (
                                        <option key={id} value={id}>{nombre}</option>
                                    ))}
                                </select>
                                {transErrors.material_id && <p className="text-xs text-red-500 mt-1">{transErrors.material_id}</p>}
                            </div>
                            <div className="grid grid-cols-2 gap-3">
                                <div>
                                    <label className="block text-xs font-medium text-gray-600 mb-1.5">Bodega origen</label>
                                    <select value={transferencia.bodega_origen_id} onChange={e => setTransferencia(t => ({ ...t, bodega_origen_id: e.target.value }))}
                                        className={`w-full px-3 py-2 text-sm border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 ${transErrors.bodega_origen_id ? 'border-red-400' : 'border-gray-200'}`}>
                                        <option value="">— Origen —</option>
                                        {bodegas.map(b => <option key={b.id} value={b.id}>{b.nombre}</option>)}
                                    </select>
                                </div>
                                <div>
                                    <label className="block text-xs font-medium text-gray-600 mb-1.5">Bodega destino</label>
                                    <select value={transferencia.bodega_destino_id} onChange={e => setTransferencia(t => ({ ...t, bodega_destino_id: e.target.value }))}
                                        className={`w-full px-3 py-2 text-sm border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 ${transErrors.bodega_destino_id ? 'border-red-400' : 'border-gray-200'}`}>
                                        <option value="">— Destino —</option>
                                        {bodegas.filter(b => String(b.id) !== transferencia.bodega_origen_id).map(b => (
                                            <option key={b.id} value={b.id}>{b.nombre}</option>
                                        ))}
                                    </select>
                                    {transErrors.bodega_destino_id && <p className="text-xs text-red-500 mt-1">{transErrors.bodega_destino_id}</p>}
                                </div>
                            </div>
                            <div>
                                <label className="block text-xs font-medium text-gray-600 mb-1.5">Cantidad (kg)</label>
                                <input type="number" min="0.001" step="0.001" value={transferencia.kg}
                                    onChange={e => setTransferencia(t => ({ ...t, kg: e.target.value }))}
                                    className={`w-full px-3 py-2 text-sm border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 ${transErrors.kg ? 'border-red-400' : 'border-gray-200'}`} />
                                {transErrors.kg && <p className="text-xs text-red-500 mt-1">{transErrors.kg}</p>}
                            </div>
                            <div>
                                <label className="block text-xs font-medium text-gray-600 mb-1.5">Observaciones</label>
                                <input type="text" value={transferencia.observaciones}
                                    onChange={e => setTransferencia(t => ({ ...t, observaciones: e.target.value }))}
                                    placeholder="Motivo de la transferencia…"
                                    className="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            </div>
                            <div className="flex justify-end gap-3 pt-2">
                                <button type="button" onClick={() => setShowTransferencia(false)}
                                    className="px-4 py-2 text-sm text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-50">Cancelar</button>
                                <button type="submit"
                                    className="px-5 py-2 text-sm bg-blue-600 text-white rounded-xl hover:bg-blue-700 font-medium">Transferir</button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </AppLayout>
    );
}
