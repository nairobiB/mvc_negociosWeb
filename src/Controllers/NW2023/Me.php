<?php

namespace Controllers\NW2023;

use Controllers\PublicController;
use Views\Renderer;
use Dao\Clases\Demo;

class Me extends PublicController
{

    public function run(): void
    {
        $viewData = array();
        $responseDao = Demo::getAResponse()["Response"];
        $viewData['response'] = $responseDao;
        Renderer::render('NW2023/me', $viewData);
    }
}


?>