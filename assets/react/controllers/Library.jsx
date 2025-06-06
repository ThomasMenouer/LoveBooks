import { createRoot } from 'react-dom/client';
import LibraryNav from '../app/features/library/components/LibraryNav';

export default (el) => {
    const root = createRoot(el);
    root.render(<LibraryNav />);
};
