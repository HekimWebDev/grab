<?php

namespace Service\AYClassic\Requests;

use Service\AYClassic\AYClient;

class GetCategories extends AYClient
{
    public function getHtmlCategories(): string
    {
        $this->getContent('');
    }


}
