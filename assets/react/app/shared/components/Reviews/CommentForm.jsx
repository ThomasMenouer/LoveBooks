import React, { useState } from 'react';

export default function CommentForm({ reviewId }) {
  const [content, setContent] = useState('');
  const [isSubmitting, setIsSubmitting] = useState(false);

  const handleSubmit = (e) => {
    e.preventDefault();
    if (!content.trim()) return;

    setIsSubmitting(true);

    fetch(`/api/reviews/${reviewId}/comments`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ content }),
    })
      .then(() => {
        setContent('');
        setIsSubmitting(false);
        // On peut aussi déclencher un refresh global des commentaires ici
      })
      .catch(() => {
        alert('Erreur lors de l\'ajout du commentaire');
        setIsSubmitting(false);
      });
  };

  return (
    <form onSubmit={handleSubmit} className="my-3">
      <div className="mb-2">
        <textarea
          className="form-control"
          rows="3"
          value={content}
          onChange={(e) => setContent(e.target.value)}
          placeholder="Ajouter un commentaire..."
        />
      </div>
      <button type="submit" className="btn btn-outline-primary btn-sm" disabled={isSubmitting}>
        {isSubmitting ? "Envoi..." : "Publier"}
      </button>
    </form>
  );
}
