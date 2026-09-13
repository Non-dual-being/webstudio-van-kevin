import { createInertiaApp } from '@inertiajs/vue3';
import { initializeTheme } from '@/composables/useAppearance';
import AppLayout from '@/layouts/AppLayout.vue';
import AuthLayout from '@/layouts/AuthLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { initializeFlashToast } from '@/lib/flashToast';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

const publicPages = [
    'Home',
    'Work/Index',
    'Work/Show',
    'Services/Index',
    'Services/Websites',
    'Services/Dashboards',
    'Services/Webshops',
    'About',
    'Contact',
    'Privacy',
];

createInertiaApp({
    title: (title, page) => {
        const siteName = publicPages.includes(page.component)
            ? 'Kevin Webstudio'
            : appName;

        return title ? `${title} - ${siteName}` : siteName;
    },
    layout: (name) => {
        switch (true) {
            case publicPages.includes(name):
                return null;
            case name.startsWith('auth/'):
                return AuthLayout;
            case name.startsWith('settings/'):
            case name.startsWith('teams/'):
                return [AppLayout, SettingsLayout];
            default:
                return AppLayout;
        }
    },
    progress: {
        color: '#4B5563',
    },
});

// This will set light / dark mode on page load...
initializeTheme();

// This will listen for flash toast data from the server...
initializeFlashToast();
