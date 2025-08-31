<script setup>
import { Head, Link } from '@inertiajs/vue3';
import ModernFooter from '@/Components/ModernFooter.vue';
import ModernNavbar from '@/Components/ModernNavbar.vue';
import { 
    TruckIcon,
    ChatBubbleLeftRightIcon,
    ChartBarIcon,
    ShieldCheckIcon,
    CogIcon,
    BoltIcon,
    StarIcon,
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
    <Head title="CarWise AI - Modern Vehicle Report Management System" />
    
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100">
        <!-- Navigation -->
        <ModernNavbar />

        <!-- Hero Section -->
        <div class="relative overflow-hidden">
            <!-- Background decoration -->
            <div class="absolute inset-0">
                <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
                <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-purple-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
                <div class="absolute top-40 left-40 w-80 h-80 bg-indigo-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000"></div>
            </div>

            <div class="relative z-10 max-w-7xl mx-auto">
                <div class="relative z-10 pb-8 sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
                    <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                        <div class="sm:text-center lg:text-left">
                            <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                                <span class="block">Menaxho Raportet</span>
                                <span class="block bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                                    e Automjeteve me AI
                                </span>
                            </h1>
                            <p class="mt-3 text-base text-gray-600 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                                Sistemi modern për menaxhimin e raporteve të problemeve të automjeteve me integrim AI. 
                                Regjistro, menaxho dhe gjurmo të gjitha raportet e automjeteve të tua në një vend.
                            </p>
                            <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                                <div class="rounded-lg shadow-lg">
                                    <Link
                                        v-if="canRegister"
                                        :href="route('register')"
                                        class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 md:py-4 md:text-lg md:px-10 transition-all duration-200 shadow-lg hover:shadow-xl"
                                    >
                                        <BoltIcon class="w-5 h-5 mr-2" />
                                        Fillo Tani
                                    </Link>
                                </div>
                                <div class="mt-3 sm:mt-0 sm:ml-3">
                                    <Link
                                        :href="route('login')"
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
        </div>

        <!-- Features Section -->
        <div class="py-16 bg-white/50 backdrop-blur-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="lg:text-center">
                    <h2 class="text-base text-blue-600 font-semibold tracking-wide uppercase">Funksionalitetet</h2>
                    <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                        Çfarë ofron CarWise AI?
                    </p>
                    <p class="mt-4 max-w-2xl text-xl text-gray-600 lg:mx-auto">
                        Një platformë e plotë për menaxhimin e raporteve të automjeteve me teknologji moderne dhe AI.
                    </p>
                </div>

                <div class="mt-16">
                    <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                        <div class="relative bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg ring-1 ring-gray-900/5 hover:shadow-xl transition-all duration-200">
                            <div class="absolute -top-3 -left-3">
                                <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-gradient-to-r from-blue-500 to-indigo-500 text-white shadow-lg">
                                    <TruckIcon class="h-6 w-6" />
                                </div>
                            </div>
                            <div class="ml-9">
                                <h3 class="text-lg font-semibold text-gray-900">Menaxhim Automjetesh</h3>
                                <p class="mt-2 text-base text-gray-600">
                                    Menaxho të gjitha automjetet e tua në një vend me informacion të detajuar dhe historik të plotë.
                                </p>
                            </div>
                        </div>

                        <div class="relative bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg ring-1 ring-gray-900/5 hover:shadow-xl transition-all duration-200">
                            <div class="absolute -top-3 -left-3">
                                <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-500 text-white shadow-lg">
                                    <ChatBubbleLeftRightIcon class="h-6 w-6" />
                                </div>
                            </div>
                            <div class="ml-9">
                                <h3 class="text-lg font-semibold text-gray-900">AI Assistant</h3>
                                <p class="mt-2 text-base text-gray-600">
                                    Chatbot inteligjent për ndihmë në kohë reale dhe rekomandime të personalizuara.
                                </p>
                            </div>
                        </div>

                        <div class="relative bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg ring-1 ring-gray-900/5 hover:shadow-xl transition-all duration-200">
                            <div class="absolute -top-3 -left-3">
                                <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-gradient-to-r from-purple-500 to-pink-500 text-white shadow-lg">
                                    <ChartBarIcon class="h-6 w-6" />
                                </div>
                            </div>
                            <div class="ml-9">
                                <h3 class="text-lg font-semibold text-gray-900">Analitika e Avancuar</h3>
                                <p class="mt-2 text-base text-gray-600">
                                    Statistikat në kohë reale dhe analitika parashikuese për vendime më të mira.
                                </p>
                            </div>
                        </div>

                        <div class="relative bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg ring-1 ring-gray-900/5 hover:shadow-xl transition-all duration-200">
                            <div class="absolute -top-3 -left-3">
                                <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-gradient-to-r from-green-500 to-emerald-500 text-white shadow-lg">
                                    <ShieldCheckIcon class="h-6 w-6" />
                                </div>
                            </div>
                            <div class="ml-9">
                                <h3 class="text-lg font-semibold text-gray-900">Siguri e Plotë</h3>
                                <p class="mt-2 text-base text-gray-600">
                                    Autentikim i sigurt dhe mbrojtje e të dhënave me teknologji të fundit.
                                </p>
                            </div>
                        </div>

                        <div class="relative bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg ring-1 ring-gray-900/5 hover:shadow-xl transition-all duration-200">
                            <div class="absolute -top-3 -left-3">
                                <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-gradient-to-r from-orange-500 to-red-500 text-white shadow-lg">
                                    <CogIcon class="h-6 w-6" />
                                </div>
                            </div>
                            <div class="ml-9">
                                <h3 class="text-lg font-semibold text-gray-900">API RESTful</h3>
                                <p class="mt-2 text-base text-gray-600">
                                    API moderne dhe të dokumentuara mirë për integrim të lehtë me sisteme të tjera.
                                </p>
                            </div>
                        </div>

                        <div class="relative bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg ring-1 ring-gray-900/5 hover:shadow-xl transition-all duration-200">
                            <div class="absolute -top-3 -left-3">
                                <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-gradient-to-r from-cyan-500 to-blue-500 text-white shadow-lg">
                                    <StarIcon class="h-6 w-6" />
                                </div>
                            </div>
                            <div class="ml-9">
                                <h3 class="text-lg font-semibold text-gray-900">Interface Modern</h3>
                                <p class="mt-2 text-base text-gray-600">
                                    Interface e bukur dhe responsive me Vue.js 3 dhe Tailwind CSS.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-8 sm:grid-cols-3">
                    <div class="text-center">
                        <div class="text-4xl font-bold text-white">1000+</div>
                        <div class="mt-2 text-blue-100">Përdorues të Regjistruar</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-white">5000+</div>
                        <div class="mt-2 text-blue-100">Raporte të Menaxhuar</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-white">99.9%</div>
                        <div class="mt-2 text-blue-100">Uptime</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="bg-white py-16">
            <div class="max-w-2xl mx-auto text-center px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    <span class="block">Gati për të filluar?</span>
                    <span class="block text-blue-600">Regjistrohu sot.</span>
                </h2>
                <p class="mt-4 text-lg leading-6 text-gray-600">
                    Bashkohu me CarWise AI dhe fillo të menaxhosh raportet e automjeteve të tua në mënyrën më të mirë.
                </p>
                <div class="mt-8 flex justify-center">
                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-blue-600 bg-white hover:bg-gray-50 shadow-lg hover:shadow-xl transition-all duration-200"
                    >
                        <BoltIcon class="w-5 h-5 mr-2" />
                        Regjistrohu Falas
                    </Link>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <ModernFooter />
    </div>
</template>

<style scoped>
@keyframes blob {
    0% {
        transform: translate(0px, 0px) scale(1);
    }
    33% {
        transform: translate(30px, -50px) scale(1.1);
    }
    66% {
        transform: translate(-20px, 20px) scale(0.9);
    }
    100% {
        transform: translate(0px, 0px) scale(1);
    }
}

.animate-blob {
    animation: blob 7s infinite;
}

.animation-delay-2000 {
    animation-delay: 2s;
}

.animation-delay-4000 {
    animation-delay: 4s;
}
</style>
