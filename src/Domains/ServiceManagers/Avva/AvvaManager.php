<?php

namespace Domains\ServiceManagers\Avva;

use http\Url;
use Service\Avva\AvvaClient;
use Service\Avva\Response;

class AvvaManager
{
    protected $service;

    public function __construct()
    {
        $this->service = new AvvaClient();
    }

    public function getProducts(string $pageId, int $pageNumber): array
    {
        $url = 'api/product/GetProductList?c=trtry0000&FilterJson=%7B%22CategoryIdList%22%3A%5B' . $pageId . '%5D%2C%22BrandIdList%22%3A%5B%5D%2C%22SupplierIdList%22%3A%5B%5D%2C%22TagIdList%22%3A%5B%5D%2C%22TagId%22%3A-1%2C%22FilterObject%22%3A%5B%5D%2C%22MinStockAmount%22%3A-1%2C%22IsShowcaseProduct%22%3A-1%2C%22IsOpportunityProduct%22%3A-1%2C%22FastShipping%22%3A-1%2C%22IsNewProduct%22%3A-1%2C%22IsDiscountedProduct%22%3A-1%2C%22IsShippingFree%22%3A-1%2C%22IsProductCombine%22%3A-1%2C%22MinPrice%22%3A0%2C%22MaxPrice%22%3A0%2C%22SearchKeyword%22%3A%22%22%2C%22StrProductIds%22%3A%22%22%2C%22IsSimilarProduct%22%3Afalse%2C%22RelatedProductId%22%3A0%2C%22ProductKeyword%22%3A%22%22%2C%22PageContentId%22%3A0%2C%22StrProductIDNotEqual%22%3A%22%22%2C%22IsVariantList%22%3A-1%2C%22IsVideoProduct%22%3A-1%2C%22ShowBlokVideo%22%3A-1%2C%22VideoSetting%22%3A%7B%22ShowProductVideo%22%3A-1%2C%22AutoPlayVideo%22%3A-1%7D%2C%22ShowList%22%3A1%2C%22VisibleImageCount%22%3A6%2C%22ShowCounterProduct%22%3A-1%2C%22ImageSliderActive%22%3Atrue%2C%22ProductListPageId%22%3A0%2C%22ShowGiftHintActive%22%3Afalse%2C%22NonStockShowEnd%22%3A1%7D&PagingJson=%7B%22PageItemCount%22%3A0%2C%22PageNumber%22%3A' . $pageNumber . '%2C%22OrderBy%22%3A%22KATEGORISIRA%22%2C%22OrderDirection%22%3A%22ASC%22%7D&CreateFilter=false&TransitionOrder=0&PageType=1&PageId=' . $pageId;

        return $this->service->getProductsFromAPI($url, $pageId, $pageNumber );
    }

    public function getPageId()
    {
        $path = storage_path('app/public/categories/AvvaPagesId.json');

        $response = new Response(file_get_contents($path));

        $url = $response->body();

        return $url;
    }

    public function getPageIdFromHtml($url): null|int
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
