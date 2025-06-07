import React from "react";
import { useState, useEffect } from "react";
import { IconSearch } from "@tabler/icons-react";
import { BookSearchResults } from "./BookSearchResults";

export default function BookSearch({ apiUrl, searchBookUrl, addBookUrl }) {
  const [query, setQuery] = useState("");
  const [results, setResults] = useState([]);

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
        <BookSearchResults results={results} addBookUrl={addBookUrl} searchBookUrl={searchBookUrl} query={query} />
      )}
    </div>
  );
}
