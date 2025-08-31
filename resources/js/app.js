import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

// Simple route helper
function route(name, params = {}) {
    const routes = window.Ziggy?.routes || {};
    const route = routes[name];
    if (!route) {
        console.warn(`Route "${name}" not found`);
        return '#';
    }
    
    let url = route.uri;
    
    // Replace parameters in the URL
    Object.keys(params).forEach(key => {
        url = url.replace(`{${key}}`, params[key]);
    });
    
    // Remove any remaining parameters
    url = url.replace(/\{[^}]+\}/g, '');
    
    const baseUrl = window.Ziggy?.url || '';
    return baseUrl + '/' + url.replace(/^\/+/, '');
}

// Make route available globally
window.route = route;

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });
        
        // Make route available in Vue components
        app.config.globalProperties.route = route;
        
        return app
            .use(plugin)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
