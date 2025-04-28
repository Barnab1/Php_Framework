<?php

/*
 * Exemple d'utilisation des classes
 */

// Configuration
$uploadDir = 'uploads/';
$maxFileSize = 2 * 1024 * 1024;
$imageFieldName = 'image';  //nom du champ input pour les images
$videoFieldName = 'video';  //nom du champ input pour les videos

// Création d'une instance de la classe ImageUploadHandler
$imageUploadHandler = new \Ninja\ImageUploadHandler($imageFieldName, $uploadDir, $maxFileSize);
$imageUploadHandler->setRenameFiles(true);
$imageUploadHandler->setDirectoryPermissions(0775);

// Création d'une instance de la classe VideoUploadHandler
$videoUploadHandler = new \Ninja\VideoUploadHandler($videoFieldName, $uploadDir, $maxFileSize);
$videoUploadHandler->setRenameFiles(true);
$videoUploadHandler->setDirectoryPermissions(0775);

// Traitement du téléchargement des images
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES[$imageFieldName])) {
    if ($imageUploadHandler->handleUpload()) {
        echo "L'image a été téléchargée avec succès. Nom final : " . $imageUploadHandler->getFinalFileName() . "<br>";
    } else {
        $errors = $imageUploadHandler->getErrors();
        echo "Erreurs lors du téléchargement de l'image :<br>";
        foreach ($errors as $error) {
            echo "- " . $error . "<br>";
        }
    }
}

// Traitement du téléchargement des vidéos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES[$videoFieldName])) {
    if ($videoUploadHandler->handleUpload()) {
        echo "La vidéo a été téléchargée avec succès. Nom final : " . $videoUploadHandler->getFinalFileName() . "<br>";
    } else {
        $errors = $videoUploadHandler->getErrors();
        echo "Erreurs lors du téléchargement de la vidéo :<br>";
        foreach ($errors as $error) {
            echo "- " . $error . "<br>";
        }
    }
}
?>

