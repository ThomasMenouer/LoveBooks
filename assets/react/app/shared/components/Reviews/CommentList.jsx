import React, { useEffect, useState } from 'react';

export default function CommentList({ reviewId }) {
  const [comments, setComments] = useState([]);

  useEffect(() => {
    fetch(`/api/reviews/${reviewId}/comments`)
      .then(res => res.json())
      .then(data => setComments(data))
      .catch(err => console.error("Erreur chargement commentaires", err));
  }, [reviewId]);

  if (comments.length === 0) {
    return <p>Aucun commentaire.</p>;
  }

  return (
    <div className="mt-3">
      <h5>Commentaires</h5>
      {comments.map(comment => (
        <div key={comment.id} className="card bg-dark text-white mb-3">
          <div className="card-header d-flex align-items-center">
            {comment.user.avatar && (
              <img
                src={`/uploads/avatars/${comment.user.avatar}`}
                alt="Avatar"
                className="rounded-circle me-2"
                style={{ height: 40, width: 40, objectFit: 'cover' }}
              />
            )}
            <strong>{comment.user.name}</strong>
            <small className="ms-auto">{new Date(comment.createdAt).toLocaleDateString()}</small>
          </div>

          <div className="card-body">
            <p>{comment.content}</p>
          </div>

          <div className="card-footer text-end">
            {comment.user.isCurrentUser && (
              <button
                className="btn btn-sm btn-outline-danger"
                onClick={() => handleDelete(comment.id)}
              >
                Supprimer
              </button>
            )}
          </div>
        </div>
      ))}
    </div>
  );

  function handleDelete(commentId) {
    if (!window.confirm("Supprimer ce commentaire ?")) return;

    fetch(`/api/comments/${commentId}`, {
      method: 'DELETE',
    })
      .then(() => setComments(comments.filter(c => c.id !== commentId)))
      .catch(err => alert("Erreur suppression"));
  }
}
