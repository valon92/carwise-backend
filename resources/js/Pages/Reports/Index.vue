<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        Raportet e Problemeve
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">
                        Menaxho dhe gjurmo të gjitha problemet e automjeteve
                    </p>
                </div>
                <Link
                    :href="route('reports.create')"
                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-600 to-orange-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:from-red-700 hover:to-orange-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    <PlusIcon class="w-4 h-4 mr-2" />
                    Krijo Raport
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 border border-gray-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <DocumentTextIcon class="h-8 w-8 text-blue-600" />
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Total Raporte
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
                                <ExclamationTriangleIcon class="h-8 w-8 text-red-600" />
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Urgjente
                                    </dt>
                                    <dd class="text-lg font-medium text-gray-900">
                                        {{ stats.urgent }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 border border-gray-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <ClockIcon class="h-8 w-8 text-yellow-600" />
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Në Progres
                                    </dt>
                                    <dd class="text-lg font-medium text-gray-900">
                                        {{ stats.in_progress }}
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
                                        Përfunduar
                                    </dt>
                                    <dd class="text-lg font-medium text-gray-900">
                                        {{ stats.completed }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters and Search -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-8">
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- Search -->
                            <div class="md:col-span-2">
                                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Kërko</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <MagnifyingGlassIcon class="h-5 w-5 text-gray-400" />
                                    </div>
                                    <input
                                        id="search"
                                        v-model="filters.search"
                                        type="text"
                                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-red-500 focus:border-red-500 sm:text-sm"
                                        placeholder="Kërko sipas titullit, përshkrimit..."
                                    />
                                </div>
                            </div>

                            <!-- Status Filter -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Statusi</label>
                                <select
                                    id="status"
                                    v-model="filters.status"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 sm:text-sm"
                                >
                                    <option value="">Të gjitha</option>
                                    <option value="pending">Në pritje</option>
                                    <option value="in_progress">Në progres</option>
                                    <option value="completed">Përfunduar</option>
                                    <option value="urgent">Urgjent</option>
                                </select>
                            </div>

                            <!-- Category Filter -->
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Kategoria</label>
                                <select
                                    id="category"
                                    v-model="filters.category"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 sm:text-sm"
                                >
                                    <option value="">Të gjitha</option>
                                    <option value="engine">Motor</option>
                                    <option value="transmission">Transmisioni</option>
                                    <option value="electrical">Elektrike</option>
                                    <option value="brakes">Frenat</option>
                                    <option value="suspension">Suspensioni</option>
                                    <option value="body">Karroceria</option>
                                    <option value="interior">Interjeri</option>
                                    <option value="other">Të tjera</option>
                                </select>
                            </div>
                        </div>

                        <!-- Filter Actions -->
                        <div class="flex items-center justify-between mt-4">
                            <div class="flex space-x-3">
                                <button
                                    @click="applyFilters"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                >
                                    <FunnelIcon class="w-4 h-4 mr-2" />
                                    Filtro
                                </button>
                                <button
                                    @click="clearFilters"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                >
                                    <XMarkIcon class="w-4 h-4 mr-2" />
                                    Pastro
                                </button>
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ reports.total }} raporte të gjetura
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reports List -->
                <div v-if="reports.data.length > 0" class="space-y-6">
                    <div
                        v-for="report in reports.data"
                        :key="report.id"
                        class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-200 hover:shadow-2xl transition-shadow duration-300"
                    >
                        <div class="p-6">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <!-- Header -->
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="h-10 w-10 bg-gradient-to-r from-red-500 to-orange-500 rounded-lg flex items-center justify-center">
                                                <ExclamationTriangleIcon class="h-5 w-5 text-white" />
                                            </div>
                                            <div>
                                                <h3 class="text-lg font-semibold text-gray-900">
                                                    {{ report.title }}
                                                </h3>
                                                <p class="text-sm text-gray-500">
                                                    {{ report.vehicle?.brand }} {{ report.vehicle?.model }} ({{ report.vehicle?.year }})
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span
                                                :class="{
                                                    'bg-red-100 text-red-800': report.status === 'urgent',
                                                    'bg-yellow-100 text-yellow-800': report.status === 'in_progress',
                                                    'bg-green-100 text-green-800': report.status === 'completed',
                                                    'bg-gray-100 text-gray-800': report.status === 'pending'
                                                }"
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                            >
                                                <ExclamationTriangleIcon v-if="report.status === 'urgent'" class="w-3 h-3 mr-1" />
                                                <ClockIcon v-else-if="report.status === 'in_progress'" class="w-3 h-3 mr-1" />
                                                <CheckCircleIcon v-else-if="report.status === 'completed'" class="w-3 h-3 mr-1" />
                                                <DocumentTextIcon v-else class="w-3 h-3 mr-1" />
                                                {{ getStatusText(report.status) }}
                                            </span>
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800"
                                            >
                                                {{ getCategoryText(report.problem_category) }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Description -->
                                    <p class="text-gray-600 mb-4 line-clamp-2">
                                        {{ report.description }}
                                    </p>

                                    <!-- Details -->
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                        <div class="flex items-center text-sm text-gray-500">
                                            <CalendarIcon class="w-4 h-4 mr-2 text-gray-400" />
                                            {{ formatDate(report.created_at) }}
                                        </div>
                                        <div class="flex items-center text-sm text-gray-500">
                                            <UserIcon class="w-4 h-4 mr-2 text-gray-400" />
                                            {{ report.user?.name }}
                                        </div>
                                        <div class="flex items-center text-sm text-gray-500">
                                            <StarIcon class="w-4 h-4 mr-2 text-gray-400" />
                                            {{ report.rating }}/5
                                        </div>
                                    </div>

                                    <!-- AI Analysis -->
                                    <div v-if="report.ai_analysis" class="mb-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0">
                                                <div class="h-6 w-6 bg-blue-500 rounded-full flex items-center justify-center">
                                                    <span class="text-white text-xs font-bold">AI</span>
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <h4 class="text-sm font-medium text-blue-900">Analiza AI</h4>
                                                <p class="text-sm text-blue-700 mt-1">{{ report.ai_analysis }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                <div class="flex space-x-3">
                                    <Link
                                        :href="route('reports.show', report.id)"
                                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors"
                                    >
                                        <EyeIcon class="w-4 h-4 mr-1" />
                                        Shiko Detajet
                                    </Link>
                                    <Link
                                        :href="route('reports.edit', report.id)"
                                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors"
                                    >
                                        <PencilIcon class="w-4 h-4 mr-1" />
                                        Edito
                                    </Link>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button
                                        v-if="report.status !== 'completed'"
                                        @click="markAsCompleted(report)"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-green-700 bg-green-100 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors"
                                    >
                                        <CheckCircleIcon class="w-4 h-4 mr-1" />
                                        Përfundo
                                    </button>
                                    <button
                                        v-if="report.status === 'pending'"
                                        @click="markAsUrgent(report)"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors"
                                    >
                                        <ExclamationTriangleIcon class="w-4 h-4 mr-1" />
                                        Urgjent
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="text-center py-12">
                    <div class="mx-auto h-24 w-24 bg-gray-100 rounded-full flex items-center justify-center">
                        <DocumentTextIcon class="h-12 w-12 text-gray-400" />
                    </div>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Nuk ka raporte</h3>
                    <p class="mt-2 text-sm text-gray-500">
                        Fillo duke krijuar raportin tënd të parë për një problem të automjetit.
                    </p>
                    <div class="mt-6">
                        <Link
                            :href="route('reports.create')"
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-600 to-orange-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:from-red-700 hover:to-orange-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"
                        >
                            <PlusIcon class="w-4 h-4 mr-2" />
                            Krijo Raportin e Parë
                        </Link>
                    </div>
                </div>

                <!-- Pagination -->
                <div v-if="reports.data.length > 0" class="mt-8">
                    <nav class="flex items-center justify-between">
                        <div class="flex-1 flex justify-between sm:hidden">
                            <Link
                                v-if="reports.prev_page_url"
                                :href="reports.prev_page_url"
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                            >
                                Para
                            </Link>
                            <Link
                                v-if="reports.next_page_url"
                                :href="reports.next_page_url"
                                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                            >
                                Pas
                            </Link>
                        </div>
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700">
                                    Duke shfaqur
                                    <span class="font-medium">{{ reports.from }}</span>
                                    deri
                                    <span class="font-medium">{{ reports.to }}</span>
                                    nga
                                    <span class="font-medium">{{ reports.total }}</span>
                                    rezultate
                                </p>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                                    <Link
                                        v-for="(link, index) in reports.links"
                                        :key="index"
                                        :href="link.url"
                                        v-html="link.label"
                                        :class="{
                                            'relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50': link.url && !link.active,
                                            'relative inline-flex items-center px-2 py-2 border border-gray-300 bg-red-50 text-sm font-medium text-red-600': link.active,
                                            'relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-300 cursor-not-allowed': !link.url
                                        }"
                                    />
                                </nav>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {
    DocumentTextIcon,
    PlusIcon,
    ExclamationTriangleIcon,
    ClockIcon,
    CheckCircleIcon,
    MagnifyingGlassIcon,
    FunnelIcon,
    XMarkIcon,
    EyeIcon,
    PencilIcon,
    CalendarIcon,
    UserIcon,
    StarIcon
} from '@heroicons/vue/24/outline';

const props = defineProps({
    reports: {
        type: Object,
        required: true
    },
    stats: {
        type: Object,
        required: true
    },
    filters: {
        type: Object,
        default: () => ({})
    }
});

const filters = reactive({
    search: props.filters.search || '',
    status: props.filters.status || '',
    category: props.filters.category || ''
});

const applyFilters = () => {
    router.get(route('reports.index'), filters, {
        preserveState: true,
        preserveScroll: true
    });
};

const clearFilters = () => {
    filters.search = '';
    filters.status = '';
    filters.category = '';
    applyFilters();
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('sq-AL');
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

const markAsCompleted = async (report) => {
    try {
        await router.put(route('reports.update', report.id), {
            status: 'completed'
        });
    } catch (error) {
        console.error('Error updating report:', error);
    }
};

const markAsUrgent = async (report) => {
    try {
        await router.put(route('reports.update', report.id), {
            status: 'urgent'
        });
    } catch (error) {
        console.error('Error updating report:', error);
    }
};

onMounted(() => {
    // Initialize filters from props
    if (props.filters) {
        filters.search = props.filters.search || '';
        filters.status = props.filters.status || '';
        filters.category = props.filters.category || '';
    }
});
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
