import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
  connect() {
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
