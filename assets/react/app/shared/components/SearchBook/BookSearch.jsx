import React from "react";
import { useState, useEffect } from "react";
import { IconSearch } from "@tabler/icons-react";

export default function BookSearch({ apiUrl, searchBookUrl, addBookUrl }) {
  const [query, setQuery] = useState("");
  const [results, setResults] = useState([]);
  const [isSubmitting, setIsSubmitting] = useState(false);

  useEffect(() => {
    if (query.trim() === "") {
      setResults([]);
      return;
    }

    const cleanup = handleSearch(query);
    return cleanup;
  }, [query]);

  const fetchData = async (query) => {
    try {
      const response = await fetch(`${apiUrl}?q=${encodeURIComponent(query)}`);
      const data = await response.json();
      setResults(data.items || []);
    } catch (err) {
      console.error("Erreur lors de la recherche :", err);
      setResults([]);
    }
  };

  const handleSearch = (query) => {
    const delay = 300;
    const timeout = setTimeout(() => {
      fetchData(query);
    }, delay);

    return () => clearTimeout(timeout);
  };

  return (

    <div>
      <form className="d-flex" action={searchBookUrl} method="GET">
        <input
          className="form-control me-2"
          type="search"
          name="title"
          value={query}
          placeholder="Rechercher un livre"
          aria-label="Search"
          autoComplete="off"
          onChange={(e) => setQuery(e.target.value)}
        />
        <button className="btn btn-outline-custom" type="submit">
          <IconSearch size={20} />
        </button>
      </form>

      

      {results.length > 0 && (
        <ul className="list-group position-absolute w-100 mt-1 z-3 shadow">
          {results.map((book, index) => {
            const info = book.volumeInfo || {};
            return (

              <li key={index} className="list-group-item d-flex align-items-start gap-3">
                {info.imageLinks?.thumbnail && (
                  <img src={info.imageLinks.thumbnail} alt={info.title} style={{ width: "60px" }}/>
                )}
                <div className="flex-grow-1">
                  <h6 className="mb-1">{info.title ?? "Titre inconnu"}</h6>
                  
                  {info.authors && (
                    <p className="mb-1">par {info.authors[0]}</p>
                  )}
                  <a className="btn btn-outline-custom btn-sm mt-1" 
                    
                    href={`${addBookUrl}?title=${encodeURIComponent(
                      info.title ?? "Titre inconnu"
                    )}&authors=${encodeURIComponent(
                      info.authors?.[0] ?? "Auteur inconnu"
                    )}&publisher=${encodeURIComponent(
                      info.publisher ?? "Éditeur inconnue"
                    )}&description=${encodeURIComponent(
                      info.description ?? "Pas de description"
                    )}&publishedDate=${encodeURIComponent(
                      info.publishedDate ?? "Date de publication inconnue"
                    )}&pageCount=${encodeURIComponent(
                      info.pageCount ?? "Nombre de pages inconnu"
                    )}&thumbnail=${encodeURIComponent(
                      info.imageLinks?.thumbnail ?? "Pas d\'image"
                    )}`}
                    onClick={(e) => alert(`You selected ${results}!`)}
                    disabled={isSubmitting}
                  >
                    Ajouter le livre
                  </a>
                </div>
              </li>
            );
          })}
        </ul>
      )}
    </div>
  );
}
