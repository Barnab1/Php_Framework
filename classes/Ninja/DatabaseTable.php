<?php
namespace Ninja;

use PDO;

//use PDOException;

class DatabaseTable

{
    private $pdo;
    private $table;
    private $primaryKey;
    private $className;
    private $constructArgs;

    public function __construct(\PDO $pdo, string $table, string $primaryKey,string $className='\stdClass', array $constructArgs=[])
    {
      $this->pdo=$pdo;
      $this->table=$table;
      $this->primaryKey=$primaryKey;
      $this->className=$className;
      $this->constructArgs=$constructArgs;  
    }


private function query($sql,$parameters=[])
{
$query=$this->pdo->prepare($sql);
$query->execute($parameters);
return $query;
}


private function processDates($fields)
{
    foreach($fields as $key=>$value)
    {
        if($value instanceof \DateTime)
        {
            $fields[$key]=$value->format('Y-m-d');
        }
    }
    return $fields;
}



//public functions


public function findAll($orderBy=null,$limit=null,$offset=null)
{
$query='SELECT * FROM `'. $this->table.'`';
if($orderBy != null)
{
    $query.='ORDER BY '.$orderBy  ;
}

if($limit !=null)
{
    $query.=' LIMIT '. $limit;
}

if($offset !=null)
{
    $query.=' OFFSET '. $offset;
}

$result=$this->query($query);
return $result->fetchAll(PDO::FETCH_CLASS,$this->className,$this->constructArgs);
}

public function findById($value)
{
    $sql='SELECT * FROM `'.$this->table. '` WHERE '.$this->primaryKey. '=:primaryKey';
    $parameters=[':primaryKey'=>$value];
    $result=$this->query($sql,$parameters);
    return $result->fetchObject($this->className,$this->constructArgs);
}

public function total($field=null,$value=null)
{
    $sql='SELECT COUNT(*) FROM `'.$this->table.'`';
    $parameters= [];

    if(!empty($field))
    {
        $sql.=' WHERE `'. $field. '`= :value';
        $parameters=['value'=>$value];
    }

    $result=$this->query($sql,$parameters);
    $row=$result->fetch();
    return $row[0];

}


public function insert($record)
{
   
    //$sql='INSERT INTO `'.$table.'` (`joketext`,`jokedate`,`authorid`) VALUES (:joketext,:jokedate,:authorid)';
    $sql='INSERT INTO `'. $this->table.'`(';
    foreach($record as $key=>$value)
    {
        $sql.='`'. $key .'` ,';
    }
    $sql=rtrim($sql,',');

    $sql.=') VALUES (';

    foreach($record as $key=>$value)
    {
        $sql.=' :'.$key. ' ,';
    }
    $sql=rtrim($sql,',');

    $sql.=')';
    $record=$this->processDates($record);
        //var_dump($sql);
    $this->query($sql,$record);
    $this->pdo->lastInsertId();

}

public function update($record)
{
    //$sql='update table unknown SET `row1`=:row1,..`rown`=:rown' WHERE `id`=:id
    $sql='UPDATE `'. $this->table .'` SET ';
    foreach($record as $key=>$value)
    {
        $sql.='`'.$key.'`= :'.$key. ',';
    }
    $sql=rtrim($sql,',');

    $sql.=' WHERE `'.$this->primaryKey. '` =:primaryKey';
    $record['primaryKey'] = $record['id'];

    $record=$this->processDates($record);
    //echo 'update';
    //var_dump($record);
    //echo '<br><br>';
    
    $this->query($sql,$record);
    

}

public function save($record)
{
    $entity= new $this->className(...$this->constructArgs);
    

        if(empty($record[$this->primaryKey]))
        {
            $record[$this->primaryKey] = null;
           // print_r($record);
            $insertId = $this->insert($record);
            $entity->{$this->primaryKey} == $insertId;
        }
        else
        {
            $this->update($record);
        }

        foreach($record as $key => $value)
        {
            $entity->$key = $value;
        }
        return $entity;
        //return relevant Entity with all properties already set
}

public function delete($value)
{
    $sql='DELETE FROM `'.$this->table.'` WHERE `'.$this->primaryKey. '`= :value';
    $parameters=['value'=>$value];
    
    $this->query($sql,$parameters);
}


public function find($column,$value,$orderBy=null,$limit=null,$offset=null)
{
    $sql='SELECT * FROM `'.$this->table. '` WHERE `'.$column. '` = :value';
    $parameters=['value'=>$value];

    if($orderBy !=null)
    {
        $sql.='ORDER BY '. $orderBy ;
    }

    if($limit !=null)
    {
        $sql.=' LIMIT '. $limit;
    }

    if($offset !=null)
    {
        $sql.=' OFFSET '. $offset;
    }

   $result =$this->query($sql,$parameters);

   return $result->fetchAll(PDO::FETCH_CLASS,$this->className,$this->constructArgs);
    
}

public function deleteWhere($column,$value)
{
    $sql='DELETE FROM `'. $this->table. '` WHERE `'.$column. '` =:value';
    $parameters=['value'=>$value];
    $this->query($sql,$parameters);
    //$this->pdo->clearstatcache
}


}