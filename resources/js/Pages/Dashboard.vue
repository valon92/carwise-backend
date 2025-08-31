<script setup>
import { ref } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
    user: Object,
    stats: Object,
    ai_insights: Object,
    recent_reports: Array,
    urgent_reports: Array,
    vehicles_needing_service: Array,
    assigned_reports: Array,
    notifications: Array,
    upcoming_events: Array,
    performance_metrics: Object,
    quick_actions: Array
})

const userMenuOpen = ref(false)

const getStatusColor = (status) => {
    return {
        'pending': 'yellow',
        'in_progress': 'blue',
        'completed': 'green',
        'cancelled': 'red'
    }[status] || 'gray'
}

const getStatusText = (status) => {
    return {
        'pending': 'Në pritje',
        'in_progress': 'Në progres',
        'completed': 'Përfunduar',
        'cancelled': 'Anuluar'
    }[status] || status
}

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('sq-AL')
}

const getEventColor = (type) => {
    return {
        'service': 'blue',
        'warranty': 'yellow',
        'insurance': 'red'
    }[type] || 'gray'
}

const getPriorityColor = (priority) => {
    return {
        'high': 'red',
        'medium': 'yellow',
        'low': 'green'
    }[priority] || 'gray'
}

const getPriorityText = (priority) => {
    return {
        'high': 'I lartë',
        'medium': 'Mesatar',
        'low': 'I ulët'
    }[priority] || priority
}
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <h1 class="text-2xl font-bold text-indigo-600">🚗 CarWise AI</h1>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <!-- AI Chat Button -->
                        <Link
                            :href="route('ai.chat')"
                            class="bg-purple-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-purple-700 transition-colors flex items-center space-x-2"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <span>AI Asistent</span>
                        </Link>
                        
                        <!-- Notifications -->
                        <div class="relative">
                            <button class="p-2 text-gray-400 hover:text-gray-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.19 4H20c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4.19C3.45 20 2.8 19.55 2.8 18.8V5.2C2.8 4.45 3.45 4 4.19 4z"></path>
                                </svg>
                                <span v-if="user.unread_notifications > 0" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                    {{ user.unread_notifications }}
                                </span>
                            </button>
                        </div>

                        <!-- User Menu -->
                        <div class="relative">
                            <button @click="userMenuOpen = !userMenuOpen" class="flex items-center space-x-2">
                                <img :src="user.avatar" :alt="user.name" class="h-8 w-8 rounded-full">
                                <span class="text-sm font-medium text-gray-700">{{ user.name }}</span>
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            
                            <div v-if="userMenuOpen" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <Link :href="route('profile.edit')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Profili
                                </Link>
                                <Link :href="route('logout')" method="post" as="button" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Dil
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <!-- Welcome Section -->
            <div class="px-4 py-6 sm:px-0">
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold">Mirëseerdhët, {{ user.name }}! 👋</h2>
                            <p class="mt-2 text-indigo-100">Kontrolloni statusin e automjeteve dhe raporteve tuaja</p>
                            <p class="text-sm text-indigo-200">Hyrja e fundit: {{ user.last_login }}</p>
                        </div>
                        <div class="text-right">
                            <div class="text-3xl font-bold">{{ user.role }}</div>
                            <div class="text-indigo-200">Roli juaj</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="px-4 py-6 sm:px-0">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Veprime të Shpejta</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div
                        v-for="action in quick_actions"
                        :key="action.title"
                        class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow cursor-pointer"
                        @click="$inertia.visit(action.route)"
                    >
                        <div class="flex items-center space-x-3">
                            <div :class="`p-2 rounded-lg bg-${action.color}-100`">
                                <svg class="w-6 h-6" :class="`text-${action.color}-600`" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900">{{ action.title }}</h4>
                                <p class="text-sm text-gray-500">{{ action.description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="px-4 py-6 sm:px-0">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Statistikat</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center">
                            <div class="p-2 rounded-lg bg-blue-100">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Raporte</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ stats.user.total }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center">
                            <div class="p-2 rounded-lg bg-yellow-100">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Në Pritje</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ stats.user.pending }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center">
                            <div class="p-2 rounded-lg bg-green-100">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Përfunduar</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ stats.user.completed }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center">
                            <div class="p-2 rounded-lg bg-red-100">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Urgjente</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ stats.user.urgent }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Reports -->
            <div class="px-4 py-6 sm:px-0">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Raportet e Fundit</h3>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div
                            v-for="report in recent_reports"
                            :key="report.id"
                            class="px-6 py-4 hover:bg-gray-50 transition-colors"
                        >
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div :class="`w-3 h-3 rounded-full bg-${getStatusColor(report.status)}-500`"></div>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900">{{ report.title }}</h4>
                                        <p class="text-sm text-gray-500">{{ report.brand }} {{ report.model }} ({{ report.year }})</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-900">{{ formatDate(report.created_at) }}</p>
                                    <p class="text-xs text-gray-500">{{ getStatusText(report.status) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- AI Insights -->
            <div class="px-4 py-6 sm:px-0">
                <div class="bg-gradient-to-r from-purple-500 to-pink-600 rounded-lg shadow-lg p-6 text-white">
                    <h3 class="text-lg font-medium mb-4">🤖 AI Insights</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold">{{ ai_insights.user_stats.total_reports }}</div>
                            <div class="text-purple-200">Raporte Totale</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold">{{ ai_insights.user_stats.average_resolution_time }}h</div>
                            <div class="text-purple-200">Kohë Mesatare</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold">{{ ai_insights.user_stats.cost_analysis.total_spent }}€</div>
                            <div class="text-purple-200">Kosto Totale</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upcoming Events -->
            <div v-if="upcoming_events.length > 0" class="px-4 py-6 sm:px-0">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Ngjarjet e Ardhshme</h3>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="divide-y divide-gray-200">
                        <div
                            v-for="event in upcoming_events"
                            :key="event.title"
                            class="px-6 py-4"
                        >
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div :class="`p-2 rounded-lg bg-${getEventColor(event.type)}-100`">
                                        <svg class="w-5 h-5" :class="`text-${getEventColor(event.type)}-600`" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900">{{ event.title }}</h4>
                                        <p class="text-sm text-gray-500">{{ event.description }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-900">{{ formatDate(event.date) }}</p>
                                    <span :class="`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-${getPriorityColor(event.priority)}-100 text-${getPriorityColor(event.priority)}-800`">
                                        {{ getPriorityText(event.priority) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
