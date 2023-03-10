<?php
require_once("Conexion.php");
class Form {
    private $profesor;
    private $lista_Profesores;
    private $comentario;
    private $nota;
    private $satifaccion;
    private $examenes;
    private $tareas;
    public function __construct(){
        $consulta = "select usuario from usuarios where tipo= 'Usuario'";
        $resultado = Conexion::getConexion()->query($consulta);
        $this->lista_Profesores = $resultado->fetchAll();
    }
    /*SETTER*/
    public function setData($postComment,$postNota,$postSatifaccion,$postExamenes,$postTareas){
        $this->comentario = htmlentities(addslashes($postComment));
        $this->nota = htmlentities(addslashes($postNota));
        $this->satifaccion = htmlentities(addslashes($postSatifaccion));
        $this->examenes = htmlentities(addslashes($postExamenes));
        $this->tareas = htmlentities(addslashes($postTareas));
    }
    public function setProfesor($profesor){
        $this->profesor= htmlentities(addslashes($profesor));
    }
    /*GETTER*/
    public function getListaProfesores(){
        return $this->lista_Profesores;
    }

    public function enviarFormulario(){
        $consulta = "INSERT INTO encuesta (nota, comentario,satifaccion,tareas,examenes,idProfesor, fecha) VALUES (:NOTA,:COMENTARIO,:SATIFACCION,:TAREAS,:EXAMENES,(select id from usuarios where usuario = :PROFESOR),CURRENT_DATE)";
        $resultado=Conexion::getConexion()->prepare($consulta);
        $arrayDatos = array(":NOTA"=> $this->nota,":COMENTARIO"=> $this->comentario,":SATIFACCION"=> $this->satifaccion,":TAREAS"=> $this->tareas,":EXAMENES"=> $this->examenes,":PROFESOR"=> $this->profesor);
        if ($resultado->execute($arrayDatos)) {
            echo "CONEXION";
        } else {
            echo "ERROR";
        }
    }
    public function PrintProfesoresHTMLSelect(){
        for ($i = 0; $i < count($this->lista_Profesores); $i++) {
            echo "<option value=" . $this->lista_Profesores[$i][0] . ">" . $this->lista_Profesores[$i][0] . "</option>";
        }
    }

}
?>