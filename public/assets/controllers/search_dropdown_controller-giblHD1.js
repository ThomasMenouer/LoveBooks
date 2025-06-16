import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
  static targets = ["input", "dropdown"]

  connect() {
    document.addEventListener("click", this.handleClickOutside)
  }

  disconnect() {
    document.removeEventListener("click", this.handleClickOutside)
  }

  handleClickOutside = (event) => {
    if (!this.element.contains(event.target)) {
      this.hideDropdown()
    }
  }

  onInput() {
    if (this.inputTarget.value.trim() === "") {
      this.hideDropdown()
    } else {
      this.showDropdown()
    }
  }

  hideDropdown() {
    this.dropdownTarget.classList.add("d-none")
  }

  showDropdown() {
    this.dropdownTarget.classList.remove("d-none")
  }
}
