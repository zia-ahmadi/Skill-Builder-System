import './bootstrap';

import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';

window.Alpine = Alpine;
window.Chart = Chart;

Alpine.start();

const skillsSearch = document.querySelector('[data-skills-search]');

if (skillsSearch) {
    const searchInput = skillsSearch.querySelector('[data-skills-search-input]');
    const skillCards = skillsSearch.querySelectorAll('[data-skill-card]');
    const emptyState = skillsSearch.querySelector('[data-skill-search-empty]');

    searchInput.addEventListener('input', () => {
        const query = searchInput.value.trim().toLowerCase();
        let visibleSkills = 0;

        skillCards.forEach((skillCard) => {
            const matches = skillCard.dataset.search.includes(query);
            skillCard.style.display = matches ? '' : 'none';

            if (matches) {
                visibleSkills++;
            }
        });

        emptyState.hidden = visibleSkills > 0;
    });
}
