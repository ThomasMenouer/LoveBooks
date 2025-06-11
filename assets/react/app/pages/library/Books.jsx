import React, { useEffect, useState } from "react";

export default function Books({ userId }) {
  const [books, setBooks] = useState([]);
  const [query, setQuery] = useState("");

  useEffect(() => {
      fetch(`/api/books`, {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
      },
      credentials: "include", 
    })
      .then((res) => res.json())
      .then((data) => setBooks(data));
  }, []);

    const handleDelete = (id) => {
    if (!window.confirm("Voulez-vous vraiment supprimer ce livre ?")) return;

    fetch(`/api/user-books/delete/${id}`, {
      method: "DELETE",
      credentials: "include",
    })
    .then(res => {
      if (res.ok) {
        // On met à jour l'état local sans refetcher
        setBooks(prevBooks => prevBooks.filter(book => book.id !== id));
      } else {
        alert("Une erreur est survenue.");
      }
    })
    .catch(err => {
      console.error(err);
      alert("Erreur réseau.");
    });
  };

  return (
    <div>
      <h3 className="text-center mb-4">Mes livres</h3>

      <div className="row justify-content-center mb-3">
        <div className="col-auto col-sm-auto">
          <input
            type="text"
            className="form-control"
            placeholder="Rechercher un livre..."
            value={query}
            onChange={e => setQuery(e.target.value)}
          />

        </div>

      </div>

      <div className="table-responsive" style={{ maxHeight: "60vh", overflowY: "auto" }}>
        <table className="table table-hover text-center">
          <thead className="sticky-top">
            <tr>
              <th></th>
              <th>Titre</th>
              <th>Auteur</th>
              <th>Note</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            {books.map(userBook => (
              <tr key={userBook.id}>
                <td>
                  <img src={userBook.book.thumbnail} className="img rounded" alt={userBook.book.title} width="120" />
                </td>
                <td>{userBook.book.title}</td>
                <td>{userBook.book.authors}</td>
                <td>
                  {userBook.rating ? (
                    <div>
                      {Array.from({ length: 5 }, (_, i) => i + 1).map(i =>
                        i <= userBook.rating ? "★" : "☆"
                      )}
                    </div>
                  ) : (
                    "Aucune note"
                  )}
                </td>
                <td>{userBook.status}</td>
                <td>
                    <button className="btn btn-outline-custom-red btn-sm" onClick={() => handleDelete(userBook.id)}>
                    Supprimer
                  </button></td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
}
