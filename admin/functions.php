<?php

/**
 * Permet de faire une requête PDO à la base de données (query ou prepare)
 *
 * @param PDO $pdo l'objet de connexion ($bdd)
 * @param string $sql la requête sql (ex: SELECT .. WHERE..)
 * @param array $params les valeurs à sécuriser (ex: [':id' => 1])
 * @return PDOStatement le résultat brut PDO après exécution
 */
function dbQuery(PDO $pdo, string $sql, array $params = []): PDOStatement {
    if (empty($params)) {
        return $pdo->query($sql);
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

/**
 * Permet de récupérer un tableau de données venant de la bdd
 *
 * @param PDO $pdo l'objet de connexion
 * @param string $sql la requête sql (SELECT...)
 * @param array $params les variables sécurisées
 * @return array tableau contenant les lignes trouvées
 */
function fetchAll(PDO $pdo, string $sql, array $params = []): array {
    return dbQuery($pdo, $sql, $params)->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Permet de récupérer une seule information venant de la bdd
 *
 * @param PDO $pdo l'objet de connexion
 * @param string $sql la requête sql (SELECT...)
 * @param array $params les variables sécurisées
 * @return array|null le tableau de la ligne trouvée ou NULL si rien dans la bdd
 */
function fetchOne(PDO $pdo, string $sql, array $params = []): ?array {
    $stmt = dbQuery($pdo, $sql, $params);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt->closeCursor();

    return $result ?: null;
}

/**
 * Permet d'insérer un élément dans la bdd
 *
 * @param PDO $pdo l'objet de connexion
 * @param string $sql la requête d'insertion (INSERT TO ...)
 * @param array $params les données à insérer
 * @return string l'identifiant de la ligne qui vient d'être créé
 */
function insert(PDO $pdo, string $sql, array $params = []): string {
    dbQuery($pdo, $sql, $params);
    return $pdo->lastInsertId();
}

/**
 * Permet de modifier ou supprimer dans la bdd
 *
 * @param PDO $pdo l'objet de connexion
 * @param string $sql la requête UPDATE ou DELETE...
 * @param array $params les paramètres de la requête
 * @return integer le nombe de ligne affectée par la requête
 */
function execute(PDO $pdo, string $sql, array $params = []): int {
    return dbQuery($pdo, $sql, $params)->rowCount();
}