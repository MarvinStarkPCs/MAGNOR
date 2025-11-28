import { Link } from '@inertiajs/react';

export default function GuestLayout({ children }) {
    return (
        <div className="min-h-screen bg-gradient-to-br from-primary-50 via-white to-secondary-50 flex items-center justify-center p-4">
            <div className="w-full max-w-md">
                {/* Login Card */}
                <div className="bg-white rounded-3xl shadow-2xl p-8 sm:p-10 backdrop-blur-sm bg-white/95">
                    {/* Título y Logo */}
                    <div className="text-center mb-8">
                        <h1 className="text-3xl font-bold bg-gradient-to-r from-primary-600 to-secondary-600 bg-clip-text text-transparent">
                            Bienvenido a MAGNOR
                        </h1>
                        <p className="mt-2 text-gray-600 mb-6">Sistema de gestión de chatarra</p>

                        {/* Logo */}
                        <Link href="/">
                            <img
                                src="/images/logo_login.png"
                                alt="MAGNOR"
                                className="h-24 w-auto mx-auto drop-shadow-lg"
                            />
                        </Link>
                    </div>

                    {children}
                </div>

                {/* Footer */}
                <div className="mt-6 text-center text-sm text-gray-600">
                    <p>© 2025 MAGNOR. Todos los derechos reservados.</p>
                </div>
            </div>
        </div>
    );
}
