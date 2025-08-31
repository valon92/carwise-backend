<template>
    <Head title="AI Chat - CarWise" />

    <AuthenticatedLayout>
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
                                    <div class="mt-2 text-xs text-gray-500">
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
                </div>

                <!-- Message Input -->
                <div class="border-t border-gray-200 p-4">
                    <form @submit.prevent="sendMessage" class="flex space-x-4">
                        <input
                            v-model="messageForm.message"
                            type="text"
                            placeholder="Shkruani mesazhin tuaj..."
                            class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            :disabled="messageForm.processing"
                        />
                        <button
                            type="submit"
                            class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                            :disabled="messageForm.processing || !messageForm.message.trim()"
                        >
                            <svg v-if="messageForm.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span v-else>Dërgo</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <button
                    @click="messageForm.message = 'Krijo një raport të ri për problemin e motorit'"
                    class="bg-white border border-gray-200 rounded-lg p-4 text-left hover:bg-gray-50 transition-colors"
                >
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                            <span class="text-red-600">🚗</span>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">Raport i Ri</h4>
                            <p class="text-xs text-gray-500">Krijo raport për problem</p>
                        </div>
                    </div>
                </button>

                <button
                    @click="messageForm.message = 'Kontrollo statusin e raporteve të mia'"
                    class="bg-white border border-gray-200 rounded-lg p-4 text-left hover:bg-gray-50 transition-colors"
                >
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <span class="text-blue-600">📊</span>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">Statusi</h4>
                            <p class="text-xs text-gray-500">Kontrollo raportet</p>
                        </div>
                    </div>
                </button>

                <button
                    @click="messageForm.message = 'Vlerëso koston e riparimit për frenat'"
                    class="bg-white border border-gray-200 rounded-lg p-4 text-left hover:bg-gray-50 transition-colors"
                >
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <span class="text-green-600">💰</span>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">Vlerësim</h4>
                            <p class="text-xs text-gray-500">Vlerëso koston</p>
                        </div>
                    </div>
                </button>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted, nextTick } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    chatHistory: {
        type: Array,
        default: () => []
    },
    user: Object
});

const chatContainer = ref(null);
const messageForm = useForm({
    message: ''
});

const sendMessage = () => {
    if (messageForm.message.trim()) {
        messageForm.post(route('ai.process-message'), {
            onSuccess: () => {
                messageForm.reset();
                scrollToBottom();
            }
        });
    }
};

const scrollToBottom = () => {
    nextTick(() => {
        if (chatContainer.value) {
            chatContainer.value.scrollTop = chatContainer.value.scrollHeight;
        }
    });
};

const formatTime = (timestamp) => {
    return new Date(timestamp).toLocaleTimeString('sq-AL', {
        hour: '2-digit',
        minute: '2-digit'
    });
};

const getConfidenceColor = (confidence) => {
    if (confidence >= 0.8) return 'text-green-600';
    if (confidence >= 0.6) return 'text-yellow-600';
    return 'text-red-600';
};

onMounted(() => {
    scrollToBottom();
});
</script>
