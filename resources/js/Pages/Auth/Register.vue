<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { 
    UserIcon,
    EnvelopeIcon, 
    LockClosedIcon,
    EyeIcon,
    EyeSlashIcon
} from '@heroicons/vue/24/outline';
import { ref } from 'vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const showPassword = ref(false);
const showConfirmPassword = ref(false);

const submit = () => {
            form.post('/register', {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />
        
        <template #title>Create your account</template>
        <template #subtitle>Join CarWise AI and start managing your vehicles</template>

        <form @submit.prevent="submit" class="space-y-6">
            <div>
                <InputLabel for="name" value="Full name" class="text-sm font-medium text-gray-700" />
                <div class="mt-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <UserIcon class="h-5 w-5 text-gray-400" />
                    </div>
                    <TextInput
                        id="name"
                        type="text"
                        class="pl-10 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        v-model="form.name"
                        required
                        autocomplete="name"
                        placeholder="Enter your full name"
                    />
                </div>
                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="email" value="Email address" class="text-sm font-medium text-gray-700" />
                <div class="mt-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <EnvelopeIcon class="h-5 w-5 text-gray-400" />
                    </div>
                    <TextInput
                        id="email"
                        type="email"
                        class="pl-10 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        v-model="form.email"
                        required
                        autocomplete="username"
                        placeholder="Enter your email address"
                    />
                </div>
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
                <InputLabel for="password" value="Password" class="text-sm font-medium text-gray-700" />
                <div class="mt-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <LockClosedIcon class="h-5 w-5 text-gray-400" />
                    </div>
                    <TextInput
                        id="password"
                        :type="showPassword ? 'text' : 'password'"
                        class="pl-10 pr-10 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        v-model="form.password"
                        required
                        autocomplete="new-password"
                        placeholder="Create a strong password"
                    />
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <button
                            type="button"
                            class="text-gray-400 hover:text-gray-600"
                            @click="showPassword = !showPassword"
                        >
                            <EyeIcon v-if="!showPassword" class="h-5 w-5" />
                            <EyeSlashIcon v-else class="h-5 w-5" />
                        </button>
                    </div>
                </div>
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div>
                <InputLabel for="password_confirmation" value="Confirm password" class="text-sm font-medium text-gray-700" />
                <div class="mt-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <LockClosedIcon class="h-5 w-5 text-gray-400" />
                    </div>
                    <TextInput
                        id="password_confirmation"
                        :type="showConfirmPassword ? 'text' : 'password'"
                        class="pl-10 pr-10 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        v-model="form.password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="Confirm your password"
                    />
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <button
                            type="button"
                            class="text-gray-400 hover:text-gray-600"
                            @click="showConfirmPassword = !showConfirmPassword"
                        >
                            <EyeIcon v-if="!showConfirmPassword" class="h-5 w-5" />
                            <EyeSlashIcon v-else class="h-5 w-5" />
                        </button>
                    </div>
                </div>
                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <!-- Password strength indicator -->
            <div v-if="form.password" class="space-y-2">
                <p class="text-sm font-medium text-gray-700">Password strength:</p>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div 
                        class="h-2 rounded-full transition-all duration-300"
                        :class="{
                            'bg-red-500 w-1/4': form.password.length < 6,
                            'bg-yellow-500 w-1/2': form.password.length >= 6 && form.password.length < 8,
                            'bg-blue-500 w-3/4': form.password.length >= 8 && form.password.length < 10,
                            'bg-green-500 w-full': form.password.length >= 10
                        }"
                    ></div>
                </div>
                <p class="text-xs text-gray-500">
                    {{ 
                        form.password.length < 6 ? 'Weak' : 
                        form.password.length < 8 ? 'Fair' : 
                        form.password.length < 10 ? 'Good' : 'Strong' 
                    }}
                </p>
            </div>

            <div>
                <PrimaryButton
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200"
                    :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
                    :disabled="form.processing"
                >
                    <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    {{ form.processing ? 'Creating account...' : 'Create account' }}
                </PrimaryButton>
            </div>

            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Already have an account?
                    <Link
                        href="/login"
                        class="font-medium text-blue-600 hover:text-blue-500 transition-colors"
                    >
                        Sign in here
                    </Link>
                </p>
            </div>

            <!-- Terms and conditions -->
            <div class="text-center">
                <p class="text-xs text-gray-500">
                    By creating an account, you agree to our 
                    <Link href="#" class="text-blue-600 hover:text-blue-500">Terms of Service</Link> 
                    and 
                    <Link href="#" class="text-blue-600 hover:text-blue-500">Privacy Policy</Link>
                </p>
            </div>
        </form>
    </GuestLayout>
</template>
