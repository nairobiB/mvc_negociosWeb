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
        "clientgender" => "FEM",
        "clientphone1" => "",
        "clientphone2" => "",
        "clientemail" => "",
        "clientIdnumber" => "",
        "clientbio" => "",
        "clientstatus" => "",
        "clientdatecrt" => "",

        "clientgender_FEM" => "selected",
        "clientgender_MAL" => "",
        "clientstatus_ACT" => "selected",
        "clientstatus_INA" => "",

        "clientname_error" => "",
        "clientphone1_error" => "",
        "clientphone2_error" => "",
        "clientIdnumber_error" => "",
        "clientemail_error" => "",
        "clientbio_error" => "",
        "clientdatecrt_error" => "",

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
    private function validatePostData()
    {
        if (isset($_POST["clientname"])) {
            if (\Utilities\Validators::IsEmpty($_POST["clientname"])) {
                $this->viewData["has_errors"] = true;
                $this->viewData["clientname_error"] = "El nombre no puede ir vacío!";
            }
        } else {
            throw new Exception("clientname not present in form");
        }
        if (isset($_POST["clientphone1"])) {
            if (\Utilities\Validators::IsEmpty($_POST["clientphone1"])) {
                $this->viewData["has_errors"] = true;
                $this->viewData["clientphone1_error"] = "El telefono 1 no puede ir vacío!";
            }
        } else {
            throw new Exception("clientphone1 not present in form");
        }
        if (isset($_POST["clientphone2"])) {
            if (\Utilities\Validators::IsEmpty($_POST["clientphone2"])) {
                $this->viewData["has_errors"] = true;
                $this->viewData["clientphone2_error"] = "El telefono 2 no puede ir vacío!";
            }
        } else {
            throw new Exception("clientphone2 not present in form");
        }
        if (isset($_POST["clientemail"])) {
            if (\Utilities\Validators::IsEmpty($_POST["clientemail"])) {
                $this->viewData["has_errors"] = true;
                $this->viewData["clientemail_error"] = "El correo no puede ir vacío!";
            }
        } else {
            throw new Exception("clientemail not present in form");
        }
        if (isset($_POST["clientIdnumber"])) {
            if (\Utilities\Validators::IsEmpty($_POST["clientIdnumber"])) {
                $this->viewData["has_errors"] = true;
                $this->viewData["clientIdnumber_error"] = "El ID no puede ir vacío!";
            }
        } else {
            throw new Exception("clientIdnumber not present in form");
        }
        if (isset($_POST["clientbio"])) {
            if (\Utilities\Validators::IsEmpty($_POST["clientbio"])) {
                $this->viewData["has_errors"] = true;
                $this->viewData["clientbio_error"] = "La biografía no puede ir vacío!";
            }
        } else {
            throw new Exception("clientbio not present in form");
        }
        if (isset($_POST["clientdatecrt"])) {
            if (\Utilities\Validators::IsEmpty($_POST["clientdatecrt"])) {
                $this->viewData["has_errors"] = true;
                $this->viewData["clientdatecrt_error"] = "La fecha no puede ir vacío!";
            }
        } else {
            throw new Exception("clientdatecrt not present in form");
        }
        if (isset($_POST["clientstatus"])) {
            if (!in_array($_POST["clientstatus"], array("ACT", "INA"))) {
                throw new Exception("clientstatus incorrect value");
            }
        } else {
            if ($this->viewData["mode"] !== "DEL") {
                throw new Exception("clientstatus not present in form");
            }
        }
        if (isset($_POST["clientgender"])) {
            if (!in_array($_POST["clientgender"], array("FEM", "MAL"))) {
                throw new Exception("clientgender incorrect value");
            }
        } else {
            if ($this->viewData["mode"] !== "DEL") {
                throw new Exception("clientgender not present in form");
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
        $this->viewData["clientemail"] = $_POST["clientemail"];
        $this->viewData["clientphone1"] = $_POST["clientphone1"];
        $this->viewData["clientphone2"] = $_POST["clientphone2"];
        $this->viewData["clientemail"] = $_POST["clientemail"];
        $this->viewData["clientIdnumber"] = $_POST["clientIdnumber"];
        $this->viewData["clientbio"] = $_POST["clientbio"];
        $this->viewData["clientdatecrt"] = $_POST["clientdatecrt"];
        if ($this->viewData["mode"] !== "DEL") {
            $this->viewData["clientstatus"] = $_POST["clientstatus"];
        }
        if ($this->viewData["mode"] !== "DEL") {
            $this->viewData["clientgender"] = $_POST["clientgender"];
        }
    }
    private function executeAction()
    {
        switch ($this->viewData["mode"]) {
            case "INS":
                $inserted = \Dao\Mnt\Clientes::insert(

                    $this->viewData["clientname"],
                    $this->viewData["clientstatus"],
                    $this->viewData["clientgender"],
                    $this->viewData["clientphone1"],
                    $this->viewData["clientphone2"],
                    $this->viewData["clientemail"],
                    $this->viewData["clientIdnumber"],
                    $this->viewData["clientbio"],
                    $this->viewData["clientdatecrt"]

                );
                if ($inserted > 0) {
                    \Utilities\Site::redirectToWithMsg(
                        $this->redirectTo,

                        "Cliente Creada Exitosamente"
                    );
                }
                break;
            case "UPD":
                $updated = \Dao\Mnt\Clientes::update(
                    $this->viewData["clientname"],
                    $this->viewData["clientstatus"],
                    $this->viewData["clientgender"],
                    $this->viewData["clientphone1"],
                    $this->viewData["clientphone2"],
                    $this->viewData["clientemail"],
                    $this->viewData["clientIdnumber"],
                    $this->viewData["clientbio"],
                    $this->viewData["clientdatecrt"],
                    $this->viewData["clientid"]
                );
                if ($updated > 0) {
                    \Utilities\Site::redirectToWithMsg(
                        $this->redirectTo,
                        "Cliente Actualizada Exitosamente"
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
                        "Cliente Eliminada Exitosamente"
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
            $this->viewData["clientgender_FEM"] = $this->viewData["clientgender"] === "FEM" ? "selected" : "";
            $this->viewData["clientgender_MAL"] = $this->viewData["clientgender"] === "MAL" ? "selected" : "";
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