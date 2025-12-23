import React, { useState } from "react";
import Alert from "../../shared/components/Alert/Alert";

export default function BookSearchResultsPage({ results, addBookUrl }) {
  const [alert, setAlert] = useState({ message: "", type: "info" });

  console.log(results);

  return (
    <>
      <Alert
        message={alert.message}
        type={alert.type}
        onClose={() => setAlert({ message: "", type: "info" })}
      />

      
    <div className="row row-cols-1 row-cols-2 row-cols-sm-1 row-cols-sm-4 g-4">

      {results.map((b, idx) => {
        const info = b.volumeInfo || {};
        return (
          <div className="col" key={idx}>
            <div className="card">
              {info.imageLinks?.thumbnail && (
                <img
                  src={info.imageLinks.thumbnail}
                  className="card-img-top"
                  alt={info.title}
                />
              )}
              <div className="card-body">
                <h5 className="card-title">{info.title}</h5>
                <button
                  className="btn btn-outline-custom"
                  onClick={() => {
                    const bookData = {
                      book: {
                        title: info.title ?? null,
                        authors: info.authors?.[0] ?? null,
                        publisher: info.publisher ?? null,
                        description: info.description ?? null,
                        pageCount: info.pageCount > 0 ? info.pageCount : null,
                        publishedDate: info.publishedDate ?? null,
                        thumbnail: info.imageLinks?.thumbnail ?? null,
                      }
                    };

                    fetch(addBookUrl, {
                      method: "POST",
                      headers: { "Content-Type": "application/ld+json" },
                      body: JSON.stringify(bookData),
                      credentials: "include",
                    })
                      .then(async (res) => {
                        const data = await res.json();

                        if (res.ok) {
                          setAlert({
                            message: "Livre ajouté !",
                            type: "success",
                          });
                        } else if (data.violations) {
                          const messages = data.violations.map((v) => v.message).join(", ");
                          setAlert({
                            message: messages,
                            type: "danger",
                          });
                        } else {
                          setAlert({
                            message: data["hydra:description"] || "Erreur lors de l'ajout",
                            type: "danger",
                          });
                        }
                      })
                      .catch((err) => {
                        console.error(err);
                        setAlert({
                          message: "Erreur de connexion",
                          type: "danger",
                        });
                      });
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
  </>
  );
}
