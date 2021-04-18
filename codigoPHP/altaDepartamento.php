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
                    <h2 class="fh5co-heading animate-box" data-animate-effect="fadeInLeft">Alta departamento</h2>
                    <?php
        /**
            @author: Bea Merino
            @since: 07/04/2021
            @description: Alta Departamento
         */
        require '../core/210322ValidacionFormularios.php'; //Importamos la libreria de validacion
        include '../config/confDB.php';

        $entradaOK = true; //Inicializamos una variable que nos ayudara a controlar si todo esta correcto
        //Inicializamos un array que se encargara de recoger los errores(Campos vacios)
        $aErrores = [
            'CodDepartamento' => null,
            'DescDepartamento' => null,
            'VolumenNegocio' => null
        ];

        //Inicializamos un array que se encargara de recoger los datos del formulario(Campos vacios)
        $aFormulario = [
            'CodDepartamento' => null,
            'DescDepartamento' => null,
            'VolumenNegocio' => null
        ];

        if (isset($_POST['aceptar']) && $_POST['aceptar'] == 'Aceptar') { //Si se ha pulsado enviar
            //La posici�n del array de errores recibe el mensaje de error si hubiera
            $aErrores['CodDepartamento'] = validacionFormularios::comprobarAlfabetico($_POST['CodDepartamento'], 3, 3, 1);  //maximo, m�nimo y opcionalidad
            $aErrores['DescDepartamento'] = validacionFormularios::comprobarAlfanumerico($_POST['DescDepartamento'], 255, 1, 1);  //maximo, m�nimo y opcionalidad
            $aErrores['VolumenNegocio'] = validacionFormularios::comprobarFloat($_POST['VolumenNegocio'], 999999, 1, 1);

            try{
                //Objeto PDO con los datos de conexi�n
                $miBD = new PDO(HOST,USER,PASS);
                $miBD->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                //Consulta SQL
                $sentenciaSQL2 = "SELECT CodDepartamento FROM Departamento WHERE CodDepartamento='{$_POST['CodDepartamento']}'";
                $consultaSelect = $miBD->prepare($sentenciaSQL2);
                $consultaSelect->execute();
                //Si la consulta devuelve alg�n valor es porque el c�digo est� duplicado, da error
                if($consultaSelect->rowCount()>0){
                    $aErrores['CodDepartamento']= "El código de Departamento introducido ya existe";

                }
                
            } catch (PDOException $mensajeError){
                //Mensaje de salida
                echo "Error " . $mensajeError->getMessage() . "<br>"; 
                //C�digo del error
                echo "Codigo del error " . $mensajeError->getCode() . "<br>"; 
            } finally {
                //Cerramos la conexi�n
                unset($miBD);
            }

            foreach ($aErrores as $campo => $error) { //Recorre el array en busca de mensajes de error
                if ($error != null) { //Si lo encuentra vacia el campo y cambia la condiccion
                    $entradaOK = false; //Cambia la condiccion de la variable
                } else {
                    if (isset($_POST[$campo])) {
                        $aFormulario[$campo] = $_POST[$campo];
                    }
                }
            }
        } else {
            $entradaOK = false; //Cambiamos el valor de la variable porque no se ha pulsado el bot�n
        }

        if ($entradaOK) { //Si el valor es true procesamos los datos que hemos recogido
            // 
            //Mostramos los datos por pantalla
            $aFormulario['CodDepartamento'] = strtoupper($_POST['CodDepartamento']); //Todo en may�sculas
            $aFormulario['DescDepartamento'] = ucfirst($_POST['DescDepartamento']); //La primera letra en may�scula
            $aFormulario['VolumenNegocio'] = $_POST['VolumenNegocio'];

            try {
               //Objeto PDO con los datos de conexi�n
            $miBD = new PDO(HOST,USER,PASS);
            //$miBD = new PDO('mysql:host=192.168.20.19:3306;dbname=DAW213DBDepartamentos', 'usuarioDAW213DBDepartamentos', 'paso');
            $miBD->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //Consulta SQL para insertar el departamento nuevo
            $sentenciaSQL = $miBD->prepare("INSERT INTO Departamento (CodDepartamento,DescDepartamento, VolumenNegocio) VALUES (:codigo, :descripcion, :volumen);");
            //El método bindParam asigna valores a los par�metros
            $sentenciaSQL->bindParam(":codigo", $aFormulario['CodDepartamento']);
            $sentenciaSQL->bindParam(":descripcion", $aFormulario['DescDepartamento']);
            $sentenciaSQL->bindParam(":volumen", $aFormulario['VolumenNegocio']);
                $sentenciaSQL->execute();
            //Consulta sql para mostrar  los departamentos
            $selectSQL = $miBD->prepare("SELECT * FROM Departamento");
            //Ejecuta la consulta
            $selectSQL->execute();

            //Redirigimos al usuario a la página inicial
            //header("Location: mtoDepartamentos.php");
            echo "<p style='color: green'>El departamento <strong>" . $aFormulario['CodDepartamento'] . "</strong> se ha añadido con éxito</p>";
            echo "<a href='mtoDepartamentos.php'><input type='button'  value='Volver'></a>";
                
            } catch (PDOException $mensajeError) { //Cuando se produce una excepcion se corta el programa y salta la excepci�n con el mensaje de error
                echo "<h3>Mensaje de ERROR</h3>";
                echo "Error: " . $mensajeError->getMessage() . "<br>";
                echo "Código de error: " . $mensajeError->getCode();
            } finally {
                unset($miBD);
            }
        } else { //Mostrar el formulario hasta que se rellene correctamente
            ?>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <fieldset>
                    <div class="obligatorio">
                        Código Departamento: 
                        <input style="border: 1px solid black" type="text" name="CodDepartamento"
                            value="<?php if ($aErrores['CodDepartamento'] == NULL && isset($_POST['CodDepartamento'])) {
                                echo $_POST['CodDepartamento'];
                            } ?>"><br> <!--//Si el valor es bueno, lo escribe en el campo-->
                            <?php if ($aErrores['CodDepartamento'] != NULL) { ?>
                            <div class="error">
                                <?php echo $aErrores['CodDepartamento']; //Mensaje de error que tiene el array aErrores    ?>
                            </div>   
                            <?php } ?>                
                    </div>
                    <br>
                    <div class="obligatorio">
                        Descripción Departamento: 
                        <input style="border: 1px solid black" type="text" name="DescDepartamento"
                               value="<?php if ($aErrores['DescDepartamento'] == NULL && isset($_POST['DescDepartamento'])) {
                                echo $_POST['DescDepartamento'];
                            } ?>"><br> <!--//Si el valor es bueno, lo escribe en el campo-->
                               <?php if ($aErrores['DescDepartamento'] != NULL) { ?>
                            <div class="error">
                                <?php echo $aErrores['DescDepartamento']; //Mensaje de error que tiene el array aErrores    ?>
                            </div>   
                        <?php } ?>                
                    </div>
                    <br/>
                    <div class="obligatorio">
                        Volumen negocio: 
                        <input style="border: 1px solid black" type="text" name="VolumenNegocio"
                               value="<?php if($aErrores['VolumenNegocio'] == NULL && isset($_POST['VolumenNegocio'])){ echo $_POST['VolumenNegocio'];} ?>"><br> <!--//Si el valor es bueno, lo escribe en el campo-->
                        <?php if ($aErrores['VolumenNegocio'] != NULL) { ?>
                        <div class="error">
                            <?php echo $aErrores['VolumenNegocio'];?>
                        </div> 
                    
                    <?php } ?> 
                    <br>
                    <div class="obligatorio">
                        <a href="mtoDepartamentos.php"><input type="submit" name="aceptar" value="Aceptar"></a>
                        <a href="mtoDepartamentos.php"><input type="button"  value="Cancelar"></a>
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