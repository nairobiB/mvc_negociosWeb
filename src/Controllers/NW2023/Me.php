<?php

namespace Controllers\NW2023;

use Controllers\PublicController;
use Views\Renderer;

class Me extends PublicController
{

    public function run(): void
    {
        $viewData = array();
        Renderer::render('NW2023/me', $viewData);
    }
}


?>