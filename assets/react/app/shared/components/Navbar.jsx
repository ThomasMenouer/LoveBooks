import React, { use, useState } from 'react';

export default function Navbar({ user, libraryUrl, profileUrl, logoutUrl, searchBookUrl, currentPath }) {

    const isActive = (url) => url === currentPath ? 'nav-link active' : 'nav-link';

    return (
        <nav className="navbar fixed-top navbar-expand-lg bg-color-black">
            <div className="container-fluid">
                <span className="navbar-text">
                    <a className="navbar-brand" href="/library">LoveBooks!</a>
                </span>

                <button
                    className="navbar-toggler navbar-dark"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent"
                    aria-expanded="false"
                    aria-label="Toggle navigation"
                >
                    <span className="navbar-toggler-icon"></span>
                </button>

                <div className="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul className="navbar-nav mx-auto mb-2 mb-lg-0">
                        {user ? (
                            <>
                                <li className="nav-item">
                                    <a className={isActive(libraryUrl)} href={libraryUrl}>Bibliothèque</a>
                                </li>
                                <li className="nav-item">
                                    <a className={isActive(searchBookUrl)} href={searchBookUrl}>Recherche un livre</a>
                                </li>
                                <li className="nav-item">
                                    <a className={isActive(profileUrl)} href={profileUrl}>Mon profil</a>
                                </li>
                                <li className="nav-item">
                                    <a className="nav-link" href={logoutUrl}>Déconnexion</a>
                                </li>
                            </>
                        ) : null}
                    </ul>

                    {/* Ici tu peux intégrer un composant React de recherche, ou garder le composant Symfony UX s'il peut s'intégrer dans React */}
                </div>
            </div>
        </nav>
    );
}
