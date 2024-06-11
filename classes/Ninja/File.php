<?php
declare(strict_types = 1);
namespace Ninja;

/*********************************************************************************************
 * *******************************************************************************************
 * File.php
 * *******************************************************************************************
 * That Function will ensure the treatment of files
 * *******************************************************************************************
  
 */

 class File 
 {

/* The type of the file : image, documents
 * 
 * @storageValue1 @storageValue2
 * are used for making a file
 * name based of at least two credentials from
 * the user. 
 * *******************************************
 * EXAMPLE : For example assuming one user is named 
 * Jean and its unique identifier is 9 we could give
 *  its picture a name of :
 *  jean9.png
 * 
 ************************************************* 
 * By default png is used bacause i don't know already 
 * how automating the process from outside
*/


    /**
     * The first Storage value
     * 
    */
    protected $storageValue1;
   
    protected $storageValue2;//place 2 of storage :could be left
    protected $storageDirectory;//
    protected $filetype;
    protected $extensions_allowed;

    public function __construct(
    string $storageValue1,
    string $storageDirectory,
    string $filetype,
    array $extensions_allowed,
    string $storageValue2 = '',
                                )

    {
        $this->storageValue1= strtolower($this->remove_character($storageValue1));
        $this->storageDirectory = $storageDirectory;
        $this->storageValue2 = $storageValue2;
        $this->filetype = $filetype;
        $this->extensions_allowed = $extensions_allowed;

       
    }

    /**
     * STEPS :
     *1- checking if there is a file sent
     *2- checking if the file's extension 
     *  is right according its type 
     *3- Saving  the file to the destination
     *provided
     *
     * 
     * 
     */
     public function saveFile($filename)
     {
        $infos_on_file = '';
        $valid = true;
        $errors = [];
       /**
        * STEP 1
        */

        //This variable will track all the errors
        // encountered during the process
      

        if(!$_FILES){
            $valid = false;
            $errors[] = 'Aucun fichier n\'a été envoyé';
        } 


            /**
             * STEP 2
             */
        
        if($this->checkIfFileRespectFormat($filename,$this->extensions_allowed) == false)
            {
                $valid = false;
                $errors[] = 'le format du fichier n\est pas supporté';
            }
            

            /**
             * STEP 3
             */
        if($valid == true)
        {
            $saveto=$this->storageDirectory.str_replace(' ','',strtolower($this->storageValue1)).$this->storageValue2.'.'.$this->filetype;
            $this->saveFileWhenOk($saveto,$filename);
            return true;
        }else
        {
            return $errors;
        }

    }


    protected function saveFileWhenOk($saveto,$filename,$extensions_allowed = [])
    {

        if($this->checkIfFileRespectFormat($filename,$extensions_allowed) == true)
        {
            move_uploaded_file($_FILES[$filename]['tmp_name'],$saveto);
           
        }
    }

    protected function checkIfFileRespectFormat($filename, $extensions_allowed  )
    {
            $infosfichier = pathinfo($_FILES[$filename]['name']);
            $extension_upload = $infosfichier['extension'];

            if(in_array($extension_upload,$extensions_allowed))
            {
               return true;
            }
            else
            {
                return false;
            }
    }
    
    
    /** FONCTION REMOVE CHARACTER
     * 
     * LE BUT DE CETTE FUNCTION EST DE RENVOYER UNIQUEMENT LE PARAMETRE SANS UN CARACTERE DONNE. 
    */
    private function remove_character($letters)
    {

        $letters=str_replace(' ','',$letters);
        $letters=str_replace('é','e',$letters);
            
        //oter tout vide au début de la chaine de caractère
        //récuperer les deux premières lettres
       
       
       return $letters;
    }
 }