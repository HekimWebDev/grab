<?php

namespace App\Console;

use App\Console\Commands\AltinyildizCategoryGabCommand;
use App\Console\Commands\AltinyildizPriceGrabCommand;
use App\Console\Commands\AltinyildizProductGrabCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        AltinyildizCategoryGabCommand::class,
        AltinyildizProductGrabCommand::class,
        AltinyildizPriceGrabCommand::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
//        $schedule->command('ay:category:grab')->weekly()->sundays()->at('01:00');
//        $schedule->command('ay:product:grab')->dailyAt('01:30');
//        $schedule->command('ay:price:grab')->dailyAt('02:30');

//        $schedule->command('rs:product:grab')->dailyAt('03:00');
//        $schedule->command('rs:price:grab')->dailyAt('03:20');
//        $schedule->command('rs:product_code:grab')->dailyAt('03:50');


//        $schedule->command('mv:category:grab')->weekly()->sundays()->at('04:25');
//        $schedule->command('mv:product:grab')->dailyAt('04:30');
//        $schedule->command('mv:price:grab')->dailyAt('05:00');

        $schedule->command('kt:category:grab')->weekly()->sundays()->at('05:10');
        $schedule->command('kt:product:grab')->dailyAt('05:15');
        $schedule->command('kt:price:grab')->dailyAt('06:00');

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
//https://www.avva.com.tr/api/product/GetProductList?FilterJson=%7B%22CategoryIdList%22%3A%5B%5D%2C%22BrandIdList%22%3A%5B%5D%2C%22SupplierIdList%22%3A%5B%5D%2C%22TagIdList%22%3A%5B%5D%2C%22TagId%22%3A-1%2C%22FilterObject%22%3A%5B%5D%2C%22MinStockAmount%22%3A-1%2C%22IsShowcaseProduct%22%3A-1%2C%22IsOpportunityProduct%22%3A-1%2C%22FastShipping%22%3A-1%2C%22IsNewProduct%22%3A-1%2C%22IsDiscountedProduct%22%3A-1%2C%22IsShippingFree%22%3A-1%2C%22IsProductCombine%22%3A-1%2C%22MinPrice%22%3A0%2C%22MaxPrice%22%3A0%2C%22SearchKeyword%22%3A%22%22%2C%22StrProductIds%22%3A%22%22%2C%22IsSimilarProduct%22%3Afalse%2C%22RelatedProductId%22%3A0%2C%22ProductKeyword%22%3A%22%22%2C%22PageContentId%22%3A0%2C%22StrProductIDNotEqual%22%3A%22%22%2C%22IsVariantList%22%3A-1%2C%22IsVideoProduct%22%3A-1%2C%22ShowBlokVideo%22%3A-1%2C%22VideoSetting%22%3A%7B%22ShowProductVideo%22%3A0%2C%22AutoPlayVideo%22%3A-1%7D%2C%22ShowList%22%3A1%2C%22VisibleImageCount%22%3A6%2C%22ShowCounterProduct%22%3A-1%2C%22ImageSliderActive%22%3Atrue%2C%22ProductListPageId%22%3A0%2C%22ShowGiftHintActive%22%3Afalse%7D&PagingJson=%7B%22PageItemCount%22%3A0%2C%22PageNumber%22%3A1%2C%22OrderBy%22%3A%22KATEGORISIRA%22%2C%22OrderDirection%22%3A%22ASC%22%7D
//https://www.avva.com.tr/api/product/GetProductList?c=trtry0000&FilterJson=%7B%22CategoryIdList%22%3A%5B738%5D%2C%22BrandIdList%22%3A%5B%5D%2C%22SupplierIdList%22%3A%5B%5D%2C%22TagIdList%22%3A%5B%5D%2C%22TagId%22%3A-1%2C%22FilterObject%22%3A%5B%5D%2C%22MinStockAmount%22%3A-1%2C%22IsShowcaseProduct%22%3A-1%2C%22IsOpportunityProduct%22%3A-1%2C%22FastShipping%22%3A-1%2C%22IsNewProduct%22%3A-1%2C%22IsDiscountedProduct%22%3A-1%2C%22IsShippingFree%22%3A-1%2C%22IsProductCombine%22%3A-1%2C%22MinPrice%22%3A0%2C%22MaxPrice%22%3A0%2C%22SearchKeyword%22%3A%22%22%2C%22StrProductIds%22%3A%22%22%2C%22IsSimilarProduct%22%3Afalse%2C%22RelatedProductId%22%3A0%2C%22ProductKeyword%22%3A%22%22%2C%22PageContentId%22%3A0%2C%22StrProductIDNotEqual%22%3A%22%22%2C%22IsVariantList%22%3A-1%2C%22IsVideoProduct%22%3A-1%2C%22ShowBlokVideo%22%3A-1%2C%22VideoSetting%22%3A%7B%22ShowProductVideo%22%3A-1%2C%22AutoPlayVideo%22%3A-1%7D%2C%22ShowList%22%3A1%2C%22VisibleImageCount%22%3A6%2C%22ShowCounterProduct%22%3A-1%2C%22ImageSliderActive%22%3Atrue%2C%22ProductListPageId%22%3A0%2C%22ShowGiftHintActive%22%3Afalse%2C%22NonStockShowEnd%22%3A1%7D&PagingJson=%7B%22PageItemCount%22%3A0%2C%22PageNumber%22%3A2%2C%22OrderBy%22%3A%22KATEGORISIRA%22%2C%22OrderDirection%22%3A%22ASC%22%7D&CreateFilter=false&TransitionOrder=0&PageType=1&PageId=738
