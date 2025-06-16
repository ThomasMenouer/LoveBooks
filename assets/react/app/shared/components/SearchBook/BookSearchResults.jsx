import React, { useState } from "react";
import Alert from "../Alert/Alert";

export const BookSearchResults = ({
  results,
  addBookUrl,
  searchBookUrl,
  query,
}) => {
  const [alert, setAlert] = useState({ message: "", type: "info" });
  const maxResults = 5;
  const limitedResults = results.slice(0, maxResults);

  const handleAddBook = (info) => {
    const bookData = {
      title: info.title ?? null,
      authors: info.authors?.[0] ?? null,
      publisher: info.publisher ?? null,
      description: info.description ?? null,
      pageCount: info.pageCount > 0 ? info.pageCount : null,
      publishedDate: info.publishedDate ?? null,
      thumbnail: info.imageLinks?.thumbnail ?? null,
    };

    fetch(addBookUrl, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(bookData),
      credentials: "include",
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          setAlert({
            message: data.message || "Livre ajouté !",
            type: "success",
          });
        } else {
          setAlert({
            message: data.error || "Erreur inconnue",
            type: "danger",
          });
        }
      })
      .catch((err) => console.error(err));
  };

  return (
      <ul className="list-group position-absolute mt-1 z-3 shadow">
        <Alert
          message={alert.message}
          type={alert.type}
          onClose={() => setAlert({ message: "", type: "info" })}
        />
        {limitedResults.map((book, index) => {
          const info = book.volumeInfo || {};

          return (
            
            <li
              key={index}
              className="list-group-item d-flex align-items-start gap-3"
            >
              {info.imageLinks?.thumbnail && (
                <img
                  src={info.imageLinks.thumbnail}
                  alt={info.title}
                  style={{ width: "60px" }}
                />
              )}
              <div className="flex-grow-1">
                <h6 className="mb-1">{info.title ?? "Titre inconnu"}</h6>
                {info.authors && <p className="mb-1">par {info.authors[0]}</p>}
                <button
                  className="btn btn-outline-custom btn-sm mt-1"
                  onClick={(e) => {
                    e.preventDefault();
                    handleAddBook(info);
                  }}
                >
                  Ajouter le livre
                </button>
              </div>
            </li>
          );
        })}
        {results.length >= maxResults && (
          <li className="list-group-item text-center">
            <a
              href={`${searchBookUrl}?title=${encodeURIComponent(query)}`}
              className="text-color-white text-decoration-none"
            >
              Voir plus de résultats...
            </a>
          </li>
        )}
      </ul>
  );
};
