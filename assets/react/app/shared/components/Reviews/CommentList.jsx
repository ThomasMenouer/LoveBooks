import React from 'react';

export default function CommentList({ comments, currentUserId, onCommentDeleted }) {

  function handleDelete(commentId) {
    fetch(`/api/comment/${commentId}`, {
      method: 'DELETE',
      credentials: 'include',
      headers: {
        'Content-Type': 'application/json',
      },
    })
    .then(res => {
      if (!res.ok) throw new Error("Erreur lors de la suppression");
      onCommentDeleted();  // Appel pour recharger les commentaires
    })
    .catch(err => console.error(err));
  }

  return (
    <div className="mt-3">
      <h5>Commentaires</h5>
      {comments.map(comment => (
        <div key={comment.id} className="card bg-color-black text-color-white mb-3">
          <div className="card-header d-flex align-items-center">
            {comment.user.avatar && (
              <img
                src={`/uploads/avatars/${comment.user.avatar}`}
                alt="Avatar"
                className="rounded-circle me-2"
                style={{ height: 60, width: 60, objectFit: 'cover' }}
              />
            )}
            <strong>{comment.user.name}</strong>
            <small className="ms-auto">{new Date(comment.createdAt).toLocaleDateString()}</small>
          </div>

          <div className="card-body">
            <p>{comment.content}</p>
          </div>

          <div className="card-footer text-end">
            {comment.user.id === currentUserId && (
              <button
                className="btn btn-sm btn-outline-custom-red"
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
}
