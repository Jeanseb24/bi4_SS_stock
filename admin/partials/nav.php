<?php
  $currentPage = basename($_SERVER['PHP_SELF']);
?>

<nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top" style="--bs-navbar-active-color: #0d6efd;">
  <div class="container-fluid">
    <a class="navbar-brand" href="../index.php">Admin Stock</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <i class="bi bi-list fs-2"></i>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link <?= ($currentPage === 'dashboard.php') ? 'active' : '' ?>" 
             <?= ($currentPage === 'dashboard.php') ? 'aria-current="page"' : '' ?> 
             href="dashboard.php">Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= ($currentPage === 'categories.php') ? 'active' : '' ?>" 
             <?= ($currentPage === 'categories.php') ? 'aria-current="page"' : '' ?> 
             href="categories.php">Catégories</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= ($currentPage === 'products.php') ? 'active' : '' ?>" 
             <?= ($currentPage === 'products.php') ? 'aria-current="page"' : '' ?> 
             href="products.php">Produits</a>
        </li>
      </ul>
      <ul class="navbar-nav ms-auto">
        <a href="dashboard.php?deco=1" title="Accès rapide Admin" class="text-decoration-none">
            <div class="text-center">
                <i class="bi bi-key-fill fs-4 text-warning"></i>
            </div>
        </a>
      </ul>
    </div>
  </div>
</nav>