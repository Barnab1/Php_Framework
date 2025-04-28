<?php
namespace Ninja;

/**
 * Classe de base pour la gestion des téléchargements de fichiers.
 */
abstract class BaseUploadHandler implements UploadHandlerInterface
{
    /**
     * @var string Le nom du champ de formulaire utilisé pour l'upload.
     */
    protected $fieldName;

    /**
     * @var string Le répertoire de destination pour les fichiers téléchargés.
     */
    protected $uploadDir;

    /**
     * @var int La taille maximale autorisée pour les fichiers téléchargés en octets.
     */
    protected $maxFileSize;

     /**
     * @var string Le nom de base du fichier téléchargé.
     */
    protected $baseFileName;

    /**
     * @var string Le nom complet du fichier téléchargé (avec horodatage si activé).
     */
    protected $finalFileName;

    /**
     * @var array Tableau contenant les erreurs survenues lors du traitement de l'upload.
     */
    protected $errors = [];

     /**
     * @var bool Indique si les fichiers doivent être renommés avec un horodatage.
     */
    protected $renameFiles = true;

     /**
     * @var int Permissions du dossier de destination.
     */
    protected $directoryPermissions = 0775;

    /**
     * Constructeur de la classe BaseUploadHandler.
     *
     * @param string $fieldName Le nom du champ de formulaire utilisé pour l'upload (par exemple, 'fichier').
     * @param string $uploadDir Le répertoire de destination pour les fichiers téléchargés.
     * @param int $maxFileSize La taille maximale autorisée pour les fichiers téléchargés en octets.
     */
    public function __construct(string $fieldName, string $uploadDir, int $maxFileSize = 2 * 1024 * 1024)
    {
        $this->fieldName = $fieldName;
        $this->uploadDir = rtrim($uploadDir, '/\\') . '/';
        $this->maxFileSize = $maxFileSize;
    }

    /**
     * Définit si les fichiers doivent être renommés avec un horodatage.
     *
     * @param bool $renameFiles
     * @return $this
     */
    public function setRenameFiles(bool $renameFiles): self
    {
        $this->renameFiles = $renameFiles;
        return $this;
    }

     /**
     * Définit les permissions du dossier de destination.
     *
     * @param int $permissions
     * @return $this
     */
    public function setDirectoryPermissions(int $permissions): self
    {
        $this->directoryPermissions = $permissions;
        return $this;
    }

    /**
     * Vérifie si un fichier a été uploadé.
     *
     * @return bool
     */
    public function isFileUploaded(): bool
    {
        return isset($_FILES[$this->fieldName]) && $_FILES[$this->fieldName]['error'] !== UPLOAD_ERR_NO_FILE;
    }

     /**
     * Retourne les erreurs survenues lors du traitement de l'upload.
     *
     * @return array Un tableau de chaînes de caractères représentant les erreurs.
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

     /**
     * Retourne le nom final du fichier.
     *
     * @return string
     */
    public function getFinalFileName(): string
    {
        return $this->finalFileName;
    }

    /**
     * Formatte une taille de fichier en une chaîne lisible (par exemple, 2MB, 1.5GB).
     *
     * @param int $bytes La taille du fichier en octets.
     * @param int $precision La précision du nombre de décimales.
     * @return string La taille formatée.
     */
    protected function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * Gestionnaire d'erreurs pour les erreurs d'upload PHP.
     *
     * @param int $errorCode Le code d'erreur d'upload PHP.
     */
    protected function handleUploadError(int $errorCode): void
    {
        switch ($errorCode) {
            case UPLOAD_ERR_INI_SIZE:
                $this->errors[] = 'Le fichier dépasse la taille maximale autorisée par le serveur (upload_max_filesize).';
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $this->errors[] = 'Le fichier dépasse la taille maximale autorisée par le formulaire (MAX_FILE_SIZE).';
                break;
            case UPLOAD_ERR_PARTIAL:
                $this->errors[] = 'Le fichier n\'a été que partiellement téléchargé.';
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $this->errors[] = 'Le dossier temporaire est manquant.';
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $this->errors[] = 'Impossible d\'écrire le fichier sur le disque.';
                break;
            case UPLOAD_ERR_EXTENSION:
                $this->errors[] = 'L\'upload du fichier a été interrompu par une extension PHP.';
                break;
            default:
                $this->errors[] = 'Erreur inconnue lors du téléchargement du fichier.';
        }
    }
}