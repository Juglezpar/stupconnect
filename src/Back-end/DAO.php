<?php
require __DIR__ . "/Parametros.php";
class DAO{
    private $ConnectionString;
    private $Conex;

    public function __construct()
    {
        try{
            $this->ConnectionString="mysql:host=" . BD_SERVIDOR . ";port="
            . BD_PUERTO . ";dbname=". BD_NOMBRE . ";charset=" . BD_CHARSET;
            //Conex = PDO class that represent a connedtion between PHP and the database
            $this->Conex= new PDO($this->ConnectionString, BD_USUARIO, BD_PASS); //CREATE DE CONECCTIO WITH DATA BASE
            
        }catch(Exception $ex){
            echo $ex->getMessage();
        }
    }
    //Method to execute the SQL querys
    public function ExecuteQuery($sql="", $values= array()){ //SELECT
        if($sql!="" && strlen($sql)>0){
            try{
                $query= $this->Conex->prepare($sql); //DAO function that prepare the query
                $query->execute($values); //Execute the query
                if(intval($query->errorCode())== 0){ //Check if there is an error in the preparation of the query
                    return  $query->fetchAll(PDO :: FETCH_ASSOC); //Transform the results from BDD to a array of asociative arrays
                } else{
                    return 0;   //Da el error en la consulta,devolvemos la información de el error¿¿Eliminar??
                }
            }catch(Exception $ex){
                return 0;
            }
        }else{ //If the query is empty return 0 (fake response)
            return 0;
        }

    }
    public function InsertData($sql="", $values= array()){ //UPDATE, DELETE, INSERT
        if($sql!="" && strlen($sql)>0){
            try{
                $this->Conex->beginTransaction(); //Start of the transaction, if not possible, rollback to this previous status
                $query= $this->Conex->prepare($sql); //DAO function that prepare the query
                $query->execute($values); //Execute the query
                if(intval($query->errorCode())== 0){ //Check if there is an error in the preparation of the query
                    $this->Conex->commit(); //Confirm the action
                   $rowsAffected= $query->rowCount();
                   return $rowsAffected;
                } else{
                    $this->Conex->rollBack();  //If there is an error rollback to the previous status
                    return -1;
                }
            }catch(Exception $ex){
                $this->Conex->rollBack(); //Return to the previous status
                return $ex->getMessage();
            }
        

        }else{
            return 0;
        }

    }
    public function GetLastInsertId() {
        return $this->Conex->lastInsertId();
    }
    
}

