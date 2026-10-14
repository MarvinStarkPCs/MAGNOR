import { Link, usePage } from '@inertiajs/react';
import { ReactNode, useState } from 'react';
import {
    LayoutDashboard, Users, Package, FileText, Menu, X,
    Recycle, ChevronRight, LogOut, Settings, ClipboardList, Wallet, Warehouse, Truck
} from 'lucide-react';
import { cn } from '@/lib/utils';

const nav = [
    { href: '/dashboard',    label: 'Dashboard',    icon: LayoutDashboard },
    { href: '/recibos',      label: 'Recibos',      icon: FileText },
    { href: '/pagos',        label: 'Pagos',        icon: Wallet },
    { href: '/inventario',   label: 'Inventario',   icon: Warehouse },
    { href: '/recicladores', label: 'Recicladores', icon: Users },
    { href: '/materiales',   label: 'Materiales',   icon: Package },
    { href: '/registro',     label: 'Registro',     icon: ClipboardList },
];

export default function AppLayout({ children }: { children: ReactNode }) {
    const { url } = usePage();
    const [open, setOpen] = useState(false);

    return (
        <div className="flex h-screen bg-gray-50 overflow-hidden">
            {/* Sidebar desktop */}
            <aside className="hidden md:flex flex-col w-64 bg-green-800 text-white">
                <SidebarContent url={url} />
            </aside>

            {/* Sidebar mobile overlay */}
            {open && (
                <div className="fixed inset-0 z-40 md:hidden" onClick={() => setOpen(false)}>
                    <div className="absolute inset-0 bg-black/50" />
                    <aside className="relative flex flex-col w-64 h-full bg-green-800 text-white" onClick={e => e.stopPropagation()}>
                        <button onClick={() => setOpen(false)} className="absolute top-4 right-4 text-white/70 hover:text-white">
                            <X size={20} />
                        </button>
                        <SidebarContent url={url} onNav={() => setOpen(false)} />
                    </aside>
                </div>
            )}

            {/* Main */}
            <div className="flex flex-col flex-1 overflow-hidden">
                {/* Topbar */}
                <header className="flex items-center h-14 px-4 bg-white border-b border-gray-200 gap-3 shrink-0">
                    <button className="md:hidden text-gray-500 hover:text-gray-700" onClick={() => setOpen(true)}>
                        <Menu size={22} />
                    </button>
                    <span className="text-sm font-medium text-gray-500">
                        {nav.find(n => url === n.href || (n.href !== '/dashboard' && url.startsWith(n.href)))?.label
                            ?? (url.startsWith('/settings') ? 'Configuración' : 'Sistema')}
                    </span>
                </header>

                {/* Content */}
                <main className="flex-1 overflow-y-auto p-6">
                    {children}
                </main>
            </div>
        </div>
    );
}

function SidebarContent({ url, onNav }: { url: string; onNav?: () => void }) {
    return (
        <>
            {/* Logo */}
            <div className="flex items-center gap-3 px-5 py-5 border-b border-green-700">
                <div className="flex items-center justify-center w-9 h-9 rounded-xl bg-white/15">
                    <Recycle size={20} className="text-white" />
                </div>
                <div>
                    <p className="text-sm font-bold leading-none">ARECUPERAR</p>
                    <p className="text-[10px] text-green-300 mt-0.5">Sistema de Reciclaje</p>
                </div>
            </div>

            {/* Nav */}
            <nav className="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
                {nav.map(({ href, label, icon: Icon }) => {
                    const active = url.startsWith(href);
                    return (
                        <Link
                            key={href}
                            href={href}
                            onClick={onNav}
                            className={cn(
                                'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors',
                                active
                                    ? 'bg-white/20 text-white'
                                    : 'text-green-100 hover:bg-white/10 hover:text-white'
                            )}
                        >
                            <Icon size={18} className="shrink-0" />
                            <span className="flex-1">{label}</span>
                            {active && <ChevronRight size={14} className="text-green-300" />}
                        </Link>
                    );
                })}
            </nav>

            {/* Footer */}
            <div className="px-3 py-3 border-t border-green-700 space-y-0.5">
                <Link href="/vehiculos" className={cn(
                    'flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors',
                    url.startsWith('/vehiculos') ? 'bg-white/20 text-white' : 'text-green-100 hover:bg-white/10 hover:text-white'
                )}>
                    <Truck size={17} />
                    <span>Vehículos</span>
                </Link>
                <Link href="/settings/empresa" className={cn(
                    'flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors',
                    url.startsWith('/settings') ? 'bg-white/20 text-white' : 'text-green-100 hover:bg-white/10 hover:text-white'
                )}>
                    <Settings size={17} />
                    <span>Configuración</span>
                </Link>
                <Link href="/logout" method="post" as="button" className="flex w-full items-center gap-3 px-3 py-2 rounded-lg text-sm text-green-100 hover:bg-white/10 hover:text-white transition-colors">
                    <LogOut size={17} />
                    <span>Cerrar sesión</span>
                </Link>
            </div>
        </>
    );
}
