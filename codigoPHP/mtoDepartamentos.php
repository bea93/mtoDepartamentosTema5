<?php
    /**
        @author: Bea Merino
        @since: 07/04/2021
        @description: Mantenimiento Departamentos Tema 4
    */

    session_start();
    //Importa la librería de validación
    require_once '../core/210322ValidacionFormularios.php';
    //Fichero de configuración de la BBDD
    require_once '../config/confDB.php';

    //Si no hay una sesión iniciada te manda al Login
    if (!isset($_SESSION['usuarioDAW213MtoDepartamentosTema5'])) {
        header('location: login.php');
        exit;
    }

    if(isset($_REQUEST['editar'])){ // si se ha pulsado el boton editar
        $_SESSION['CodDepartamento']=$_REQUEST['editar']; // asignacion del codigo de departamento a la variable de sesion 'CodDepartamento'
        header('Location: editarDepartamento.php'); // redirige a la ventana de editar departamento
        exit;
    }

    if(isset($_REQUEST['mostrar'])){ // si se ha pulsado el boton consultar
        $_SESSION['CodDepartamento']=$_REQUEST['mostrar']; // asignacion del codigo de departamento a la variable de sesion 'CodDepartamento'
        header('Location: mostrarDepartamento.php'); // redirige a la ventana de mostrar departamento
        exit;
    }

    if(isset($_REQUEST['borrar'])){ // si se ha pulsado el boton borrar
        $_SESSION['CodDepartamento']=$_REQUEST['borrar']; // asignacion del codigo de departamento a la variable de sesion 'CodDepartamento'
        header('Location: bajaDepartamento.php'); // redirige a la ventana de baja departamento
        exit;
    }

    if(isset($_REQUEST['importar'])){ // si se ha pulsado  el boton importar
        header('Location: importarDepartamentos.php'); // redirige a la ventana importar departamentos
        exit;
    }
    if(isset($_REQUEST['exportar'])){ // si se ha pulsado el boton exportar
        header('Location: exportarDepartamentos.php'); // redirige al archivo de exportar departamentos
        exit;
    }


    ?>
<!DOCTYPE html>
<html class="no-js">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Bea Merino</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
        <link rel="shortcut icon" href="webroot/images/favicon.png">

        <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,600,400italic,700' rel='stylesheet'
              type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>

        <!-- Animate.css -->
        <link rel="stylesheet" href="../webroot/css/animate.css">
        <!-- Icomoon Icon Fonts-->
        <link rel="stylesheet" href="../webroot/css/icomoon.css">
        <!-- Bootstrap  -->
        <link rel="stylesheet" href="../webroot/css/bootstrap.css">
        <!-- Owl Carousel -->
        <link rel="stylesheet" href="../webroot/css/owl.carousel.min.css">
        <link rel="stylesheet" href="../webroot/css/owl.theme.default.min.css">
        <!-- Theme style  -->
        <link rel="stylesheet" href="../webroot/css/style.css">

        <!-- Modernizr JS -->
        <script src="../webroot/js/modernizr-2.6.2.min.js"></script>
    </head>
    <body>
        <div id="fh5co-page">
            <aside id="fh5co-aside" role="complementary" class="border js-fullheight">
                <h1 id="fh5co-logo"><a href="../../proyectoDWES/indexProyectoDWES.html"><img src="../webroot/images/logo.png"
                                                                                             alt="Free HTML5 Bootstrap Website Template"></a></h1>
                <nav id="fh5co-main-menu" role="navigation">
                    <ul>
                        <li class="fh5co-active" style="color: #1512da">Log in Log out Tema 5</li>
                        <li><a href="programa.php">Volver</a></li>
                    </ul>
                </nav>
                <div class="fh5co-footer">
                    <p style="font-size: 1.5em"><a style="text-decoration: none; color: black" href="https://github.com/bea93/LogInLogOutTema5/tree/Developer" target="_blank">GitHub</a></p>
                    <p><a href="../../index.html" style=" text-decoration: none; color: black">&copy; 2021 Beatriz Merino Macía.</a></p>
                </div>
            </aside>
            <div id="fh5co-main">
                <div class="fh5co-narrow-content">
                    <h2 class="fh5co-heading animate-box" data-animate-effect="fadeInLeft">Mto. Departamentos Tema 4</h2>
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <fieldset>
                        <div class="obligatorio">
                            Descripción Departamento: 
                            <input style="border: 1px solid black" type="text" name="descDepartamento"
                                   value="<?php
                                   if (isset($_REQUEST['descDepartamento'])) {
                                       echo $_REQUEST['descDepartamento'];
                                   }
                                   ?>">
                            <br>
                            <br>
                        </div>
                    </fieldset>
                    <input type="submit" name="enviar" value="Buscar">
                    <a href="altaDepartamento.php"><input type="button"  name="anadir" value="Añadir"></a>
                    <a href="importarDepartamentos.php"><input type="button"  name="importar" value="Importar"></a>
                    <a href="exportarDepartamentos.php"><input type="button"  name="exportar" value="Exportar"></a>
                    <br><br>
                </form>

                <?php

                try {
                    //Objeto PDO con los datos de conexión
                    $miBD = new PDO(HOST,USER,PASS);
                    $miBD->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    //Saca la descripción del departamento a buscar 
                    $descrip = "";
                    if (isset($_REQUEST['descDepartamento'])) {
                        $descrip = $_REQUEST['descDepartamento'];
                    }

                    //Consulta SQL para mostrar un departamento por la descripción
                    $sentenciaSQL = "SELECT * FROM T02_Departamento WHERE T02_DescDepartamento LIKE '%$descrip%';"; 
                    $consultaSelect = $miBD->prepare($sentenciaSQL);
                    $consultaSelect->execute();
                    
                    //Si la consulta no devuelve ningún valor se muestra ese mensaje
                    if ($consultaSelect->rowCount() == 0) {
                        echo "No se ha encontrado ningún departamento con esa descripcion";
                    } else {
                        ?>
                    <form name="formularioBotones" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                            <table border='0'>
                                <tr>
                                    <th>Codigo</th>
                                    <th>Descripción</th>
                                    <th>Volumen de negocio</th>
                                    <th></th>
                                </tr>
                                <?php
                                //Al realizar el fetchObject, se pueden sacar los datos de $registro como si fuera un objeto
                                //Obtenemos la siguiente fila del resultado de la consulta y avanzamos el puntero a la siguiente fila
                                $registro = $consultaSelect->fetchObject();
                                while ($registro) {
                                    $codDepartamento = $registro->T02_CodDepartamento; // variable que almacena el codigo del departamento
                                    ?>
                                    <tr>
                                        <td><?php echo $codDepartamento?></td>
                                        <td><?php echo $registro->T02_DescDepartamento?></td>
                                        <td><?php echo $registro->T02_VolumenNegocio?></td>
                                        <td>
                                            <button type="submit" name='mostrar' value="<?php echo $codDepartamento;//Almacenamos el valor del codigo del departamento devuelto por la consulta en el valor del boton ?>" style="background-color: transparent; border: 0;" ><i class='far fa-eye'></i></button> 
                                        </td>
                                        <td>
                                            <button type="submit" name='editar' value="<?php echo $codDepartamento;//Almacenamos el valor del codigo del departamento devuelto por la consulta en el valor del boton ?>" style="background-color: transparent; border: 0;"><i class='fas fa-pencil-alt'></i></button>
                                        </td>
                                        <td>
                                            <button type="submit" name='borrar' value="<?php echo $codDepartamento;//Almacenamos el valor del codigo del departamento devuelto por la consulta en el valor del boton ?>" style="background-color: transparent; border: 0;"><i class='far fa-trash-alt'></i></button>
                                        </td>
                                    </tr>
                                    <?php
                                    //Obtenemos la siguiente fila del resultado de la consulta y avanzamos el puntero a la siguiente fila
                                        $registro = $consultaSelect->fetchObject();
                                    }

                                ?>
                            </table>
                        </form>
                    <?php }
                //Captura la excepción
                } catch (PDOException $mensajeError) {
                    //Mensaje de salida
                    echo "Error " . $mensajeError->getMessage() . "<br>"; 
                    //Código del error
                    echo "Codigo del error " . $mensajeError->getCode() . "<br>"; 
                } finally {
                    //Cerramos la conexion
                    unset($miBD); 
                } ?>
                </div> 
            </div> 
            <!-- jQuery -->
            <script src="../webroot/js/jquery.min.js"></script>
            <!-- jQuery Easing -->
            <script src="../webroot/js/jquery.easing.1.3.js"></script>
            <!-- Bootstrap -->
            <script src="../webroot/js/bootstrap.min.js"></script>
            <!-- Carousel -->
            <script src="../webroot/js/owl.carousel.min.js"></script>
            <!-- Stellar -->
            <script src="../webroot/js/jquery.stellar.min.js"></script>
            <!-- Waypoints -->
            <script src="../webroot/js/jquery.waypoints.min.js"></script>
            <!-- Counters -->
            <script src="../webroot/js/jquery.countTo.js"></script>
            <!-- MAIN JS -->
            <script src="../webroot/js/main.js"></script>
    </body>
</html>