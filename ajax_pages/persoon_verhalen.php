<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != true) {
    echo json_encode(array('success' => false, 'message' => 'Permission denied'));
    die;
}
if ($_SERVER['REQUEST_METHOD'] != 'POST' || !isset($_POST['persoon_id'])) {
    echo json_encode(array('success' => false, 'messaage' => 'Invalid request method'));
    die;
}
include '../includes/db.php';
try {
    $sql = "SELECT vpr.id, p.voornaam, p.achternaam, v.titel, v.tekst 
	FROM persoon p 
    LEFT JOIN verhaal_persoon_relatie vpr
    	ON vpr.persoon_id=p.id
    LEFT JOIN verhaal v
    	ON v.id=vpr.verhaal_id
    WHERE p.id=?";
    $stmt = $handler->prepare($sql);
    $stmt->execute([$_POST['persoon_id']]);
    $result = array();
    foreach ($stmt->fetchAll() as $loop => $item) {
        $result[$loop]['id'] = $item['id'];
        $result[$loop]['voornaam'] = $item['voornaam'];
        $result[$loop]['achternaam'] = $item['achternaam'];
        $result[$loop]['titel'] = $item['titel'];
        $result[$loop]['tekst'] = $item['tekst'];
    }
    echo json_encode(array('success' => true, 'content' => $result));
} catch (PDOException $e) {
    echo json_encode(array('success' => false, 'message' => $e->getMessage()));
}

