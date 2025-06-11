import React, { useEffect, useState } from "react";
import CurrentlyReading from "./../../shared/components/CurrentlyReading/CurrentlyReading";


export default function Home({ userId }) {
  const [stats, setStats] = useState(null);

  useEffect(() => {
      fetch(`/api/stats/${userId}`, {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
      },
      credentials: "include", 
    })
      .then((res) => res.json())
      .then((data) => setStats(data));
  }, [userId]);

  if (!stats) {
    return <div>Chargement en cours...</div>;
  } 

  return (
    <div className="row">
      <div className="col-6">
        <h3 className="text-center mb-4">Aperçu rapide</h3>

        <div className="col-12 mb-3">
          <div className="card border-color-green">
            <div className="card-body text-color-green d-flex justify-content-between align-items-center">
              <span>Nombre de livres :</span>
              <span className="badge border bg-transparent border-color-green text-color-green">{stats.totalBooks}</span>
            </div>
          </div>
        </div>

        <div className="col-12 mb-3">
          <div className="card border-color-yellow">
            <div className="card-body text-color-yellow d-flex justify-content-between align-items-center">
              <span>Nombre de pages lues :</span>
              <span className="badge border bg-transparent border-color-yellow text-color-yellow">{stats.totalPagesRead}</span>
            </div>
          </div>
        </div>
      </div>

      <div className="col-6">
        <h3 className="text-center mb-4">Statuts</h3>
        {stats.bookStats.map(item => (
          <div className="col-12 mb-3" key={item.status}>
            <div className={`card border-${item.color}`}>
              <div className={`card-body text-${item.color} d-flex justify-content-between align-items-center`}>
                <span>{item.label}</span>
                <span className={`badge border border-${item.color} text-${item.color}`}>
                  {item.count}
                </span>
              </div>
            </div>
          </div>
        ))}
      </div>

      <div className="row">
        <h3 className="my-4 text-center">Vos lectures du moment</h3>
        <CurrentlyReading userId={userId} isOwnProfile={true} />
      </div>
    </div>
  );
}
