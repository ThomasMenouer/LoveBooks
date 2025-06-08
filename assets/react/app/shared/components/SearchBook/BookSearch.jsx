import React, { useState, useEffect } from "react";
import { IconSearch } from "@tabler/icons-react";
import { BookSearchResults } from "./BookSearchResults";

export default function BookSearch({ apiUrl, searchBookUrl, addBookUrl, currentPath, onQueryChange }) {
  const [query, setQuery] = useState("");
  const [results, setResults] = useState([]);

  useEffect(() => {
    if (!query.trim()) {
      setResults([]);
      return;
    }

    const timeout = setTimeout(() => {
      fetchData(query);
    }, 300);

    return () => clearTimeout(timeout);
  }, [query]);

  const fetchData = async (q) => {
    try {
      const response = await fetch(`${apiUrl}?q=${encodeURIComponent(q)}`);
      const data = await response.json();
      setResults(data.items || []);
    } catch (err) {
      console.error("Erreur lors de la recherche :", err);
      setResults([]);
    }
  };

  const handleSubmit = (e) => {
    e.preventDefault(); // empêcher le rechargement
    if (!query.trim()) return;

    if (currentPath === searchBookUrl) {
      // On est déjà sur /search/, donc recherche dynamique
      onQueryChange(query);
    } else {
      // Sinon, redirection vers /search/?title=...
      window.location.href = `${searchBookUrl}?title=${encodeURIComponent(query)}`;
    }
  };

  return (
    <div>
      <form className="d-flex" onSubmit={handleSubmit}>
        <input
          id="search-book"
          className="form-control me-2"
          type="search"
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

      {/* Résultats dynamiques uniquement si pas sur la page de résultats */}
      {currentPath !== searchBookUrl && results.length > 0 && (
        <BookSearchResults
          results={results}
          addBookUrl={addBookUrl}
          searchBookUrl={searchBookUrl}
          query={query}
        />
      )}
    </div>
  );
}
