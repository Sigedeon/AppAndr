<?php

header('Content-Type: application/json; charset=utf-8');
include_once('../actions/config.php');
$json = json_decode(file_get_contents('php://input'), true);

// Vérifier si les champs nécessaires sont présents
$id_user = htmlspecialchars($json['id_user']);


$totalAC = $bdd->prepare("SELECT * FROM contacts WHERE decision = 'AC' and id_user = ? ");
$totalAC->execute([$id_user]);

$totalRC = $bdd->prepare("SELECT * FROM contacts WHERE decision = 'RC' and id_user = ? ");
$totalRC->execute([$id_user]);

$totalRDV = $bdd->prepare("SELECT * FROM contacts WHERE decision = 'RDV' and id_user = ? ");
$totalRDV->execute([$id_user]);

$ac = $totalAC->rowCount();

$rc = $totalRC->rowCount();

$rdv = $totalRDV->rowCount();

$result["success"] = true;
$result["totalac"] = $ac;
$result["totalrc"] = $rc;
$result["totalrdv"] = $rdv;

$result["message"] = " ";

// Retourner la réponse en JSON
echo json_encode($result);
