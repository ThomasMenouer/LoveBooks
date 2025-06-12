import React from "react";
import { BrowserRouter, Routes, Route, Link, Navigate } from "react-router-dom";
import Home from "./Home";
import Books from "./Books";

export default function LibraryApp({ userId, isOwnProfile, bookDetailUrl }) {
  return (
    <BrowserRouter basename="/library">
      <div className="row my-4">
        <ul className="nav nav-underline justify-content-center">
          <li className="nav-item">
            <Link className="nav-link" to="/home">Home</Link>
          </li>
          <li className="nav-item">
            <Link className="nav-link" to="/books">Mes livres</Link>
          </li>
        </ul>
      </div>

      <Routes>
        <Route path="/" element={<Navigate to="/home" replace />} />
        <Route path="/home" element={<Home userId={userId} />} />
        <Route path="/books" element={<Books userId={userId} />} />
      </Routes>
    </BrowserRouter>
  );
}
