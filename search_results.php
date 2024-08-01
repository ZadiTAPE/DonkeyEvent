<?php
// search_results.php
require_once '../config/database.php';

$query = $_GET['query'] ?? '';

if ($query) {
    // Préparer une requête SQL pour rechercher dans les films et les séances
    $sql = "
        SELECT films.title, films.description, seances.seance_time, seances.salle 
        FROM films 
        LEFT JOIN seances ON films.id = seances.film_id
        WHERE films.title LIKE :query
        OR films.description LIKE :query
        OR seances.seance_time LIKE :query
        OR seances.salle LIKE :query
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['query' => '%' . $query . '%']);
    $results = $stmt->fetchAll();
} else {
    $results = [];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Résultats de la Recherche</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Résultats de la Recherche</h2>
        <?php if (count($results) > 0): ?>
            <ul class="list-group">
                <?php foreach ($results as $result): ?>
                    <li class="list-group-item">
                        <h5><?php echo htmlspecialchars($result['title']); ?></h5>
                        <p><?php echo htmlspecialchars($result['description']); ?></p>
                        <p><strong>Séance :</strong> <?php echo htmlspecialchars($result['seance_time']); ?></p>
                        <p><strong>Salle :</strong> <?php echo htmlspecialchars($result['salle']); ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Aucun résultat trouvé pour "<?php echo htmlspecialchars($query); ?>"</p>
        <?php endif; ?>
    </div>
</body>
</html>
