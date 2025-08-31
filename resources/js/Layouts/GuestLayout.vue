<script setup>
import { Link } from '@inertiajs/vue3';
import { 
    TruckIcon,
    ShieldCheckIcon,
    CogIcon,
    ChartBarIcon
} from '@heroicons/vue/24/outline';
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">
        <!-- Background decoration -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-purple-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
            <div class="absolute top-40 left-40 w-80 h-80 bg-indigo-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000"></div>
        </div>

        <div class="relative flex min-h-screen flex-col justify-center py-12 sm:px-6 lg:px-8">
            <div class="sm:mx-auto sm:w-full sm:max-w-md">
                <div class="flex justify-center">
                    <Link href="/" class="flex items-center">
                        <div class="h-12 w-12 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                            <span class="text-white font-bold text-2xl">🚗</span>
                        </div>
                        <span class="ml-3 text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                            CarWise AI
                        </span>
                    </Link>
                </div>
                <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900">
                    <slot name="title" />
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    <slot name="subtitle" />
                </p>
            </div>

            <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
                <div class="bg-white/80 backdrop-blur-sm py-8 px-4 shadow-xl ring-1 ring-gray-900/5 sm:rounded-xl sm:px-10">
                    <slot />
                </div>
            </div>

            <!-- Features preview -->
            <div class="mt-12 sm:mx-auto sm:w-full sm:max-w-4xl">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                    <div class="bg-white/60 backdrop-blur-sm rounded-lg p-6 text-center shadow-lg ring-1 ring-gray-900/5">
                        <div class="mx-auto h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <TruckIcon class="h-6 w-6 text-blue-600" />
                        </div>
                        <h3 class="mt-4 text-lg font-semibold text-gray-900">Vehicle Management</h3>
                        <p class="mt-2 text-sm text-gray-600">Manage all your vehicles in one place</p>
                    </div>
                    
                    <div class="bg-white/60 backdrop-blur-sm rounded-lg p-6 text-center shadow-lg ring-1 ring-gray-900/5">
                        <div class="mx-auto h-12 w-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                            <ShieldCheckIcon class="h-6 w-6 text-indigo-600" />
                        </div>
                        <h3 class="mt-4 text-lg font-semibold text-gray-900">AI-Powered</h3>
                        <p class="mt-2 text-sm text-gray-600">Smart insights and recommendations</p>
                    </div>
                    
                    <div class="bg-white/60 backdrop-blur-sm rounded-lg p-6 text-center shadow-lg ring-1 ring-gray-900/5">
                        <div class="mx-auto h-12 w-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <ChartBarIcon class="h-6 w-6 text-purple-600" />
                        </div>
                        <h3 class="mt-4 text-lg font-semibold text-gray-900">Analytics</h3>
                        <p class="mt-2 text-sm text-gray-600">Advanced reporting and insights</p>
                    </div>
                </div>
            </div>
        </div>
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
