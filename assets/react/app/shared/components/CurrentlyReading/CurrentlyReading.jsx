import React, { useEffect, useState } from "react";

export default function CurrentlyReading() {
  const [books, setBooks] = useState([]);
  const [formVisible, setFormVisible] = useState({}); // stocke l'état de visibilité des formulaires

  useEffect(() => {
    fetch("/profile/api/reading-list", {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
      },
      credentials: "include",
    })
      .then((res) => res.json())
      .then((data) => setBooks(data));
  }, []);

  const toggleForm = (bookId) => {
    setFormVisible((prev) => ({
      ...prev,
      [bookId]: !prev[bookId],
    }));
  };

  const handleSubmit = async (e, bookId) => {
    e.preventDefault();
    const formData = new FormData(e.target);

    await fetch(`/book/${bookId}/update`, {
      method: "POST",
      body: formData,
      credentials: "include",
    });

    // Optionnel : rafraîchir les données après maj
    const updatedBooks = await fetch("/profile/api/reading-list").then((res) =>
      res.json()
    );
    setBooks(updatedBooks);
    setFormVisible((prev) => ({ ...prev, [bookId]: false }));
  };

  if (books.length === 0) {
    return <p className="text-center">Aucun livre en cours de lecture.</p>;
  }

  return (
    <div>
      {books.map((item) => {
        const progress = Math.round(
          (item.pagesRead * 100) / item.book.pageCount
        );
        const { id: bookId, title, authors, thumbnail, pageCount } = item.book;

        return (
          <div className="card mb-3 w-50 mx-auto" key={item.id}>
            <div className="row g-0">
              <div className="col-md-3">
                <img
                  src={thumbnail}
                  alt={title}
                  className="img-fluid rounded-start h-100 w-100"
                />
              </div>
              <div className="col-md-9">
                <div className="card-body">
                  <a href={`/book/books/${bookId}`} className="card-title h5">
                    {title}
                  </a>
                  <p className="card-text">{authors}</p>
                  <p className="card-text text-center">
                    <small className="text-color-gray">
                      {progress}% - {item.pagesRead} / {pageCount}
                    </small>
                  </p>
                  <div className="progress" style={{ height: "3px" }}>
                    <div
                      className="progress-bar"
                      role="progressbar"
                      style={{ width: `${progress}%` }}
                    ></div>
                  </div>

                  {/* BOUTON toggle + FORMULAIRE */}
                  <div className="text-center my-3">
                    <button
                      className="btn btn-outline-primary btn-sm"
                      type="button"
                      onClick={() => toggleForm(bookId)}
                    >
                      Mettre à jour la progression
                    </button>

                    {formVisible[bookId] && (
                      <form
                        onSubmit={(e) => handleSubmit(e, bookId)}
                        className="mt-3 col-9 col-sm-5 mx-auto"
                      >
                        <div className="mb-2">
                          <label htmlFor={`status-${bookId}`}>Statut</label>
                          <select
                            name="status"
                            id={`status-${bookId}`}
                            className="form-select"
                            defaultValue={item.status}
                          >
                            <option value="reading">En cours</option>
                            <option value="finished">Terminé</option>
                            <option value="paused">En pause</option>
                          </select>
                        </div>

                        <div className="mb-2">
                          <label htmlFor={`pagesRead-${bookId}`}>Pages lues</label>
                          <input
                            type="number"
                            name="pagesRead"
                            id={`pagesRead-${bookId}`}
                            className="form-control"
                            min={0}
                            max={pageCount}
                            defaultValue={item.pagesRead}
                          />
                        </div>

                        <button type="submit" className="btn btn-sm btn-primary w-100">
                          Mettre à jour
                        </button>
                      </form>
                    )}
                  </div>
                </div>
              </div>
            </div>
          </div>
        );
      })}
    </div>
  );
}
