import React, { useEffect, useState } from "react";
import CurrentlyReading from "./../../shared/components/CurrentlyReading/CurrentlyReading";
import { IconBook, IconBook2, IconBooks, IconBookOff, IconRefresh } from "@tabler/icons-react";

// Changer : READ,  READING etd
const statusIcons = {
  READ: <IconBook2 size={30} />,
  READING: <IconBook size={30} />,
  ABANDONED: <IconBookOff size={30} />,
  NOT_READ: <IconBooks size={30} />,
};

const getStatusIcon = (status) => statusIcons[status] || <IconRefresh />;

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

  console.log(stats);

  return (
    <div className="row">
      <div className="col-6">
        <h3 className="text-center mb-4">Aperçu rapide</h3>

        <div className="col-12 mb-3">
          <div className="card border-color-green mx-auto" style={{width: "18rem"}}>
            <div className="card-body text-color-green d-flex justify-content-between align-items-center">
                <div className="d-flex align-items-center">
                  <IconBooks size={30} />
                  <span className="ms-3">Nombre de livres :</span>
                </div>
                <span className="badge border bg-transparent border-color-green text-color-green">{stats.totalBooks}</span>
            </div>
          </div>
        </div>

        <div className="col-12 mb-3">
          <div className="card border-color-green mx-auto" style={{width: "18rem"}}>
            <div className="card-body text-color-green d-flex justify-content-between align-items-center">
              <div className="d-flex align-items-center">
                <IconBook size={30} />
                <span className="ms-3">Pages lues :</span>
              </div>
              <span className="badge border bg-transparent border-color-green text-color-green">{stats.totalPagesRead}</span>
            </div>
          </div>
        </div>
      </div>

      <div className="col-6">
        <h3 className="text-center mb-4">Statuts</h3>
        {stats.bookStats.map(item => (
          <div className="col-12 mb-3" key={item.status}>
            <div className={`card border-${item.color} mx-auto`} style={{width: "18rem"}}>
              <div className={`card-body text-${item.color} d-flex justify-content-between align-items-center`}>
                <div className="d-flex align-items-center">
                  {getStatusIcon(item.status)}
                  <span className="ms-3">{item.label}</span>
                </div>
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
