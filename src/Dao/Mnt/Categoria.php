<?php

namespace Controllers\Mnt;

use Controllers\PublicController;
use Exception;

/*
Cargar la pagina carga los datos de la vista
*/
class Categoria extends PublicController
{
    private $viewData = array(
        "mode" => "DSP",
        "modedsc" => "",
        "catid" => 0,
        "catnom" => "",
        "catest" => "ACT",
        "catest_ACT" => "selected",
        "catest_INA" => "",
        "catnom_error" => "",
        "general_errors" => array(),
        "has_errors" => false
    );
    public function run(): void
    {
        try {
            $this->page_loaded(); //se carga la pagina, si hay un error se manda a la excepcion, y muestra la vista de error
            if ($this->isPostBack()) {
                $this->validatePostData();
            }
        } catch (Exception $error) {

            error_log(sprintf("Controllers/Mnt/Categoria ERROR: %s", $error->getMessage()));
            \Utilities\Site::redirectToWithMsg(
                "index.php?page=Mnt-Categorias",
                "Algo Inesperado Sucedió. Intente de nuevo."
            );
        }



        /*
        * 1. Captura de Valores Iniciales QueryParams -> Parámetros de Query ? https://ax.ex.com/index.php?page=abc&mode=UPD&id=1029
        * 2. Determinar el método POST GET
        * Todo lo que esta despues del ? son query params
        * 3. Procesar la Entrada
        3.1) Si es un POST
        3.2) Capturar y Calidará datos del formulario
        3.3) Según el modo realizar la acción
        3.4) Notificar Error si hay
        3.5) Redirigir a la Lista
        4.1) Si es un GET
        4.2) Obtener valores de la DB si no es INS
        4.3) Mostrar Valores
        4. 
        */

    }
    private $modes = array(
        "DSP" => "Detalle de %s (%s)",
        "INS" => "Nueva Categoría",
        "UPD" => "Editar %s (%s)",
        "DEL" => "Borrar %s (%s)"
    );
    private function page_loaded()
    {
        if (isset($_GET['mode'])) {
            if (isset($this->modes[$_GET['mode']])) {

                $this->viewData["mode"] = $_GET["mode"];

            } else {
                throw new Exception("Mode Not Available");

            }
        } else {
            throw new Exception("Mode not defined on Query Params");


        }
        if ($this->viewData['mode'] !== "INS") {
            if (isset($_GET["catid"])) {
                $this->viewData["catid"];

            } else {
                throw new Exception("Id not found on Query Params");


            }
        }

    }

    private function validatePostData()
    {
        if (isset($_POST["catnom"])) {
            //validators tiene varias funciones, se puede ir a la clase y verlas
            if (\Utilities\Validators::IsEmpty($_POST["catnom"])) {
                $this->viewData["has_errors"] = true;
                $this->viewData["catnom_error"] = "El nombre no puede ir vacío";
            }
        } else {
            throw new Exception("CatNom not present in form");
        }
        if (isset($_POST["catest"])) {

        } else {
            throw new Exception("CatEst not present in form");
        }
        if (isset($_POST["mode"])) {

        } else {
            throw new Exception("Mode not present in form");
        }
        if (isset($_POST["catid"])) {

        } else {
            throw new Exception("CatId not present in form");
        }
    }

}

?>