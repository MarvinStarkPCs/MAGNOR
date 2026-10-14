import { usePage } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import { CheckCircle2, XCircle, X } from 'lucide-react';

export default function Flash() {
    const { props } = usePage<{ flash?: { success?: string; error?: string } }>();
    const [visible, setVisible] = useState(false);
    const msg = props.flash?.success || props.flash?.error;
    const isSuccess = !!props.flash?.success;

    useEffect(() => {
        if (msg) {
            setVisible(true);
            const t = setTimeout(() => setVisible(false), 4000);
            return () => clearTimeout(t);
        }
    }, [msg]);

    if (!visible || !msg) return null;

    return (
        <div className={`fixed top-4 right-4 z-50 flex items-center gap-3 px-4 py-3 rounded-xl shadow-lg text-sm font-medium max-w-sm animate-in slide-in-from-top-2 ${
            isSuccess ? 'bg-green-50 text-green-800 border border-green-200' : 'bg-red-50 text-red-800 border border-red-200'
        }`}>
            {isSuccess ? <CheckCircle2 size={18} className="text-green-600 shrink-0" /> : <XCircle size={18} className="text-red-600 shrink-0" />}
            <span className="flex-1">{msg}</span>
            <button onClick={() => setVisible(false)} className="opacity-50 hover:opacity-100 transition-opacity">
                <X size={16} />
            </button>
        </div>
    );
}
