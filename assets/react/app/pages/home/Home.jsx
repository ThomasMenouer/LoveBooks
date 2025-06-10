import React from 'react';

export default function Home({ loginUrl, registerUrl }) {


    return (
        <main id="home-main">
            <div className="container">
                <div className="row d-flex flex-column min-vh-100 justify-content-center align-items-center">
                    <div className="col-12 col-sm-8">
                        <div className="card">
                            <div className="card-body">
                                <h1 className="card-title text-center">LoveBooks !</h1>

                                <ul className="list-group list-group-flush">
                                    <li className="list-group-item card-text">📚 Enregistrez vos livres : Ajoutez facilement vos ouvrages et organisez votre bibliothèque.</li>
                                    <li className="list-group-item card-text">✅ Suivez vos lectures : Identifiez d’un coup d’œil vos livres lus, en cours ou à lire.</li>
                                    <li className="list-group-item card-text">📖 Tracker votre progression : Notez le nombre de pages lues et suivez vos avancées en temps réel.</li>
                                </ul>
                            </div>
                            <div className="card-body d-flex justify-content-center gap-3">
                                <a href={loginUrl} className="btn btn-outline-custom text-decoration-none">Se connecter</a>
                                <a href={registerUrl} className="btn btn-outline-custom text-decoration-none">S'inscrire</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    );
}