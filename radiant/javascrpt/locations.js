// Locations page JavaScript

function searchLocations() {
    const searchInput = document.getElementById('locationSearch');
    const searchTerm = searchInput.value.toLowerCase().trim();
    const locationCards = document.querySelectorAll('.location-card');
    
    locationCards.forEach(card => {
        const locationName = card.getAttribute('data-location-name');
        if (locationName.includes(searchTerm)) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });
}

// Search on Enter key
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('locationSearch');
    if (searchInput) {
        searchInput.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                searchLocations();
            } else {
                // Real-time search
                searchLocations();
            }
        });
    }
});
