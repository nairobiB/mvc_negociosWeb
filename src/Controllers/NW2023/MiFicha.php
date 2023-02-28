<?php

namespace Controllers\NW2023;

use Controllers\PublicController;
use Views\Renderer;

class MiFicha extends PublicController
{

    public function run(): void
    {
        $viewData = array(
            "nombre" => "Breanie Nairobi Bodden",
            "email" => "nayabodden@gmail.com",
            "title" => "Software Engineer"
        );
        Renderer::render("NW2023/miFicha", $viewData);
    }

}

?>