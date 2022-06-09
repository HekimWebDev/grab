<?php

namespace Service\Avva\Request;

trait PriceRequest
{
    public function getPricesFromAPI($url, int $pageId, int $pageNumber): array
    {
//        $url = 'api/product/GetProductList?c=trtry0000&FilterJson=%7B"CategoryIdList"%3A%5B974%5D%2C"BrandIdList"%3A%5B%5D%2C"SupplierIdList"%3A%5B%5D%2C"TagIdList"%3A%5B%5D%2C"TagId"%3A-1%2C"FilterObject"%3A%5B%5D%2C"MinStockAmount"%3A-1%2C"IsShowcaseProduct"%3A-1%2C"IsOpportunityProduct"%3A-1%2C"FastShipping"%3A-1%2C"IsNewProduct"%3A-1%2C"IsDiscountedProduct"%3A-1%2C"IsShippingFree"%3A-1%2C"IsProductCombine"%3A-1%2C"MinPrice"%3A0%2C"MaxPrice"%3A0%2C"SearchKeyword"%3A""%2C"StrProductIds"%3A""%2C"IsSimilarProduct"%3Afalse%2C"RelatedProductId"%3A0%2C"ProductKeyword"%3A""%2C"PageContentId"%3A0%2C"StrProductIDNotEqual"%3A""%2C"IsVariantList"%3A-1%2C"IsVideoProduct"%3A-1%2C"ShowBlokVideo"%3A-1%2C"VideoSetting"%3A%7B"ShowProductVideo"%3A-1%2C"AutoPlayVideo"%3A-1%7D%2C"ShowList"%3A1%2C"VisibleImageCount"%3A6%2C"ShowCounterProduct"%3A-1%2C"ImageSliderActive"%3Atrue%2C"ProductListPageId"%3A0%2C"ShowGiftHintActive"%3Afalse%2C"NonStockShowEnd"%3A1%7D&PagingJson=%7B"PageItemCount"%3A0%2C"PageNumber"%3A10%2C"OrderBy"%3A"KATEGORISIRA"%2C"OrderDirection"%3A"ASC"%7D&CreateFilter=false&TransitionOrder=0&PageType=1&PageId=974';
        //get API from $url
        $this->getFromAPI($url);

        if ($this->response->getStatusCode() != 200)
            return [];

        $body = json_decode($this->response->getBody()->getContents(), true);

//        return $body['products'][0];

        if( empty(($body['products'])) )
            return [];

        foreach ($body['products'] as $item){

            $product['original_price'] = $item['productPriceOriginalStr'];

            $product['sale_price'] = $item['productSellPriceStr'];

            $product['internal_code'] = "av_" . $item['productId'] . '_' . $item['stockCode'];

            $arr[] = $product;
        }

        return $arr;
    }
}
