<?php
namespace Ninja;

/**
 * Classe pour la gestion des téléchargements de vidéos.
 */
class VideoUploadHandler extends BaseUploadHandler implements UploadHandlerInterface
{
    /**
     * @var array Les types MIME autorisés pour les vidéos téléchargées.
     */
    protected $allowedMimeTypes = ['video/mp4', 'video/webm', 'video/quicktime'];

    /**
     * Valide le fichier uploadé.
     *
     * @return bool
     */
    public function validateFile(): bool
    {
        if (!parent::isFileUploaded()) {
            $this->errors[] = 'Aucun fichier n\'a été envoyé.';
            return false;
        }

        $file = $_FILES[$this->fieldName];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $this->handleUploadError($file['error']);
            return false;
        }

        if ($file['size'] > $this->maxFileSize) {
            $this->errors[] = 'Le fichier est trop volumineux (max ' . $this->formatBytes($this->maxFileSize) . ').';
            return false;
        }

        if (!in_array($file['type'], $this->allowedMimeTypes, true)) {
            $this->errors[] = 'Type de fichier non autorisé (' . $file['type'] . '). Types autorisés : ' . implode(', ', $this->allowedMimeTypes);
            return false;
        }

        return true;
    }

    /**
     * Déplace le fichier uploadé vers le répertoire de destination.
     *
     * @return bool
     */
    public function moveFile(): bool
    {
        $file = $_FILES[$this->fieldName];
        $this->baseFileName = pathinfo($file['name'], PATHINFO_FILENAME);
        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);

        if ($this->renameFiles) {
             $this->finalFileName = $this->baseFileName . '_' . time() . '.' . $fileExtension;
        }
        else{
            $this->finalFileName = $file['name'];
        }


        $destination = $this->uploadDir . $this->finalFileName;

        // Crée le répertoire de destination s'il n'existe pas
        if (!is_dir($this->uploadDir)) {
            if (!mkdir($this->uploadDir, $this->directoryPermissions, true) && !is_dir($this->uploadDir)) {
                $this->errors[] = 'Impossible de créer le répertoire de destination.';
                return false;
            }
        }
        // Déplace le fichier
        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            $this->errors[] = 'Impossible de déplacer le fichier téléchargé.';
            return false;
        }

        return true;
    }

    /**
     * Traite le téléchargement du fichier.
     *
     * @return bool
     */
    public function handleUpload(): bool
    {
        if (!$this->isFileUploaded()) {
            $this->errors[] = 'Aucun fichier n\'a été envoyé.';
            return false;
        }

        if (!$this->validateFile()) {
            return false;
        }

        if (!$this->moveFile()) {
            return false;
        }

        return true;
    }
}