<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        Automjetet e Mia
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">
                        Menaxho të gjitha automjetet e tua në një vend
                    </p>
                </div>
                <Link
                    href="/vehicles/create"
                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    <PlusIcon class="w-4 h-4 mr-2" />
                    Shto Automjet
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 border border-gray-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <TruckIcon class="h-8 w-8 text-blue-600" />
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Total Automjete
                                    </dt>
                                    <dd class="text-lg font-medium text-gray-900">
                                        {{ stats.total }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 border border-gray-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <CheckCircleIcon class="h-8 w-8 text-green-600" />
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Aktive
                                    </dt>
                                    <dd class="text-lg font-medium text-gray-900">
                                        {{ stats.active }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 border border-gray-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <StarIcon class="h-8 w-8 text-yellow-600" />
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Kryesor
                                    </dt>
                                    <dd class="text-lg font-medium text-gray-900">
                                        {{ stats.primary }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Vehicles Grid -->
                <div v-if="vehicles.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div
                        v-for="vehicle in vehicles"
                        :key="vehicle.id"
                        class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-200 hover:shadow-2xl transition-shadow duration-300"
                    >
                        <div class="p-6">
                            <!-- Vehicle Header -->
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center">
                                    <div class="h-12 w-12 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                        <TruckIcon class="h-6 w-6 text-white" />
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-lg font-semibold text-gray-900">
                                            {{ vehicle.brand }} {{ vehicle.model }}
                                        </h3>
                                        <p class="text-sm text-gray-500">{{ vehicle.year }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span
                                        v-if="vehicle.is_primary"
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800"
                                    >
                                        <StarIcon class="w-3 h-3 mr-1" />
                                        Kryesor
                                    </span>
                                    <span
                                        v-if="!vehicle.is_active"
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800"
                                    >
                                        Jo Aktive
                                    </span>
                                </div>
                            </div>

                            <!-- Vehicle Details -->
                            <div class="space-y-3">
                                <div v-if="vehicle.license_plate" class="flex items-center text-sm text-gray-600">
                                    <IdentificationIcon class="w-4 h-4 mr-2 text-gray-400" />
                                    {{ vehicle.license_plate }}
                                </div>
                                <div v-if="vehicle.mileage" class="flex items-center text-sm text-gray-600">
                                    <ClockIcon class="w-4 h-4 mr-2 text-gray-400" />
                                    {{ formatNumber(vehicle.mileage) }} km
                                </div>
                                <div v-if="vehicle.color" class="flex items-center text-sm text-gray-600">
                                    <div class="w-4 h-4 mr-2 rounded-full border" :style="{ backgroundColor: vehicle.color }"></div>
                                    {{ vehicle.color }}
                                </div>
                                <div v-if="vehicle.fuel_type" class="flex items-center text-sm text-gray-600">
                                    <BeakerIcon class="w-4 h-4 mr-2 text-gray-400" />
                                    {{ vehicle.fuel_type }}
                                </div>
                            </div>

                            <!-- Service Status -->
                            <div v-if="vehicle.next_service_date" class="mt-4 p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center text-sm">
                                        <WrenchScrewdriverIcon class="w-4 h-4 mr-2 text-gray-400" />
                                        <span class="text-gray-600">Servisi i radhës:</span>
                                    </div>
                                    <span
                                        :class="{
                                            'text-red-600 font-medium': isServiceOverdue(vehicle.next_service_date),
                                            'text-yellow-600 font-medium': isServiceDueSoon(vehicle.next_service_date),
                                            'text-green-600': !isServiceOverdue(vehicle.next_service_date) && !isServiceDueSoon(vehicle.next_service_date)
                                        }"
                                        class="text-sm"
                                    >
                                        {{ formatDate(vehicle.next_service_date) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="mt-6 flex items-center justify-between">
                                <div class="flex space-x-2">
                                    <Link
                                        :href="`/vehicles/${vehicle.id}`"
                                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
                                    >
                                        <EyeIcon class="w-4 h-4 mr-1" />
                                        Shiko
                                    </Link>
                                    <Link
                                        :href="`/vehicles/${vehicle.id}/edit`"
                                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
                                    >
                                        <PencilIcon class="w-4 h-4 mr-1" />
                                        Edito
                                    </Link>
                                </div>
                                <button
                                    v-if="!vehicle.is_primary"
                                    @click="markAsPrimary(vehicle)"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-yellow-700 bg-yellow-100 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors"
                                >
                                    <StarIcon class="w-4 h-4 mr-1" />
                                    Kryesor
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="text-center py-12">
                    <div class="mx-auto h-24 w-24 bg-gray-100 rounded-full flex items-center justify-center">
                        <TruckIcon class="h-12 w-12 text-gray-400" />
                    </div>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Nuk ke automjete</h3>
                    <p class="mt-2 text-sm text-gray-500">
                        Fillo duke shtuar automjetin tënd të parë për të menaxhuar raportet dhe servisin.
                    </p>
                    <div class="mt-6">
                        <Link
                            href="/vehicles/create"
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
                        >
                            <PlusIcon class="w-4 h-4 mr-2" />
                            Shto Automjetin të Parë
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {
    TruckIcon,
    PlusIcon,
    CheckCircleIcon,
    StarIcon,
    IdentificationIcon,
    ClockIcon,
    BeakerIcon,
    WrenchScrewdriverIcon,
    EyeIcon,
    PencilIcon
} from '@heroicons/vue/24/outline';

const props = defineProps({
    vehicles: {
        type: Array,
        required: true
    },
    stats: {
        type: Object,
        required: true
    }
});

const formatNumber = (number) => {
    return new Intl.NumberFormat('sq-AL').format(number);
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('sq-AL');
};

const isServiceOverdue = (date) => {
    return new Date(date) < new Date();
};

const isServiceDueSoon = (date) => {
    const serviceDate = new Date(date);
    const now = new Date();
    const diffTime = serviceDate - now;
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    return diffDays <= 30 && diffDays > 0;
};

const markAsPrimary = async (vehicle) => {
    try {
        const response = await fetch(`/vehicles/${vehicle.id}/primary`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        if (response.ok) {
            window.location.reload();
        }
    } catch (error) {
        console.error('Error marking vehicle as primary:', error);
    }
};
</script>
