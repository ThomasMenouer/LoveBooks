import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    static targets = ["input"]
    static values = {
        initial: Number
    }

    connect() {
        this.stars = this.element.querySelectorAll('.star')
        this.setStars(this.initialValue)
    }

    setRating(event) {
        const value = parseInt(event.currentTarget.dataset.value)
        this.inputTarget.value = value
        this.setStars(value)
    }

    previewRating(event) {
        const value = parseInt(event.currentTarget.dataset.value)
        this.setStars(value)
    }

    resetPreview() {
        this.setStars(parseInt(this.inputTarget.value))
    }

    setStars(value) {
        this.stars.forEach(star => {
            const starValue = parseInt(star.dataset.value)
            if (starValue <= value) {
                star.innerHTML = '★' // étoile pleine
                star.classList.add('text-warning')
            } else {
                star.innerHTML = '☆' // étoile vide
                star.classList.remove('text-warning')
            }
        })
    }
}
