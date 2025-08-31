<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        Krijo Raport të Ri
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">
                        Raporto një problem të automjetit tënd
                    </p>
                </div>
                <Link
                    :href="route('reports.index')"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors"
                >
                    <ArrowLeftIcon class="w-4 h-4 mr-2" />
                    Kthehu
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <form @submit.prevent="submit" class="p-6 space-y-8">
                        <!-- Basic Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                <InformationCircleIcon class="w-5 h-5 mr-2 text-blue-600" />
                                Informacione Bazike
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <InputLabel for="title" value="Titulli i Problemit" class="text-sm font-medium text-gray-700" />
                                    <TextInput
                                        id="title"
                                        type="text"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"
                                        v-model="form.title"
                                        required
                                        placeholder="p.sh. Zëri i çuditshëm nga motori"
                                    />
                                    <InputError class="mt-2" :message="form.errors.title" />
                                </div>

                                <div>
                                    <InputLabel for="vehicle_id" value="Automjeti" class="text-sm font-medium text-gray-700" />
                                    <select
                                        id="vehicle_id"
                                        v-model="form.vehicle_id"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"
                                        required
                                    >
                                        <option value="">Zgjidh automjetin</option>
                                        <option
                                            v-for="vehicle in vehicles"
                                            :key="vehicle.id"
                                            :value="vehicle.id"
                                        >
                                            {{ vehicle.brand }} {{ vehicle.model }} ({{ vehicle.year }}) - {{ vehicle.license_plate || 'N/A' }}
                                        </option>
                                    </select>
                                    <InputError class="mt-2" :message="form.errors.vehicle_id" />
                                </div>
                            </div>
                        </div>

                        <!-- Problem Details -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                <ExclamationTriangleIcon class="w-5 h-5 mr-2 text-red-600" />
                                Detajet e Problemit
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <InputLabel for="problem_category" value="Kategoria e Problemit" class="text-sm font-medium text-gray-700" />
                                    <select
                                        id="problem_category"
                                        v-model="form.problem_category"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"
                                        required
                                    >
                                        <option value="">Zgjidh kategorinë</option>
                                        <option value="engine">Motor</option>
                                        <option value="transmission">Transmisioni</option>
                                        <option value="electrical">Elektrike</option>
                                        <option value="brakes">Frenat</option>
                                        <option value="suspension">Suspensioni</option>
                                        <option value="body">Karroceria</option>
                                        <option value="interior">Interjeri</option>
                                        <option value="other">Të tjera</option>
                                    </select>
                                    <InputError class="mt-2" :message="form.errors.problem_category" />
                                </div>

                                <div>
                                    <InputLabel for="severity" value="Rëndësia" class="text-sm font-medium text-gray-700" />
                                    <select
                                        id="severity"
                                        v-model="form.severity"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"
                                        required
                                    >
                                        <option value="">Zgjidh rëndësinë</option>
                                        <option value="low">E ulët</option>
                                        <option value="medium">Mesatare</option>
                                        <option value="high">E lartë</option>
                                        <option value="critical">Kritike</option>
                                    </select>
                                    <InputError class="mt-2" :message="form.errors.severity" />
                                </div>
                            </div>

                            <div class="mt-6">
                                <InputLabel for="description" value="Përshkrimi i Detajuar" class="text-sm font-medium text-gray-700" />
                                <textarea
                                    id="description"
                                    v-model="form.description"
                                    rows="4"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"
                                    required
                                    placeholder="Përshkruaj problemin në detaje. Kur filloi? Çfarë ndodh? A ka ndonjë zë të çuditshëm?"
                                ></textarea>
                                <InputError class="mt-2" :message="form.errors.description" />
                            </div>
                        </div>

                        <!-- Vehicle Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                <TruckIcon class="w-5 h-5 mr-2 text-green-600" />
                                Informacione të Automjetit
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <InputLabel for="mileage_at_issue" value="Kilometrazha kur u shfaq problemi" class="text-sm font-medium text-gray-700" />
                                    <TextInput
                                        id="mileage_at_issue"
                                        type="number"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"
                                        v-model="form.mileage_at_issue"
                                        placeholder="50000"
                                    />
                                    <InputError class="mt-2" :message="form.errors.mileage_at_issue" />
                                </div>

                                <div>
                                    <InputLabel for="issue_date" value="Data kur u shfaq problemi" class="text-sm font-medium text-gray-700" />
                                    <TextInput
                                        id="issue_date"
                                        type="date"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"
                                        v-model="form.issue_date"
                                    />
                                    <InputError class="mt-2" :message="form.errors.issue_date" />
                                </div>

                                <div>
                                    <InputLabel for="location" value="Vendndodhja" class="text-sm font-medium text-gray-700" />
                                    <TextInput
                                        id="location"
                                        type="text"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"
                                        v-model="form.location"
                                        placeholder="p.sh. Prishtinë, Gjakovë"
                                    />
                                    <InputError class="mt-2" :message="form.errors.location" />
                                </div>
                            </div>
                        </div>

                        <!-- Symptoms and Conditions -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                <BeakerIcon class="w-5 h-5 mr-2 text-purple-600" />
                                Simptomat dhe Kushtet
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <InputLabel for="symptoms" value="Simptomat" class="text-sm font-medium text-gray-700" />
                                    <textarea
                                        id="symptoms"
                                        v-model="form.symptoms"
                                        rows="3"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"
                                        placeholder="Përshkruaj simptomat që vëren: zëra, dridhje, ndryshime në performancë, etc."
                                    ></textarea>
                                    <InputError class="mt-2" :message="form.errors.symptoms" />
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <InputLabel for="weather_conditions" value="Kushtet e Motit" class="text-sm font-medium text-gray-700" />
                                        <select
                                            id="weather_conditions"
                                            v-model="form.weather_conditions"
                                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"
                                        >
                                            <option value="">Zgjidh kushtet</option>
                                            <option value="sunny">Diell</option>
                                            <option value="rainy">Shi</option>
                                            <option value="snowy">Borrë</option>
                                            <option value="cold">Ftohtë</option>
                                            <option value="hot">Nxehtë</option>
                                            <option value="humid">Me lagështi</option>
                                        </select>
                                        <InputError class="mt-2" :message="form.errors.weather_conditions" />
                                    </div>

                                    <div>
                                        <InputLabel for="driving_conditions" value="Kushtet e Ngasjes" class="text-sm font-medium text-gray-700" />
                                        <select
                                            id="driving_conditions"
                                            v-model="form.driving_conditions"
                                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"
                                        >
                                            <option value="">Zgjidh kushtet</option>
                                            <option value="city">Qytet</option>
                                            <option value="highway">Autostradë</option>
                                            <option value="mountain">Mal</option>
                                            <option value="traffic">Trafik</option>
                                            <option value="parking">Parking</option>
                                            <option value="idle">Në vend</option>
                                        </select>
                                        <InputError class="mt-2" :message="form.errors.driving_conditions" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                <DocumentTextIcon class="w-5 h-5 mr-2 text-indigo-600" />
                                Informacione Shtesë
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <InputLabel for="previous_repairs" value="Riparimet e Mëparshme" class="text-sm font-medium text-gray-700" />
                                    <textarea
                                        id="previous_repairs"
                                        v-model="form.previous_repairs"
                                        rows="3"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"
                                        placeholder="A ka pasur riparime të mëparshme për këtë problem? Përshkruaj."
                                    ></textarea>
                                    <InputError class="mt-2" :message="form.errors.previous_repairs" />
                                </div>

                                <div>
                                    <InputLabel for="estimated_cost" value="Kostoja e Vlerësuar (€)" class="text-sm font-medium text-gray-700" />
                                    <TextInput
                                        id="estimated_cost"
                                        type="number"
                                        step="0.01"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"
                                        v-model="form.estimated_cost"
                                        placeholder="0.00"
                                    />
                                    <InputError class="mt-2" :message="form.errors.estimated_cost" />
                                </div>

                                <div>
                                    <InputLabel for="notes" value="Shënime Shtesë" class="text-sm font-medium text-gray-700" />
                                    <textarea
                                        id="notes"
                                        v-model="form.notes"
                                        rows="3"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"
                                        placeholder="Çdo informacion tjetër që mendon se mund të jetë i dobishëm..."
                                    ></textarea>
                                    <InputError class="mt-2" :message="form.errors.notes" />
                                </div>
                            </div>
                        </div>

                        <!-- Priority and Status -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                <FlagIcon class="w-5 h-5 mr-2 text-orange-600" />
                                Prioriteti dhe Statusi
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <InputLabel for="priority" value="Prioriteti" class="text-sm font-medium text-gray-700" />
                                    <select
                                        id="priority"
                                        v-model="form.priority"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"
                                        required
                                    >
                                        <option value="">Zgjidh prioritetin</option>
                                        <option value="low">I ulët</option>
                                        <option value="normal">Normal</option>
                                        <option value="high">I lartë</option>
                                        <option value="urgent">Urgjent</option>
                                    </select>
                                    <InputError class="mt-2" :message="form.errors.priority" />
                                </div>

                                <div>
                                    <InputLabel for="status" value="Statusi" class="text-sm font-medium text-gray-700" />
                                    <select
                                        id="status"
                                        v-model="form.status"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"
                                        required
                                    >
                                        <option value="pending">Në pritje</option>
                                        <option value="in_progress">Në progres</option>
                                        <option value="completed">Përfunduar</option>
                                        <option value="urgent">Urgjent</option>
                                    </select>
                                    <InputError class="mt-2" :message="form.errors.status" />
                                </div>
                            </div>
                        </div>

                        <!-- AI Analysis Request -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                <CpuChipIcon class="w-5 h-5 mr-2 text-blue-600" />
                                Analiza AI
                            </h3>
                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="h-6 w-6 bg-blue-500 rounded-full flex items-center justify-center">
                                            <span class="text-white text-xs font-bold">AI</span>
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="text-sm font-medium text-blue-900">Kërko Analizë AI</h4>
                                        <p class="text-sm text-blue-700 mt-1">
                                            AI-ja do të analizojë problemin dhe do të japë rekomandime bazuar në të dhënat e ngjashme.
                                        </p>
                                        <div class="mt-3">
                                            <label class="flex items-center">
                                                <input
                                                    type="checkbox"
                                                    v-model="form.request_ai_analysis"
                                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                                />
                                                <span class="ml-2 text-sm text-blue-700">
                                                    Kërko analizë AI për këtë problem
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                            <Link
                                :href="route('reports.index')"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors"
                            >
                                Anulo
                            </Link>
                            <PrimaryButton
                                type="submit"
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-600 to-orange-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:from-red-700 hover:to-orange-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
                                :disabled="form.processing"
                            >
                                <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                {{ form.processing ? 'Duke ruajtur...' : 'Krijo Raportin' }}
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import {
    ArrowLeftIcon,
    InformationCircleIcon,
    ExclamationTriangleIcon,
    TruckIcon,
    BeakerIcon,
    DocumentTextIcon,
    FlagIcon,
    CpuChipIcon
} from '@heroicons/vue/24/outline';

const props = defineProps({
    vehicles: {
        type: Array,
        required: true
    }
});

const form = useForm({
    title: '',
    vehicle_id: '',
    problem_category: '',
    severity: '',
    description: '',
    mileage_at_issue: '',
    issue_date: '',
    location: '',
    symptoms: '',
    weather_conditions: '',
    driving_conditions: '',
    previous_repairs: '',
    estimated_cost: '',
    notes: '',
    priority: 'normal',
    status: 'pending',
    request_ai_analysis: true,
    rating: 0,
    ai_analysis: '',
    resolution_notes: '',
    resolved_at: '',
    cost: '',
    technician_notes: '',
    parts_used: '',
    warranty_claim: false,
    insurance_claim: false
});

const submit = () => {
    form.post(route('reports.store'));
};
</script>
