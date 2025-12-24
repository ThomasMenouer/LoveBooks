import React, { useEffect, useState } from "react";
import CurrentlyReading from "./../../shared/components/CurrentlyReading/CurrentlyReading";
import Stats from "../../shared/components/Stats/Stats";


export default function Home({ userId }) {
  const [stats, setStats] = useState(null);

  const fetchStats = () => {
    fetch(`/api/user_books/stats`, {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
      },
      credentials: "include", 
    })
      .then((res) => res.json())
      .then((data) => setStats(data));
  };

  useEffect(() => {
    fetchStats();
  }, [userId]);

  if (!stats) {
    return <div>Chargement en cours...</div>;
  } 

  return (
    <>
      <Stats stats={stats} />

      <div className="row">
        <h3 className="my-4 text-center">Vos lectures du moment</h3>
        <CurrentlyReading userId={userId} isOwnProfile={true} refreshStats={fetchStats} />
      </div>
    </>
  );
}
