<script setup>
import { Link } from '@inertiajs/vue3';
import { 
    Bars3Icon,
    XMarkIcon
} from '@heroicons/vue/24/outline';
import { ref } from 'vue';

const mobileMenuOpen = ref(false);
</script>

<template>
    <nav class="relative z-10 bg-white/80 backdrop-blur-sm border-b border-gray-200/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <Link href="/" class="flex items-center">
                            <div class="h-8 w-8 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center shadow-lg">
                                <span class="text-white font-bold text-lg">🚗</span>
                            </div>
                            <span class="ml-2 text-xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                                CarWise AI
                            </span>
                        </Link>
                    </div>
                </div>
                
                <!-- Desktop menu -->
                <div class="hidden md:flex items-center space-x-4">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-lg hover:shadow-xl"
                    >
                        Dashboard
                    </Link>
                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors hover:bg-gray-50"
                        >
                            Kyçu
                        </Link>
                        <Link
                            :href="route('register')"
                            class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-lg hover:shadow-xl"
                        >
                            Regjistrohu
                        </Link>
                    </template>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button
                        @click="mobileMenuOpen = !mobileMenuOpen"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500"
                    >
                        <span class="sr-only">Open main menu</span>
                        <Bars3Icon v-if="!mobileMenuOpen" class="block h-6 w-6" />
                        <XMarkIcon v-else class="block h-6 w-6" />
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div v-if="mobileMenuOpen" class="md:hidden">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-white/95 backdrop-blur-sm border-t border-gray-200/50">
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50"
                    @click="mobileMenuOpen = false"
                >
                    Dashboard
                </Link>
                <template v-else>
                    <Link
                        :href="route('login')"
                        class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50"
                        @click="mobileMenuOpen = false"
                    >
                        Kyçu
                    </Link>
                    <Link
                        :href="route('register')"
                        class="block px-3 py-2 rounded-md text-base font-medium text-blue-600 hover:text-blue-700 hover:bg-blue-50"
                        @click="mobileMenuOpen = false"
                    >
                        Regjistrohu
                    </Link>
                </template>
            </div>
        </div>
    </nav>
</template>
