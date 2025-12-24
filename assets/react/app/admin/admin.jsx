  import React from 'react';
  import { createRoot } from 'react-dom/client';
  import { HydraAdmin } from '@api-platform/admin';

  const Admin = () => (
      <HydraAdmin entrypoint="/api" />
  );

  const container = document.getElementById('admin-root');
  if (container) {
      createRoot(container).render(<Admin />);
  }