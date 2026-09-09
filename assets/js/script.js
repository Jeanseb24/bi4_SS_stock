// Fonction de filtre AJAX
function filterProducts() {
    const query = document.getElementById('searchInput').value;
    const category = document.getElementById('category').value;

    fetch(`searchProduct.php?q=${encodeURIComponent(query)}&category=${encodeURIComponent(category)}`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('productsTableBody').innerHTML = html;
        })
        .catch(error => console.error('Erreur lors de la recherche :', error));
}

// Gestion globale du clic sur les lignes (Event Delegation)
// Fonctionne au chargement ET après le filtre AJAX
document.addEventListener('click', function (e) {
    const row = e.target.closest('.clickable-row');
    
    // Si on clique sur une ligne ET qu'on n'est PAS dans une zone 'data-no-click'
    if (row && !e.target.closest('[data-no-click]')) {
        const href = row.dataset.href;
        if (href) {
            window.location.href = href;
        }
    }
});