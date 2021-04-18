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
                    <h2 class="fh5co-heading animate-box" data-animate-effect="fadeInLeft">Importar Departamentos</h2>
                    <?php
                    /**
                     * @author Bea Merino
                     * @since 12/04/2021
                     * @description: Importar departamentos
                     */

                    //Fichero de configuración de la BBDD
                    require_once '../config/confDB.php';

                    //Carga el archivo 
                    $xml = simplexml_load_file("../tmp/DBXML.xml");

                    try{
                        //Objeto PDO con los datos de conexión
                        $miBD = new PDO(HOST,USER,PASS);
                        $miBD->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        $miBD->beginTransaction();

                        //Crea un query que borre la tabla y la vuelva a crear para que no dé errores de claves duplicadas
                        $consultaDrop = $miBD->prepare('DROP TABLE Departamento'); 
                        $consultaDrop->execute();

                        $consultaCrear = $miBD->prepare(' CREATE TABLE Departamento (
                                                        CodDepartamento VARCHAR(3) PRIMARY KEY,
                                                        DescDepartamento VARCHAR(255) NOT NULL,
                                                        FechaBaja INT DEFAULT NULL,
                                                        VolumenNegocio FLOAT DEFAULT NULL
                                                        )ENGINE=INNODB;'); 
                        $consultaCrear->execute();

                        //Recorremos el archivo y creamos el sql para su inserción, si se produce algun error sale por el catch
                        foreach ($xml as $value) {
                            $consultaInsert = $miBD->prepare("INSERT INTO Departamento(CodDepartamento, DescDepartamento) VALUES(:codigo,:desc)");
                            //Saca los parámetros del xml
                            $parametros = [ ":codigo"=>$value->CodDepartamento,
                                            ":desc"=>$value->DescDepartamento
                                    ];
                            //Ejecuta la sentencia
                            $consultaInsert->execute($parametros);
                        }
                        //Realiza el commit
                        $miBD->commit();
                        //Mensaje en caso de que todo vaya bien
                        echo "<h3 style='color: green'>Datos cargados</h3><br>";
                        //Query para mostrar todos los registros de la tabla Departamentos
                        $consultaSelect = $miBD->prepare('select * from Departamento'); 
                        $consultaSelect->execute();


                        ?>            
            <table>
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Descripción</th>
                        <th>Volumen Negocio</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    //Al realizar el fetchObject, se pueden sacar los datos de $registro como si fuera un objeto
                    while ($registro = $consultaSelect->fetchObject()) {
                        ?>
                        <tr>
                            <?php
                            echo "<td>" . $registro->CodDepartamento . "</td>";
                            echo "<td>" . $registro->DescDepartamento . "</td>";
                            echo "<td>$registro->VolumenNegocio</td>";                        }
                        ?>
                    </tr>
                </tbody>
            </table>
            <a href='mtoDepartamentos.php'><input type='button'  value='Volver'></a>
            <?php
        } catch (PDOException $mensajeError){
            //Mensaje de salida
            echo "Error: " . $mensajeError->getMessage() . "<br>";
            //Código del error
            echo "Código de error: " . $mensajeError->getCode();
            echo "<a href='mtoDepartamentos.php'><input type='button'  value='Volver'></a>";
            $miBD->rollBack();
        }
          ?>  
       
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