<?php
try {
    require_once __DIR__.'/db.php';
	$stmt = $connection->query('SELECT * FROM sizes');
	if (!$stmt)
	    die('db fetch fali');
	echo json_encode($stmt->fetchAll());
} catch (PDOException $e) {
    die();
}
