<?php
/**
  @author Bea Merino
  @since 14/04/2021
  @description: Inicio de sesión
 */

//Importamos la libreria de validacion
require_once '../core/210322ValidacionFormularios.php';
//Fichero de configuración de la BBDD
require_once '../config/confDB.php';

$entradaOK = true;

//Array para almacenar los errores del formulario
$aErrores = [
    'codUsuario' => null,
    'password' => null
];


//Si se ha pulsado enviar
if (isset($_POST['enviar'])) {
    //La posición del array de errores recibe el mensaje de error si hubiera
    $aErrores['codUsuario'] = validacionFormularios::comprobarAlfabetico($_POST['codUsuario'], 50, 1, 1);
    $aErrores['password'] = validacionFormularios::comprobarAlfaNumerico($_POST['password'], 20, 1, 1);
    //Recorre el array en busca de mensajes de error
    foreach ($aErrores as $campo => $error) {
        if ($error != null) {
            //Cambia la condición de la variable
            $entradaOK = false;
        }
    }
} else {
    //Cambiamos el valor de la variable porque no se ha pulsado el botón
    $entradaOK = false;
}
if ($entradaOK) {
    try {
        // Datos de la conexión a la base de datos
        $miBD = new PDO(HOST, USER, PASS);
        $miBD->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $codUsuario = $_POST['codUsuario'];
        $password = $_POST['password'];
        //Selecciona los datos del usuario que se loguea
        $consultaSQL = "SELECT * FROM T01_Usuario WHERE T01_CodUsuario = :codigo AND T01_Password = :passHash";
        $resultadoSQL = $miBD->prepare($consultaSQL);
        $resultadoSQL->bindValue(':codigo', $codUsuario);
        $resultadoSQL->bindValue(':passHash', hash('sha256', $codUsuario . $password));
        $resultadoSQL->execute();

        //Si el resultado del select devuelve algún valor es que el usuario introducido existe
        if ($resultadoSQL->rowCount() == 1) {
            $usuario = $resultadoSQL->fetchObject();
            session_start();
            $_SESSION['usuarioDAW213MtoDepartamentosTema5'] = $usuario->T01_CodUsuario;
            $_SESSION['ultimaConexionAnterior'] = $usuario->T01_FechaHoraUltimaConexion;
            //Actualiza la fecha de última conexión a la de hoy
            $fechaSQL = "UPDATE T01_Usuario SET T01_FechaHoraUltimaConexion = " . time() . " WHERE T01_CodUsuario = :codigo;";
            $actualizarFechaSQL = $miBD->prepare($fechaSQL);
            $actualizarFechaSQL->bindParam(":codigo", $_SESSION['usuarioDAW213MtoDepartamentosTema5']);
            $actualizarFechaSQL->execute();
            //Actualiza el número de conexiones sumándole 1
            $conexionesSQL = "UPDATE T01_Usuario SET T01_NumConexiones = T01_NumConexiones + 1 WHERE T01_CodUsuario = :codigo;";
            $actualizarConexionesSQL = $miBD->prepare($conexionesSQL);
            $actualizarConexionesSQL->bindParam(":codigo", $_SESSION['usuarioDAW213MtoDepartamentosTema5']);
            $actualizarConexionesSQL->execute();
            //Una vez ejecutadas las sentencias se redirige a programa.php
            header("Location: programa.php");
        } else {
            //Si el resultado del select no devuelve ningún valor, el usuario no existe, se queda en el login
            header('Location: login.php');
        }
        //Cuando se produce una excepcion se corta el programa y salta la excepción con el mensaje de error
    } catch (PDOException $mensajeError) {
        echo "<h3>Mensaje de ERROR</h3>";
        echo "Error: " . $mensajeError->getMessage() . "<br>";
        echo "Código de error: " . $mensajeError->getCode();
    } finally {
        unset($miBD);
    }
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
                        <h2 class="fh5co-heading animate-box" data-animate-effect="fadeInLeft">Login Logout Tema 5</h2>

                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                            <fieldset>
                                <div class="obligatorio">
                                    <strong>Nombre: </strong>
                                    <input type="text" name="codUsuario" style="border: 1px solid black" 
                                           value="<?php
                                                if ($aErrores['codUsuario'] == NULL && isset($_POST['codUsuario'])) {
                                                    echo $_POST['codUsuario'];
                                                }?>">            
                                </div>
                                <br>
                                <div class="obligatorio">
                                    <strong>Contraseña: </strong>
                                    <input type="password" name="password" style="border: 1px solid black" 
                                           value="<?php
                                               if ($aErrores['password'] == NULL && isset($_POST['password'])) {
                                                   echo $_POST['password'];
                                               }?>">               
                                </div>
                                <br>
                                <div>                
                                    <input type="submit" name="enviar" value="ENTRAR" >
                                    <input type="button" name="registro" onclick="location = 'registro.php'" value="REGISTRAR">
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
