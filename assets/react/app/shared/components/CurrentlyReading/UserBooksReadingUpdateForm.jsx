import React, { useState } from 'react';

const statuses = [
    { value: 'Lu', label: 'Lu' },
    { value: 'En cours de lecture', label: 'En cours de lecture' },
    { value: 'Abandonné', label: 'Abandonné' },
];

export default function UserBooksReadingUpdateForm({ userBook, onUpdated }) {
    const [status, setStatus] = useState(userBook.status ||"En cours de lecture");
    const [pagesRead, setPagesRead] = useState(userBook.pagesRead || 0 );
    const [error, setError] = useState(null);

    const handleSubmit = async (e) => {
        e.preventDefault();

        try {
            const response = await fetch(`/api/reading-list/${userBook.id}/update`, {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                },
                credentials: "include",
                body: JSON.stringify({
                    status,
                    pagesRead,
                }),
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.error || 'Erreur lors de la mise à jour');
            }

            const modal = window.bootstrap.Modal.getInstance(
                document.getElementById(`ReadingListModal-${userBook.book.id}`)
            );
            modal.hide();

            onUpdated();
        } catch (err) {
            setError(err.message);
            console.error(err);
        }
    };


  return (
    <form onSubmit={handleSubmit}>
        {error && <div className="alert alert-danger">{error}</div>}

        <div className="mb-3">
            <label htmlFor="status" className="form-label">Statut</label>
            <select
                id="status"
                className="form-select"
                value={status}
                onChange={(e) => setStatus(e.target.value)}
                required
            >
                {statuses.map(({ value, label }) => (
                <option key={value} value={value}>{label}</option>
                ))}
            </select>
        </div>

        <div className="mb-3">
            <label htmlFor="pagesRead" className="form-label">Pages lues</label>
            <input
                id="pagesRead"
                type="number"
                min="0"
                className="form-control"
                value={pagesRead}
                onChange={(e) => setPagesRead(Number(e.target.value))}
            />
        </div>

        <div className="d-flex justify-content-center">
            <button type="submit" className="btn btn-outline-custom-yellow">Mettre à jour</button>
        </div>
    </form>
  );
}
