// pour admin/products.php >> redirige vers product.php au clic + désactiver click sur zone 'data-no-click' pour éviter click sur Actions

document.querySelectorAll('.clickable-row').forEach(function(row) {
    row.addEventListener('click', function(e) {
        // si le clic vient d'une zone marquée "data-no-click" (colonne Actions), on ignore
        if (e.target.closest('[data-no-click]')) {
            return;
        }
        window.location = this.dataset.href;
    });
});