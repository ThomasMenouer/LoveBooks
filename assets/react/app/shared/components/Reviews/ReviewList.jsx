import React, { useEffect, useState } from 'react';
import ReviewCard from './ReviewCard';

export default function ReviewList({ bookId, currentUserId }) {
    const [reviews, setReviews] = useState([]);

    useEffect(() => {
        fetch(`/api/reviews/${bookId}/review`,
            {
            method: 'GET',
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
            },
            }
        )
        .then(res => res.json())
        .then(data => setReviews(data));
    }, [bookId]);

    return (
        <div className="row">
            <hr className="my-4 border-custom-white" />

            <div className="col-12 col-sm-12">
                <h4 className="mb-4">Critiques des utilisateurs :</h4>
                {reviews.length === 0 ? (
                    <p>Aucune critique pour ce livre.</p>
                ) : (
                    reviews.map(review => (
                        <ReviewCard 
                            key={review.id} 
                            review={review}
                            profileUrl={`/profile/${review.user.name}-${review.user.id}`}
                            currentUserId={currentUserId}
                            commentsCount={review.commentsCount}
                        />
                    ))
                )}
            </div>
        </div>
    );
}
