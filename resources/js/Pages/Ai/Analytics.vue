<template>
    <Head title="AI Analytics - CarWise" />

    <div class="min-h-screen bg-gray-50">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <Link :href="route('dashboard')" class="flex items-center space-x-2 text-gray-600 hover:text-gray-900">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            <span>Dashboard</span>
                        </Link>
                        <span class="mx-2 text-gray-400">/</span>
                        <Link :href="route('ai.chat')" class="text-gray-600 hover:text-gray-900">
                            AI Chat
                        </Link>
                        <span class="mx-2 text-gray-400">/</span>
                        <span class="text-gray-900 font-medium">Analytics</span>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                            <span class="text-sm text-gray-600">AI System Active</span>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">📊 AI Analytics</h1>
                <p class="text-gray-600">Analitikat e performancës së AI-së dhe sistemit</p>
            </div>

            <!-- System Overview -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="p-2 rounded-lg bg-blue-100">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Raporte</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ analytics.system_stats.total_reports }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="p-2 rounded-lg bg-green-100">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Përdorues</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ analytics.system_stats.total_users }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="p-2 rounded-lg bg-purple-100">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">AI Biseda</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ analytics.ai_performance.total_chats }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="p-2 rounded-lg bg-yellow-100">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Kosto Mesatare</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ analytics.system_stats.total_cost_this_month }}€</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- AI Performance -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- AI Performance Stats -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">🤖 Performanca e AI</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Shkalla e Zgjidhjes</span>
                            <span class="text-sm font-medium text-gray-900">{{ analytics.ai_performance.resolution_rate }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" :style="{ width: analytics.ai_performance.resolution_rate + '%' }"></div>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Besueshmëria e Lartë</span>
                            <span class="text-sm font-medium text-gray-900">{{ analytics.ai_performance.high_confidence_rate }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" :style="{ width: analytics.ai_performance.high_confidence_rate + '%' }"></div>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Vlerësimi Mesatar</span>
                            <span class="text-sm font-medium text-gray-900">{{ analytics.ai_performance.average_rating }}/5</span>
                        </div>
                        <div class="flex items-center">
                            <div class="flex text-yellow-400">
                                <svg v-for="i in 5" :key="i" class="w-4 h-4" :class="i <= analytics.ai_performance.average_rating ? 'text-yellow-400' : 'text-gray-300'" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Koha Mesatare e Përgjigjes</span>
                            <span class="text-sm font-medium text-gray-900">{{ analytics.ai_performance.average_response_time }}s</span>
                        </div>
                    </div>
                </div>

                <!-- Reports by Status -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">📊 Raportet sipas Statusit</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                                <span class="text-sm text-gray-600">Në pritje</span>
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ analytics.system_stats.reports_by_status.pending }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                <span class="text-sm text-gray-600">Në progres</span>
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ analytics.system_stats.reports_by_status.in_progress }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                <span class="text-sm text-gray-600">Përfunduar</span>
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ analytics.system_stats.reports_by_status.completed }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                <span class="text-sm text-gray-600">Anuluar</span>
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ analytics.system_stats.reports_by_status.cancelled }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reports by Severity -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">⚠️ Raportet sipas Rëndësisë</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                <span class="text-sm text-gray-600">E ulët</span>
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ analytics.system_stats.reports_by_severity.low }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                                <span class="text-sm text-gray-600">Mesatare</span>
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ analytics.system_stats.reports_by_severity.medium }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-orange-500 rounded-full"></div>
                                <span class="text-sm text-gray-600">E lartë</span>
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ analytics.system_stats.reports_by_severity.high }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                <span class="text-sm text-gray-600">Kritike</span>
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ analytics.system_stats.reports_by_severity.critical }}</span>
                        </div>
                    </div>
                </div>

                <!-- Trends -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">📈 Tendencat</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Raporte këtë muaj</span>
                            <span class="text-sm font-medium text-gray-900">{{ analytics.trends.reports_trend.current_month }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Raporte muajin e kaluar</span>
                            <span class="text-sm font-medium text-gray-900">{{ analytics.trends.reports_trend.last_month }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">AI përdorim këtë muaj</span>
                            <span class="text-sm font-medium text-gray-900">{{ analytics.trends.ai_usage_trend.current_month }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">AI përdorim muajin e kaluar</span>
                            <span class="text-sm font-medium text-gray-900">{{ analytics.trends.ai_usage_trend.last_month }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Insights -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">👤 Insights për Përdoruesin</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-purple-600">{{ analytics.user_insights.user_stats.total_reports }}</div>
                        <div class="text-sm text-gray-600">Raporte Totale</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600">{{ analytics.user_insights.user_stats.average_resolution_time }}h</div>
                        <div class="text-sm text-gray-600">Kohë Mesatare e Zgjidhjes</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-600">{{ analytics.user_insights.user_stats.cost_analysis.total_spent }}€</div>
                        <div class="text-sm text-gray-600">Kosto Totale</div>
                    </div>
                </div>
            </div>

            <!-- Recommendations -->
            <div v-if="analytics.user_insights.recommendations.length > 0" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">💡 Rekomandimet e AI</h3>
                <div class="space-y-4">
                    <div
                        v-for="recommendation in analytics.user_insights.recommendations"
                        :key="recommendation.title"
                        class="flex items-start space-x-3 p-4 rounded-lg"
                        :class="{
                            'bg-red-50 border border-red-200': recommendation.priority === 'high',
                            'bg-yellow-50 border border-yellow-200': recommendation.priority === 'medium',
                            'bg-green-50 border border-green-200': recommendation.priority === 'low'
                        }"
                    >
                        <div class="flex-shrink-0">
                            <div class="w-6 h-6 rounded-full flex items-center justify-center"
                                :class="{
                                    'bg-red-100': recommendation.priority === 'high',
                                    'bg-yellow-100': recommendation.priority === 'medium',
                                    'bg-green-100': recommendation.priority === 'low'
                                }"
                            >
                                <svg class="w-4 h-4"
                                    :class="{
                                        'text-red-600': recommendation.priority === 'high',
                                        'text-yellow-600': recommendation.priority === 'medium',
                                        'text-green-600': recommendation.priority === 'low'
                                    }"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-medium text-gray-900">{{ recommendation.title }}</h4>
                            <p class="text-sm text-gray-600 mt-1">{{ recommendation.message }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3'

const props = defineProps({
    analytics: Object
})
</script>
