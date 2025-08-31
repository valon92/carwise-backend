<script setup>
import { Link } from '@inertiajs/vue3';
import { 
    TruckIcon,
    ChatBubbleLeftRightIcon,
    ChartBarIcon,
    EnvelopeIcon,
    PhoneIcon,
    MapPinIcon
} from '@heroicons/vue/24/outline';
</script>

<template>
    <footer class="bg-gray-900">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <!-- Main footer content -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Company info -->
                <div class="col-span-1 lg:col-span-2">
                    <div class="flex items-center">
                        <div class="h-10 w-10 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                            <span class="text-white font-bold text-xl">🚗</span>
                        </div>
                        <span class="ml-3 text-2xl font-bold text-white">CarWise AI</span>
                    </div>
                    <p class="mt-4 text-gray-400 max-w-md">
                        Sistemi modern për menaxhimin e raporteve të automjeteve me integrim AI. 
                        Menaxho automjetet e tua me teknologji të fundit dhe rekomandime inteligjente.
                    </p>
                    
                    <!-- Social links -->
                    <div class="mt-6 flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <span class="sr-only">Facebook</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <span class="sr-only">Instagram</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <span class="sr-only">Twitter</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <span class="sr-only">LinkedIn</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Product links -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">Produkti</h3>
                    <ul class="mt-4 space-y-4">
                        <li>
                            <Link href="#" class="text-base text-gray-300 hover:text-white transition-colors flex items-center">
                                <TruckIcon class="h-4 w-4 mr-2" />
                                Menaxhim Automjetesh
                            </Link>
                        </li>
                        <li>
                            <Link href="#" class="text-base text-gray-300 hover:text-white transition-colors flex items-center">
                                <ChatBubbleLeftRightIcon class="h-4 w-4 mr-2" />
                                AI Assistant
                            </Link>
                        </li>
                        <li>
                            <Link href="#" class="text-base text-gray-300 hover:text-white transition-colors flex items-center">
                                <ChartBarIcon class="h-4 w-4 mr-2" />
                                Analitika
                            </Link>
                        </li>
                        <li>
                            <Link href="#" class="text-base text-gray-300 hover:text-white transition-colors">
                                API & Integrimi
                            </Link>
                        </li>
                    </ul>
                </div>

                <!-- Company links -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">Kompania</h3>
                    <ul class="mt-4 space-y-4">
                        <li>
                            <Link href="#" class="text-base text-gray-300 hover:text-white transition-colors">
                                Rreth Nesh
                            </Link>
                        </li>
                        <li>
                            <Link href="#" class="text-base text-gray-300 hover:text-white transition-colors">
                                Kariera
                            </Link>
                        </li>
                        <li>
                            <Link href="#" class="text-base text-gray-300 hover:text-white transition-colors">
                                Blog
                            </Link>
                        </li>
                        <li>
                            <Link href="#" class="text-base text-gray-300 hover:text-white transition-colors">
                                Press
                            </Link>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Contact info -->
            <div class="mt-12 pt-8 border-t border-gray-800">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="flex items-center">
                        <EnvelopeIcon class="h-5 w-5 text-gray-400 mr-3" />
                        <span class="text-gray-300">info@carwise.ai</span>
                    </div>
                    <div class="flex items-center">
                        <PhoneIcon class="h-5 w-5 text-gray-400 mr-3" />
                        <span class="text-gray-300">+355 44 123 456</span>
                    </div>
                    <div class="flex items-center">
                        <MapPinIcon class="h-5 w-5 text-gray-400 mr-3" />
                        <span class="text-gray-300">Tirana, Shqipëri</span>
                    </div>
                </div>
            </div>

            <!-- Bottom footer -->
            <div class="mt-8 pt-8 border-t border-gray-800">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <p class="text-base text-gray-400">
                        © 2024 CarWise AI. Të gjitha të drejtat e rezervuara.
                    </p>
                    <div class="mt-4 md:mt-0 flex space-x-6">
                        <Link href="#" class="text-sm text-gray-400 hover:text-white transition-colors">
                            Politika e Privatësisë
                        </Link>
                        <Link href="#" class="text-sm text-gray-400 hover:text-white transition-colors">
                            Kushtet e Përdorimit
                        </Link>
                        <Link href="#" class="text-sm text-gray-400 hover:text-white transition-colors">
                            Cookies
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</template>
