<?php
namespace Controllers\Mnt;

use Controllers\PublicController;
use Exception;
use Views\Renderer;

class Cliente extends PublicController
{
    private $redirectTo = "index.php?page=Mnt-Clientes";
    private $viewData = array(

        "mode" => "DSP",
        "modedsc" => "",

        "clientid" => 0,
        "clientname" => "",
        "clientgender" => "",
        "clientphone1" => "",
        "clientphone2" => "",
        "clientemail" => "",
        "clientIdnumber" => "",
        "clientbio" => "",
        "clientstatus" => "",
        "clientdatecrt" => "",
        "clientusercreates" => 0,

        "clientgender_FEM" => "selected",
        "clientgender_MAL" => "",
        "clientstatus_ACT" => "selected",
        "clientstatus_INA" => "",

        "clientname_error" => "",
        "clientemail_error" => "",
        "clientbio_error" => "",

        "general_errors" => array(),
        "has_errors" => false,
        "show_action" => true,
        "readonly" => false,
    );
    private $modes = array(
        "DSP" => "Detalle de %s (%s)",
        "INS" => "Nuevo Cliente",
        "UPD" => "Editar %s (%s)",
        "DEL" => "Borrar %s (%s)"
    );
    public function run(): void
    {
        try {
            $this->page_loaded();
            if ($this->isPostBack()) {
                $this->validatePostData();
                if (!$this->viewData["has_errors"]) {
                    $this->executeAction();
                }
            }
            $this->render();
        } catch (Exception $error) {
            error_log(sprintf("Controller/Mnt/Cliente ERROR: %s", $error->getMessage()));
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
    private function page_loaded()
    {
        if (isset($_GET['mode'])) {
            if (isset($this->modes[$_GET['mode']])) {
                $this->viewData["mode"] = $_GET['mode'];
            } else {
                throw new Exception("Mode Not available");
            }
        } else {
            throw new Exception("Mode not defined on Query Params");
        }
        if ($this->viewData["mode"] !== "INS") {
            if (isset($_GET['clientid'])) {
                $this->viewData["clientid"] = intval($_GET["clientid"]);
            } else {
                throw new Exception("Id not found on Query Params");
            }
        }
    }
    private function validatePostData() //extrae la informacion que viene del formulario y valida que esta correcto
    {
        if (isset($_POST["clientname"])) { //con esta condicional se ve si existe
            if (\Utilities\Validators::IsEmpty($_POST["clientname"])) { //si es vacío, el has_errors se pone en true y se envía el mensake de error
                //la clase Validators tiene mas funciones para validar
                $this->viewData["has_errors"] = true;
                $this->viewData["clientname_error"] = "El nombre no puede ir vacío!";
            }
        } else {
            throw new Exception("clientname not present in form");
        }
        if (isset($_POST["clientstatus"])) {
            if (!in_array($_POST["clientstatus"], array("ACT", "INA"))) { //será un select pero aun asi se va validar los que estan dentro de un arreglo existente
                throw new Exception("clientstatus incorrect value");
            }
        } else {
            if ($this->viewData["mode"] !== "DEL") {
                throw new Exception("clientstatus not present in form");
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
        if (isset($_POST["clientid"])) {
            if (($this->viewData["mode"] !== "INS" && intval($_POST["clientid"]) <= 0)) {
                throw new Exception("clientid is not Valid");
            }
            if ($this->viewData["clientid"] !== intval($_POST["clientid"])) {
                throw new Exception("clientid value is different from query");
            }
        } else {
            throw new Exception("clientid not present in form");
        }
        $this->viewData["clientname"] = $_POST["clientname"];
        if ($this->viewData["mode"] !== "DEL") {
            $this->viewData["clientstatus"] = $_POST["clientstatus"];
        }
    }
    private function executeAction() //dependiendo de la accion que se realiza 
    {
        switch ($this->viewData["mode"]) { //el mode es la opcion o el metodo
            case "INS":
                $inserted = \Dao\Mnt\Clientes::insert(

                    $this->viewData["clientname"],
                    $this->viewData["clientstatus"]
                );
                if ($inserted > 0) {
                    \Utilities\Site::redirectToWithMsg(
                        $this->redirectTo,
                        //lo redirige con un mensaje
                        "Categoría Creada Exitosamente"
                    );
                }
                break;
            case "UPD":
                $updated = \Dao\Mnt\Clientes::update(
                    $this->viewData["clientname"],
                    $this->viewData["clientstatus"],
                    $this->viewData["clientid"]
                );
                if ($updated > 0) {
                    \Utilities\Site::redirectToWithMsg(
                        $this->redirectTo,
                        "Categoría Actualizada Exitosamente"
                    );
                }
                break;
            case "DEL":
                $deleted = \Dao\Mnt\Clientes::delete(
                    $this->viewData["clientid"]
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
            $tmpClientes = \Dao\Mnt\Clientes::findById($this->viewData["clientid"]);
            if (!$tmpClientes) {
                throw new Exception("Cliente no existe en DB");
            }

            \Utilities\ArrUtils::mergeFullArrayTo($tmpClientes, $this->viewData);
            $this->viewData["clientstatus_ACT"] = $this->viewData["clientstatus"] === "ACT" ? "selected" : "";
            $this->viewData["clientstatus_INA"] = $this->viewData["clientstatus"] === "INA" ? "selected" : "";
            $this->viewData["modedsc"] = sprintf(
                $this->modes[$this->viewData["mode"]],
                $this->viewData["clientname"],
                $this->viewData["clientid"]
            );
            if (in_array($this->viewData["mode"], array("DSP", "DEL"))) {
                $this->viewData["readonly"] = "readonly";
            }
            if ($this->viewData["mode"] === "DSP") {
                $this->viewData["show_action"] = false;
            }
        }
        Renderer::render("mnt/cliente", $this->viewData);
    }
}

?>