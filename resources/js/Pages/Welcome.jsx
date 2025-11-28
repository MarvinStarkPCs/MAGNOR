import { Head, Link } from '@inertiajs/react';

export default function Welcome({ canLogin, canRegister }) {
    return (
        <>
            <Head title="MAGNOR - Sistema de Gestión de Chatarrería" />

            <div className="min-h-screen bg-gradient-to-br from-green-900 via-green-800 to-green-950">
                {/* Header/Navigation */}
                <nav className="fixed top-0 w-full bg-white/10 backdrop-blur-md border-b border-white/20 z-50">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="flex justify-between items-center h-16">
                            <div className="flex items-center gap-3">
                                <div className="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                                    <span className="text-white font-bold text-xl">M</span>
                                </div>
                                <span className="text-white font-bold text-xl">MAGNOR</span>
                            </div>

                            {canLogin && (
                                <div className="flex items-center gap-4">
                                    <Link
                                        href={route('login')}
                                        className="text-white hover:text-green-200 transition font-medium"
                                    >
                                        Iniciar Sesión
                                    </Link>
                                    {canRegister && (
                                        <Link
                                            href={route('register')}
                                            className="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg font-medium transition shadow-lg"
                                        >
                                            Registrarse
                                        </Link>
                                    )}
                                </div>
                            )}
                        </div>
                    </div>
                </nav>

                {/* Hero Section */}
                <div className="pt-32 pb-20 px-4 sm:px-6 lg:px-8">
                    <div className="max-w-7xl mx-auto">
                        <div className="text-center">
                            <h1 className="text-5xl md:text-6xl font-bold text-white mb-6">
                                Sistema de Gestión para
                                <span className="block text-green-300 mt-2">Chatarrerías</span>
                            </h1>
                            <p className="text-xl md:text-2xl text-green-100 mb-8 max-w-3xl mx-auto">
                                Controla tu inventario, ventas, compras y precios en tiempo real con MAGNOR
                            </p>
                            {canLogin && (
                                <div className="flex flex-col sm:flex-row gap-4 justify-center">
                                    <Link
                                        href={route('login')}
                                        className="bg-green-500 hover:bg-green-600 text-white px-8 py-4 rounded-lg font-bold text-lg transition shadow-xl"
                                    >
                                        Acceder al Sistema
                                    </Link>
                                    {canRegister && (
                                        <Link
                                            href={route('register')}
                                            className="bg-white hover:bg-gray-100 text-green-800 px-8 py-4 rounded-lg font-bold text-lg transition shadow-xl"
                                        >
                                            Crear Cuenta
                                        </Link>
                                    )}
                                </div>
                            )}
                        </div>

                        {/* Features Grid */}
                        <div className="mt-20 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <div className="bg-white/10 backdrop-blur-sm p-6 rounded-xl border border-white/20 hover:bg-white/15 transition">
                                <div className="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center mb-4">
                                    <svg className="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                </div>
                                <h3 className="text-white font-bold text-lg mb-2">Inventario</h3>
                                <p className="text-green-100 text-sm">
                                    Control total de materiales y stock en tiempo real
                                </p>
                            </div>

                            <div className="bg-white/10 backdrop-blur-sm p-6 rounded-xl border border-white/20 hover:bg-white/15 transition">
                                <div className="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center mb-4">
                                    <svg className="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h3 className="text-white font-bold text-lg mb-2">Ventas</h3>
                                <p className="text-green-100 text-sm">
                                    Gestión completa de ventas con facturación automática
                                </p>
                            </div>

                            <div className="bg-white/10 backdrop-blur-sm p-6 rounded-xl border border-white/20 hover:bg-white/15 transition">
                                <div className="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center mb-4">
                                    <svg className="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <h3 className="text-white font-bold text-lg mb-2">Compras</h3>
                                <p className="text-green-100 text-sm">
                                    Registro de compras a proveedores y recicladores
                                </p>
                            </div>

                            <div className="bg-white/10 backdrop-blur-sm p-6 rounded-xl border border-white/20 hover:bg-white/15 transition">
                                <div className="w-12 h-12 bg-orange-500 rounded-lg flex items-center justify-center mb-4">
                                    <svg className="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                </div>
                                <h3 className="text-white font-bold text-lg mb-2">Precios</h3>
                                <p className="text-green-100 text-sm">
                                    Actualización diaria de precios de materiales
                                </p>
                            </div>
                        </div>

                        {/* Additional Features */}
                        <div className="mt-20 bg-white/5 backdrop-blur-sm rounded-2xl p-8 border border-white/10">
                            <h2 className="text-3xl font-bold text-white text-center mb-12">
                                Características Principales
                            </h2>
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div className="flex gap-4">
                                    <div className="flex-shrink-0">
                                        <div className="w-10 h-10 bg-green-500/20 rounded-lg flex items-center justify-center">
                                            <svg className="w-5 h-5 text-green-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div>
                                        <h3 className="text-white font-semibold mb-1">Gestión de Clientes y Proveedores</h3>
                                        <p className="text-green-200 text-sm">Mantén un registro completo de todos tus contactos comerciales</p>
                                    </div>
                                </div>

                                <div className="flex gap-4">
                                    <div className="flex-shrink-0">
                                        <div className="w-10 h-10 bg-green-500/20 rounded-lg flex items-center justify-center">
                                            <svg className="w-5 h-5 text-green-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div>
                                        <h3 className="text-white font-semibold mb-1">Facturación Automática</h3>
                                        <p className="text-green-200 text-sm">Genera e imprime facturas profesionales al instante</p>
                                    </div>
                                </div>

                                <div className="flex gap-4">
                                    <div className="flex-shrink-0">
                                        <div className="w-10 h-10 bg-green-500/20 rounded-lg flex items-center justify-center">
                                            <svg className="w-5 h-5 text-green-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div>
                                        <h3 className="text-white font-semibold mb-1">Caja Menor</h3>
                                        <p className="text-green-200 text-sm">Control de ingresos y egresos diarios de tu negocio</p>
                                    </div>
                                </div>

                                <div className="flex gap-4">
                                    <div className="flex-shrink-0">
                                        <div className="w-10 h-10 bg-green-500/20 rounded-lg flex items-center justify-center">
                                            <svg className="w-5 h-5 text-green-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div>
                                        <h3 className="text-white font-semibold mb-1">Reportes y Estadísticas</h3>
                                        <p className="text-green-200 text-sm">Dashboard con métricas clave de tu chatarrería</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Footer */}
                <footer className="py-8 border-t border-white/10">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <p className="text-center text-green-200 text-sm">
                            © {new Date().getFullYear()} MAGNOR - Sistema de Gestión de Chatarrería
                        </p>
                    </div>
                </footer>
            </div>
        </>
    );
}
