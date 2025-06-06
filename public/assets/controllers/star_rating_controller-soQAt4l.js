import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static targets = ["star"];
    static values = {
        selected: Number
    };

    connect() {
        this.highlight(this.selectedValue || 0);
    }

    select(event) {
        const value = parseInt(event.currentTarget.dataset.value);
        const input = this.element.querySelector('input[type="hidden"]');

        if (input) {
            input.value = value;
        }

        this.selectedValue = value;
        this.highlight(value);
    }

    highlight(value) {
        this.starTargets.forEach(star => {
            const starValue = parseInt(star.dataset.value);
            const svg = star.querySelector('svg');

            if (!svg) return;

            svg.outerHTML = starValue <= value
                ? this.filledStar()
                : this.emptyStar();
        });
    }

    filledStar() {
        return `<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-star-filled" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="#facc15" fill="#facc15" stroke-linecap="round" stroke-linejoin="round"><path d="M12 17.75L5.75 21l1.2-7L1 9.25l7.1-1L12 2l3.9 6.25 7.1 1-5.95 4.75 1.2 7z" /></svg>`;
    }

    emptyStar() {
        return `<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-star" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="#facc15" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M12 17.75L5.75 21l1.2-7L1 9.25l7.1-1L12 2l3.9 6.25 7.1 1-5.95 4.75 1.2 7z" /></svg>`;
    }
}
