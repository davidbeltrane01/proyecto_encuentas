<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./main.css" rel="stylesheet"></link>
    <link href="./style.css" rel="stylesheet"></link>
    <script src="main.js"></script>
    <title>Resumen</title>
</head>
<body>
    <div class="page">
    <?php
        require("optionsbbdd.php");

        error_reporting(0);
        mysqli_report(MYSQLI_REPORT_ERROR|MYSQLI_REPORT_STRICT);


        try{
            $conexion = new mysqli($host,$user,$pass,$bbdd) or die("ERROR");
            /*Consulta  comentario*/
            $consulta = "SELECT comentario,fecha FROM encuesta";
            $resultado = $conexion->query($consulta);
            $comentario_with_fecha = $resultado->fetch_all(MYSQLI_ASSOC);

            /*Consulta Puntuacion*/
            $consulta = "select sum(nota)/count(nota) from encuesta";
            $resultado = $conexion->query($consulta);
            $notaAvg = $resultado->fetch_row();
        }catch (Exception $e){
            echo "ERROR BASE DE DATOS";
        }

        /*IMPRIMIR COMENTARIO IZQ Y DERECHA*/
        function imprimirComentario($lado){
            global $comentario_with_fecha;
            $guardado="";
            switch ($lado){
                case "izquierda":
                    global $guardado;
                    for($i=0;$i<count($comentario_with_fecha);$i++){
                        global $guardado;
                        if($i==0 || $i%2==0){
                            global $guardado;
                            $guardado.="
                            <div class='comment box'>
                            <p id='comment-text'>".$comentario_with_fecha[$i]["comentario"]."</p>
                            <p id='date'>".$comentario_with_fecha[$i]["fecha"]."</p>
                            </div>";
                            
                        }
                    }
                    return $guardado;
                    break;
                case "derecha":
                    global $guardado;
                    for($i=0;$i<count($comentario_with_fecha);$i++){
                        global $guardado;
                        if($i%2!=0){
                            global $guardado;
                            $guardado.="
                            <div class='comment box'>
                            <p id='comment-text'>".$comentario_with_fecha[$i]["comentario"]."</p>
                            <p id='date'>".$comentario_with_fecha[$i]["fecha"]."</p>
                            </div>";
                        }
                    }
                    return $guardado;
                    break;
                default:
                    $guardado =  "<div class='comment box'>
                    <p id='comment-text'>"."ERROR"."</p>
                    <p id='date'>"."ERROR"."</p>
                    </div>";
                    return $guardado;
            }
        }

        /*IMPRIMIR MENSAJE DEPENDE DE SI ES NULL O NO*/
        function switchPrintValueNullMessage($valorCompNull,$conecta,$no_conecta){
            if($valorCompNull===NULL){
                echo $no_conecta;
            }else{
                echo $conecta;
            }
        }
    ?>
        


        <div class="hamburger-menu">
            <div id="menuToggle" class="no-selectable">
                <input type="checkbox"/>
                <span></span>
                <span></span>
                <span></span>
                <ul id="menu">
                  <a href="./index.html"><li>Inicio</li></a>
                  <a href="./form.html"><li>Encuesta</li></a>
                  <a href="#"><li>Resumen</li></a>
                  <a href="#"><li>Contacto</li></a>
                </ul>
            </div>
        </div>
        
        <div class="menu">
            <img src="./logo-example.png" alt="logo">
            <a href="./index.html">Inicio</a>
            <a href="./form.html">Encuesta</a>
            <a href="">Contacto</a>
        </div>


        <div class="content">
            <div class="header">
                <div class="prev-summary">
                    <div class="summary box">
                        <table class="summary-table">
                            <thead>
                                <th colspan="2">Resumen</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Valoración</td>
                                    <td> <?php switchPrintValueNullMessage($notaAvg,round($notaAvg[0],1),"-"); ?> </td>
                                </tr>
                                <tr>
                                    <td>Satisfecho</td>
                                    <td>173</td>
                                </tr>
                                <tr>
                                    <td>Insatisfecho</td>
                                    <td>46</td>
                                </tr>
                                <tr>
                                    <td>Tareas</td>
                                    <td>9</td>
                                </tr>
                                <tr>
                                    <td>Exámenes</td>
                                    <td>3.4</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="prev-graph">
                    <div class="graph box"></div>
                </div>
            </div>
            <div class="divisor">
                <div class="half-1"></div>
                <div class="divisor-text">
                    <p>Comentarios</p>
                </div>
                <div class="half-2"></div>
            </div>
            <div class="comments">
                <div class="comments-left">
                    <?php switchPrintValueNullMessage($comentario_with_fecha,imprimirComentario("izquierda"),imprimirComentario("error")); ?>
                </div>
                <div class="comments-right">
                    <?php switchPrintValueNullMessage($comentario_with_fecha,imprimirComentario("derecha"),imprimirComentario("error")); ?>
                </div>

                
            </div>
        </div>
        <div class="footer">
            <p>Texto de ejemplo en el footer</p>
        </div>
    </div>
    <?php mysqli_close($conexion) ?>
</body>
<script>
setTimeout(() => {noWLogo()}, 0);
</script>
</html>