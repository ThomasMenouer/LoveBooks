import React, { useState, useEffect } from 'react';
import BookSearch from '../../../shared/components/SearchBook/BookSearch';
import BookSearchResultsPage from './BookSearchResultsPage';

export default function BookSearchPage({ apiUrl, searchBookUrl, addBookUrl, currentPath }) {
  const [query, setQuery] = useState('');
  const [results, setResults] = useState([]);
  
  const isSearchPage = searchBookUrl;

  // Charger la valeur initiale depuis l’URL uniquement au chargement
  useEffect(() => {
    const params = new URLSearchParams(window.location.search);
    const title = params.get("title");
    if (title) {
      setQuery(title);
    }
  }, []);

  // Requête API déclenchée quand query change
  useEffect(() => {
    if (!query.trim()) {
      setResults([]);
      return;
    }

    const timeout = setTimeout(async () => {
      try {
        const resp = await fetch(`${apiUrl}?q=${encodeURIComponent(query)}`, 
          {
            method: 'GET',
            credentials: 'include',
            headers: {
              'Content-Type': 'application/json',
            },
          }
        );
        const json = await resp.json();
        setResults(json.items || []);
      } catch {
        setResults([]);
      }
    }, 300);

    return () => clearTimeout(timeout);
  }, [query]);

  return (
    <div className='container mt-5'>

      <div className="row">
        <div className="col-12 text-center mb-3">
          <h3>Rechercher un livre</h3>
        </div>

      </div>

      <div className="row justify-content-center mb-3">

        <div className="col-auto col-sm-auto">

          <BookSearch
            apiUrl={apiUrl}
            searchBookUrl={searchBookUrl}
            onQueryChange={setQuery}
            currentPath={currentPath}
          />
        </div>
      </div>

      {isSearchPage === currentPath && (
        <BookSearchResultsPage
          results={results}
          addBookUrl={addBookUrl}
        />
      )}
    </div>
  );
}
