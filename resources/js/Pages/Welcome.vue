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
                                        :href="route('register')"
                                        class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 md:py-4 md:text-lg md:px-10 transition-all duration-200 shadow-lg hover:shadow-xl"
                                    >
                                        Regjistrohu Falas
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
                                Krijo dhe gjurmo raportet e problemeve me detaje të plota.
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
                                Bisedo me AI për diagnostikim dhe rekomandime të avancuara.
                            </dd>
                        </div>

                        <div class="relative">
                            <dt>
                                <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white">
                                    <ChartBarIcon class="h-6 w-6" />
                                </div>
                                <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Analitika e Avancuar</p>
                            </dt>
                            <dd class="mt-2 ml-16 text-base text-gray-500">
                                Shiko statistikat dhe trendet për automjetet e tua.
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="bg-blue-700">
            <div class="max-w-2xl mx-auto text-center py-16 px-4 sm:py-20 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
                    <span class="block">Gati për të filluar?</span>
                    <span class="block">Regjistrohu sot.</span>
                </h2>
                <p class="mt-4 text-lg leading-6 text-blue-200">
                    Bashkohu me mijëra përdorues që po përdorin CarWise AI për menaxhimin e automjeteve të tyre.
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
    </div>
</template>
