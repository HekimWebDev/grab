<?php

namespace Domains\ServiceManagers\Avva;

use Service\Avva\AvvaClient;

class AvvaManager
{
    protected $service;

    public function __construct()
    {
        $this->service = new AvvaClient();
    }

    public function getPageId($url): null|int
    {
        return $this->service->getPageIdFromHtml($url);
    }

    public function getCategories()
    {
        $categories = $this->service->getCategoriesFromHtml();

        foreach ($categories[0][0] as $category){

            if ($category == '#') continue;

            if (str_contains($category, 'https://www.avva.com.tr/')){

                $urls[] = substr($category, 23);

                continue;
            }

            $urls[] = $category;
        }

        return $urls;
    }
}
