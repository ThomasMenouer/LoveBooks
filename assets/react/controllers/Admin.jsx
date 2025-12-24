import React from 'react';
import { HydraAdmin, fetchHydra } from '@api-platform/admin';

const fetchWithCredentials = (url, options = {}) =>
    fetchHydra(url, {
        ...options,
        credentials: 'include',
    });

export default function Admin() {
    return <HydraAdmin entrypoint="/api" fetchHydra={fetchWithCredentials} />;
}