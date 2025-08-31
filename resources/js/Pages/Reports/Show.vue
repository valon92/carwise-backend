<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ report.title }}
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">
                        Detajet e plota të raportit të problemit
                    </p>
                </div>
                <div class="flex items-center space-x-3">
                    <Link
                        :href="`/reports/${report.id}/edit`"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
                    >
                        <PencilIcon class="w-4 h-4 mr-2" />
                        Edito
                    </Link>
                    <Link
                        href="/reports"
                        class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                    >
                        <ArrowLeftIcon class="w-4 h-4 mr-2" />
                        Kthehu
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Report Header -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-8">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center">
                                <div class="h-16 w-16 bg-gradient-to-r from-red-500 to-orange-500 rounded-xl flex items-center justify-center">
                                    <ExclamationTriangleIcon class="h-8 w-8 text-white" />
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-2xl font-bold text-gray-900">
                                        {{ report.title }}
                                    </h3>
                                    <p class="text-lg text-gray-600">
                                        {{ report.vehicle?.brand }} {{ report.vehicle?.model }} ({{ report.vehicle?.year }})
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <span
                                    :class="{
                                        'bg-red-100 text-red-800': report.status === 'urgent',
                                        'bg-yellow-100 text-yellow-800': report.status === 'in_progress',
                                        'bg-green-100 text-green-800': report.status === 'completed',
                                        'bg-gray-100 text-gray-800': report.status === 'pending'
                                    }"
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                                >
                                    <ExclamationTriangleIcon v-if="report.status === 'urgent'" class="w-4 h-4 mr-1" />
                                    <ClockIcon v-else-if="report.status === 'in_progress'" class="w-4 h-4 mr-1" />
                                    <CheckCircleIcon v-else-if="report.status === 'completed'" class="w-4 h-4 mr-1" />
                                    <DocumentTextIcon v-else class="w-4 h-4 mr-1" />
                                    {{ getStatusText(report.status) }}
                                </span>
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800"
                                >
                                    {{ getCategoryText(report.problem_category) }}
                                </span>
                            </div>
                        </div>

                        <!-- Quick Stats -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="flex items-center">
                                    <CalendarIcon class="w-5 h-5 text-gray-400 mr-2" />
                                    <span class="text-sm font-medium text-gray-500">Krijuar</span>
                                </div>
                                <p class="text-lg font-semibold text-gray-900 mt-1">
                                    {{ formatDate(report.created_at) }}
                                </p>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="flex items-center">
                                    <UserIcon class="w-5 h-5 text-gray-400 mr-2" />
                                    <span class="text-sm font-medium text-gray-500">Raportuar nga</span>
                                </div>
                                <p class="text-lg font-semibold text-gray-900 mt-1">
                                    {{ report.user?.name }}
                                </p>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="flex items-center">
                                    <FlagIcon class="w-5 h-5 text-gray-400 mr-2" />
                                    <span class="text-sm font-medium text-gray-500">Prioriteti</span>
                                </div>
                                <p class="text-lg font-semibold text-gray-900 mt-1">
                                    {{ getPriorityText(report.priority) }}
                                </p>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="flex items-center">
                                    <StarIcon class="w-5 h-5 text-gray-400 mr-2" />
                                    <span class="text-sm font-medium text-gray-500">Vlerësimi</span>
                                </div>
                                <p class="text-lg font-semibold text-gray-900 mt-1">
                                    {{ report.rating }}/5
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left Column -->
                    <div class="lg:col-span-2 space-y-8">
                        <!-- Problem Description -->
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                    <DocumentTextIcon class="w-5 h-5 mr-2 text-blue-600" />
                                    Përshkrimi i Problemit
                                </h3>
                                <div class="prose max-w-none">
                                    <p class="text-gray-700 mb-4">{{ report.description }}</p>
                                    
                                    <div v-if="report.symptoms" class="mt-4">
                                        <h4 class="text-sm font-medium text-gray-900 mb-2">Simptomat:</h4>
                                        <p class="text-gray-600">{{ report.symptoms }}</p>
                                    </div>

                                    <div v-if="report.previous_repairs" class="mt-4">
                                        <h4 class="text-sm font-medium text-gray-900 mb-2">Riparimet e Mëparshme:</h4>
                                        <p class="text-gray-600">{{ report.previous_repairs }}</p>
                                    </div>

                                    <div v-if="report.notes" class="mt-4">
                                        <h4 class="text-sm font-medium text-gray-900 mb-2">Shënime Shtesë:</h4>
                                        <p class="text-gray-600">{{ report.notes }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Vehicle Information -->
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                    <TruckIcon class="w-5 h-5 mr-2 text-green-600" />
                                    Informacione të Automjetit
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Automjeti</label>
                                        <p class="mt-1 text-sm text-gray-900">
                                            {{ report.vehicle?.brand }} {{ report.vehicle?.model }} ({{ report.vehicle?.year }})
                                        </p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Targa</label>
                                        <p class="mt-1 text-sm text-gray-900">
                                            {{ report.vehicle?.license_plate || 'N/A' }}
                                        </p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Kilometrazha kur u shfaq problemi</label>
                                        <p class="mt-1 text-sm text-gray-900">
                                            {{ formatNumber(report.mileage_at_issue) }} km
                                        </p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Data kur u shfaq problemi</label>
                                        <p class="mt-1 text-sm text-gray-900">
                                            {{ formatDate(report.issue_date) }}
                                        </p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Vendndodhja</label>
                                        <p class="mt-1 text-sm text-gray-900">
                                            {{ report.location || 'N/A' }}
                                        </p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Kostoja e Vlerësuar</label>
                                        <p class="mt-1 text-sm text-gray-900">
                                            {{ formatCurrency(report.estimated_cost) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Conditions -->
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                    <BeakerIcon class="w-5 h-5 mr-2 text-purple-600" />
                                    Kushtet
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Kushtet e Motit</label>
                                        <p class="mt-1 text-sm text-gray-900">
                                            {{ getWeatherText(report.weather_conditions) }}
                                        </p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Kushtet e Ngasjes</label>
                                        <p class="mt-1 text-sm text-gray-900">
                                            {{ getDrivingText(report.driving_conditions) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- AI Analysis -->
                        <div v-if="report.ai_analysis" class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                    <CpuChipIcon class="w-5 h-5 mr-2 text-blue-600" />
                                    Analiza AI
                                </h3>
                                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <div class="h-8 w-8 bg-blue-500 rounded-full flex items-center justify-center">
                                                <span class="text-white text-sm font-bold">AI</span>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <h4 class="text-sm font-medium text-blue-900">Analiza e Problemit</h4>
                                            <p class="text-sm text-blue-700 mt-2">{{ report.ai_analysis }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Resolution -->
                        <div v-if="report.status === 'completed'" class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                    <CheckCircleIcon class="w-5 h-5 mr-2 text-green-600" />
                                    Zgjidhja
                                </h3>
                                <div class="space-y-4">
                                    <div v-if="report.resolution_notes">
                                        <label class="block text-sm font-medium text-gray-700">Shënimet e Zgjidhjes</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ report.resolution_notes }}</p>
                                    </div>
                                    <div v-if="report.technician_notes">
                                        <label class="block text-sm font-medium text-gray-700">Shënimet e Teknikut</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ report.technician_notes }}</p>
                                    </div>
                                    <div v-if="report.parts_used">
                                        <label class="block text-sm font-medium text-gray-700">Pjesët e Përdorura</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ report.parts_used }}</p>
                                    </div>
                                    <div v-if="report.cost">
                                        <label class="block text-sm font-medium text-gray-700">Kostoja Finale</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ formatCurrency(report.cost) }}</p>
                                    </div>
                                    <div v-if="report.resolved_at">
                                        <label class="block text-sm font-medium text-gray-700">Data e Zgjidhjes</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ formatDate(report.resolved_at) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-8">
                        <!-- Status Actions -->
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Veprime</h3>
                                <div class="space-y-3">
                                    <button
                                        v-if="report.status !== 'completed'"
                                        @click="markAsCompleted"
                                        class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-green-700 bg-green-100 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors"
                                    >
                                        <CheckCircleIcon class="w-4 h-4 mr-2" />
                                        Shëno si Përfunduar
                                    </button>
                                    <button
                                        v-if="report.status === 'pending'"
                                        @click="markAsUrgent"
                                        class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors"
                                    >
                                        <ExclamationTriangleIcon class="w-4 h-4 mr-2" />
                                        Shëno si Urgjent
                                    </button>
                                    <button
                                        v-if="report.status === 'pending'"
                                        @click="markAsInProgress"
                                        class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-yellow-700 bg-yellow-100 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors"
                                    >
                                        <ClockIcon class="w-4 h-4 mr-2" />
                                        Fillo Punën
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Info -->
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Informacione të Shpejta</h3>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Rëndësia</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ getSeverityText(report.severity) }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Kategoria</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ getCategoryText(report.problem_category) }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Statusi</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ getStatusText(report.status) }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Prioriteti</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ getPriorityText(report.priority) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Claims -->
                        <div v-if="report.warranty_claim || report.insurance_claim" class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Kërkesat</h3>
                                <div class="space-y-3">
                                    <div v-if="report.warranty_claim" class="flex items-center">
                                        <ShieldCheckIcon class="w-5 h-5 text-green-500 mr-2" />
                                        <span class="text-sm text-gray-700">Kërkesë Garancie</span>
                                    </div>
                                    <div v-if="report.insurance_claim" class="flex items-center">
                                        <ShieldCheckIcon class="w-5 h-5 text-blue-500 mr-2" />
                                        <span class="text-sm text-gray-700">Kërkesë Sigurimi</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {
    ArrowLeftIcon,
    PencilIcon,
    ExclamationTriangleIcon,
    ClockIcon,
    CheckCircleIcon,
    DocumentTextIcon,
    TruckIcon,
    BeakerIcon,
    CpuChipIcon,
    CalendarIcon,
    UserIcon,
    StarIcon,
    FlagIcon,
    ShieldCheckIcon
} from '@heroicons/vue/24/outline';

const props = defineProps({
    report: {
        type: Object,
        required: true
    }
});

const formatDate = (date) => {
    if (!date) return 'N/A';
    return new Date(date).toLocaleDateString('sq-AL');
};

const formatNumber = (number) => {
    if (!number) return 'N/A';
    return new Intl.NumberFormat('sq-AL').format(number);
};

const formatCurrency = (amount) => {
    if (!amount) return 'N/A';
    return new Intl.NumberFormat('sq-AL', {
        style: 'currency',
        currency: 'EUR'
    }).format(amount);
};

const getStatusText = (status) => {
    const statusMap = {
        'pending': 'Në pritje',
        'in_progress': 'Në progres',
        'completed': 'Përfunduar',
        'urgent': 'Urgjent'
    };
    return statusMap[status] || status;
};

const getCategoryText = (category) => {
    const categoryMap = {
        'engine': 'Motor',
        'transmission': 'Transmisioni',
        'electrical': 'Elektrike',
        'brakes': 'Frenat',
        'suspension': 'Suspensioni',
        'body': 'Karroceria',
        'interior': 'Interjeri',
        'other': 'Të tjera'
    };
    return categoryMap[category] || category;
};

const getPriorityText = (priority) => {
    const priorityMap = {
        'low': 'I ulët',
        'normal': 'Normal',
        'high': 'I lartë',
        'urgent': 'Urgjent'
    };
    return priorityMap[priority] || priority;
};

const getSeverityText = (severity) => {
    const severityMap = {
        'low': 'E ulët',
        'medium': 'Mesatare',
        'high': 'E lartë',
        'critical': 'Kritike'
    };
    return severityMap[severity] || severity;
};

const getWeatherText = (weather) => {
    const weatherMap = {
        'sunny': 'Diell',
        'rainy': 'Shi',
        'snowy': 'Borrë',
        'cold': 'Ftohtë',
        'hot': 'Nxehtë',
        'humid': 'Me lagështi'
    };
    return weatherMap[weather] || weather || 'N/A';
};

const getDrivingText = (driving) => {
    const drivingMap = {
        'city': 'Qytet',
        'highway': 'Autostradë',
        'mountain': 'Mal',
        'traffic': 'Trafik',
        'parking': 'Parking',
        'idle': 'Në vend'
    };
    return drivingMap[driving] || driving || 'N/A';
};

const markAsCompleted = async () => {
    try {
        await router.put(`/reports/${props.report.id}`, {
            status: 'completed'
        });
    } catch (error) {
        console.error('Error updating report:', error);
    }
};

const markAsUrgent = async () => {
    try {
        await router.put(`/reports/${props.report.id}`, {
            status: 'urgent'
        });
    } catch (error) {
        console.error('Error updating report:', error);
    }
};

const markAsInProgress = async () => {
    try {
        await router.put(`/reports/${props.report.id}`, {
            status: 'in_progress'
        });
    } catch (error) {
        console.error('Error updating report:', error);
    }
};
</script>
