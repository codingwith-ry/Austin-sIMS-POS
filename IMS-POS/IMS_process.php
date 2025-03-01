<?php include '../Login/database.php' ?>
<?php

try {
    $pdo = new PDO($attrs, $db_user, $db_pass,$opts);
    echo 'database connected';    
} catch (Exception $e) {
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}

$fetchCategoryQuery = "SELECT * FROM tbl_categories";
$categories = $pdo->query($fetchCategoryQuery)->fetchAll(PDO::FETCH_ASSOC);
?>

<ul>
    <?php foreach ($categories as $category): ?>
        <li><?php echo htmlspecialchars($category['categoryName']); ?></li>
    <?php endforeach; ?>
</ul>