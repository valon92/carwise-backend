<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps({
    analytics: {
        type: Object,
        default: () => ({
            system_stats: {
                total_reports: 0,
                total_users: 0,
                total_cost_this_month: 0
            },
            ai_performance: {
                total_chats: 0,
                accuracy_rate: 0,
                response_time: 0
            },
            user_insights: {
                most_active_users: [],
                top_vehicles: [],
                common_issues: []
            }
        })
    }
});
</script>

<template>
    <Head title="AI Analytics - CarWise" />

    <AuthenticatedLayout>
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
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Saktësia</span>
                            <span class="text-sm font-medium text-gray-900">{{ analytics.ai_performance.accuracy_rate }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" :style="{ width: analytics.ai_performance.accuracy_rate + '%' }"></div>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Koha e Përgjigjes</span>
                            <span class="text-sm font-medium text-gray-900">{{ analytics.ai_performance.response_time }}s</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" :style="{ width: Math.min(100, (analytics.ai_performance.response_time / 5) * 100) + '%' }"></div>
                        </div>
                    </div>
                </div>

                <!-- User Activity -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">👥 Aktiviteti i Përdoruesve</h3>
                    <div class="space-y-3">
                        <div v-for="user in analytics.user_insights.most_active_users" :key="user.id" class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-medium text-blue-600">{{ user.name.charAt(0) }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ user.name }}</p>
                                    <p class="text-xs text-gray-500">{{ user.reports_count }} raporte</p>
                                </div>
                            </div>
                            <span class="text-sm text-gray-500">{{ user.last_activity }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Vehicles -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">🚗 Automjetet Më të Përdorura</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div v-for="vehicle in analytics.user_insights.top_vehicles" :key="vehicle.id" class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                <span class="text-lg">🚗</span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ vehicle.brand }} {{ vehicle.model }}</p>
                                <p class="text-xs text-gray-500">{{ vehicle.year }} - {{ vehicle.reports_count }} raporte</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Common Issues -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">🔧 Problemet Më të Zakonshme</h3>
                <div class="space-y-3">
                    <div v-for="issue in analytics.user_insights.common_issues" :key="issue.name" class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                <span class="text-sm">🔧</span>
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ issue.name }}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-500">{{ issue.count }} herë</span>
                            <div class="w-16 bg-gray-200 rounded-full h-2">
                                <div class="bg-red-600 h-2 rounded-full" :style="{ width: (issue.count / Math.max(...analytics.user_insights.common_issues.map(i => i.count))) * 100 + '%' }"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
