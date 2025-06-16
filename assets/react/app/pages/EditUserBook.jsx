import React, { useState, useEffect } from "react";
import { IconStar, IconStarFilled } from "@tabler/icons-react";

const statuses = [
  { value: "Lu", label: "Lu" },
  { value: "En cours de lecture", label: "En cours de lecture" },
  { value: "Abandonné", label: "Abandonné" },
  { value: "Non lu", label: "Non lu" },
];

export default function EditUserBook({ userBookId, onClose }) {
  const [status, setStatus] = useState("");
  const [pagesRead, setPagesRead] = useState(0);
  const [isPreferred, setIsPreferred] = useState(false);
  const [userRating, setUserRating] = useState(0);
  const [hoverRating, setHoverRating] = useState(0);
  const [loading, setLoading] = useState(true);
  const [alert, setAlert] = useState(null);

  useEffect(() => {
    // Charge les données initiales via API
    fetch(`/api/user-books/${userBookId}`)
      .then((res) => res.json())
      .then((data) => {
        setStatus(data.status);
        setPagesRead(data.pagesRead);
        setIsPreferred(data.isPreferred);
        setUserRating(data.userRating || 0);
      })
      .catch(() =>
        setAlert({ type: "danger", message: "Erreur de chargement" })
      )
      .finally(() => setLoading(false));
  }, [userBookId]);

  const handleSubmit = (e) => {
    e.preventDefault();
    setAlert(null);

    fetch(`/api/user-books/${userBookId}/edit`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ status, pagesRead, isPreferred, userRating }),
      credentials: "include",
    })
      .then((res) => {
        if (!res.ok) throw new Error("Erreur serveur");
        return res.json();
      })
      .then(() => {
        setAlert({ type: "success", message: "Livre modifié avec succès" });

        
      setTimeout(() => {
        window.location.reload();
      }, 500);
      })
      .catch(() =>
        setAlert({ type: "danger", message: "Erreur lors de la modification" })
      );
  };

  if (loading) return <p>Chargement...</p>;

  return (
    <form onSubmit={handleSubmit}>
      <div className="modal-body">
        {alert && (
          <div className={`alert alert-${alert.type}`} role="alert">
            {alert.message}
          </div>
        )}
        {/* Status */}
        <div className="mb-3">
          <label className="form-label">Statut</label>
          <select
            className="form-select"
            value={status}
            onChange={(e) => setStatus(e.target.value)}
            required
          >
            <option value="">-- Choisir un statut --</option>
            {statuses.map(({ value, label }) => (
              <option key={value} value={value}>
                {label}
              </option>
            ))}
          </select>
        </div>

        {/* Pages lues */}
        <div className="mb-3">
          <label className="form-label">Pages lues</label>
          <input
            type="number"
            min="0"
            className="form-control"
            value={pagesRead}
            onChange={(e) => setPagesRead(Number(e.target.value))}
          />
        </div>

        {/* Livre préféré */}
        <fieldset className="mb-3">
          <legend className="form-label">Livre préféré</legend>
          <div className="form-check form-check-inline">
            <input
              type="radio"
              id="preferred-yes"
              name="isPreferred"
              className="form-check-input"
              checked={isPreferred === true}
              onChange={() => setIsPreferred(true)}
            />
            <label htmlFor="preferred-yes" className="form-check-label">
              Oui
            </label>
          </div>
          <div className="form-check form-check-inline">
            <input
              type="radio"
              id="preferred-no"
              name="isPreferred"
              className="form-check-input"
              checked={isPreferred === false}
              onChange={() => setIsPreferred(false)}
            />
            <label htmlFor="preferred-no" className="form-check-label">
              Non
            </label>
          </div>
        </fieldset>

        {/* Note utilisateur (stars) */}
        <div className="mb-3 text-center">
          <label className="form-label d-block">Votre note</label>
          {[1, 2, 3, 4, 5].map((i) => {
            const filled = i <= (hoverRating || userRating);
            return (
              <span
                key={i}
                style={{ cursor: "pointer", marginRight: 5 }}
                onClick={() => setUserRating(i)}
                onMouseEnter={() => setHoverRating(i)}
                onMouseLeave={() => setHoverRating(0)}
                aria-label={`${i} étoiles`}
              >
                {filled ? (
                  <IconStarFilled size={24} color="#ffc107" />
                ) : (
                  <IconStar size={20} />
                )}
              </span>
            );
          })}
        </div>


      </div>

      <div className="modal-footer">
        <button
          type="button"
          className="btn btn-outline-custom-red"
          data-bs-dismiss="modal"
        >
          Annuler
        </button>

        <button type="submit" className="btn btn-outline-custom-yellow">
          Modifier
        </button>

      </div>
    </form>
  );
}
