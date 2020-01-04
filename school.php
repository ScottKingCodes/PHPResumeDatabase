<?php
session_start();

require_once "pdo.php";
require_once "validation.php";

if (isset($_REQUEST['term']))
{
	$stmt = $pdo->prepare('
		SELECT name FROM Institution
		WHERE name LIKE :prefix'
	);
	$stmt->execute([
		':prefix' => $_REQUEST['term']."%"
	]);
	$retval = [];
	while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) 
	{
		$retval[] = $row['name'];
	}
	echo(json_encode($retval, JSON_PRETTY_PRINT));
}
?>