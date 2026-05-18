import React from 'react';
import { createRoot } from 'react-dom/client';
import SearchForm from './components/SearchForm';
import TripList from './components/TripList';
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
    renderRoot(searchFormEl, <SearchForm />);

    const tripListEl = document.getElementById('react-trip-list');
    renderRoot(tripListEl, <TripList />);
};

// DOM Content Loaded
document.addEventListener('DOMContentLoaded', () => {
    initReactApps();
    initHomePage();
});
