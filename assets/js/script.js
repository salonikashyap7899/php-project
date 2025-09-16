document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    const searchForm = document.getElementById('searchForm');
    let debounceTimeout;

    searchInput.addEventListener('input', function () {
        clearTimeout(debounceTimeout);
        const query = this.value.trim();

        if (query.length < 2) {
            searchResults.style.display = 'none';
            searchResults.innerHTML = '';
            return;
        }

        debounceTimeout = setTimeout(() => {
            fetch(`search_api.php?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length === 0) {
                        searchResults.innerHTML = '<div class="list-group-item">No products found</div>';
                    } else {
                        searchResults.innerHTML = data.map(product => `
                            <a href="product.php?id=${product.id}" class="list-group-item list-group-item-action d-flex align-items-center">
                                <img src="assets/images/${product.image}" alt="${product.name}" 
                                     style="width:40px; height:40px; object-fit:cover; border-radius:5px; margin-right:10px;">
                                <div>
                                    <div>${product.name}</div>
                                    <small class="text-muted">₹${parseFloat(product.price).toFixed(2)}</small>
                                </div>
                            </a>
                        `).join('');
                    }
                    searchResults.style.display = 'block';
                })
                .catch(error => {
                    console.error("Error fetching search results:", error);
                    searchResults.innerHTML = '<div class="list-group-item text-danger">Error fetching results</div>';
                    searchResults.style.display = 'block';
                });
        }, 300);
    });

    document.addEventListener('click', function (e) {
        if (!searchForm.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    });

    searchForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const firstResult = searchResults.querySelector('a.list-group-item');
        if (firstResult) {
            window.location.href = firstResult.href;
        }
    });
});
