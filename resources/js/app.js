import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
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
});
