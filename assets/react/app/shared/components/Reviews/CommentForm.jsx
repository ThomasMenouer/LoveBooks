import React, { useState } from 'react';

export default function CommentForm({ reviewId, onCommentAdded }) {
  const [content, setContent] = useState('');
  const [isSubmitting, setIsSubmitting] = useState(false);

  const handleSubmit = (e) => {
    e.preventDefault();
    if (!content.trim()) return;

    setIsSubmitting(true);

    fetch(`/api/review/${reviewId}/comment/create`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ content }),
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error("Erreur lors de l'envoi");
        }
        return response.json();
      })
      .then(() => {
        setContent('');
        setIsSubmitting(false);
        if (onCommentAdded) onCommentAdded(); // ici le refresh
      })
      .catch(() => {
        alert('Erreur lors de l\'ajout du commentaire');
        setIsSubmitting(false);
      });
  };

  return (
    <form onSubmit={handleSubmit} className="row row-cols-auto my-3">
      <div className="col-md-10">
        <textarea
          className="form-control"
          rows="1"
          value={content}
          onChange={(e) => setContent(e.target.value)}
          placeholder="Ajouter un commentaire..."
        />
      </div>

      <div className="col-md-2">
        <button type="submit" className="btn btn-outline-custom-blue" disabled={isSubmitting}>
          {isSubmitting ? "Envoi..." : "Publier"}
        </button>

      </div>

    </form>
  );
}
