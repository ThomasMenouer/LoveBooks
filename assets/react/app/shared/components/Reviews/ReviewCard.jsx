import React from 'react';
import CommentList from './CommentList';
import CommentForm from './CommentForm';
import { IconStar, IconStarFilled } from "@tabler/icons-react";

export default function ReviewCard({ review, profileUrl }) {
  return (
    <div className="card mb-3 text-white">
      <div className="card-header">
        <img 
          src={`/uploads/avatars/${review.user.avatar}`} 
          alt="Avatar"
          className="img-fluid rounded-circle" 
          style={{ height: "50px", width: "50px", objectFit: "cover" }}
        />
        <a href={profileUrl} className="card-title h5 profile-link">{review.user.name}</a>
        <p>Statut : {review.status}</p>
        <div className="text-warning fs-4">
          {Array.from({ length: 5 }, (_, i) => i + 1 <= review.rating ? <IconStarFilled size={20}/> : <IconStar size={20}/>)}
        </div>
      </div>

      <div className="card-body">
        <p>{review.content}</p>
        <p><small>Posté le {new Date(review.createdAt).toLocaleDateString()}</small></p>
      </div>

      <div className="card-footer">
        <CommentForm reviewId={review.id} />
        <CommentList reviewId={review.id} />
      </div>
    </div>
  );
}
