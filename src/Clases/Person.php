<?php
//Implementation of clas Person
//This class will be used to save 
//the values of the user during his session
class Person implements JsonSerializable {
    public $name;
    public $email;
    public $Picture;
    public function __construct($n, $e,$pp) //Constructor
    {
        //Assign the google name to te atribute
        $this->name= $n;
        //Assign the gmail to the atribute email
        $this->email = $e; 
        //Assign the google profile pic to the user
        $this ->Picture= $pp;

    }
    public function jsonSerialize(): mixed // We serialize the object as JSON
    {
        return get_object_vars($this); 
    }
}


?>