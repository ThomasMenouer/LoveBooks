import React, { useState, useEffect } from "react";

export default function ReviewModal({ 
    mode, // "create" ou "edit"
    bookId = null, 
    reviewId = null, 
    initialContent = "", 
    onSuccess 
}) {
  const [content, setContent] = useState(initialContent);
  const modalId = mode === "create" ? "createReviewModal" : "editReviewModal";

  useEffect(() => {
    setContent(initialContent);
  }, [initialContent]);

  const handleSubmit = (e) => {
    e.preventDefault();

    let url = "";
    let method = "POST";
    let body = {};

    if (mode === "create") {
      url = "/api/review/create";
      body = { content, bookId };
    } else if (mode === "edit") {
      url = `/api/review/${reviewId}/edit`;
      body = { content };
    }

    fetch(url, {
      method: method,
      credentials: "include",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(body),
    })
      .then((res) => {
        if (!res.ok) throw new Error("Erreur serveur");
        return res.json();
      })
      .then((data) => {
        if (onSuccess) onSuccess();
        const modalEl = document.getElementById(modalId);
        const modal = bootstrap.Modal.getInstance(modalEl);
        modal.hide();
      })
      .catch((err) => {
        alert("Erreur lors de la soumission de la review.");
        console.error(err);
      });
  };

  return (
    <div
      className="modal fade"
      id={modalId}
      tabIndex="-1"
      aria-labelledby={`${modalId}Label`}
      aria-hidden="true"
    >
      <div className="modal-dialog modal-dialog-centered">
        <div className="modal-content bg-color-black">
          <div className="modal-header">
            <h5 className="modal-title fs-5" id={`${modalId}Label`}>
              {mode === "create" ? "Créer votre review" : "Modifier votre review"}
            </h5>
            <button
              type="button"
              className="btn-close btn-close-white"
              data-bs-dismiss="modal"
              aria-label="Fermer"
            ></button>
          </div>
          <form onSubmit={handleSubmit}>
            <div className="modal-body">
              <div className="mb-3">
                <label className="form-label">Contenu</label>
                <textarea
                  className="form-control bg-color-black text-color-white"
                  placeholder="Écrivez votre critique ici..."
                  value={content}
                  onChange={(e) => setContent(e.target.value)}
                  required
                ></textarea>
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
              <button type="submit" className="btn btn-outline-custom">
                {mode === "create" ? "Créer" : "Modifier"}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  );
}
