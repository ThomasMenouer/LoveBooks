import React from "react";

export default function BookSearchResultsPage({ results, addBookUrl }) {

  console.log(results);
  return (
    <div className="row row-cols-1 row-cols-2 row-cols-sm-1 row-cols-sm-4 g-4">
      {results.map((b, idx) => {
        const info = b.volumeInfo || {};
        return (
          <div className="col" key={idx}>
            <div className="card">
              {info.imageLinks?.thumbnail && (
                <img src={info.imageLinks.thumbnail} className="card-img-top" alt="" />
              )}
              <div className="card-body">
                <h5 className="card-title">{info.title}</h5>
                <button
                  className="btn btn-outline-custom"
                  onClick={() => {
                    const bookData = {
                        title: info.title || "",
                        authors: info.authors[0] || "",
                        publisher: info.publisher || "",
                        description: info.description || "",
                        pageCount: info.pageCount || 0,
                        publishedDate: info.publishedDate || "",
                        thumbnail: info.imageLinks.thumbnail || ""
                    };

                    fetch(addBookUrl, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(bookData),
                        credentials: 'include',
                    })
                    .then(res => res.json())
                    .then(data => {
                        window.location.href = '/library/home';
                    })
                    .catch(err => console.error(err));
                  }}
                >
                  Ajouter le livre
                </button>
              </div>
            </div>
          </div>
        );
      })}
    </div>
  );
}
