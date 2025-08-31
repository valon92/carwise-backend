<script setup>
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { 
    HomeIcon, 
    ChatBubbleLeftRightIcon, 
    ChartBarIcon, 
    UserGroupIcon, 
    Cog6ToothIcon,
    BellIcon,
    MagnifyingGlassIcon,
    Bars3Icon,
    XMarkIcon,
    ChevronDownIcon,
    TruckIcon,
    DocumentTextIcon
} from '@heroicons/vue/24/outline';

const page = usePage();
const showingNavigationDropdown = ref(false);
const showingMobileMenu = ref(false);
const showingUserDropdown = ref(false);

// Use authenticated user
const user = computed(() => page.props.auth.user);

const navigation = [
    { name: 'Dashboard', href: '/dashboard', icon: HomeIcon, current: $page.url.startsWith('/dashboard') },
    { name: 'Vehicles', href: '/vehicles', icon: TruckIcon, current: $page.url.startsWith('/vehicles') },
    { name: 'Reports', href: '/reports', icon: DocumentTextIcon, current: $page.url.startsWith('/reports') },
    { name: 'AI Chat', href: '/ai/chat', icon: ChatBubbleLeftRightIcon, current: $page.url.startsWith('/ai/chat') },
    { name: 'Analytics', href: '/ai/analytics', icon: ChartBarIcon, current: $page.url.startsWith('/ai/analytics') },
];

const userNavigation = [
    { name: 'Your Profile', href: '/profile' },
    { name: 'Settings', href: '#' },
    { name: 'Sign out', href: '/logout', method: 'post' },
];
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Mobile menu -->
        <div v-if="showingMobileMenu" class="relative z-50 lg:hidden">
            <div class="fixed inset-0 bg-gray-900/80" @click="showingMobileMenu = false"></div>
            
            <div class="fixed inset-0 flex">
                <div class="relative mr-16 flex w-full max-w-xs flex-1">
                    <div class="absolute left-full top-0 flex w-16 justify-center pt-5">
                        <button 
                            type="button" 
                            class="-m-2.5 p-2.5"
                            @click="showingMobileMenu = false"
                        >
                            <span class="sr-only">Close sidebar</span>
                            <XMarkIcon class="h-6 w-6 text-white" aria-hidden="true" />
                        </button>
                    </div>
                    
                    <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-white px-6 pb-4">
                        <div class="flex h-16 shrink-0 items-center">
                            <Link href="/dashboard" class="flex items-center">
                                <div class="h-8 w-8 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center">
                                    <span class="text-white font-bold text-lg">🚗</span>
                                </div>
                                <span class="ml-2 text-xl font-bold text-gray-900">CarWise AI</span>
                            </Link>
                        </div>
                        <nav class="flex flex-1 flex-col">
                            <ul role="list" class="flex flex-1 flex-col gap-y-7">
                                <li>
                                    <ul role="list" class="-mx-2 space-y-1">
                                        <li v-for="item in navigation" :key="item.name">
                                            <Link
                                                :href="item.href"
                                                :class="[
                                                    item.current
                                                        ? 'bg-gray-50 text-blue-600'
                                                        : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50',
                                                    'group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold'
                                                ]"
                                            >
                                                <component :is="item.icon" 
                                                    :class="[
                                                        item.current ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600',
                                                        'h-6 w-6 shrink-0'
                                                    ]" 
                                                    aria-hidden="true" 
                                                />
                                                {{ item.name }}
                                            </Link>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Static sidebar for desktop -->
        <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
            <div class="flex grow flex-col gap-y-5 overflow-y-auto border-r border-gray-200 bg-white px-6 pb-4">
                <div class="flex h-16 shrink-0 items-center">
                    <Link href="/dashboard" class="flex items-center">
                        <div class="h-8 w-8 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-lg">🚗</span>
                        </div>
                        <span class="ml-2 text-xl font-bold text-gray-900">CarWise AI</span>
                    </Link>
                </div>
                <nav class="flex flex-1 flex-col">
                    <ul role="list" class="flex flex-1 flex-col gap-y-7">
                        <li>
                            <ul role="list" class="-mx-2 space-y-1">
                                <li v-for="item in navigation" :key="item.name">
                                    <Link
                                        :href="item.href"
                                        :class="[
                                            item.current
                                                ? 'bg-gray-50 text-blue-600'
                                                : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50',
                                            'group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold'
                                        ]"
                                    >
                                        <component :is="item.icon" 
                                            :class="[
                                                item.current ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600',
                                                'h-6 w-6 shrink-0'
                                            ]" 
                                            aria-hidden="true" 
                                        />
                                        {{ item.name }}
                                    </Link>
                                </li>
                            </ul>
                        </li>
                        <li class="mt-auto">
                            <Link
                                href="/profile"
                                class="group -mx-2 flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 text-gray-700 hover:bg-gray-50 hover:text-blue-600"
                            >
                                <Cog6ToothIcon
                                    class="h-6 w-6 shrink-0 text-gray-400 group-hover:text-blue-600"
                                    aria-hidden="true"
                                />
                                Settings
                            </Link>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <div class="lg:pl-72">
            <!-- Top bar -->
            <div class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
                <button 
                    type="button" 
                    class="-m-2.5 p-2.5 text-gray-700 lg:hidden"
                    @click="showingMobileMenu = true"
                >
                    <span class="sr-only">Open sidebar</span>
                    <Bars3Icon class="h-6 w-6" aria-hidden="true" />
                </button>

                <!-- Separator -->
                <div class="h-6 w-px bg-gray-200 lg:hidden" aria-hidden="true" />

                <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
                    <form class="relative flex flex-1" action="#" method="GET">
                        <label for="search-field" class="sr-only">Search</label>
                        <MagnifyingGlassIcon
                            class="pointer-events-none absolute inset-y-0 left-0 h-full w-5 text-gray-400"
                            aria-hidden="true"
                        />
                        <input
                            id="search-field"
                            class="block h-full w-full border-0 py-0 pl-8 pr-0 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm"
                            placeholder="Search..."
                            type="search"
                            name="search"
                        />
                    </form>
                    <div class="flex items-center gap-x-4 lg:gap-x-6">
                        <!-- Notifications -->
                        <button type="button" class="-m-2.5 p-2.5 text-gray-400 hover:text-gray-500">
                            <span class="sr-only">View notifications</span>
                            <BellIcon class="h-6 w-6" aria-hidden="true" />
                        </button>

                        <!-- Separator -->
                        <div class="hidden lg:block lg:h-6 lg:w-px lg:bg-gray-200" aria-hidden="true" />

                        <!-- Profile dropdown -->
                        <div class="relative">
                            <button
                                type="button"
                                class="-m-1.5 flex items-center p-1.5"
                                @click="showingUserDropdown = !showingUserDropdown"
                            >
                                <span class="sr-only">Open user menu</span>
                                <div class="h-8 w-8 rounded-full bg-gradient-to-r from-blue-600 to-indigo-600 flex items-center justify-center">
                                    <span class="text-white font-medium text-sm">
                                        {{ user.name.charAt(0).toUpperCase() }}
                                    </span>
                                </div>
                                <span class="hidden lg:flex lg:items-center">
                                    <span class="ml-4 text-sm font-semibold leading-6 text-gray-900" aria-hidden="true">
                                        {{ user.name }}
                                    </span>
                                    <ChevronDownIcon class="ml-2 h-5 w-5 text-gray-400" aria-hidden="true" />
                                </span>
                            </button>

                            <!-- Dropdown menu -->
                            <div
                                v-if="showingUserDropdown"
                                class="absolute right-0 z-10 mt-2.5 w-32 origin-top-right rounded-md bg-white py-2 shadow-lg ring-1 ring-gray-900/5 focus:outline-none"
                            >
                                <Link
                                    v-for="item in userNavigation"
                                    :key="item.name"
                                    :href="item.href"
                                    :method="item.method"
                                    class="block px-3 py-1 text-sm leading-6 text-gray-900 hover:bg-gray-50"
                                    @click="showingUserDropdown = false"
                                >
                                    {{ item.name }}
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page content -->
            <main class="py-10">
                <div class="px-4 sm:px-6 lg:px-8">
                    <!-- Page Heading -->
                    <header v-if="$slots.header" class="mb-8">
                        <slot name="header" />
                    </header>

                    <!-- Page Content -->
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>
