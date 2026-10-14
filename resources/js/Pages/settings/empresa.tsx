import { useForm, usePage } from '@inertiajs/react';
import { Save, Building2, FileDigit, Truck, Percent } from 'lucide-react';

interface ConfigEntry { valor: string; descripcion: string }
interface Props {
    config: Record<string, ConfigEntry>;
}

interface FormData {
    empresa_nombre: string;
    empresa_municipio: string;
    nueca: string;
    numacro: string;
    numero_sitio_destino: string;
    tipo_sitio_destino: string;
    retefuente_porcentaje: string;
    porcentaje_rechazo_def: string;
    placa_sin_vehiculo: string;
}

function Field({ label, value, onChange, error, type = 'text', hint }: {
    label: string; value: string;
    onChange: (v: string) => void; error?: string;
    type?: string; hint?: string;
}) {
    return (
        <div>
            <label className="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">
                {label}
            </label>
            <input
                type={type} value={value} onChange={e => onChange(e.target.value)}
                className="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition"
            />
            {hint && <p className="text-xs text-gray-400 mt-1">{hint}</p>}
            {error && <p className="text-xs text-red-500 mt-1">{error}</p>}
        </div>
    );
}

function Section({ title, icon: Icon, children }: { title: string; icon: any; children: React.ReactNode }) {
    return (
        <div className="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-4">
            <div className="flex items-center gap-2 px-5 py-4 border-b border-gray-100 bg-gray-50">
                <Icon size={16} className="text-green-700" />
                <h2 className="font-semibold text-gray-800 text-sm">{title}</h2>
            </div>
            <div className="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                {children}
            </div>
        </div>
    );
}

export default function EmpresaSettings({ config }: Props) {
    const { flash } = usePage().props as any;

    const val = (k: keyof FormData) => config[k]?.valor ?? '';

    const { data, setData, patch, processing, errors } = useForm<FormData>({
        empresa_nombre:         val('empresa_nombre'),
        empresa_municipio:      val('empresa_municipio'),
        nueca:                  val('nueca'),
        numacro:                val('numacro'),
        numero_sitio_destino:   val('numero_sitio_destino'),
        tipo_sitio_destino:     val('tipo_sitio_destino'),
        retefuente_porcentaje:  val('retefuente_porcentaje'),
        porcentaje_rechazo_def: val('porcentaje_rechazo_def'),
        placa_sin_vehiculo:     val('placa_sin_vehiculo'),
    });

    const set = (k: keyof FormData) => (v: string) => setData(k, v);

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        patch('/settings/empresa');
    };

    return (
        <form onSubmit={submit}>
            <div className="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 mb-6">
                <div>
                    <h2 className="text-lg font-semibold text-gray-900">Configuración de Empresa</h2>
                    <p className="text-sm text-gray-500 mt-0.5">Parámetros generales del sistema ARECUPERAR</p>
                </div>
                <button type="submit" disabled={processing}
                    className="inline-flex items-center gap-2 bg-green-700 hover:bg-green-800 disabled:opacity-60 text-white text-sm px-4 py-2.5 rounded-xl transition-colors shrink-0">
                    <Save size={15} />
                    {processing ? 'Guardando...' : 'Guardar cambios'}
                </button>
            </div>

            {flash?.success && (
                <div className="bg-green-50 border border-green-200 text-green-700 text-sm rounded-xl px-4 py-3 mb-5">
                    {flash.success}
                </div>
            )}

            <Section title="Datos de la empresa" icon={Building2}>
                <Field label="Razón social"
                    value={data.empresa_nombre} onChange={set('empresa_nombre')}
                    error={errors.empresa_nombre} />
                <Field label="Municipio sede"
                    value={data.empresa_municipio} onChange={set('empresa_municipio')}
                    error={errors.empresa_municipio} />
            </Section>

            <Section title="Códigos SUI" icon={FileDigit}>
                <Field label="NUECA"
                    value={data.nueca} onChange={set('nueca')}
                    error={errors.nueca}
                    hint="Número Único de Establecimiento Comercial Autorizado" />
                <Field label="NUMACRO"
                    value={data.numacro} onChange={set('numacro')}
                    error={errors.numacro}
                    hint="Número de Macroruta asignado por la SSPD" />
                <Field label="N° único sitio destino del rechazo"
                    value={data.numero_sitio_destino} onChange={set('numero_sitio_destino')}
                    error={errors.numero_sitio_destino} />
                <Field label="Tipo de sitio destino"
                    value={data.tipo_sitio_destino} onChange={set('tipo_sitio_destino')}
                    type="number" error={errors.tipo_sitio_destino}
                    hint="1 = ECA · 2 = Planta · 3 = Relleno · 4 = Celda · 5 = Bodega" />
            </Section>

            <Section title="Parámetros financieros y operativos" icon={Percent}>
                <Field label="Retención en la fuente (%)"
                    value={data.retefuente_porcentaje} onChange={set('retefuente_porcentaje')}
                    type="number" error={errors.retefuente_porcentaje}
                    hint="Se aplica automáticamente al total de cada recibo" />
                <Field label="% de rechazo por defecto"
                    value={data.porcentaje_rechazo_def} onChange={set('porcentaje_rechazo_def')}
                    type="number" error={errors.porcentaje_rechazo_def}
                    hint="Porcentaje que se sugiere al ingresar KG brutos en un recibo" />
            </Section>

            <Section title="Vehículos" icon={Truck}>
                <Field label="Placa para recicladores sin vehículo"
                    value={data.placa_sin_vehiculo} onChange={set('placa_sin_vehiculo')}
                    error={errors.placa_sin_vehiculo}
                    hint="Generalmente 999 — se usa en el reporte SUI" />
            </Section>
        </form>
    );
}
