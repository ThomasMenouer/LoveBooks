import React from "react";

export default function Profile({ user, totalBooks, bookStats, preferredBooks, currentlyReading }) {
    return (
        <main>
            <div className="container mt-5">
                {/* Avatar */}
                <div className="row g-0 mb-4">
                    <div className="col-3 col-sm-2">
                        {user.avatar ? (
                            <img
                                src={`/uploads/avatars/${user.avatar}`}
                                alt="Avatar"
                                className="img-fluid rounded-circle"
                                style={{ height: "100px", width: "100px", objectFit: "cover" }}
                            />
                        ) : (
                            <p>Pas d’avatar.</p>
                        )}
                    </div>
                    <div className="col-9 col-sm-10">
                        <div className="card">
                            <div className="card-body">
                                <h5 className="card-title">{user.name}</h5>
                                {user.isOwner && (
                                    <a href="/profile/edit/avatar" className="text-color-white">Edit Profile</a>
                                )}
                            </div>
                        </div>
                    </div>
                </div>

                {/* Statistiques */}
                <div className="row mb-4">
                    <h3 className="mb-4 text-center">Statistiques</h3>
                    <div className="col-12 col-sm-6 mx-auto">
                        <div className="card">
                            <div className="card-body d-flex justify-content-between align-items-center">
                                <div className="d-flex align-items-center">
                                    <i className="tabler-icon tabler-icon-books" style={{ width: "2rem", height: "2rem" }}></i>
                                    <span className="ms-2">Nombre de livres :</span>
                                </div>
                                <span className="badge badge border bg-transparent border-white text-color-white">{totalBooks}</span>
                            </div>

                            {bookStats.map(item => (
                                <div
                                    key={item.status.label}
                                    className={`card-body text-${item.status.color} d-flex justify-content-between align-items-center`}
                                >
                                    <div className="d-flex align-items-center">
                                        <i className={`tabler-icon ${getStatusIcon(item.status.label)}`} style={{ width: "2rem", height: "2rem" }}></i>
                                        <span className="ms-2">{item.status.label}</span>
                                    </div>
                                    <span className={`badge border bg-transparent border-${item.status.color} text-${item.status.color}`}>{item.count}</span>
                                </div>
                            ))}
                        </div>
                    </div>
                </div>

                {/* Livres préférés */}
                <div className="row mb-2">
                    <h3 className="mb-4 text-center">Mes livres préférés</h3>
                    <div className="row row-cols-auto justify-content-center">
                        {preferredBooks.length > 0 ? (
                            <>
                                {preferredBooks.slice(0, 3).map(book => (
                                    <div className="col" key={book.book.id}>
                                        <a href={`/book/${book.book.id}`} className="text-decoration-none">
                                            <img
                                                src={book.book.thumbnail}
                                                className="img-fluid rounded mb-2 img-mobile"
                                                style={{ height: "225px", objectFit: "cover" }}
                                                alt={book.book.title}
                                            />
                                        </a>
                                    </div>
                                ))}
                                {preferredBooks.length > 3 && (
                                    <div className="col-12 my-2 text-center">
                                        <a href="/book/preferred" className="btn btn-outline-custom-blue">Voir plus</a>
                                    </div>
                                )}
                            </>
                        ) : (
                            <p className="text-center">Aucun livre préféré.</p>
                        )}
                    </div>
                </div>

                {/* En cours de lecture */}
                <div className="row mb-4">
                    <h3 className="mb-4 text-center">En cours de lecture</h3>
                    <div className="align-items-center">
                        {currentlyReading.length > 0 ? (
                            currentlyReading.map(book => {
                                const progress = Math.round((book.pagesRead * 100) / book.book.pageCount);
                                return (
                                    <div className="col-12" key={book.book.id}>
                                        <div className="card mb-3 w-50 mx-auto">
                                            <div className="row g-0">
                                                <div className="col-md-3">
                                                    <img
                                                        src={book.book.thumbnail}
                                                        className="img-fluid rounded-start h-100 w-100"
                                                        alt={book.book.title}
                                                    />
                                                </div>
                                                <div className="col-md-9">
                                                    <div className="card-body">
                                                        <a href={`/book/${book.book.id}`} className="card-title h5">
                                                            {book.book.title}
                                                        </a>
                                                        <p className="card-text">{book.book.authors}</p>
                                                        <p className="card-text text-center">
                                                            <small className="text-color-gray">
                                                                {progress}% - {book.pagesRead} / {book.book.pageCount}
                                                            </small>
                                                        </p>
                                                        <div className="progress" style={{ height: "3px" }}>
                                                            <div
                                                                className="progress-bar"
                                                                role="progressbar"
                                                                style={{ width: `${progress}%` }}
                                                                aria-valuenow={book.pagesRead}
                                                                aria-valuemin="0"
                                                                aria-valuemax={book.book.pageCount}
                                                            ></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                );
                            })
                        ) : (
                            <p className="text-center">Aucun livre en cours de lecture.</p>
                        )}
                    </div>
                </div>
            </div>
        </main>
    );
}

function getStatusIcon(label) {
    switch (label) {
        case "Non lu":
            return "tabler-icon-books";
        case "En cours de lecture":
            return "tabler-icon-book";
        case "Lu":
            return "tabler-icon-book-2";
        case "Abandonné":
            return "tabler-icon-book-off";
        default:
            return "tabler-icon-book";
    }
}