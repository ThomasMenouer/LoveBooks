import React, { useEffect, useState } from "react";
import CommentList from "./CommentList";
import CommentForm from "./CommentForm";
import { IconStar, IconStarFilled } from "@tabler/icons-react";

export default function ReviewCard({ review, profileUrl, currentUserId, commentsCount }) {
  const [comments, setComments] = useState([]);
  const [showComments, setShowComments] = useState(false);
  const [loadingComments, setLoadingComments] = useState(false);

  const loadComments = () => {
    setLoadingComments(true);
    fetch(`/api/reviews/${review.id}/comments`)
      .then((res) => res.json())
      .then((data) => {
        setComments(data.comments);
        setLoadingComments(false);
      });
  };

  const handleToggleComments = () => {
    if (!showComments && comments.length === 0) {
      loadComments();
    }
    setShowComments(!showComments);
  };

  return (
    <div className="row">
      <div className="card mb-3 text-color-white">
        <div className="card-header">
          <img
            src={`/uploads/avatars/${review.user.avatar}`}
            alt="Avatar"
            className="img-fluid rounded-circle"
            style={{ height: "50px", width: "50px", objectFit: "cover" }}
          />
          <a href={profileUrl} className="card-title h5 profile-link">
            {review.user.name}
          </a>
          <p>Statut : {review.status}</p>
          <div className="text-warning fs-4">
            {Array.from({ length: 5 }, (_, i) =>
              i + 1 <= review.rating ? (
                <IconStarFilled key={i} size={20} />
              ) : (
                <IconStar key={i} size={20} />
              )
            )}
          </div>
        </div>

        <div className="card-body">
          <p>{review.content}</p>
          <p>
            <small>
              Posté le {new Date(review.createdAt).toLocaleDateString()}
            </small>
          </p>
        </div>

        <div className="card-footer">
          <CommentForm reviewId={review.id} onCommentAdded={loadComments} />
        </div>
      </div>
      <div className="text-start my-2">
        {commentsCount > 0 ? (
          <button 
            className="btn btn-link p-0 text-color-white text-decoration-none" 
            onClick={handleToggleComments}
          >
            {showComments 
              ? "Masquer les commentaires" 
              : `Afficher les commentaires (${commentsCount})`
            }
          </button>
        ) : (
          <p>Aucun commentaire</p>
        )}
      </div>


      {showComments && (
          <>
            {loadingComments ? (
              <p>Chargement des commentaires...</p>
            ) : (
              <CommentList comments={comments} currentUserId={currentUserId} onCommentDeleted={loadComments} />
            )}
          </>
        )}
      <hr className="my-2 border-custom-white" />
    </div>
  );
}
