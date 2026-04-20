// Cities page JavaScript

function searchCities() {
    const searchInput = document.getElementById('citySearch');
    const searchTerm = searchInput.value.toLowerCase().trim();
    const cityCards = document.querySelectorAll('.city-card');
    const categories = document.querySelectorAll('.city-category');
    
    // If search is empty, show all categories and cards
    if (searchTerm === '') {
        cityCards.forEach(card => {
            card.style.display = 'flex';
        });
        categories.forEach(category => {
            category.style.display = 'block';
        });
        return;
    }
    
    // Filter cards and categories
    cityCards.forEach(card => {
        const cityName = card.getAttribute('data-city-name');
        const category = card.getAttribute('data-category');
        if (cityName.includes(searchTerm)) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });
    
    // Hide categories that have no visible cards
    categories.forEach(category => {
        const visibleCards = category.querySelectorAll('.city-card[style="display: flex"], .city-card:not([style*="display: none"])');
        const hasVisibleCards = Array.from(visibleCards).some(card => card.style.display !== 'none');
        category.style.display = hasVisibleCards ? 'block' : 'none';
    });
}

// Search on Enter key
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('citySearch');
    if (searchInput) {
        searchInput.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                searchCities();
            } else {
                // Real-time search
                searchCities();
            }
        });
    }
});
