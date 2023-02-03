<?php
require("Conexion.php");
class DatosSummary extends Conexion{
    private $comment_with_date;
    private $notaAvg;
    private $satisfecho;
    private $noSatisfecho;
    private $examenesAvg;
    private $tareasAvg;
    private $notaDia;
    private $fecha;
    public function __construct($profesor){
        parent::__construct();
        if($this->db_conexion!=NULL){
            /*DATO COMENTARIO*/
            $consulta = "SELECT comentario,fecha from encuesta where idProfesor=(select id from usuarios where usuario = '$profesor')";
            $resultado = $this->db_conexion->query($consulta);
            $this->comment_with_date = $resultado->fetchAll(PDO::FETCH_ASSOC);
            $resultado->closeCursor();
            /*DATOS GENERALES*/
            $consulta = "select sum(nota)/count(nota),sum(examenes)/count(examenes),sum(tareas)/count(tareas) from encuesta where idProfesor = (select id from usuarios where usuario='$profesor')";
            $resultado = $this->db_conexion->query($consulta);
            $resultado = $resultado->fetch();

            $this->notaAvg = round($resultado[0],1);
            $this->examenesAvg = round($resultado[1],1);
            $this->tareasAvg = round($resultado[2],1);
            /*DATOS POR DIA*/
            $consulta = "select sum(nota)/count(nota),sum(examenes)/count(examenes),sum(tareas)/count(tareas) from encuesta where idProfesor = (select id from usuarios where usuario='$profesor') group by fecha order by fecha asc;";
            $resultado = $this->db_conexion->query($consulta);
            $resultado = $resultado->fetchAll();
            

            $this->notaDia = $resultado;
            /*FECHAS*/
            $consulta = "select distinct fecha from encuesta where idProfesor = (select id from usuarios where usuario='$profesor') group by fecha order by fecha asc;";
            $resultado = $this->db_conexion->query($consulta);
            $resultado = $resultado->fetchAll();

            $this->fecha = $resultado;

            /*SATISFECHO E INSATISFECHO*/
            $consulta = "SELECT count(satifaccion) from encuesta where idProfesor=(select id from usuarios where usuario = '$profesor') and satifaccion='si'";
            $resultado = $this->db_conexion->query($consulta);
            $resultado = $resultado->fetch();
            $this->satisfecho = $resultado[0];

            $consulta = "SELECT count(satifaccion) from encuesta where idProfesor=(select id from usuarios where usuario = '$profesor') and satifaccion='no'";
            $resultado = $this->db_conexion->query($consulta);
            $resultado = $resultado->fetch();
            $this->noSatisfecho = $resultado[0];
        }else{
            $this->notaAvg= NULL;
            $this->comment_with_date = NULL;
        }
    }
    /*GETTERS*/
    public function getDatosComentario(){
        $resultado = $this->comment_with_date;
        echo();
        return $resultado;
    }
    public function getNota(){
        $resultado = $this->notaAvg;
        return $resultado;
    }
    public function getExamen(){
        $resultado = $this->examenesAvg;
        return $resultado;
    }
    public function getTareas(){
        $resultado = $this->tareasAvg;
        return $resultado;
    }
    public function getSatisfecho(){
        $resultado = $this->satisfecho;
        return $resultado;
    }
    public function getNoSatisfecho(){
        $resultado = $this->noSatisfecho;
        return $resultado;
    }

    public function getNotaDia(){
        $resultado = $this->notaDia;
        return $resultado;
    }
    public function getFecha(){
        $resultado = $this->fecha;
        return $resultado;
    }


    /*IMPRIMIR E ITERAR COMENTARIO IZQ Y DERECHA*/
    public function printCommentAndDate($lado){
        $guardado="";
        if($this->comment_with_date!==NULL && ( $lado=="izquierda" ||$lado=="derecha" || $lado=="todos") ){
            switch ($lado){
                case "izquierda":
                    for($i=0;$i<count($this->comment_with_date);$i++){
                        if($i==0 || $i%2==0){
                            $guardado.="
                            <div class='comment box'>
                            <p id='comment-text'>".$this->comment_with_date[$i]["comentario"]."</p>
                            <p id='date'>".$this->comment_with_date[$i]["fecha"]."</p>
                            </div>";
                        }
                    }
                    return $guardado;
                case "derecha":
                    for($i=0;$i<count($this->comment_with_date);$i++){
                        if($i%2!=0){
                            $guardado.="
                            <div class='comment box'>
                            <p id='comment-text'>".$this->comment_with_date[$i]["comentario"]."</p>
                            <p id='date'>".$this->comment_with_date[$i]["fecha"]."</p>
                            </div>";
                        }
                    }
                    return $guardado;
                    case "todos":
                        for($i=0;$i<count($this->comment_with_date);$i++){
                                $guardado.="
                                <div class='comment box'>
                                <p id='comment-text'>".$this->comment_with_date[$i]["comentario"]."</p>
                                <p id='date'>".$this->comment_with_date[$i]["fecha"]."</p>
                                </div>";
                        }
                    return $guardado;
            }
        }else{
            return "<div class='comment box'>
            <p id='comment-text'>ERROR</p>
            <p id='date'>ERROR</p>
            </div>";
        }
    }

    

/*STATIC METHOD*/

    /*IMPRIMIR MENSAJE DEPENDE DE SI ES NULL O NO*/
    public static function switchPrintNullOrValueMessage($valorCompNull,$no_conecta,$conecta){
        if($valorCompNull===NULL){
            echo $no_conecta;
        }else{
            echo $conecta;
        }
    }
    
}

?>