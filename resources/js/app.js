import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { Ziggy } from './ziggy.js';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

// Create a simple route helper function
function route(name, params = {}) {
    const route = Ziggy.routes[name];
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
    
    return Ziggy.url + '/' + url.replace(/^\/+/, '');
}

// Create a Vue plugin for route helper
const RoutePlugin = {
    install(app) {
        app.config.globalProperties.route = route;
        app.provide('route', route);
    }
};

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(RoutePlugin)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
        showSpinner: true,
    },
    beforeRender: (page) => {
        // Global error handling
        if (page.props.errors && Object.keys(page.props.errors).length > 0) {
            console.error('Form errors:', page.props.errors);
        }
        
        // Global flash message handling
        if (page.props.flash) {
            const { success, error, warning, info } = page.props.flash;
            
            if (success) {
                // You can integrate with a toast library here
                console.log('Success:', success);
            }
            
            if (error) {
                console.error('Error:', error);
            }
            
            if (warning) {
                console.warn('Warning:', warning);
            }
            
            if (info) {
                console.info('Info:', info);
            }
        }
    },
    onError: (errors) => {
        console.error('Inertia errors:', errors);
    },
});
