import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    toggleForm(event) {
        const bookId = event.currentTarget.dataset.bookId
        const formContainer = document.getElementById(`form-container-${bookId}`)
        formContainer.classList.toggle('d-none')
    }
}