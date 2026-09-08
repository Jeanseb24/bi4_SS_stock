<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Stock</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" aria-current="page" href="index.php?action=home">Accueil</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="index.php?action=product">Produits</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="index.php?action=gallery">Galerie</a>
        </li>
      </ul>
    </div>
    <button id="theme-toggle" class="btn btn-outline-secondary btn-sm ms-auto" type="button">
        <i class="bi bi-circle-half"></i>
    </button>
  </div>
</nav>

<script>
    document.getElementById('theme-toggle').addEventListener('click', () => {
        const html = document.documentElement;
        const current = html.getAttribute('data-bs-theme');
        const next = current === 'dark' ? 'light' : 'dark';
        html.setAttribute('data-bs-theme', next);
        localStorage.setItem('theme', next);
    });
</script>