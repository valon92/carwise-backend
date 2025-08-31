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
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <!-- Welcome Section -->
            <div class="px-4 py-6 sm:px-0">
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold">Mirëseerdhët, {{ user.name }}! 👋</h2>
                            <p class="mt-2 text-indigo-100">Kontrolloni statusin e automjeteve dhe raporteve tuaja</p>
                        </div>
                        <div class="text-right">
                            <div class="text-3xl font-bold">{{ user.role || 'Përdorues' }}</div>
                            <div class="text-indigo-200">Roli juaj</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="px-4 py-6 sm:px-0">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 border border-gray-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Total Raporte
                                    </dt>
                                    <dd class="text-lg font-medium text-gray-900">
                                        {{ stats?.total || 0 }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 border border-gray-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Përfunduar
                                    </dt>
                                    <dd class="text-lg font-medium text-gray-900">
                                        {{ stats?.completed || 0 }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 border border-gray-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Në Progres
                                    </dt>
                                    <dd class="text-lg font-medium text-gray-900">
                                        {{ stats?.in_progress || 0 }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 border border-gray-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Urgjente
                                    </dt>
                                    <dd class="text-lg font-medium text-gray-900">
                                        {{ stats?.urgent || 0 }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Reports -->
            <div class="px-4 py-6 sm:px-0">
                <div class="bg-white rounded-lg shadow-xl sm:rounded-lg border border-gray-200">
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
                            <div class="text-2xl font-bold">{{ ai_insights?.user_stats?.total_reports || 0 }}</div>
                            <div class="text-purple-200">Raporte Totale</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold">{{ ai_insights?.user_stats?.average_resolution_time || 0 }}h</div>
                            <div class="text-purple-200">Kohë Mesatare</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold">{{ ai_insights?.user_stats?.cost_analysis?.total_spent || 0 }}€</div>
                            <div class="text-purple-200">Kosto Totale</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
