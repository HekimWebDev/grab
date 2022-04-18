<?php

namespace Domains\ServiceManagers\Mavi;


use Service\Mavi\MaviClient;

class MaviManager
{
    protected MaviClient $cilent;

    public function __construct()
    {
        $this->client = new MaviClient();
    }

    public function getCategories(): array
    {
        $urls = [];
        $categories = $this->client->getCategoriesFromHtml();

        foreach ($categories as $items){

            foreach ($items as $name => $item){

                if ($name == 'url'){
                    $urlOfParent = $item;
                    continue;
                }

//                dd($url, $item);

                foreach ($item as $key => $value){

                    $position = strpos($value, '?q=:relevance');

                    if ($position > 0){

                        $urls[] = $urlOfParent . '/results' . substr($value, $position) . '&page=';

                        continue;
                    }

                    $position = strpos($value, 'aksesuar');

                    if ($position > 0){

                        $urls[] = $urlOfParent . "/results?q=:relevance:categoryValue:Aksesuar:subCategoryValue:$key&page=";

                        continue;
                    }

                    $urls[] = $urlOfParent . "/results?q=:relevance:categoryValue:$key&page=";
                }
            }
        }

        return $urls;
    }
}