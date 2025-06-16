import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
  connect() {
    const currentPath = window.location.pathname

    this.element.querySelectorAll("a.nav-link, a.dropdown-item").forEach(link => {
      if (link.getAttribute("href") === currentPath) {
        link.classList.add("active")
      }
    })
  }
}