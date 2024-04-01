<?php

use PDO;

$pdo = new PDO('pgsql:host=localhost;dbname=postgres', 'postgres', 'postgres');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$statement = $pdo->query('SELECT id, name, parent_id FROM categories');
$categories = $statement->fetchAll(PDO::FETCH_ASSOC);

$categoryMap = [];
foreach ($categories as $category) {
    $categoryId = $category['id'];
    $parentId = $category['parent_id'];
    if (!isset($categoryMap[$parentId])) {
        $categoryMap[$parentId] = [];
    }
    $categoryMap[$parentId][$categoryId] = $category;
}

$categoryTree = buildCategoryTree($categoryMap);

$jsonResult = json_encode($categoryTree, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

echo $jsonResult;

function buildCategoryTree($categoryMap, $parentId = null)
{
    $tree = [];
    if (isset($categoryMap[$parentId])) {
        foreach ($categoryMap[$parentId] as $categoryId => $category) {
            $category['children'] = buildCategoryTree($categoryMap, $categoryId);
            $tree[] = $category;
        }
    }

    return $tree;
}
