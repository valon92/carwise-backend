<template>
    <Head title="AI Chat - CarWise" />

    <AuthenticatedLayout>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <Link :href="route('dashboard')" class="flex items-center space-x-2 text-gray-600 hover:text-gray-900">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            <span>Kthehu në Dashboard</span>
                        </Link>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                            <span class="text-sm text-gray-600">AI Online</span>
                        </div>
                        <Link :href="route('ai.analytics')" class="text-sm text-gray-600 hover:text-gray-900">
                            Analitikat
                        </Link>
                    </div>
                </div>
            </div>

        <div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">🤖 AI Asistent</h1>
                <p class="text-gray-600">Bisedoni me AI-në tonë për ndihmë me automjetet tuaja</p>
            </div>

            <!-- Chat Container -->
            <div class="bg-white rounded-lg shadow-lg border border-gray-200 h-96 flex flex-col">
                <!-- Chat Messages -->
                <div class="flex-1 overflow-y-auto p-6 space-y-4" ref="chatContainer">
                    <!-- Welcome Message -->
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="bg-purple-50 rounded-lg p-4">
                                <p class="text-sm text-gray-900">
                                    Mirëseerdhët! Unë jam asistenti juaj AI për të gjitha çështjet e automjeteve. 
                                    Mund t'ju ndihmoj me:
                                </p>
                                <ul class="mt-2 text-sm text-gray-700 space-y-1">
                                    <li>• Krijimin e raporteve të problemeve</li>
                                    <li>• Kontrollimin e statusit të raporteve</li>
                                    <li>• Informacionin e automjeteve</li>
                                    <li>• Vlerësimin e kostos së riparimit</li>
                                    <li>• Programimin e servisit</li>
                                </ul>
                                <p class="mt-2 text-sm text-gray-600">
                                    Si mund t'ju ndihmoj sot?
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Chat History -->
                    <div
                        v-for="chat in chatHistory"
                        :key="chat.id"
                        class="flex items-start space-x-3"
                        :class="chat.user_id ? 'justify-end' : ''"
                    >
                        <!-- AI Message -->
                        <template v-if="!chat.user_id">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 max-w-xs">
                                <div class="bg-purple-50 rounded-lg p-4">
                                    <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ chat.response }}</p>
                                    <div class="mt-2 flex items-center justify-between text-xs text-gray-500">
                                        <span>{{ formatTime(chat.created_at) }}</span>
                                        <span v-if="chat.confidence" class="flex items-center space-x-1">
                                            <span>Besueshmëria:</span>
                                            <span :class="getConfidenceColor(chat.confidence)">
                                                {{ Math.round(chat.confidence * 100) }}%
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <!-- User Message -->
                        <template v-else>
                            <div class="flex-1 max-w-xs">
                                <div class="bg-blue-50 rounded-lg p-4">
                                    <p class="text-sm text-gray-900">{{ chat.message }}</p>
                                    <div class="mt-2 text-xs text-gray-500 text-right">
                                        {{ formatTime(chat.created_at) }}
                                    </div>
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Loading Indicator -->
                    <div v-if="isLoading" class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 max-w-xs">
                            <div class="bg-purple-50 rounded-lg p-4">
                                <div class="flex space-x-1">
                                    <div class="w-2 h-2 bg-purple-400 rounded-full animate-bounce"></div>
                                    <div class="w-2 h-2 bg-purple-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                                    <div class="w-2 h-2 bg-purple-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chat Input -->
                <div class="border-t border-gray-200 p-4">
                    <form @submit.prevent="sendMessage" class="flex space-x-4">
                        <div class="flex-1">
                            <input
                                v-model="message"
                                type="text"
                                placeholder="Shkruani mesazhin tuaj..."
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                :disabled="isLoading"
                            />
                        </div>
                        <button
                            type="submit"
                            :disabled="!message.trim() || isLoading"
                            class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                        >
                            <svg v-if="!isLoading" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            <svg v-else class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Veprime të Shpejta</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <button
                        v-for="action in quickActions"
                        :key="action.text"
                        @click="sendQuickMessage(action.text)"
                        :disabled="isLoading"
                        class="p-4 bg-white rounded-lg border border-gray-200 hover:border-purple-300 hover:shadow-md transition-all text-left"
                    >
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-purple-100 rounded-lg">
                                <component :is="action.icon" class="w-5 h-5 text-purple-600" />
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ action.text }}</span>
                        </div>
                    </button>
                </div>
            </div>

            <!-- AI Insights -->
            <div class="mt-8 bg-gradient-to-r from-purple-500 to-pink-600 rounded-lg shadow-lg p-6 text-white">
                <h3 class="text-lg font-medium mb-4">🤖 AI Insights</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold">{{ insights.user_stats.total_reports }}</div>
                        <div class="text-purple-200">Raporte Totale</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold">{{ insights.user_stats.average_resolution_time }}h</div>
                        <div class="text-purple-200">Kohë Mesatare</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold">{{ insights.user_stats.cost_analysis.total_spent }}€</div>
                        <div class="text-purple-200">Kosto Totale</div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted, nextTick, watch } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import axios from 'axios'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
    chatHistory: Array,
    insights: Object,
    userStats: Object,
    vehicles: Array
})

const message = ref('')
const isLoading = ref(false)
const chatContainer = ref(null)
const sessionId = ref(null)

// Quick action icons
const DocumentIcon = {
    template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>`
}

const ClockIcon = {
    template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`
}

const CarIcon = {
    template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>`
}

const CurrencyIcon = {
    template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path></svg>`
}

const quickActions = [
    { text: 'Krijo raport të ri', icon: DocumentIcon },
    { text: 'Kontrollo statusin', icon: ClockIcon },
    { text: 'Informacion automjeti', icon: CarIcon },
    { text: 'Vlerësim kosto', icon: CurrencyIcon }
]

const sendMessage = async () => {
    if (!message.value.trim() || isLoading.value) return

    const userMessage = message.value
    message.value = ''
    isLoading.value = true

    try {
        const response = await axios.post(route('ai.process-message'), {
            message: userMessage,
            session_id: sessionId.value
        })

        if (response.data.success) {
            // Add user message to chat
            props.chatHistory.push({
                id: Date.now(),
                user_id: 1, // Current user
                message: userMessage,
                created_at: new Date().toISOString()
            })

            // Add AI response to chat
            props.chatHistory.push({
                id: response.data.chat_id,
                user_id: null, // AI response
                response: response.data.response,
                confidence: response.data.confidence,
                created_at: new Date().toISOString()
            })

            sessionId.value = response.data.session_id
        }
    } catch (error) {
        console.error('Error sending message:', error)
        // Add error message
        props.chatHistory.push({
            id: Date.now(),
            user_id: null,
            response: 'Na vjen keq, po has probleme teknike. Ju lutem provoni përsëri.',
            created_at: new Date().toISOString()
        })
    } finally {
        isLoading.value = false
        scrollToBottom()
    }
}

const sendQuickMessage = (text) => {
    message.value = text
    sendMessage()
}

const scrollToBottom = () => {
    nextTick(() => {
        if (chatContainer.value) {
            chatContainer.value.scrollTop = chatContainer.value.scrollHeight
        }
    })
}

const formatTime = (date) => {
    return new Date(date).toLocaleTimeString('sq-AL', {
        hour: '2-digit',
        minute: '2-digit'
    })
}

const getConfidenceColor = (confidence) => {
    if (confidence >= 0.8) return 'text-green-600'
    if (confidence >= 0.6) return 'text-yellow-600'
    return 'text-red-600'
}

onMounted(() => {
    scrollToBottom()
})

watch(() => props.chatHistory, () => {
    scrollToBottom()
}, { deep: true })
</script>
