// assets/controllers/dropdown_controller.js
// eviter les conflits avec Bootstrap 5 et Turbo
import { Controller } from '@hotwired/stimulus'
import * as bootstrap from 'bootstrap'

export default class extends Controller {
  connect() {
    this.initDropdowns()

    // Si Turbo est utilisé, ré-initialise à chaque navigation
    if (window.Turbo) {
      document.addEventListener('turbo:load', () => {
        this.initDropdowns()
      })
    }
  }

  initDropdowns() {
    const dropdownToggles = this.element.querySelectorAll('[data-bs-toggle="dropdown"]')
    dropdownToggles.forEach(toggle => {
      // Désactive l’instance existante (si elle existe) avant de recréer
      const instance = bootstrap.Dropdown.getInstance(toggle)
      if (instance) {
        instance.dispose()
      }
      new bootstrap.Dropdown(toggle)
    })
  }
}
