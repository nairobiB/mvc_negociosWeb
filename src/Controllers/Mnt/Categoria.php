<?php
namespace Controllers\Mnt;

use Controllers\PublicController;
use Exception;
use Views\Renderer;

class Categoria extends PublicController
{
    private $redirectTo = "index.php?page=Mnt-Categorias"; //link a donde se va redirigir
    private $viewData = array(
        //esta es la informacion que le vamos a enviar al renderizador el cual va mostrar los datos en la pantalla
        "mode" => "DSP",
        "modedsc" => "",
        "catid" => 0,
        "catnom" => "",
        "catest" => "ACT",
        "catest_ACT" => "selected",
        "catest_INA" => "",
        "catnom_error" => "",
        "general_errors" => array(),
        "has_errors" => false,
        //sirve como bandera
        "show_action" => true,
        "readonly" => false,
    );
    private $modes = array(
        "DSP" => "Detalle de %s (%s)",
        "INS" => "Nueva Categoría",
        "UPD" => "Editar %s (%s)",
        "DEL" => "Borrar %s (%s)"
    );
    public function run(): void
    {
        try {
            $this->page_loaded(); // se carga la página y los parámetros
            if ($this->isPostBack()) { //esta funcion es heredada de PublicController y va determinar si es un post en un formulario <form method="POST">
                $this->validatePostData(); //si es un post, va validar los datos que se van a enviar en el formulario
                if (!$this->viewData["has_errors"]) { //si no tiene errores realizará una acción
                    $this->executeAction();
                }
            }
            $this->render(); // si no hay un error, va renderizar la vista
        } catch (Exception $error) {
            error_log(sprintf("Controller/Mnt/Categoria ERROR: %s", $error->getMessage()));
            \Utilities\Site::redirectToWithMsg(
                $this->redirectTo,
                "Algo Inesperado Sucedió. Intente de Nuevo."
            );
        }
        /*
        1) Captura de Valores Iniciales QueryParams -> Parámetros de Query ? 
        https://ax.ex.com/index.php?page=abc&mode=UPD&id=1029
        2) Determinamos el método POST GET
        3) Procesar la Entrada
        3.1) Si es un POST
        3.2) Capturar y Validara datos del formulario
        3.3) Según el modo realizar la acción solicitada
        3.4) Notificar Error si hay
        3.5) Redirigir a la Lista
        4.1) Si es un GET
        4.2) Obtener valores de la DB sin no es INS
        4.3) Mostrar Valores
        4) Renderizar
        */

    }
    private function page_loaded() //se va determinar el modo, que metodo se esta utilizando, insertar, actualizar o eliminar
    { //verifica la informacion que viene en la url
        //valida que los parametros en la URL y que tenga valores validos
        if (isset($_GET['mode'])) {
            if (isset($this->modes[$_GET['mode']])) {
                $this->viewData["mode"] = $_GET['mode'];
            } else {
                throw new Exception("Mode Not available");
            }
        } else {
            throw new Exception("Mode not defined on Query Params");
        }
        if ($this->viewData["mode"] !== "INS") { //se valida el modo no es insertado
            if (isset($_GET['catid'])) {
                $this->viewData["catid"] = intval($_GET["catid"]);
            } else {
                throw new Exception("Id not found on Query Params");
            }
        }
    }
    private function validatePostData() //extrae la informacion que viene del formulario y valida que esta correcto
    {
        if (isset($_POST["catnom"])) { //con esta condicional se ve si existe
            if (\Utilities\Validators::IsEmpty($_POST["catnom"])) { //si es vacío, el has_errors se pone en true y se envía el mensake de error
                //la clase Validators tiene mas funciones para validar
                $this->viewData["has_errors"] = true;
                $this->viewData["catnom_error"] = "El nombre no puede ir vacío!";
            }
        } else {
            throw new Exception("CatNom not present in form");
        }
        if (isset($_POST["catest"])) {
            if (!in_array($_POST["catest"], array("ACT", "INA"))) { //será un select pero aun asi se va validar los que estan dentro de un arreglo existente
                throw new Exception("CatEst incorrect value");
            }
        } else {
            if ($this->viewData["mode"] !== "DEL") {
                throw new Exception("CatEst not present in form");
            }
        }
        if (isset($_POST["mode"])) {
            if (!key_exists($_POST["mode"], $this->modes)) {
                throw new Exception("mode has a bad value");
            }
            if ($this->viewData["mode"] !== $_POST["mode"]) {
                throw new Exception("mode value is different from query");
            }
        } else {
            throw new Exception("mode not present in form");
        }
        if (isset($_POST["catid"])) {
            if (($this->viewData["mode"] !== "INS" && intval($_POST["catid"]) <= 0)) {
                throw new Exception("catId is not Valid");
            }
            if ($this->viewData["catid"] !== intval($_POST["catid"])) {
                throw new Exception("catid value is different from query");
            }
        } else {
            throw new Exception("catid not present in form");
        }
        $this->viewData["catnom"] = $_POST["catnom"];
        if ($this->viewData["mode"] !== "DEL") {
            $this->viewData["catest"] = $_POST["catest"];
        }
    }
    private function executeAction() //dependiendo de la accion que se realiza 
    {
        switch ($this->viewData["mode"]) { //el mode es la opcion o el metodo
            case "INS":
                $inserted = \Dao\Mnt\Categorias::insert(
                    //llamamos la funcion
                    $this->viewData["catnom"],
                    $this->viewData["catest"]
                );
                if ($inserted > 0) { //la funcion retorna un valor entero
                    \Utilities\Site::redirectToWithMsg(
                        $this->redirectTo,
                        //lo redirige con un mensaje
                        "Categoría Creada Exitosamente"
                    );
                }
                break;
            case "UPD":
                $updated = \Dao\Mnt\Categorias::update(
                    $this->viewData["catnom"],
                    $this->viewData["catest"],
                    $this->viewData["catid"]
                );
                if ($updated > 0) {
                    \Utilities\Site::redirectToWithMsg(
                        $this->redirectTo,
                        "Categoría Actualizada Exitosamente"
                    );
                }
                break;
            case "DEL":
                $deleted = \Dao\Mnt\Categorias::delete(
                    $this->viewData["catid"]
                );
                if ($deleted > 0) {
                    \Utilities\Site::redirectToWithMsg(
                        $this->redirectTo,
                        "Categoría Eliminada Exitosamente"
                    );
                }
                break;
        }
    }
    private function render()
    {
        if ($this->viewData["mode"] === "INS") {
            $this->viewData["modedsc"] = $this->modes["INS"];
        } else {
            $tmpCategorias = \Dao\Mnt\Categorias::findById($this->viewData["catid"]);
            if (!$tmpCategorias) {
                throw new Exception("Categoria no existe en DB");
            }
            //$this->viewData["catnom"] = $tmpCategorias["catnom"];
            //$this->viewData["catest"] = $tmpCategorias["catest"];
            \Utilities\ArrUtils::mergeFullArrayTo($tmpCategorias, $this->viewData);
            $this->viewData["catest_ACT"] = $this->viewData["catest"] === "ACT" ? "selected" : "";
            $this->viewData["catest_INA"] = $this->viewData["catest"] === "INA" ? "selected" : "";
            $this->viewData["modedsc"] = sprintf(
                $this->modes[$this->viewData["mode"]],
                $this->viewData["catnom"],
                $this->viewData["catid"]
            );
            if (in_array($this->viewData["mode"], array("DSP", "DEL"))) {
                $this->viewData["readonly"] = "readonly";
            }
            if ($this->viewData["mode"] === "DSP") {
                $this->viewData["show_action"] = false;
            }
        }
        Renderer::render("mnt/categoria", $this->viewData);
    }
}

?>