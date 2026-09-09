<?php


/**
 * Permet de faire une requête PDO à la base de données (query ou prepare)
 *
 * @param PDO $pdo
 * @param string $sql
 * @param array $params
 * @return PDOStatement
 */
function dbQuery(PDO $pdo, string $sql, array $params = []): PDOStatement
{
    if(empty($params)){
        return $pdo->query($sql);
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

/**
 * Permet de récupèrer un tableau de données venant de la bdd
 *
 * @param PDO $pdo
 * @param string $sql
 * @param array $params
 * @return array
 */
function fetchAll(PDO $pdo, string $sql, array $params = []): array
{
    return dbQuery($pdo, $sql, $params)->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Permet de récupèrer une seule information venant de la bdd
 *
 * @param PDO $pdo
 * @param string $sql
 * @param array $params
 * @return array|null
 */
function fetchOne(PDO $pdo, string $sql, array $params = []): ?array
{
    $stmt = dbQuery($pdo, $sql, $params);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt->closeCursor();

    return $result ?: null;
}

/**
 * Permet d'insèrer un élément à la base de données
 *
 * @param PDO $pdo
 * @param string $sql
 * @param array $params
 * @return string
 */
function insert(PDO $pdo, string $sql, array $params = []): string
{
    dbQuery($pdo, $sql, $params);
    return $pdo->lastInsertId();
}

/**
 * Permet de modifier ou supprimer dans la base de données
 *
 * @param PDO $pdo
 * @param string $sql
 * @param array $params
 * @return integer
 */
function execute(PDO $pdo, string $sql, array $params = []): int
{
    return dbQuery($pdo, $sql, $params)->rowCount();
}

