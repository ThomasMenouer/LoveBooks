import React from 'react';
import { useParams } from 'react-router-dom';

export default function Profile() {
  const { id } = useParams();

  return (
    <div>
      <h1>Profil utilisateur</h1>
      <p>ID de l'utilisateur : {id}</p>
    </div>
  );
}
