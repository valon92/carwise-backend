<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ vehicle.brand }} {{ vehicle.model }}
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">
                        Detajet e automjetit dhe historiku i raporteve
                    </p>
                </div>
                <div class="flex items-center space-x-3">
                    <Link
                        :href="`/vehicles/${vehicle.id}/edit`"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
                    >
                        <PencilIcon class="w-4 h-4 mr-2" />
                        Edito
                    </Link>
                    <Link
                        href="/vehicles"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
                    >
                        <ArrowLeftIcon class="w-4 h-4 mr-2" />
                        Kthehu
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Vehicle Overview -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-8">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center">
                                <div class="h-16 w-16 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                                    <TruckIcon class="h-8 w-8 text-white" />
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-2xl font-bold text-gray-900">
                                        {{ vehicle.brand }} {{ vehicle.model }}
                                    </h3>
                                    <p class="text-lg text-gray-600">{{ vehicle.year }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <span
                                    v-if="vehicle.is_primary"
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800"
                                >
                                    <StarIcon class="w-4 h-4 mr-1" />
                                    Kryesor
                                </span>
                                <span
                                    :class="{
                                        'bg-green-100 text-green-800': vehicle.is_active,
                                        'bg-red-100 text-red-800': !vehicle.is_active
                                    }"
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                                >
                                    <CheckCircleIcon v-if="vehicle.is_active" class="w-4 h-4 mr-1" />
                                    <XCircleIcon v-else class="w-4 h-4 mr-1" />
                                    {{ vehicle.is_active ? 'Aktiv' : 'Jo Aktiv' }}
                                </span>
                            </div>
                        </div>

                        <!-- Vehicle Details Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="flex items-center">
                                    <IdentificationIcon class="w-5 h-5 text-gray-400 mr-2" />
                                    <span class="text-sm font-medium text-gray-500">Targa</span>
                                </div>
                                <p class="text-lg font-semibold text-gray-900 mt-1">
                                    {{ vehicle.license_plate || 'N/A' }}
                                </p>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="flex items-center">
                                    <ClockIcon class="w-5 h-5 text-gray-400 mr-2" />
                                    <span class="text-sm font-medium text-gray-500">Kilometrazha</span>
                                </div>
                                <p class="text-lg font-semibold text-gray-900 mt-1">
                                    {{ formatNumber(vehicle.mileage) }} km
                                </p>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="flex items-center">
                                    <BeakerIcon class="w-5 h-5 text-gray-400 mr-2" />
                                    <span class="text-sm font-medium text-gray-500">Karburanti</span>
                                </div>
                                <p class="text-lg font-semibold text-gray-900 mt-1">
                                    {{ vehicle.fuel_type || 'N/A' }}
                                </p>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="flex items-center">
                                    <CogIcon class="w-5 h-5 text-gray-400 mr-2" />
                                    <span class="text-sm font-medium text-gray-500">Transmisioni</span>
                                </div>
                                <p class="text-lg font-semibold text-gray-900 mt-1">
                                    {{ vehicle.transmission || 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Service Status and Important Dates -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <!-- Service Status -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                <WrenchScrewdriverIcon class="w-5 h-5 mr-2 text-blue-600" />
                                Statusi i Servisit
                            </h3>
                            
                            <div v-if="vehicle.next_service_date" class="space-y-4">
                                <div class="flex items-center justify-between p-4 rounded-lg border"
                                    :class="{
                                        'border-red-200 bg-red-50': isServiceOverdue(vehicle.next_service_date),
                                        'border-yellow-200 bg-yellow-50': isServiceDueSoon(vehicle.next_service_date),
                                        'border-green-200 bg-green-50': !isServiceOverdue(vehicle.next_service_date) && !isServiceDueSoon(vehicle.next_service_date)
                                    }"
                                >
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Servisi i radhës</p>
                                        <p class="text-sm text-gray-600">{{ formatDate(vehicle.next_service_date) }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span
                                            :class="{
                                                'text-red-600': isServiceOverdue(vehicle.next_service_date),
                                                'text-yellow-600': isServiceDueSoon(vehicle.next_service_date),
                                                'text-green-600': !isServiceOverdue(vehicle.next_service_date) && !isServiceDueSoon(vehicle.next_service_date)
                                            }"
                                            class="text-sm font-medium"
                                        >
                                            {{ getServiceStatusText(vehicle.next_service_date) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div v-else class="text-center py-8">
                                <WrenchScrewdriverIcon class="w-12 h-12 text-gray-400 mx-auto mb-4" />
                                <p class="text-gray-500">Nuk ka datë të vendosur për servisin e radhës</p>
                            </div>

                            <button
                                @click="showServiceModal = true"
                                class="mt-4 w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                <PlusIcon class="w-4 h-4 mr-2" />
                                Shto Regjistër Servisi
                            </button>
                        </div>
                    </div>

                    <!-- Important Dates -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                <CalendarIcon class="w-5 h-5 mr-2 text-red-600" />
                                Datat e Rëndësishme
                            </h3>
                            
                            <div class="space-y-4">
                                <div v-if="vehicle.warranty_expiry" class="flex items-center justify-between p-4 rounded-lg border"
                                    :class="{
                                        'border-red-200 bg-red-50': isDateExpired(vehicle.warranty_expiry),
                                        'border-yellow-200 bg-yellow-50': isDateExpiringSoon(vehicle.warranty_expiry),
                                        'border-green-200 bg-green-50': !isDateExpired(vehicle.warranty_expiry) && !isDateExpiringSoon(vehicle.warranty_expiry)
                                    }"
                                >
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Garancia</p>
                                        <p class="text-sm text-gray-600">{{ formatDate(vehicle.warranty_expiry) }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span
                                            :class="{
                                                'text-red-600': isDateExpired(vehicle.warranty_expiry),
                                                'text-yellow-600': isDateExpiringSoon(vehicle.warranty_expiry),
                                                'text-green-600': !isDateExpired(vehicle.warranty_expiry) && !isDateExpiringSoon(vehicle.warranty_expiry)
                                            }"
                                            class="text-sm font-medium"
                                        >
                                            {{ getDateStatusText(vehicle.warranty_expiry) }}
                                        </span>
                                    </div>
                                </div>

                                <div v-if="vehicle.insurance_expiry" class="flex items-center justify-between p-4 rounded-lg border"
                                    :class="{
                                        'border-red-200 bg-red-50': isDateExpired(vehicle.insurance_expiry),
                                        'border-yellow-200 bg-yellow-50': isDateExpiringSoon(vehicle.insurance_expiry),
                                        'border-green-200 bg-green-50': !isDateExpired(vehicle.insurance_expiry) && !isDateExpiringSoon(vehicle.insurance_expiry)
                                    }"
                                >
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Sigurimi</p>
                                        <p class="text-sm text-gray-600">{{ formatDate(vehicle.insurance_expiry) }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span
                                            :class="{
                                                'text-red-600': isDateExpired(vehicle.insurance_expiry),
                                                'text-yellow-600': isDateExpiringSoon(vehicle.insurance_expiry),
                                                'text-green-600': !isDateExpired(vehicle.insurance_expiry) && !isDateExpiringSoon(vehicle.insurance_expiry)
                                            }"
                                            class="text-sm font-medium"
                                        >
                                            {{ getDateStatusText(vehicle.insurance_expiry) }}
                                        </span>
                                    </div>
                                </div>

                                <div v-if="!vehicle.warranty_expiry && !vehicle.insurance_expiry" class="text-center py-8">
                                    <CalendarIcon class="w-12 h-12 text-gray-400 mx-auto mb-4" />
                                    <p class="text-gray-500">Nuk ka data të vendosura</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reports Section -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                <DocumentTextIcon class="w-5 h-5 mr-2 text-indigo-600" />
                                Raportet e Automjetit
                            </h3>
                            <Link
                                href="/reports/create"
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                <PlusIcon class="w-4 h-4 mr-2" />
                                Krijo Raport
                            </Link>
                        </div>

                        <div v-if="reports.data.length > 0" class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Titulli
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Statusi
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Data
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Vlerësimi
                                        </th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Veprime
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="report in reports.data" :key="report.id">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ report.title }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ report.problem_category }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                :class="{
                                                    'bg-green-100 text-green-800': report.status === 'completed',
                                                    'bg-yellow-100 text-yellow-800': report.status === 'in_progress',
                                                    'bg-red-100 text-red-800': report.status === 'pending',
                                                    'bg-blue-100 text-blue-800': report.status === 'urgent'
                                                }"
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                            >
                                                {{ getStatusText(report.status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ formatDate(report.created_at) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex items-center">
                                                    <StarIcon
                                                        v-for="i in 5"
                                                        :key="i"
                                                        :class="{
                                                            'text-yellow-400': i <= report.rating,
                                                            'text-gray-300': i > report.rating
                                                        }"
                                                        class="w-4 h-4"
                                                    />
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <Link
                                                :href="`/reports/${report.id}`"
                                                class="text-indigo-600 hover:text-indigo-900"
                                            >
                                                Shiko
                                            </Link>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div v-else class="text-center py-12">
                            <DocumentTextIcon class="w-12 h-12 text-gray-400 mx-auto mb-4" />
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Nuk ka raporte</h3>
                            <p class="text-gray-500 mb-4">
                                Ky automjet nuk ka raporte të krijuara ende.
                            </p>
                            <Link
                                href="/reports/create"
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                <PlusIcon class="w-4 h-4 mr-2" />
                                Krijo Raportin e Parë
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Service Record Modal -->
        <div v-if="showServiceModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Shto Regjistër Servisi</h3>
                    <form @submit.prevent="addServiceRecord" class="space-y-4">
                        <div>
                            <InputLabel for="service_type" value="Lloji i Servisit" class="text-sm font-medium text-gray-700" />
                            <TextInput
                                id="service_type"
                                type="text"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                v-model="serviceForm.service_type"
                                required
                                placeholder="p.sh. Mirëmbajtje e përgjithshme"
                            />
                        </div>

                        <div>
                            <InputLabel for="description" value="Përshkrimi" class="text-sm font-medium text-gray-700" />
                            <textarea
                                id="description"
                                v-model="serviceForm.description"
                                rows="3"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                required
                                placeholder="Përshkrimi i detajuar i servisit..."
                            ></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <InputLabel for="service_date" value="Data e Servisit" class="text-sm font-medium text-gray-700" />
                                <TextInput
                                    id="service_date"
                                    type="date"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                    v-model="serviceForm.service_date"
                                    required
                                />
                            </div>

                            <div>
                                <InputLabel for="cost" value="Kostoja (€)" class="text-sm font-medium text-gray-700" />
                                <TextInput
                                    id="cost"
                                    type="number"
                                    step="0.01"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                    v-model="serviceForm.cost"
                                    placeholder="0.00"
                                />
                            </div>
                        </div>

                        <div>
                            <InputLabel for="next_service_date" value="Servisi i Radhës" class="text-sm font-medium text-gray-700" />
                            <TextInput
                                id="next_service_date"
                                type="date"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                v-model="serviceForm.next_service_date"
                            />
                        </div>

                        <div class="flex items-center justify-end space-x-3 pt-4">
                            <button
                                type="button"
                                @click="showServiceModal = false"
                                class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                Anulo
                            </button>
                            <PrimaryButton
                                type="submit"
                                class="px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                :disabled="serviceForm.processing"
                            >
                                {{ serviceForm.processing ? 'Duke ruajtur...' : 'Ruaj' }}
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import {
    TruckIcon,
    ArrowLeftIcon,
    PencilIcon,
    StarIcon,
    CheckCircleIcon,
    XCircleIcon,
    IdentificationIcon,
    ClockIcon,
    BeakerIcon,
    CogIcon,
    WrenchScrewdriverIcon,
    CalendarIcon,
    DocumentTextIcon,
    PlusIcon
} from '@heroicons/vue/24/outline';

const props = defineProps({
    vehicle: {
        type: Object,
        required: true
    },
    reports: {
        type: Object,
        required: true
    }
});

const showServiceModal = ref(false);

const serviceForm = useForm({
    service_type: '',
    description: '',
    cost: '',
    service_date: '',
    next_service_date: '',
    mileage: '',
    service_provider: ''
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

const isDateExpired = (date) => {
    return new Date(date) < new Date();
};

const isDateExpiringSoon = (date) => {
    const expiryDate = new Date(date);
    const now = new Date();
    const diffTime = expiryDate - now;
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    return diffDays <= 30 && diffDays > 0;
};

const getServiceStatusText = (date) => {
    if (isServiceOverdue(date)) {
        return 'VONUAR';
    } else if (isServiceDueSoon(date)) {
        return 'SË SHPEJTI';
    } else {
        return 'NË KOHË';
    }
};

const getDateStatusText = (date) => {
    if (isDateExpired(date)) {
        return 'SKADUAR';
    } else if (isDateExpiringSoon(date)) {
        return 'SË SHPEJTI';
    } else {
        return 'AKTIV';
    }
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

const addServiceRecord = () => {
    serviceForm.post(`/vehicles/${props.vehicle.id}/service`, {
        onSuccess: () => {
            showServiceModal.value = false;
            serviceForm.reset();
        }
    });
};
</script>
