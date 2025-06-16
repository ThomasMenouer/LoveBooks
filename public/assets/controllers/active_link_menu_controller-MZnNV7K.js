import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
  connect() {
    // Ne fait rien ici : pas de check d'URL
  }

  setActive(event) {
    this.removeActiveClasses()
    event.currentTarget.classList.add("active")
  }

  removeActiveClasses() {
    this.element.querySelectorAll(".nav-link").forEach(link => {
      link.classList.remove("active")
    })
  }
}
