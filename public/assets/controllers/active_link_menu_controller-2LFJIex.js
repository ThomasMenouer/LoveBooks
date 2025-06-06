import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
  connect() {
    this.setActiveLinkFromCurrent() // Optionnel : active le lien selon l'URL au chargement
  }

  setActive(event) {
    // Supprime toutes les classes actives
    this.removeActiveClasses()

    // Ajoute la classe active au lien cliqué
    event.currentTarget.classList.add("active")
  }

  removeActiveClasses() {
    this.element.querySelectorAll(".nav-link").forEach(link => {
      link.classList.remove("active")
    })
  }

  setActiveLinkFromCurrent() {
    const currentUrl = window.location.pathname
    this.element.querySelectorAll(".nav-link").forEach(link => {
      if (link.getAttribute("href") === currentUrl) {
        link.classList.add("active")
      }
    })
  }
}
