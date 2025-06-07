import React from "react";

export const BookSearchResults = ({ results, addBookUrl, searchBookUrl, query }) => {
  const maxResults = 5;
  const limitedResults = results.slice(0, maxResults);

  return (
    <ul className="list-group position-absolute w-100 mt-1 z-3 shadow">
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

                  const params = new URLSearchParams({
                    title: info.title ?? "",
                    authors: info.authors?.[0] ?? "",
                    publisher: info.publisher ?? "",
                    description: info.description ?? "",
                    publishedDate: info.publishedDate ?? "",
                    pageCount: info.pageCount ?? "",
                    thumbnail: info.imageLinks?.thumbnail ?? "",
                  });

                  window.location.href = `${addBookUrl}?${params.toString()}`;
                }}
              >
                Ajouter le livre
              </button>
            </div>
          </li>
        );
      })}
      {results.length >= maxResults && (
        <li className="list-group-item ">
          <a
            href={`${searchBookUrl}?title=${encodeURIComponent(
              query
            )}`}
            className="text-color-white"
          >
            Voir plus de résultats...
          </a>
        </li>
      )}
    </ul>
  );
};
