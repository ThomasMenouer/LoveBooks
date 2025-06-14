import React, { useState, useEffect } from 'react';
import { IconStar, IconStarFilled } from "@tabler/icons-react";
import Alert from '../Alert/Alert';
import ReviewModal from './ReviewModal';  // ← on importe notre nouveau modal

export default function ReviewSection({ bookId }) {
  const [review, setReview] = useState(null);
  const [alert, setAlert] = useState({ message: "", type: "info" });

  const fetchReview = () => {
    fetch(`/api/reviews/${bookId}/user-review`, {
      method: 'GET',
      credentials: 'include',
      headers: {
        'Content-Type': 'application/json',
      },
    })
      .then(res => {
        if (res.status === 404) {
          setReview(null);
        } else {
          return res.json().then(data => setReview(data));
        }
      })
      .catch(err => {
        console.error(err);
      });
  };

  useEffect(() => {
    fetchReview();
  }, [bookId]);

  const handleDelete = () => {
    if (!window.confirm("Supprimer votre review ?")) return;

    fetch(`/api/review/${review.id}/delete`, { 
      method: 'DELETE',
      credentials: 'include',
    })
    .then(() => {
      setReview(null);
      setAlert({ message: "Votre review a bien été supprimée.", type: "success" });
    })
    .catch(err => {
      console.error(err);
      setAlert({ message: "Erreur lors de la suppression.", type: "danger" });
    });
  };

  return (
    <div className="row">
      <hr className="my-4 border-custom-white" />
      <div className="col-12 col-sm-12">
        <h4 className="mb-4">Ma review</h4>
        <Alert
          message={alert.message}
          type={alert.type}
          onClose={() => setAlert({ message: "", type: "info" })}
        />

        {!review ? (
          <div className="text-center">
            <p>Vous n'avez pas encore laissé de review pour ce livre.</p>
            <button 
              className="btn btn-outline-primary" 
              data-bs-toggle="modal" 
              data-bs-target="#createReviewModal"
            >
              Créer ma review
            </button>
          </div>
        ) : (
          <div className="card mb-3 text-white">
            <div className="card-header">
              <h5 className="card-title"><strong>{review.user.name}</strong></h5>
              <p className="card-text">Statut : {review.status}</p>
              <div className="text-warning fs-4">
                {Array.from({ length: 5 }, (_, i) => 
                  i + 1 <= review.rating ? <IconStarFilled key={i} size={20}/> : <IconStar key={i} size={20}/>
                )}
              </div>
            </div>
            <div className="card-body">
              <p>{review.content}</p>
            </div>
            <div className="card-footer">
              <p className="card-text"><small>Posté le {new Date(review.createdAt).toLocaleDateString()}</small></p>
              <div className="text-center">
                <button 
                  className="btn btn-outline-warning me-2" 
                  data-bs-toggle="modal" 
                  data-bs-target="#editReviewModal"
                >
                  Editer
                </button>
                <button className="btn btn-outline-danger" onClick={handleDelete}>Supprimer</button>
              </div>
            </div>
          </div>
        )}

        {/* Modal Création */}
        <ReviewModal 
          mode="create" 
          bookId={bookId} 
          onSuccess={fetchReview}
        />

        {/* Modal Edition */}
        {review && (
          <ReviewModal 
            mode="edit" 
            reviewId={review.id} 
            initialContent={review.content}
            onSuccess={fetchReview}
          />
        )}
      </div>
    </div>
  );
}
