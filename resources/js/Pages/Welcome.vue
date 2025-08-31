<script setup>
import { Head, Link } from '@inertiajs/vue3';
import ModernNavbar from '@/Components/ModernNavbar.vue';
import { 
    BoltIcon, 
    ShieldCheckIcon, 
    ChartBarIcon, 
    ChatBubbleLeftRightIcon,
    TruckIcon,
    DocumentTextIcon,
    CogIcon,
    CheckCircleIcon
} from '@heroicons/vue/24/outline';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});
</script>

<template>
    <Head title="CarWise AI - Menaxhimi i Automjeteve me AI" />
    
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50">
        <ModernNavbar />
        
        <!-- Hero Section -->
        <div class="relative overflow-hidden">
            <div class="max-w-7xl mx-auto">
                <div class="relative z-10 pb-8 sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
                    <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                        <div class="sm:text-center lg:text-left">
                            <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                                <span class="block xl:inline">Menaxhimi i Automjeteve</span>
                                <span class="block text-blue-600 xl:inline">me AI</span>
                            </h1>
                            <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                                Platforma moderne për menaxhimin e raporteve të problemeve të automjeteve, 
                                me integrim AI për diagnostikim të avancuar dhe rekomandime inteligjente.
                            </p>
                            <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                                <div class="rounded-lg shadow-lg">
                                    <Link
                                        v-if="canRegister"
                                        href="/register"
                                        class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 md:py-4 md:text-lg md:px-10 transition-all duration-200 shadow-lg hover:shadow-xl"
                                    >
                                        Regjistrohu Falas
                                    </Link>
                                </div>
                                <div class="mt-3 sm:mt-0 sm:ml-3">
                                    <Link
                                        href="/login"
                                        class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-lg text-blue-700 bg-blue-100 hover:bg-blue-200 md:py-4 md:text-lg md:px-10 transition-all duration-200"
                                    >
                                        Kyçu
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </main>
                </div>
            </div>
            <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
                <img class="h-56 w-full object-cover sm:h-72 md:h-96 lg:w-full lg:h-full" src="https://images.unsplash.com/photo-1550355291-bbee04a92027?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1000&q=80" alt="CarWise AI" />
            </div>
        </div>

        <!-- Features Section -->
        <div class="py-12 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="lg:text-center">
                    <h2 class="text-base text-blue-600 font-semibold tracking-wide uppercase">Features</h2>
                    <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                        Çfarë ofron CarWise AI
                    </p>
                    <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">
                        Platforma jonë kombinon teknologjinë më të fundit me ekspertizën në fushën e automjeteve.
                    </p>
                </div>

                <div class="mt-10">
                    <dl class="space-y-10 md:space-y-0 md:grid md:grid-cols-2 md:gap-x-8 md:gap-y-10">
                        <div class="relative">
                            <dt>
                                <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white">
                                    <TruckIcon class="h-6 w-6" />
                                </div>
                                <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Menaxhimi i Automjeteve</p>
                            </dt>
                            <dd class="mt-2 ml-16 text-base text-gray-500">
                                Regjistro dhe menaxho të gjitha automjetet e tua në një vend të sigurt.
                            </dd>
                        </div>

                        <div class="relative">
                            <dt>
                                <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white">
                                    <DocumentTextIcon class="h-6 w-6" />
                                </div>
                                <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Raportet e Problemeve</p>
                            </dt>
                            <dd class="mt-2 ml-16 text-base text-gray-500">
                                Krijo dhe gjurmo raportet e problemeve me automjetet e tua.
                            </dd>
                        </div>

                        <div class="relative">
                            <dt>
                                <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white">
                                    <ChatBubbleLeftRightIcon class="h-6 w-6" />
                                </div>
                                <p class="ml-16 text-lg leading-6 font-medium text-gray-900">AI Asistent</p>
                            </dt>
                            <dd class="mt-2 ml-16 text-base text-gray-500">
                                Bisedo me AI-në tonë për ndihmë me diagnostikimin dhe riparimin.
                            </dd>
                        </div>

                        <div class="relative">
                            <dt>
                                <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white">
                                    <ChartBarIcon class="h-6 w-6" />
                                </div>
                                <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Analitika</p>
                            </dt>
                            <dd class="mt-2 ml-16 text-base text-gray-500">
                                Shiko statistikat dhe analitikat e performancës së automjeteve të tua.
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-gray-900">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8">
                <div class="xl:grid xl:grid-cols-3 xl:gap-8">
                    <!-- Brand -->
                    <div class="space-y-8 xl:col-span-1">
                        <div class="flex items-center">
                            <div class="h-8 w-8 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center shadow-lg">
                                <span class="text-white font-bold text-lg">🚗</span>
                            </div>
                            <span class="ml-2 text-xl font-bold text-white">
                                CarWise AI
                            </span>
                        </div>
                        <p class="text-gray-300 text-base">
                            Platforma moderne për menaxhimin e automjeteve me AI.
                        </p>
                        <div class="flex space-x-6">
                            <a href="#" class="text-gray-400 hover:text-gray-300">
                                <span class="sr-only">Facebook</span>
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-gray-300">
                                <span class="sr-only">Instagram</span>
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-gray-300">
                                <span class="sr-only">Twitter</span>
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <div class="mt-12 grid grid-cols-2 gap-8 xl:mt-0 xl:col-span-2">
                        <div class="md:grid md:grid-cols-2 md:gap-8">
                            <!-- Solutions -->
                            <div>
                                <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">Zgjidhjet</h3>
                                <ul role="list" class="mt-4 space-y-4">
                                    <li>
                                        <a href="#" class="text-base text-gray-300 hover:text-white">
                                            Menaxhimi i Automjeteve
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="text-base text-gray-300 hover:text-white">
                                            AI Asistent
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="text-base text-gray-300 hover:text-white">
                                            Raportet
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="text-base text-gray-300 hover:text-white">
                                            Analitika
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <!-- Support -->
                            <div class="mt-12 md:mt-0">
                                <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">Mbështetja</h3>
                                <ul role="list" class="mt-4 space-y-4">
                                    <li>
                                        <a href="#" class="text-base text-gray-300 hover:text-white">
                                            Dokumentimi
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="text-base text-gray-300 hover:text-white">
                                            FAQ
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="text-base text-gray-300 hover:text-white">
                                            Kontakti
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="text-base text-gray-300 hover:text-white">
                                            Statusi i Shërbimit
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="md:grid md:grid-cols-2 md:gap-8">
                            <!-- Company -->
                            <div>
                                <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">Kompania</h3>
                                <ul role="list" class="mt-4 space-y-4">
                                    <li>
                                        <a href="#" class="text-base text-gray-300 hover:text-white">
                                            Rreth Nesh
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="text-base text-gray-300 hover:text-white">
                                            Blog
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="text-base text-gray-300 hover:text-white">
                                            Karriera
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="text-base text-gray-300 hover:text-white">
                                            Press
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <!-- Legal -->
                            <div class="mt-12 md:mt-0">
                                <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">Ligjore</h3>
                                <ul role="list" class="mt-4 space-y-4">
                                    <li>
                                        <a href="#" class="text-base text-gray-300 hover:text-white">
                                            Privatësia
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="text-base text-gray-300 hover:text-white">
                                            Kushtet
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="text-base text-gray-300 hover:text-white">
                                            Cookies
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="text-base text-gray-300 hover:text-white">
                                            GDPR
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bottom Section -->
                <div class="mt-12 border-t border-gray-700 pt-8">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <p class="text-base text-gray-400">
                            &copy; 2024 CarWise AI. Të gjitha të drejtat e rezervuara.
                        </p>
                        <div class="mt-4 md:mt-0 flex space-x-6">
                            <a href="#" class="text-gray-400 hover:text-gray-300 text-sm">
                                Politika e Privatësisë
                            </a>
                            <a href="#" class="text-gray-400 hover:text-gray-300 text-sm">
                                Kushtet e Përdorimit
                            </a>
                            <a href="#" class="text-gray-400 hover:text-gray-300 text-sm">
                                Cookies
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>
