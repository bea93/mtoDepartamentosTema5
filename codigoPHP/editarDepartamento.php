<?php
/*
  @author: Bea Merino
  @since: 07/04/2021
  @description: Editar Departamento
 */

session_start(); // inicia una sesion, o recupera una existente
if(!isset($_SESSION['usuarioDAW213MtoDepartamentosTema5'])){ // si no se ha logueado le usuario
    header('Location: login.php'); // redireige a la pagina del login
    exit;
}

if (isset($_POST["Modificar"])) {
    header('Location: mtoDepartamentos.php');
}
if (isset($_POST["Cancelar"])) {
    header('Location: mtoDepartamentos.php');
}
//Importa la librería de validación
require '../core/210322ValidacionFormularios.php';
//Fichero de configuración de la BBDD
require_once '../config/confDB.php';
//Inicializa una variable que nos ayudará a controlar si todo esta correcto
$entradaOK = true;

//Inicializamos un array que se encargará de recoger los errores(Campos vacíos)
$aErrores = [
    'DescDepartamento' => null,
    'VolumenNegocio' => null
];

try {
 //Objeto PDO con los datos de conexión
                    $miBD = new PDO(HOST, USER, PASS);
                    $miBD->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                  
                    $sql = "SELECT * FROM T02_Departamento WHERE T02_CodDepartamento=:CodDepartamento";

                    $consultaObtencionDepartamento = $miBD->prepare($sql); // preparo la consulta

                    $parametros = [":CodDepartamento" => $_SESSION['CodDepartamento']]; // asigno los valores del formulario en el array de parametros

                    $consultaObtencionDepartamento->execute($parametros); // ejecuto la consulta pasando los parametros del array de parametros

                    $oDepartamento = $consultaObtencionDepartamento->fetchObject(); //guarda en la variable el resultado de la consulta en forma de objeto

                    $codDepartamento = $oDepartamento->T02_CodDepartamento; // guarda el codigo de departamento en una variable
                    $descDepartamento = $oDepartamento->T02_DescDepartamento; // guarda la descripcion del departamento en una variable

                    $volumenNegocio = $oDepartamento->T02_VolumenNegocio; // guarda el volumen de negocio del departamento en una variable
} catch (PDOException $mensajeError) {
    //Mensaje de salida
    echo "Error " . $mensajeError->getMessage() . "<br>";
    //Código del error
    echo "Codigo del error " . $mensajeError->getCode() . "<br>";
} finally {
    //Cerramos la conexion
    unset($miBD);
}


if (isset($_REQUEST['Modificar'])) { //Comprobamos que el usuario haya enviado el formulario
    $aErrores['DescDepartamento'] = validacionFormularios::comprobarAlfaNumerico($_REQUEST['DescDepartamento'], 255, 1, OBLIGATORIO); //Comprobamos que la descripción del departamento sea alfanumérico
    $aErrores['VolumenNegocio'] = validacionFormularios::comprobarFloat($_REQUEST['VolumenNegocio'], PHP_FLOAT_MAX, PHP_FLOAT_MIN, OBLIGATORIO); //Comprobamos que el volumen de negocio sea float
    // Recorremos el array de errores
    foreach ($aErrores as $campo => $error) {
        if ($error != null) { // Comprobamos que el campo no esté vacio
            $entradaOK = false; // En caso de que haya algún error le asignamos a entradaOK el valor false para que vuelva a rellenar el formulario      
            $_REQUEST[$campo] = "";
        }
    }
} else {
    $entradaOK = false; // Si el usuario no ha enviado el formulario asignamos a entradaOK el valor false para que rellene el formulario
}
if ($entradaOK == true && isset($_REQUEST['Modificar'])) {
    try {
        $miBD = new PDO(HOST, USER, PASS);
        $miBD->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sqlDepartamento = 'UPDATE T02_Departamento SET T02_DescDepartamento=:DescDepartamento, T02_VolumenNegocio=:VolumenNegocio WHERE T02_CodDepartamento=:CodDepartamento';
        $consulta2 = $miBD->prepare($sql2); // preparo la consulta

        $parametros = [":DescDepartamento" => $_REQUEST['DescDepartamento'], // asigno los valores del formulario en el array de parametros
                       ":VolumenNegocio" => $_REQUEST['VolumenNegocio'],
                       ":CodDepartamento" => $codDepartamento];

        $consulta2->execute($parametros);
    } catch (PDOException $mensajeError) {
        //Mensaje de salida
        echo "Error " . $mensajeError->getMessage() . "<br>";
        //Código del error
        echo "Codigo del error " . $mensajeError->getCode() . "<br>";
    } finally {
        //Cerramos la conexion
        unset($miBD);
    }
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
                <style>
                    .obligatorio{
                        background-color: lightgray;
                    }
                    .error{
                        color: red;
                    }
                </style>

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
                            <h2 class="fh5co-heading animate-box" data-animate-effect="fadeInLeft">Editar departamento</h2>
                            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" name="Añadirformulario" method="POST">
                                <fieldset>
                                    <label for="codigo">Código:</label>
                                    <input type="text" name="codigo2" id="codigo2" disabled style="border: 1px solid black" 
                                           value="<?php echo $codDepartamento?>"><br><br>
                                    <input type="hidden" name="codigo" id="codigo" value="<?php echo $codDepartamento?>">

                                    <label for="descripcion">Descripción:</label>
                                    <input type="text" name="descripcion" id="descripcion" class="obligatorio" lenght="25px" style="border: 1px solid black" 
                                           value="<?php
                                           if (isset($_REQUEST['DescDepartamento'])) {
                                               if ($aErrores['DescDepartamento'] != null) {
                                                   echo $descDepartamento;
                                               } else {
                                                   echo $_REQUEST['DescDepartamento'];
                                               }
                                           } else {
                                               echo $descDepartamento;
                                           }
                                           ?>">
                                    <?php echo($aErrores['DescDepartamento']!=null ? "<span style='color:red'>".$aErrores['DescDepartamento']."</span>" : null); ?>
                                    <br><br>

                                    <label for="volumen">Volumen de negocio:</label>
                                    <input type="text" name="volumen" id="volumen" class="obligatorio" style="border: 1px solid black" 
                                           value="<?php
                                           if (isset($_REQUEST['VolumenNegocio'])) {
                                               if ($aErrores['VolumenNegocio'] != null) {
                                                   echo $volumenNegocio;
                                               } else {
                                                   echo $_REQUEST['VolumenNegocio'];
                                               }
                                           } else {
                                               echo $volumenNegocio;
                                           }
                                           ?>"><br><br>
                                    <?php echo($aErrores['VolumenNegocio']!=null ? "<span style='color:red'>".$aErrores['VolumenNegocio']."</span>" : null); ?>

                                    <input type="submit" value="Modificar" name="Modificar">
                                    <input type="submit" value="Cancelar" name="Cancelar">
                                </fieldset>
                            </form>
                        </div> 
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