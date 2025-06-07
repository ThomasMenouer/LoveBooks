import React from "react";

export default function BookSearchResults({ book }) {
  const info = book.volumeInfo || {};

  const addUrl = `/search/add_book?${new URLSearchParams({
    title: info.title || "Titre inconnu",
    authors: info.authors?.[0] || "Auteur inconnu",
    publisher: info.publisher || "Éditeur inconnu",
    description: info.description || "Pas de description",
    publishedDate: info.publishedDate || "Date de publication inconnue",
    pageCount: info.pageCount?.toString() || "Nombre de pages inconnu",
    thumbnail: info.imageLinks?.thumbnail || "Pas d'image",
  }).toString()}`;

  return (
    <li className="list-group-item">
      <div className="d-flex align-items-start gap-3">
        {info.imageLinks?.thumbnail && (
          <img
            src={info.imageLinks.thumbnail}
            alt={info.title}
            style={{ width: "60px", height: "auto" }}
          />
        )}

        <div className="flex-grow-1">
          <h6 className="mb-1">{info.title || "Titre inconnu"}</h6>
          {info.authors && (
            <p className="mb-1">par {info.authors[0]}</p>
          )}
          <a
            href={addUrl}
            className="btn btn-outline-custom btn-sm mt-1"
          >
            Ajouter le livre
          </a>
        </div>
      </div>
    </li>
  );
}
