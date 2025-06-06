import React, { useState } from 'react';

// Simples composants pour demo, tu peux les remplacer par tes vrais composants
function Home() {
    return <div>Contenu Home</div>;
}

function Books() {
    return <div>Contenu Mes livres</div>;
}

export default function LibraryNav() {
    const [activeTab, setActiveTab] = useState('home');

    return (
        <main>
            <div className="container">
                <div className="row my-4">
                    <ul className="nav nav-underline justify-content-center">
                        <li className="nav-item">
                            <button
                                className={`nav-link ${activeTab === 'home' ? 'active' : ''}`}
                                onClick={() => setActiveTab('home')}
                            >
                                Home
                            </button>
                        </li>

                        <li className="nav-item">
                            <button
                                className={`nav-link ${activeTab === 'books' ? 'active' : ''}`}
                                onClick={() => setActiveTab('books')}
                            >
                                Mes livres
                            </button>
                        </li>
                    </ul>
                </div>
                <div className="row">
                    <div id="library_content" className="col-12">
                        {activeTab === 'home' && <Home />}
                        {activeTab === 'books' && <Books />}
                    </div>
                </div>
            </div>
        </main>
    );
}
