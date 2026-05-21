import React from 'react';
import { createRoot } from 'react-dom/client';
import SearchForm from './components/SearchForm';
import TripList from './components/TripList';
import AuthMenu from './components/AuthMenu';
import AuthModal from './components/AuthModal';
import ProfileForm from './components/ProfileForm';
import TicketLookup from './components/TicketLookup';
import { initDailyveDateRangePicker } from './components/DailyveDateRangePicker';
import { initHomePage } from './home';

const roots = new WeakMap();

const renderRoot = (element, component) => {
    if (!element) {
        return;
    }

    const root = roots.get(element) || createRoot(element);
    roots.set(element, root);
    root.render(component);
};

/**
 * Initialize React Components
 */
const initReactApps = () => {
    const searchFormEl = document.getElementById('react-search-form');
    renderRoot(
        searchFormEl,
        <SearchForm initialService={searchFormEl?.dataset.initialService || 'bus'} />,
    );

    const tripListEl = document.getElementById('react-trip-list');
    renderRoot(tripListEl, <TripList />);

    const authMenuEl = document.getElementById('react-auth-menu');
    renderRoot(authMenuEl, <AuthMenu />);

    const authModalEl = document.getElementById('react-auth-modal');
    renderRoot(authModalEl, <AuthModal />);

    const profileEl = document.getElementById('react-profile');
    renderRoot(profileEl, <ProfileForm />);

    const ticketLookupEl = document.getElementById('react-ticket-lookup');
    renderRoot(ticketLookupEl, <TicketLookup />);
};

// DOM Content Loaded
document.addEventListener('DOMContentLoaded', () => {
    initReactApps();
    initDailyveDateRangePicker();
    initHomePage();
});
