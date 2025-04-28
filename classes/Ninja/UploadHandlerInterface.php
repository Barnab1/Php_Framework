<?php
namespace Ninja;
interface UploadHandlerInterface
{
    /**
     * Vérifie si un fichier a été uploadé.
     *
     * @return bool
     */
    public function isFileUploaded(): bool;

    /**
     * Valide le fichier uploadé.
     *
     * @return bool
     */
    public function validateFile(): bool;

    /**
     * Déplace le fichier uploadé vers le répertoire de destination.
     *
     * @return bool
     */
    public function moveFile(): bool;

    /**
     * Traite le téléchargement du fichier.
     *
     * @return bool
     */
    public function handleUpload(): bool;

    /**
     * Retourne les erreurs survenues lors du traitement de l'upload.
     *
     * @return array
     */
    public function getErrors(): array;

    /**
     * Retourne le nom final du fichier.
     *
     * @return string
     */
    public function getFinalFileName(): string;
}