import React, { useEffect, useState } from "react";
import UserBooksReadingUpdateForm from "./UserBooksReadingUpdateForm";

export default function CurrentlyReading({userId, isOwnProfile}) {
  const [books, setBooks] = useState([]);

  const fetchBooks = () => {
    fetch(`/api/reading-list/${userId}`, {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
      },
      credentials: "include",
    })
      .then((res) => res.json())
      .then((data) => setBooks(data));
  };

  useEffect(() => {
    fetchBooks();
  }, []);

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
          <div className="card border-0 mb-3 w-50 mx-auto" key={item.id}>
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

                  {isOwnProfile && (
                    <div className="text-center my-3">
                      <button
                        type="button"
                        className="btn btn-outline-custom-blue text-center"
                        data-bs-toggle="modal"
                        data-bs-target={`#ReadingListModal-${bookId}`}
                      >
                        Mettre à jour la progression
                      </button>

                      <div
                        className="modal fade"
                        id={`ReadingListModal-${bookId}`}
                        tabIndex="-1"
                        aria-labelledby="ReadingListModalLabel"
                        aria-hidden="true"
                      >
                        <div className="modal-dialog modal-dialog-centered">
                          <div className="modal-content bg-color-black">
                            <div className="modal-header">
                              <h1
                                className="modal-title fs-5 text-color-white"
                                id="ReadingListModalLabel"
                              >
                                {title}
                              </h1>
                              <button
                                type="button"
                                data-bs-theme="dark"
                                className="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Close"
                              ></button>
                            </div>
                            <div className="modal-body">
                              <UserBooksReadingUpdateForm
                                userBook={item}
                                onUpdated={fetchBooks}
                              />
                            </div>
                            <div className="modal-footer">
                              <button
                                type="button"
                                className="btn btn-outline-custom-red"
                                data-bs-dismiss="modal"
                              >
                                Fermer
                              </button>
                              <button
                                type="button"
                                className="btn btn-outline-custom"
                              >
                                Enregistrer
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  )}
                </div>
              </div>
            </div>
          </div>
        );
      })}
    </div>
  );
}
