<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MAGNOR - Sistema de Gestión de Chatarrería y Reciclaje</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'recycle-green': '#146e39',
                        'primary-blue': '#276691',
                        'danger-red': '#cc2128',
                        'warning-orange': '#f78921',
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }
    </style>
</head>
<body class="bg-white">
    <!-- Navigation -->
    <nav class="bg-white border-b-2 border-recycle-green shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('images/logo_nav.png') }}" alt="MAGNOR Logo" class="h-12 w-auto">
                    <div class="hidden md:block border-l-2 border-gray-300 pl-4">
                        <span class="text-gray-900 font-bold text-lg">MAGNOR</span>
                        <p class="text-gray-600 text-sm">Sistema de Gestión</p>
                    </div>
                </div>
                <div class="flex items-center space-x-6">
                    <a href="#features" class="hidden md:inline-block text-gray-700 hover:text-recycle-green font-medium transition-colors">
                        Características
                    </a>
                    <a href="#about" class="hidden md:inline-block text-gray-700 hover:text-recycle-green font-medium transition-colors">
                        Nosotros
                    </a>
                    <a href="/admin" class="inline-flex items-center px-6 py-2.5 bg-recycle-green text-white font-semibold rounded-lg hover:bg-opacity-90 transition-all shadow-md hover:shadow-lg">
                        Acceder
                        <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="bg-gradient-to-b from-green-50 to-white py-16 md:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="fade-in-up">
                    <div class="inline-flex items-center px-4 py-2 bg-green-100 text-recycle-green rounded-full text-sm font-semibold mb-6">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Solución Profesional
                    </div>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 mb-6 leading-tight">
                        Sistema de Gestión <span class="text-recycle-green">MAGNOR</span>
                    </h1>
                    <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                        Plataforma integral para la administración eficiente de chatarrerías y operaciones de reciclaje. Control total de inventario, compras, ventas y gestión de proveedores.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="/admin" class="inline-flex items-center justify-center px-8 py-4 bg-recycle-green text-white font-semibold rounded-lg hover:bg-opacity-90 transition-all shadow-lg hover:shadow-xl">
                            Comenzar Ahora
                            <svg class="ml-2 w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </a>
                        <a href="#features" class="inline-flex items-center justify-center px-8 py-4 bg-white text-recycle-green font-semibold rounded-lg border-2 border-recycle-green hover:bg-green-50 transition-all">
                            Ver más
                        </a>
                    </div>
                </div>
                <div class="flex justify-center lg:justify-end">
                    <div class="relative">
                        <div class="absolute inset-0 bg-recycle-green opacity-10 rounded-3xl transform rotate-6"></div>
                        <img src="{{ asset('images/logo_login.png') }}" alt="MAGNOR Logo" class="relative h-80 w-auto drop-shadow-2xl">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="bg-recycle-green py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center text-white">
                <div>
                    <div class="text-4xl font-bold mb-2">100%</div>
                    <div class="text-green-100">Sistema Digital</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">24/7</div>
                    <div class="text-green-100">Disponibilidad</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">∞</div>
                    <div class="text-green-100">Materiales</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">+</div>
                    <div class="text-green-100">Eco-Friendly</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-base text-recycle-green font-semibold tracking-wide uppercase mb-3">Características</h2>
                <h3 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Solución Completa para su Negocio
                </h3>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Herramientas profesionales diseñadas para optimizar cada aspecto de su operación
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Feature 1: Compras -->
                <div class="bg-white rounded-xl shadow-md p-8 hover:shadow-xl transition-shadow border-t-4 border-recycle-green">
                    <div class="flex items-center justify-center h-16 w-16 rounded-lg bg-green-100 text-recycle-green mb-6 mx-auto">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 text-center mb-3">Gestión de Compras</h3>
                    <p class="text-gray-600 text-center">
                        Control completo de compras a proveedores y recicladores con precios del día actualizables.
                    </p>
                </div>

                <!-- Feature 2: Ventas -->
                <div class="bg-white rounded-xl shadow-md p-8 hover:shadow-xl transition-shadow border-t-4 border-primary-blue">
                    <div class="flex items-center justify-center h-16 w-16 rounded-lg bg-blue-100 text-primary-blue mb-6 mx-auto">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 text-center mb-3">Control de Ventas</h3>
                    <p class="text-gray-600 text-center">
                        Facturación eficiente y seguimiento detallado de clientes con reportes de rentabilidad.
                    </p>
                </div>

                <!-- Feature 3: Inventario -->
                <div class="bg-white rounded-xl shadow-md p-8 hover:shadow-xl transition-shadow border-t-4 border-warning-orange">
                    <div class="flex items-center justify-center h-16 w-16 rounded-lg bg-orange-100 text-warning-orange mb-6 mx-auto">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 text-center mb-3">Inventario en Tiempo Real</h3>
                    <p class="text-gray-600 text-center">
                        Seguimiento automático de stock con alertas configurables de nivel bajo.
                    </p>
                </div>

                <!-- Feature 4: Reportes -->
                <div class="bg-white rounded-xl shadow-md p-8 hover:shadow-xl transition-shadow border-t-4 border-danger-red">
                    <div class="flex items-center justify-center h-16 w-16 rounded-lg bg-red-100 text-danger-red mb-6 mx-auto">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 text-center mb-3">Reportes y Análisis</h3>
                    <p class="text-gray-600 text-center">
                        Dashboard completo con métricas clave y reportes exportables en múltiples formatos.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-base text-recycle-green font-semibold tracking-wide uppercase mb-3">Sobre MAGNOR</h2>
                    <h3 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                        Tecnología para un Futuro Sostenible
                    </h3>
                    <p class="text-lg text-gray-600 mb-6">
                        MAGNOR es una plataforma diseñada específicamente para el sector del reciclaje y gestión de materiales. Nuestra solución permite a las empresas operar de manera más eficiente mientras contribuyen al cuidado del medio ambiente.
                    </p>
                    <ul class="space-y-4">
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-recycle-green mr-3 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-700"><strong>Gestión Integral:</strong> Controle todos los aspectos de su negocio desde una sola plataforma</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-recycle-green mr-3 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-700"><strong>Apoyo a Recicladores:</strong> Gestión transparente de compras a proveedores y recicladores</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-recycle-green mr-3 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-700"><strong>Cumplimiento Normativo:</strong> Herramientas para cumplir con regulaciones ambientales</span>
                        </li>
                    </ul>
                </div>
                <div class="grid grid-cols-2 gap-6">
                    <div class="bg-green-50 rounded-xl p-6 border-l-4 border-recycle-green">
                        <div class="text-3xl font-bold text-recycle-green mb-2">Eficiencia</div>
                        <p class="text-gray-600">Optimice sus procesos operativos</p>
                    </div>
                    <div class="bg-blue-50 rounded-xl p-6 border-l-4 border-primary-blue">
                        <div class="text-3xl font-bold text-primary-blue mb-2">Control</div>
                        <p class="text-gray-600">Visibilidad total de su negocio</p>
                    </div>
                    <div class="bg-orange-50 rounded-xl p-6 border-l-4 border-warning-orange">
                        <div class="text-3xl font-bold text-warning-orange mb-2">Precisión</div>
                        <p class="text-gray-600">Datos exactos en tiempo real</p>
                    </div>
                    <div class="bg-red-50 rounded-xl p-6 border-l-4 border-danger-red">
                        <div class="text-3xl font-bold text-danger-red mb-2">Análisis</div>
                        <p class="text-gray-600">Decisiones basadas en datos</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-gradient-to-r from-recycle-green to-primary-blue py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">
                ¿Listo para Optimizar su Operación?
            </h2>
            <p class="text-xl text-green-100 mb-8 max-w-2xl mx-auto">
                Comience a utilizar MAGNOR hoy y transforme la gestión de su chatarrería
            </p>
            <a href="/admin" class="inline-flex items-center px-10 py-4 bg-white text-recycle-green font-bold rounded-lg hover:bg-gray-100 transition-all shadow-xl text-lg">
                Acceder al Sistema
                <svg class="ml-3 w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div class="col-span-1 md:col-span-2">
                    <img src="{{ asset('images/logo_nav.png') }}" alt="MAGNOR Logo" class="h-10 w-auto mb-4 brightness-0 invert">
                    <p class="text-gray-400 mb-4">Sistema Profesional de Gestión de Chatarrería y Reciclaje</p>
                    <p class="text-gray-500 text-sm">Optimizando el sector del reciclaje con tecnología avanzada</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Características</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li class="hover:text-white transition-colors cursor-pointer">Gestión de Compras</li>
                        <li class="hover:text-white transition-colors cursor-pointer">Control de Ventas</li>
                        <li class="hover:text-white transition-colors cursor-pointer">Inventario</li>
                        <li class="hover:text-white transition-colors cursor-pointer">Reportes</li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Empresa</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li class="hover:text-white transition-colors cursor-pointer">Sobre Nosotros</li>
                        <li class="hover:text-white transition-colors cursor-pointer">Contacto</li>
                        <li class="hover:text-white transition-colors cursor-pointer">Soporte</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm mb-4 md:mb-0">
                    &copy; {{ date('Y') }} MAGNOR. Todos los derechos reservados.
                </p>
                <div class="flex items-center space-x-2 text-gray-400 text-sm">
                    <span>Sistema de Gestión Sostenible</span>
                    <span class="text-recycle-green">●</span>
                    <span>Eco-Friendly</span>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
