<?php
/**
  @author Bea Merino
  @since 14/04/2021
  @description: Programa
 */

//Recupera la sesión del Login
session_start();

//Fichero de configuración de la BBDD
require_once '../config/confDB.php';

//Si no hay una sesión iniciada te manda al Login
if (!isset($_SESSION['usuarioDAW213MtoDepartamentosTema5'])) {
    header('location: login.php');
    exit;
}
if (isset($_POST["MtoDepartamentos"])) {
    header('Location: mtoDepartamentos.php');
    exit;
}

try {
    // Bloque de código que puede tener excepciones en el objeto PDO
    $miBD = new PDO(HOST, USER, PASS);
    $miBD->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //Selecciona el número de conexiones y la descripción del usuario
    $consultaSQL = "SELECT T01_NumConexiones, T01_DescUsuario FROM T01_Usuario WHERE T01_CodUsuario=:codigo";
    $resultadoSQL = $miBD->prepare($consultaSQL); // prepara la consulta
    $resultadoSQL->bindParam(":codigo", $_SESSION['usuarioDAW213MtoDepartamentosTema5']);
    $resultadoSQL->execute();

    $usuario = $resultadoSQL->fetchObject();
    $numConexiones = $usuario->T01_NumConexiones;
    $descUsuario = $usuario->T01_DescUsuario;

    //Cuando se produce una excepcion se corta el programa y salta la excepción con el mensaje de error
} catch (PDOException $mensajeError) {
    echo "<h3>Mensaje de ERROR</h3>";
    echo "Error: " . $mensajeError->getMessage() . "<br>";
    echo "Código de error: " . $mensajeError->getCode();
} finally {
    unset($miBD);
}
?>
<!DOCTYPE html>
<html class="no-js">
    <!--<![endif]-->
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
                <h1 id="fh5co-logo"><a href=""><img src="../webroot/images/logo.png"
                                                                    alt="Free HTML5 Bootstrap Website Template"></a></h1>
                <nav id="fh5co-main-menu" role="navigation">
                    <ul>
                        <li class="fh5co-active" style="color: #1512da">Log in Log out Tema 5</li>
                        <li><a href="login.php">Cerrar Sesión</a></li>
                        <li><a href="editarPerfil.php">Editar</a></li>
                        <li><a href="detalle.php">Detalle</a></li>
                    </ul>
                </nav>
                <div class="fh5co-footer">
                    <p style="font-size: 1.5em"><a style="text-decoration: none; color: black" href="https://github.com/bea93/LogInLogOutTema5/tree/Developer" target="_blank">GitHub</a></p>
                    <p><a href="../../index.html" style=" text-decoration: none; color: black">&copy; 2021 Beatriz Merino Macía.</a></p>
                </div>
            </aside>
            <div id="fh5co-main">
                <div class="fh5co-narrow-content">
                    <h2 class="fh5co-heading animate-box" data-animate-effect="fadeInLeft">Usuario correcto</h2>

                    <!--Mensaje de bienvenida cuando el usuario se loguea correctamente. Muestra la descripción del usuario -->
                    <h3>¡Bienvenid@ <?php echo $descUsuario; ?>!</h3>
                    <?php
                    //Si la última conexión del usuario es null se muestra un mensaje de conexión por primera vez
                    if ($_SESSION['ultimaConexionAnterior'] === null) {
                        echo "<h3>Esta es la primera vez que te conectas.</h3>";
                    } else {
                        ?>
                        <!-- Si la última conexión no es null, se muestra la fecha y el número de conexiones-->
                        <h3>Usted se ha conectado <?php echo $numConexiones . " veces"; ?></h3>
                        <h3>Su última conexión fue el día <?php echo date('d/m/Y', $_SESSION['ultimaConexionAnterior']); ?> a las <?php echo date('H:i:s', $_SESSION['ultimaConexionAnterior']); ?></h3>
                    <?php } ?>
                        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" name="mtoDepartamentos" method="POST">
                            <input type="submit" name="MtoDepartamentos" value="Mantenimiento Departamentos">
                        </form>
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

