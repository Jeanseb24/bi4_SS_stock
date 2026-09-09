<?php
require "../config/session.php";

if (!isset($_SESSION['email']) || !isset($_SESSION['id'])) {
    http_response_code(403);
    exit("Accès refusé");
}

require_once "../config/connexion.php";
require "functions.php";

$q = trim($_GET['q'] ?? '');
$category = $_GET['category'] ?? '';

// Construction dynamique de la requête SQL
$sql = "SELECT products.id, products.name, categories.name as category_name, products.price, products.cover 
        FROM products 
        INNER JOIN categories ON products.id_category = categories.id 
        WHERE 1=1";

$params = [];

// Filtre par catégorie si elle est sélectionnée
if ($category !== '') {
    $sql .= " AND products.id_category = ?";
    $params[] = $category;
}

// Filtre par terme de recherche si saisi
// Exemple pour chercher dans le NOM du produit OU dans le NOM de la catégorie
if ($q !== '') {
    $sql .= " AND (products.name LIKE ? OR categories.name LIKE ?)";
    $params[] = '%' . $q . '%';
    $params[] = '%' . $q . '%';
}

$sql .= " ORDER BY products.id ASC";

$products = fetchAll($bdd, $sql, $params);

if (empty($products)) {
    echo '<tr><td colspan="6" class="text-center text-muted py-4">Aucun produit trouvé.</td></tr>';
    exit();
}

// Génération du HTML pour chaque ligne
foreach ($products as $product) :
    $coverPath = "../images/" . ($product['cover'] ?? '');
    $hasImage = !empty($product['cover']);
?>
    <tr class="clickable-row" style="cursor: pointer;" data-href="product.php?id=<?= urlencode($product['id']) ?>">
        <th scope="row"><?= $product['id'] ?></th>
        <td>
            <?php if ($hasImage) : ?>
                <img src="<?= htmlspecialchars($coverPath) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
            <?php else : ?>
                <div class="bg-secondary rounded d-flex align-items-center justify-content-center mx-auto" style="width: 60px; height: 60px;">
                    <i class="bi bi-image text-white"></i>
                </div>
            <?php endif; ?>
        </td>
        <td class="d-none d-md-table-cell"><?= htmlspecialchars($product['name']) ?></td>
        <td class="d-none d-md-table-cell"><?= htmlspecialchars($product['category_name']) ?></td>
        <td class="d-none d-md-table-cell"><?= number_format($product['price'], 2, ',', ' ') ?>€</td>
        <td data-no-click>
            <div class="d-flex justify-content-center gap-2">
                <a href="updateProduct.php?id=<?= urlencode($product['id']) ?>" class="btn btn-warning btn-sm">Modifier</a>
                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal<?= $product['id'] ?>">Supprimer</button>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="exampleModal<?= $product['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel<?= $product['id'] ?>" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel<?= $product['id'] ?>">Confirmation</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Voulez-vous vraiment<br>supprimer <strong><?= htmlspecialchars($product['name']) ?></strong> ?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Non</button>
                            <a href="products.php?delete=<?= urlencode($product['id']) ?>" class="btn btn-danger btn-sm">Supprimer</a>
                        </div>
                    </div>
                </div>
            </div>
        </td>
    </tr>
<?php endforeach; ?>