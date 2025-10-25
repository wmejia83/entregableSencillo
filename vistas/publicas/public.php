    <!-- styles public -->
     <link href="vistas/publicas/css/styles.css" rel="stylesheet" />


                <?php
                    //Cargamos el contenido de acuerdo a la ruta
                    if(isset($_GET["route"])){

                        //creamos una lista de rutas permitidas
                        if(
                            $_GET["route"] == "inicio" || 
                            $_GET["route"] == "registro" || 
                            $_GET["route"] == "ingreso"
                        ){
                            include_once "vistas/publicas/".$_GET["route"]."/".$_GET["route"].".php";

                        }
                        
                        // else if($_GET["route"] == "login"){

                        //     $login = new ControladorUsuarios();
                        //     $login -> ingresoUsuario();

                        // }
                        else{
                            include_once "vistas/404/404.php";
                        }

                    }else{
                        include_once "vistas/publicas/inicio/inicio.php";
                    }
                    
                ?>







    <!-- End of Page Wrapper -->

                <?php
                    include_once "vistas/modulos/botones-flotantes.php";
                    include_once "vistas/modulos/modals.php";
                ?>



