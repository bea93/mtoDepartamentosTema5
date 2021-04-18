<?php
/**
  @author Bea Merino
  @since 14/04/2021
  @description: Registro
 */

//Importamos la libreria de validacion
require_once '../core/210322ValidacionFormularios.php';
//Fichero de configuración de la BBDD
require_once '../config/confDB.php';

//Inicializamos una variable que nos ayudara a controlar si todo esta correcto
$entradaOK = true;

//Recupera la sesión del Login
session_start();

//Array para almacenar los errores del formulario
$aErrores = [
    'nombre' => null,
    'desc' => null,
    'pass' => null,
    'pass2' => null
];

try {
    // Datos de la conexión a la base de datos
    $miBD = new PDO(HOST, USER, PASS);
    $miBD->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //Cuando se produce una excepcion se corta el programa y salta la excepción con el mensaje de error
} catch (PDOException $mensajeError) {
    echo "<h3>Mensaje de ERROR</h3>";
    echo "Error: " . $mensajeError->getMessage() . "<br>";
    echo "Código de error: " . $mensajeError->getCode();
}

//Si se ha pulsado enviar
if (isset($_POST['enviar'])) {
    //La posición del array de errores recibe el mensaje de error si hubiera
    $aErrores['nombre'] = validacionFormularios::comprobarAlfabetico($_POST['nombre'], 50, 1, 1);
    $aErrores['desc'] = validacionFormularios::comprobarAlfaNumerico($_POST['desc'], 255, 1, 1);
    $aErrores['pass'] = validacionFormularios::comprobarAlfaNumerico($_POST['pass'], 64, 1, 1);
    $aErrores['pass2'] = validacionFormularios::comprobarAlfaNumerico($_POST['pass2'], 64, 1, 1);

    //Comprobación de que el usuario a registrar no exista ya y de que las contraseñas introducidas sean iguales
    if (isset($_POST['nombre']) && isset($_POST['pass']) && isset($_POST['pass2'])) {
        if ($_POST['pass'] === $_POST['pass2']) {
            $codUsuario = $_POST['nombre'];
            $password = $_POST['pass'];
            $consultaSQL1 = "SELECT * FROM T01_Usuario WHERE T01_CodUsuario LIKE '$codUsuario'";
            $resultadoSQL1 = $miBD->query($consultaSQL1);
            if ($resultadoSQL1->rowCount() === 1) {
                $aErrores['nombre'] = "Nombre de usuario ya existente";
            }
        } else {
            $aErrores['pass2'] = "Las contraseñas no coinciden";
        }
    }
    //Recorre el array en busca de mensajes de error
    foreach ($aErrores as $campo => $error) {
        if ($error != null) {
            $entradaOK = false;
        }
    }
} else {
    //Cambiamos el valor de la variable porque no se ha pulsado el botón
    $entradaOK = false;
}
if ($entradaOK) {
    //Sentencias SQL para crear el nuevo usuario, actualizar la fecha de última conexión y el número de conexiones
    $consultaSQL = "INSERT INTO T01_Usuario(T01_CodUsuario, T01_DescUsuario, T01_Password) VALUES (:codigo, :desc, SHA2(:pass,256));";
    $resultadoSQL = $miBD->prepare($consultaSQL);
    $resultadoSQL->execute(array(':codigo' => $_POST['nombre'], ':desc' => $_POST['desc'], ':pass' => $_POST['nombre'] . $_POST['pass']));

    $fechaSQL = "UPDATE T01_Usuario SET T01_FechaHoraUltimaConexion = " . time() . " WHERE T01_CodUsuario = :codigo;";
    $actualizarFechaSQL = $miBD->prepare($fechaSQL);
    $actualizarFechaSQL->execute(array(':codigo' => $_POST['nombre']));

    $conexionesSQL = "UPDATE T01_Usuario SET T01_NumConexiones = T01_NumConexiones + 1 WHERE T01_CodUsuario = :codigo;";
    $actualizarConexionesSQL = $miBD->prepare($conexionesSQL);
    $actualizarConexionesSQL->execute(array(':codigo' => $_POST['nombre']));

    $_SESSION['usuarioDAW213MtoDepartamentosTema5'] = $_POST['nombre'];
    $_SESSION['descUser213'] = $_POST['desc'];
    $_SESSION['ultconex213'] = null;
    $_SESSION['perfil213'] = "usuario";
    //Una vez ejecutadas las sentencias se redirige a programa.php
    header("Location: programa.php");
} else {
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
            <style>
                .error{
                    color: red;
                }
            </style>
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
                        </ul>
                    </nav>
                    <div class="fh5co-footer">
                        <p style="font-size: 1.5em"><a style="text-decoration: none; color: black" href="https://github.com/bea93/LogInLogOutTema5/tree/Developer" target="_blank">GitHub</a></p>
                        <p><a href="../../index.html" style=" text-decoration: none; color: black">&copy; 2021 Beatriz Merino Macía.</a></p>
                    </div>
                </aside>
                <div id="fh5co-main">
                    <div class="fh5co-narrow-content">
                        <h2 class="fh5co-heading animate-box" data-animate-effect="fadeInLeft">Registro</h2>

                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                            <fieldset>
                                <div class="obligatorio">
                                    <strong>Nombre: </strong>
                                    <input type="text" name="nombre" style="border: 1px solid black" placeholder="Nombre" 
                                           value="<?php
                                           if ($aErrores['nombre'] == NULL && isset($_POST['nombre'])) {
                                               echo $_POST['nombre'];
                                           }?>">
                                    <?php if ($aErrores['nombre'] != NULL) { ?>
                                        <div class="error">
                                            <?php echo $aErrores['nombre']; ?>
                                        </div>   
                                    <?php } ?>                
                                </div><br>

                                <div class="obligatorio">
                                    <strong>Descripción: </strong>
                                    <input type="text" name="desc" style="border: 1px solid black" placeholder="Descripción" 
                                           value="<?php
                                           if ($aErrores['desc'] == NULL && isset($_POST['desc'])) {
                                               echo $_POST['desc'];
                                           }?>"><br>
                                    <?php if ($aErrores['desc'] != NULL) { ?>
                                        <div class="error">
                                            <?php echo $aErrores['desc']; ?>
                                        </div>   
                                    <?php } ?>                
                                </div><br>

                                <div class="obligatorio">
                                    <strong>Contraseña: </strong>
                                    <input type="password" name="pass" style="border: 1px solid black" placeholder="Contraseña" 
                                           value="<?php
                                        if ($aErrores['pass'] == NULL && isset($_POST['pass'])) {
                                            echo $_POST['pass'];
                                        }?>"><br>
                                    <?php if ($aErrores['pass'] != NULL) { ?>
                                        <div class="error">
                                            <?php echo $aErrores['pass']; ?>
                                        </div>   
                                   <?php } ?>                
                                </div><br>

                                <div class="obligatorio">
                                    <strong>Repita la contraseña: </strong>
                                    <input type="password" name="pass2" style="border: 1px solid black" placeholder="Contraseña" 
                                           value="<?php
                                        if ($aErrores['pass2'] == NULL && isset($_POST['pass2'])) {
                                            echo $_POST['pass2'];
                                        }?>"><br>
                                    <?php if ($aErrores['pass2'] != NULL) { ?>
                                        <div class="error">
                                            <?php echo $aErrores['pass2']; ?>
                                        </div>   
                                    <?php } ?>                
                                </div><br>
                                <div>                
                                    <input type="submit" name="enviar" value="REGISTRARSE" >                    
                                    <input type="button" name="cancelar" value="ATRÁS" onclick="location = 'login.php'">
                                </div>
                            </fieldset>
                        </form>
<?php } ?>
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
