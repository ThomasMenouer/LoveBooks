import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['link'];

    connect() {
        this.links = this.element.querySelectorAll('.nav-link');
        this.navbarCollapse = document.querySelector('.navbar-collapse'); // Sélection du menu
        this.navbarToggler = document.querySelector('.navbar-toggler'); // Sélection du bouton

        this.updateActiveLink(window.location.hash); // Gère le cas du chargement initial avec une ancre
    }

    setActive(event) {
        event.preventDefault();

        // Supprimer la classe active de tous les liens
        this.links.forEach((link) => link.classList.remove('active'));

        // Ajouter la classe active au lien cliqué
        event.currentTarget.classList.add('active');

        // Gérer le déplacement vers la section correspondante
        const target = event.currentTarget.getAttribute('href');

        // Si le lien contient un # (ancre)
        if (target.startsWith('#')) {
            // Si nous ne sommes pas déjà sur la page d'accueil, rediriger vers la page d'accueil
            if (window.location.pathname !== '/' && !window.location.pathname.startsWith('/project')) {
                window.location.href = '/' + target; // Redirige vers la page d'accueil avec l'ancre
            } else {
                // Si nous sommes sur la page d'accueil, juste changer l'ancre
                window.location.hash = target;
            }
        } else {
            // Pour les autres liens, navigation normale
            window.location.href = target;
        }

        // Fermer le menu uniquement si on est en version mobile
        if (window.innerWidth < 992 && this.navbarCollapse.classList.contains('show')) {
            this.navbarToggler.click(); // Simule un clic pour fermer le menu
        }
    }

    updateActiveLink(hash) {
        this.links.forEach((link) => {
            if (link.getAttribute('href') === hash) {
                link.classList.add('active');
            } else {
                link.classList.remove('active');
            }
        });
    }
}