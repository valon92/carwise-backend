<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        Shto Automjet të Ri
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">
                        Plotëso informacionet për automjetin tënd të ri
                    </p>
                </div>
                <Link
                    href="/vehicles"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
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
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <InputLabel for="brand" value="Marka" class="text-sm font-medium text-gray-700" />
                                    <TextInput
                                        id="brand"
                                        type="text"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        v-model="form.brand"
                                        required
                                        placeholder="p.sh. BMW, Mercedes, Audi"
                                    />
                                    <InputError class="mt-2" :message="form.errors.brand" />
                                </div>

                                <div>
                                    <InputLabel for="model" value="Modeli" class="text-sm font-medium text-gray-700" />
                                    <TextInput
                                        id="model"
                                        type="text"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        v-model="form.model"
                                        required
                                        placeholder="p.sh. X5, C-Class, A4"
                                    />
                                    <InputError class="mt-2" :message="form.errors.model" />
                                </div>

                                <div>
                                    <InputLabel for="year" value="Viti" class="text-sm font-medium text-gray-700" />
                                    <TextInput
                                        id="year"
                                        type="number"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        v-model="form.year"
                                        required
                                        min="1900"
                                        :max="new Date().getFullYear() + 1"
                                        placeholder="2020"
                                    />
                                    <InputError class="mt-2" :message="form.errors.year" />
                                </div>
                            </div>
                        </div>

                        <!-- Identification -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                <IdentificationIcon class="w-5 h-5 mr-2 text-green-600" />
                                Identifikim
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <InputLabel for="vin" value="VIN (Opsional)" class="text-sm font-medium text-gray-700" />
                                    <TextInput
                                        id="vin"
                                        type="text"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        v-model="form.vin"
                                        maxlength="17"
                                        placeholder="1HGBH41JXMN109186"
                                    />
                                    <InputError class="mt-2" :message="form.errors.vin" />
                                </div>

                                <div>
                                    <InputLabel for="license_plate" value="Targa (Opsional)" class="text-sm font-medium text-gray-700" />
                                    <TextInput
                                        id="license_plate"
                                        type="text"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        v-model="form.license_plate"
                                        placeholder="AB 123 CD"
                                    />
                                    <InputError class="mt-2" :message="form.errors.license_plate" />
                                </div>
                            </div>
                        </div>

                        <!-- Technical Specifications -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                <CogIcon class="w-5 h-5 mr-2 text-purple-600" />
                                Specifikimet Teknike
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                <div>
                                    <InputLabel for="mileage" value="Kilometrazha (km)" class="text-sm font-medium text-gray-700" />
                                    <TextInput
                                        id="mileage"
                                        type="number"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        v-model="form.mileage"
                                        min="0"
                                        placeholder="50000"
                                    />
                                    <InputError class="mt-2" :message="form.errors.mileage" />
                                </div>

                                <div>
                                    <InputLabel for="fuel_type" value="Lloji i Karburantit" class="text-sm font-medium text-gray-700" />
                                    <select
                                        id="fuel_type"
                                        v-model="form.fuel_type"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                    >
                                        <option value="">Zgjidh llojin</option>
                                        <option value="Benzinë">Benzinë</option>
                                        <option value="Naftë">Naftë</option>
                                        <option value="Elektrik">Elektrik</option>
                                        <option value="Hibrid">Hibrid</option>
                                        <option value="LNG">LNG</option>
                                        <option value="LPG">LPG</option>
                                    </select>
                                    <InputError class="mt-2" :message="form.errors.fuel_type" />
                                </div>

                                <div>
                                    <InputLabel for="transmission" value="Transmisioni" class="text-sm font-medium text-gray-700" />
                                    <select
                                        id="transmission"
                                        v-model="form.transmission"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                    >
                                        <option value="">Zgjidh transmisionin</option>
                                        <option value="Manual">Manual</option>
                                        <option value="Automatik">Automatik</option>
                                        <option value="CVT">CVT</option>
                                        <option value="Semi-automatik">Semi-automatik</option>
                                    </select>
                                    <InputError class="mt-2" :message="form.errors.transmission" />
                                </div>

                                <div>
                                    <InputLabel for="color" value="Ngjyra" class="text-sm font-medium text-gray-700" />
                                    <TextInput
                                        id="color"
                                        type="text"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        v-model="form.color"
                                        placeholder="Bardhë, Zi, etc."
                                    />
                                    <InputError class="mt-2" :message="form.errors.color" />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">
                                <div>
                                    <InputLabel for="engine_size" value="Madhësia e Motorit" class="text-sm font-medium text-gray-700" />
                                    <TextInput
                                        id="engine_size"
                                        type="text"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        v-model="form.engine_size"
                                        placeholder="2.0L, 3.0L, etc."
                                    />
                                    <InputError class="mt-2" :message="form.errors.engine_size" />
                                </div>

                                <div>
                                    <InputLabel for="horsepower" value="Kuaj Fuqi" class="text-sm font-medium text-gray-700" />
                                    <TextInput
                                        id="horsepower"
                                        type="number"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        v-model="form.horsepower"
                                        min="0"
                                        placeholder="150"
                                    />
                                    <InputError class="mt-2" :message="form.errors.horsepower" />
                                </div>

                                <div>
                                    <InputLabel for="torque" value="Çift Rrotullimi (Nm)" class="text-sm font-medium text-gray-700" />
                                    <TextInput
                                        id="torque"
                                        type="number"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        v-model="form.torque"
                                        min="0"
                                        placeholder="300"
                                    />
                                    <InputError class="mt-2" :message="form.errors.torque" />
                                </div>

                                <div>
                                    <InputLabel for="fuel_efficiency" value="Konsumi (L/100km)" class="text-sm font-medium text-gray-700" />
                                    <TextInput
                                        id="fuel_efficiency"
                                        type="number"
                                        step="0.1"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        v-model="form.fuel_efficiency"
                                        min="0"
                                        placeholder="7.5"
                                    />
                                    <InputError class="mt-2" :message="form.errors.fuel_efficiency" />
                                </div>
                            </div>
                        </div>

                        <!-- Physical Specifications -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                <CubeIcon class="w-5 h-5 mr-2 text-orange-600" />
                                Specifikimet Fizike
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                <div>
                                    <InputLabel for="body_type" value="Lloji i Karrocerisë" class="text-sm font-medium text-gray-700" />
                                    <select
                                        id="body_type"
                                        v-model="form.body_type"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                    >
                                        <option value="">Zgjidh llojin</option>
                                        <option value="Sedan">Sedan</option>
                                        <option value="SUV">SUV</option>
                                        <option value="Hatchback">Hatchback</option>
                                        <option value="Wagon">Wagon</option>
                                        <option value="Coupe">Coupe</option>
                                        <option value="Convertible">Convertible</option>
                                        <option value="Pickup">Pickup</option>
                                        <option value="Van">Van</option>
                                    </select>
                                    <InputError class="mt-2" :message="form.errors.body_type" />
                                </div>

                                <div>
                                    <InputLabel for="doors" value="Numri i Dyerve" class="text-sm font-medium text-gray-700" />
                                    <TextInput
                                        id="doors"
                                        type="number"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        v-model="form.doors"
                                        min="1"
                                        max="10"
                                        placeholder="4"
                                    />
                                    <InputError class="mt-2" :message="form.errors.doors" />
                                </div>

                                <div>
                                    <InputLabel for="seats" value="Numri i Vendeve" class="text-sm font-medium text-gray-700" />
                                    <TextInput
                                        id="seats"
                                        type="number"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        v-model="form.seats"
                                        min="1"
                                        max="20"
                                        placeholder="5"
                                    />
                                    <InputError class="mt-2" :message="form.errors.seats" />
                                </div>

                                <div>
                                    <InputLabel for="weight" value="Pesha (kg)" class="text-sm font-medium text-gray-700" />
                                    <TextInput
                                        id="weight"
                                        type="number"
                                        step="0.1"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        v-model="form.weight"
                                        min="0"
                                        placeholder="1500"
                                    />
                                    <InputError class="mt-2" :message="form.errors.weight" />
                                </div>
                            </div>
                        </div>

                        <!-- Important Dates -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                <CalendarIcon class="w-5 h-5 mr-2 text-red-600" />
                                Datat e Rëndësishme
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <InputLabel for="warranty_expiry" value="Skadimi i Garancisë" class="text-sm font-medium text-gray-700" />
                                    <TextInput
                                        id="warranty_expiry"
                                        type="date"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        v-model="form.warranty_expiry"
                                    />
                                    <InputError class="mt-2" :message="form.errors.warranty_expiry" />
                                </div>

                                <div>
                                    <InputLabel for="insurance_expiry" value="Skadimi i Sigurimit" class="text-sm font-medium text-gray-700" />
                                    <TextInput
                                        id="insurance_expiry"
                                        type="date"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        v-model="form.insurance_expiry"
                                    />
                                    <InputError class="mt-2" :message="form.errors.insurance_expiry" />
                                </div>

                                <div>
                                    <InputLabel for="next_service_date" value="Servisi i Radhës" class="text-sm font-medium text-gray-700" />
                                    <TextInput
                                        id="next_service_date"
                                        type="date"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        v-model="form.next_service_date"
                                    />
                                    <InputError class="mt-2" :message="form.errors.next_service_date" />
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                <DocumentTextIcon class="w-5 h-5 mr-2 text-indigo-600" />
                                Informacione Shtesë
                            </h3>
                            <div>
                                <InputLabel for="notes" value="Shënime" class="text-sm font-medium text-gray-700" />
                                <textarea
                                    id="notes"
                                    v-model="form.notes"
                                    rows="4"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                    placeholder="Shënime shtesë për automjetin..."
                                ></textarea>
                                <InputError class="mt-2" :message="form.errors.notes" />
                            </div>
                        </div>

                        <!-- Status -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                <CheckCircleIcon class="w-5 h-5 mr-2 text-green-600" />
                                Statusi
                            </h3>
                            <div class="flex items-center space-x-6">
                                <div class="flex items-center">
                                    <input
                                        id="is_active"
                                        type="checkbox"
                                        v-model="form.is_active"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                    />
                                    <label for="is_active" class="ml-2 block text-sm text-gray-700">
                                        Automjeti është aktiv
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input
                                        id="is_primary"
                                        type="checkbox"
                                        v-model="form.is_primary"
                                        class="h-4 w-4 text-yellow-600 focus:ring-yellow-500 border-gray-300 rounded"
                                    />
                                    <label for="is_primary" class="ml-2 block text-sm text-gray-700">
                                        Automjeti kryesor
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                            <Link
                                href="/vehicles"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
                            >
                                Anulo
                            </Link>
                            <PrimaryButton
                                type="submit"
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:from-blue-700 hover:to-indigo-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
                                :disabled="form.processing"
                            >
                                <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                {{ form.processing ? 'Duke ruajtur...' : 'Ruaj Automjetin' }}
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
    IdentificationIcon,
    CogIcon,
    CubeIcon,
    CalendarIcon,
    DocumentTextIcon,
    CheckCircleIcon
} from '@heroicons/vue/24/outline';

const form = useForm({
    brand: '',
    model: '',
    year: '',
    vin: '',
    license_plate: '',
    mileage: '',
    fuel_type: '',
    transmission: '',
    color: '',
    engine_size: '',
    horsepower: '',
    torque: '',
    fuel_efficiency: '',
    body_type: '',
    doors: '',
    seats: '',
    weight: '',
    length: '',
    width: '',
    height: '',
    warranty_expiry: '',
    insurance_expiry: '',
    last_service_date: '',
    next_service_date: '',
    service_history: [],
    modifications: [],
    notes: '',
    is_active: true,
    is_primary: false,
});

const submit = () => {
    form.post('/vehicles');
};
</script>
