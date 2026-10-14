import { createInertiaApp } from '@inertiajs/react';
import { Toaster } from '@/components/ui/sonner';
import { TooltipProvider } from '@/components/ui/tooltip';
import { initializeTheme } from '@/hooks/use-appearance';
import GreenLayout from '@/layouts/AppLayout';
import AuthLayout from '@/layouts/auth-layout';
import SettingsLayout from '@/layouts/settings/layout';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    layout: (name) => {
        switch (true) {
            // Estas páginas gestionan su propio layout internamente
            case name === 'welcome':
            case name === 'dashboard':
            case name.startsWith('Recibos/'):
            case name.startsWith('Recicladores/'):
            case name.startsWith('Materiales/'):
            case name.startsWith('Vehiculos/'):
            case name.startsWith('Inventario/'):
            case name.startsWith('Pagos/'):
            case name.startsWith('Registro/'):
                return null;
            case name.startsWith('auth/'):
                return AuthLayout;
            // Settings: el sidebar verde envuelve el sub-nav de configuración
            case name.startsWith('settings/'):
                return [GreenLayout, SettingsLayout];
            default:
                return null;
        }
    },
    strictMode: true,
    withApp(app) {
        return (
            <TooltipProvider delayDuration={0}>
                {app}
                <Toaster />
            </TooltipProvider>
        );
    },
    progress: {
        color: '#4B5563',
    },
});

// This will set light / dark mode on load...
initializeTheme();
