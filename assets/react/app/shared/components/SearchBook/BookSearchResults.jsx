import React from "react";

export const BookSearchResults = ({ results, addBookUrl, searchBookUrl, query }) => {
  const maxResults = 5;
  const limitedResults = results.slice(0, maxResults);

  console.log(results);

  return (
    <ul className="list-group position-absolute mt-1 z-3 shadow">
      {limitedResults.map((book, index) => {
        const info = book.volumeInfo || {};

        return (
          <li
            key={index}
            className="list-group-item d-flex align-items-start gap-3"
          >
            {info.imageLinks.thumbnail && (
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

                  const bookData = {
                    title: info.title,
                    authors: info.authors?.[0],
                    publisher: info.publisher,
                    description: info.description,
                    pageCount: info.pageCount,
                    publishedDate: info.publishedDate,
                    thumbnail: info.imageLinks?.thumbnail,
                  };

                  fetch(addBookUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(bookData),
                    credentials: 'include',
                  })
                  .then(res => res.json())
                  .then(data => {
                    window.location.href = `/library/home`;
                  });
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
          <a href={`${searchBookUrl}?title=${encodeURIComponent(
              query
            )}`}
            className="text-color-white text-decoration-none">
            Voir plus de résultats...
          </a>
        </li>
      )}
    </ul>
  );
};
