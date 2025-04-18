window.onload = attachSearchFunction;

function attachSearchFunction() {
    const searchInput = document.getElementById('searchInput');
    const container = document.getElementById('cardContainer');

    searchInput.addEventListener('input', () => {
        const searchTerm = searchInput.value.toLowerCase();
        const cards = Array.from(document.querySelectorAll('.card-column'));

        cards.forEach(card => {
            const title = card.querySelector('.card-title').textContent.toLowerCase();
            if (title.includes(searchTerm)) {
                card.style.display = 'block';
                container.appendChild(card); 
            } else {
                card.style.display = 'none';
            }
        });

        if (!container.querySelectorAll('.card-column:visible').length) {
            const noResults = document.createElement('div');
            noResults.textContent = 'No matching properties found.';
            noResults.className = 'no-results'; 
            container.appendChild(noResults);
        }
    });
}
