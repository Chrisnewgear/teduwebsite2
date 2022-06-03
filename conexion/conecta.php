<?php

//comenzamos declarando el nombre de la clse
class conexion {
    //creamos los atributos de la clase
    private $server;
    private $user;
    private $password;
    private $database;
    private $port;
    private $conexion;

    //declaramos el contructor 
    function __construct(){
        //obtenemos los datos del archivo config mediente el metodo datosConexion
        $listadatos = $this->datosConexion();
        foreach ($listadatos as $key => $value) {
            $this->server = $value['server'];
            $this->user = $value['user'];
            $this->password = $value['password'];
            $this->database = $value['database'];
            $this->port = $value['port'];
        }
        $this->conexion = new mysqli($this->server,$this->user,$this->password,$this->database,$this->port);
        if($this->conexion->connect_errno){
            echo "algo va mal con la conexion";
            die();
        }

    }
    
    //obtenemos los datos del archivo config
    private function datosConexion(){
        $direccion = dirname(__FILE__);
        //print($direccion);
        $jsondata = file_get_contents($direccion . "/" . "config.json");        
        return json_decode($jsondata, true);
    }


    //convertiremos los datos obtenidos en utf8
    private function convertirUTF8($array){
        array_walk_recursive($array,function(&$item,$key){
            if(!mb_detect_encoding($item,'utf-8',true)){
                $item = utf8_encode($item);
            }
        });
        return $array;
    }

    //esta funcion la invocaremos cuando necesitemos utilizar un select
    public function obtenerDatos($sqlstr){
        $results = $this->conexion->query($sqlstr);
        $resultArray = array();
        foreach ($results as $key) {
            $resultArray[] = $key;
        }
        return $this->convertirUTF8($resultArray);

    }

    //esta funcion la invocaremos cuando necesitemos utilizar insert,delete,update
    public function nonQuery($sqlstr){
        $results = $this->conexion->query($sqlstr);
        return $this->conexion->affected_rows;
    }


    //UNICAMENTE INSERT YA QUE NOS DEVOLVERA EL ULTIMO ID INSERTADO 
    public function nonQueryId($sqlstr){
        $results = $this->conexion->query($sqlstr);
         $filas = $this->conexion->affected_rows;
         if($filas >= 1){
            return $this->conexion->insert_id;
         }else{
             return 0;
         }
    }
     
    //encriptar CONTRASEÑAS

    protected function encriptar($string){
        return md5($string);
    }

}

?>