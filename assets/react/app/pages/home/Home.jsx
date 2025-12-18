import React from "react";

export default function Home({ loginUrl, registerUrl }) {
  return (
    <main id="home-main">
      <section className="hero d-flex flex-column justify-content-center align-items-center text-center">
        <div className="container">
          <h1 className="hero-title">LoveBooks</h1>
          <p className="hero-subtitle">
            Gérez vos lectures, suivez vos progrès, partagez vos émotions.
          </p>
          <div className="d-flex justify-content-center gap-3 mt-4">
            <a href={registerUrl} className="btn btn-custom">
              Créer un compte
            </a>
            <a href={loginUrl} className="btn btn-outline-custom">
              Se connecter
            </a>
          </div>
        </div>
      </section>

      <section className="features container py-5">
        <div className="row text-center gy-4">
          <div className="col-md-3 col-12">
            <div className="feature-card p-4">
              <div className="emoji mb-3">📚</div>
              <h3>Enregistrez vos livres</h3>
              <p>
                Ajoutez facilement vos ouvrages et organisez votre bibliothèque
                à votre image.
              </p>
            </div>
          </div>

          <div className="col-md-3 col-12">
            <div className="feature-card p-4">
              <div className="emoji mb-3">✅</div>
              <h3>Suivez vos lectures</h3>
              <p>
                D’un coup d’œil, visualisez vos livres lus, en cours ou à lire.
              </p>
            </div>
          </div>

          <div className="col-md-3 col-12">
            <div className="feature-card p-4">
              <div className="emoji mb-3">📖</div>
              <h3>Trackez votre progression</h3>
              <p>
                Notez vos pages lues et suivez votre avancée en temps réel pour
                garder la motivation.
              </p>
            </div>
          </div>

          <div className="col-md-3 col-12">
            <div className="feature-card p-4">
              <div className="emoji mb-3">💬</div>
              <h3>Partagez vos avis</h3>
              <p>
                Écrivez vos reviews et commentez celles des autres lecteurs.
              </p>
            </div>
          </div>
        </div>
      </section>

      <footer className="text-center py-4 mt-5">
        <p>© 2025 LoveBooks</p>
      </footer>
    </main>
  );
}
