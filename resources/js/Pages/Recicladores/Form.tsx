import AppLayout from '@/layouts/AppLayout';
import { useForm, Link } from '@inertiajs/react';
import { ArrowLeft, Save } from 'lucide-react';

interface Props {
    reciclador?: any;
    municipios: { id: number; nombre: string }[];
    vehiculos: { id: number; placa: string; tipo: string }[];
    dias: { id: number; nombre: string }[];
}

export default function RecicladorForm({ reciclador, municipios, vehiculos, dias }: Props) {
    const esEdicion = !!reciclador;

    const { data, setData, post, put, processing, errors } = useForm({
        nombre:      reciclador?.nombre ?? '',
        documento:   reciclador?.documento ?? '',
        telefono:    reciclador?.telefono ?? '',
        direccion:   reciclador?.direccion ?? '',
        municipio_id: reciclador?.municipio_id ?? '',
        vehiculo_id:  reciclador?.vehiculo_id ?? '',
        activo:       reciclador?.activo ?? true,
        rutas: dias.map(d => ({
            dia_id: d.id,
            descripcion: reciclador?.rutas?.find((r: any) => r.dia_id === d.id)?.descripcion ?? '',
        })),
    });

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        esEdicion
            ? put(`/recicladores/${reciclador.id}`)
            : post('/recicladores');
    };

    const field = (label: string, name: string, props: any = {}) => (
        <div>
            <label className="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">{label}</label>
            <input
                value={(data as any)[name]}
                onChange={e => setData(name as any, e.target.value)}
                className={`w-full px-3 py-2.5 text-sm border rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 ${errors[name as keyof typeof errors] ? 'border-red-400 bg-red-50' : 'border-gray-200'}`}
                {...props}
            />
            {errors[name as keyof typeof errors] && <p className="mt-1 text-xs text-red-500">{errors[name as keyof typeof errors]}</p>}
        </div>
    );

    return (
        <AppLayout>
            <div className="max-w-2xl mx-auto">
                <div className="flex items-center gap-3 mb-6">
                    <Link href="/recicladores" className="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-xl transition-colors">
                        <ArrowLeft size={18} />
                    </Link>
                    <h1 className="text-xl font-bold text-gray-900">
                        {esEdicion ? 'Editar reciclador' : 'Nuevo reciclador'}
                    </h1>
                </div>

                <form onSubmit={submit} className="space-y-5">
                    {/* Datos personales */}
                    <div className="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                        <h2 className="text-sm font-semibold text-gray-700 mb-4">Datos personales</h2>
                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div className="sm:col-span-2">{field('Nombre completo', 'nombre', { placeholder: 'Nombre completo del reciclador' })}</div>
                            {field('Número de cédula', 'documento', { placeholder: 'Sin puntos ni guiones' })}
                            {field('Teléfono', 'telefono', { placeholder: '300 000 0000' })}
                            <div className="sm:col-span-2">{field('Dirección', 'direccion', { placeholder: 'Dirección de residencia' })}</div>

                            <div>
                                <label className="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Municipio</label>
                                <select value={data.municipio_id} onChange={e => setData('municipio_id', e.target.value)}
                                    className="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 bg-white">
                                    <option value="">Sin municipio</option>
                                    {municipios.map(m => <option key={m.id} value={m.id}>{m.nombre}</option>)}
                                </select>
                            </div>

                            <div>
                                <label className="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Vehículo</label>
                                <select value={data.vehiculo_id} onChange={e => setData('vehiculo_id', e.target.value)}
                                    className="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 bg-white">
                                    <option value="">Sin vehículo</option>
                                    {vehiculos.map(v => <option key={v.id} value={v.id}>{v.placa} — {v.tipo}</option>)}
                                </select>
                            </div>

                            {esEdicion && (
                                <div className="flex items-center gap-3">
                                    <input type="checkbox" id="activo" checked={data.activo}
                                        onChange={e => setData('activo', e.target.checked)}
                                        className="w-4 h-4 rounded border-gray-300 text-green-600 focus:ring-green-500" />
                                    <label htmlFor="activo" className="text-sm text-gray-700">Reciclador activo</label>
                                </div>
                            )}
                        </div>
                    </div>

                    {/* Rutas */}
                    <div className="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                        <h2 className="text-sm font-semibold text-gray-700 mb-4">Rutas de recolección</h2>
                        <div className="space-y-3">
                            {dias.map((dia, i) => (
                                <div key={dia.id} className="flex items-start gap-3">
                                    <span className="w-20 text-xs font-semibold text-gray-500 uppercase pt-2.5 shrink-0">{dia.nombre}</span>
                                    <input
                                        value={data.rutas[i]?.descripcion ?? ''}
                                        onChange={e => {
                                            const rutas = [...data.rutas];
                                            rutas[i] = { ...rutas[i], descripcion: e.target.value };
                                            setData('rutas', rutas);
                                        }}
                                        placeholder={`Barrios que recorre los ${dia.nombre.toLowerCase()}s`}
                                        className="flex-1 px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500"
                                    />
                                </div>
                            ))}
                        </div>
                    </div>

                    <div className="flex gap-3 justify-end">
                        <Link href="/recicladores" className="px-4 py-2.5 text-sm text-gray-600 hover:text-gray-800 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
                            Cancelar
                        </Link>
                        <button type="submit" disabled={processing}
                            className="inline-flex items-center gap-2 bg-green-700 hover:bg-green-800 disabled:opacity-60 text-white text-sm font-medium px-5 py-2.5 rounded-xl transition-colors">
                            <Save size={16} />
                            {processing ? 'Guardando...' : 'Guardar reciclador'}
                        </button>
                    </div>
                </form>
            </div>
        </AppLayout>
    );
}
