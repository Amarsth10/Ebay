<?php
use \DTS\eBaySDK\Constants;
use \DTS\eBaySDK\Trading\Services;
use \DTS\eBaySDK\Trading\Types;
use \DTS\eBaySDK\Trading\Enums;


Class EbayController extends BaseController
{
    public function AddItem(){

        $config = Config::get('configuration');
        $service = new Services\TradingService(array(
            'apiVersion' => $config['tradingApiVersion'],
            // 'siteId' => '15'
            'siteId' => Constants\SiteIds::US,
            'sandbox' => true
        ));

        $request = new Types\AddFixedPriceItemRequestType();
        /**
         * An user token is required when using the Trading service.
         */
        $request->RequesterCredentials = new Types\CustomSecurityHeaderType();
        $request->RequesterCredentials->eBayAuthToken = $config['sandbox']['userToken'];
        /**
         * Begin creating the fixed price item.
         */
        $item = new Types\ItemType();
        /**
         * We want a multiple quantity fixed price listing.
         */
        $item->ListingType = Enums\ListingTypeCodeType::C_FIXED_PRICE_ITEM;
        $item->Quantity = (Integer)Input::get('quantity');
        /**
         * Let the listing be automatically renewed every 30 days until cancelled.
         */
        $item->ListingDuration = Enums\ListingDurationCodeType::C_GTC;
        /**
         * The cost of the item is $19.99.
         * Note that we don't have to specify a currency as eBay will use the site id
         * that we provided earlier to determine that it will be United States Dollars (USD).
         */
        $item->StartPrice = new Types\AmountType(['value' => (Double)Input::get('price')]);
        /**
         * Allow buyers to submit a best offer.
         */
        $item->BestOfferDetails = new Types\BestOfferDetailsType();
        $item->BestOfferDetails->BestOfferEnabled = true;
        /**
         * Automatically accept best offers of $17.99 and decline offers lower than $15.99.
         */
        $item->ListingDetails = new Types\ListingDetailsType();
        $item->ListingDetails->BestOfferAutoAcceptPrice = new Types\AmountType(['value' =>7.1]);
        $item->ListingDetails->MinimumBestOfferPrice = new Types\AmountType(['value' => 6.99]);
        /**
         * Provide a title and description and other information such as the item's location.
         * Note that any HTML in the title or description must be converted to HTML entities.
         */
        $item->Title = Input::get('title');
        $item->Description = Input::get('description');
        $item->SKU = 'ABC-001';
        $item->Country = 'US';
        $item->Location = 'Beverly Hills';
        $item->PostalCode = '90210';
        /**
         * This is a required field.
         */
        $item->Currency = 'USD';
        /**
         * Display a picture with the item.
         */
        $item->PictureDetails = new Types\PictureDetailsType();
        $item->PictureDetails->GalleryType = Enums\GalleryTypeCodeType::C_GALLERY;
        $item->PictureDetails->PictureURL = ['http://lorempixel.com/1500/1024/abstract'];
        /**
         * List item in the Books > Audiobooks (29792) category.
         */
        $item->PrimaryCategory = new Types\CategoryType();
        $item->PrimaryCategory->CategoryID = '29792';
        /**
         * Tell buyers what condition the item is in.
         * For the category that we are listing in the value of 1000 is for Brand New.
         */
        $item->ConditionID = 1000;
        /**
         * Buyers can use one of two payment methods when purchasing the item.
         * Visa / Master Card
         * PayPal
         * The item will be dispatched within 1 business days once payment has cleared.
         * Note that you have to provide the PayPal account that the seller will use.
         * This is because a seller may have more than one PayPal account.
         */
        $item->PaymentMethods = [
            'VisaMC',
            'PayPal'
        ];
        $item->PayPalEmailAddress = 'example@example.com';
        $item->DispatchTimeMax = 1;
        /**
         * Setting up the shipping details.
         * We will use a Flat shipping rate for both domestic and international.
         */
        $item->ShippingDetails = new Types\ShippingDetailsType();
        $item->ShippingDetails->ShippingType = Enums\ShippingTypeCodeType::C_FLAT;
        /**
         * Create our first domestic shipping option.
         * Offer the Economy Shipping (1-10 business days) service at $2.00 for the first item.
         * Additional items will be shipped at $1.00.
         */
        $shippingService = new Types\ShippingServiceOptionsType();
        $shippingService->ShippingServicePriority = 1;
        $shippingService->ShippingService = 'Other';
        $shippingService->ShippingServiceCost = new Types\AmountType(['value' => 2.00]);
        $shippingService->ShippingServiceAdditionalCost = new Types\AmountType(['value' => 1.00]);
        $item->ShippingDetails->ShippingServiceOptions[] = $shippingService;
        /**
         * Create our second domestic shipping option.
         * Offer the USPS Parcel Select (2-9 business days) at $3.00 for the first item.
         * Additional items will be shipped at $2.00.
         */
        $shippingService = new Types\ShippingServiceOptionsType();
        $shippingService->ShippingServicePriority = 2;
        $shippingService->ShippingService = 'USPSParcel';
        $shippingService->ShippingServiceCost = new Types\AmountType(['value' => 3.00]);
        $shippingService->ShippingServiceAdditionalCost = new Types\AmountType(['value' => 2.00]);
        $item->ShippingDetails->ShippingServiceOptions[] = $shippingService;
        /**
         * Create our first international shipping option.
         * Offer the USPS First Class Mail International service at $4.00 for the first item.
         * Additional items will be shipped at $3.00.
         * The item can be shipped Worldwide with this service.
         */

        /**
         * The return policy.
         * Returns are accepted.
         * A refund will be given as money back.
         * The buyer will have 14 days in which to contact the seller after receiving the item.
         * The buyer will pay the return shipping cost.
         */
        $item->ReturnPolicy = new Types\ReturnPolicyType();
        $item->ReturnPolicy->ReturnsAcceptedOption = 'ReturnsAccepted';
        $item->ReturnPolicy->RefundOption = 'MoneyBack';
        $item->ReturnPolicy->ReturnsWithinOption = 'Days_14';
        $item->ReturnPolicy->ShippingCostPaidByOption = 'Buyer';
        /**
         * Finish the request object.
         */
        $request->Item = $item;
        /**
         * Send the request.
         */
        $response = $service->addFixedPriceItem($request);
        /**
         * Output the result of calling the service operation.
         */
        if (isset($response->Errors)) {
            foreach ($response->Errors as $error) {
                printf(
                    "%s: %s\n%s\n\n",
                    $error->SeverityCode === Enums\SeverityCodeType::C_ERROR ? 'Error' : 'Warning',
                    $error->ShortMessage,
                    $error->LongMessage
                );
            }
        }
        if ($response->Ack !== 'Failure') {
            printf(
                "The item was listed to the eBay Sandbox with the Item number %s\n",
                $response->ItemID
            );
        }

}
public function Time(){
    $config = Config::get('configuration');
    $service = new Services\TradingService(array(
        'apiVersion' => $config['tradingApiVersion'],
        // 'siteId' => '15'
        'siteId' => Constants\SiteIds::US,
        'sandbox' => true
    ));

    $request = new Types\GeteBayOfficialTimeRequestType();
/**
 * An user token is required when using the Trading service.
 */
$request->RequesterCredentials = new Types\CustomSecurityHeaderType();
$request->RequesterCredentials->eBayAuthToken = $config['sandbox']['userToken'];
/**
 * Send the request.
 */
$response = $service->geteBayOfficialTime($request);
/**
 * Output the result of calling the service operation.
 */
if (isset($response->Errors)) {
    foreach ($response->Errors as $error) {
        printf(
            "%s: %s\n%s\n\n",
            $error->SeverityCode === Enums\SeverityCodeType::C_ERROR ? 'Error' : 'Warning',
            $error->ShortMessage,
            $error->LongMessage
        );
    }
}
if ($response->Ack !== 'Failure') {
    printf("The official eBay time is: %s\n", $response->Timestamp->format('H:i (\G\M\T) \o\n l jS F Y'));
}
}
public function GetCat(){
    $config = Config::get('configuration');
    $service = new Services\TradingService(array(
        'apiVersion' => $config['tradingApiVersion'],
        // 'siteId' => '15'
        'siteId' => Constants\SiteIds::AU,
        'sandbox' => true
    ));

    $request = new Types\GetCategoriesRequestType();
/**
 * An user token is required when using the Trading service.
 */
$request->RequesterCredentials = new Types\CustomSecurityHeaderType();
$request->RequesterCredentials->eBayAuthToken = $config['sandbox']['userToken'];
/**
 * By specifying 'ReturnAll' we are telling the API return the full category hierarchy.
 */
$request->DetailLevel = ['ReturnAll'];
/**
 * OutputSelector can be used to reduce the amount of data returned by the API.
 * http://developer.ebay.com/DevZone/XML/docs/Reference/ebay/GetCategories.html#Request.OutputSelector
 */
$request->OutputSelector = [
    'CategoryArray.Category.CategoryID',
    'CategoryArray.Category.CategoryParentID',
    'CategoryArray.Category.CategoryLevel',
    'CategoryArray.Category.CategoryName'
];
/**
 * Send the request.
 */
$response = $service->getCategories($request);
/**
 * Output the result of calling the service operation.
 */
if (isset($response->Errors)) {
    foreach ($response->Errors as $error) {
        printf(
            "%s: %s\n%s\n\n",
            $error->SeverityCode === Enums\SeverityCodeType::C_ERROR ? 'Error' : 'Warning',
            $error->ShortMessage,
            $error->LongMessage
        );
    }
}
if ($response->Ack !== 'Failure') {
    /**
     * For the US site this will output approximately 18,000 categories.
     */
    foreach ($response->CategoryArray->Category as $category) {
        printf(
            "Level %s : %s (%s) : Parent ID %s\n",
            $category->CategoryLevel,
            $category->CategoryName,
            $category->CategoryID,
            $category->CategoryParentID[0]
        );
    }
}
}
    public function GetSel(){
        $config = Config::get('configuration');
        $service = new Services\TradingService(array(
            'apiVersion' => $config['tradingApiVersion'],
            // 'siteId' => '15'
            'siteId' => Constants\SiteIds::US,
            'sandbox' => true
        ));

$request = new Types\GetMyeBaySellingRequestType();
/**
 * An user token is required when using the Trading service.
 */
$request->RequesterCredentials = new Types\CustomSecurityHeaderType();
$request->RequesterCredentials->eBayAuthToken = $config['sandbox']['userToken'];
/**
 * Request that eBay returns the list of actively selling items.
 * We want 10 items per page and they should be sorted in descending order by the current price.
 */
$request->ActiveList = new Types\ItemListCustomizationType();
$request->ActiveList->Include = true;
$request->ActiveList->Pagination = new Types\PaginationType();
$request->ActiveList->Pagination->EntriesPerPage = 10;
$request->ActiveList->Sort = Enums\ItemSortTypeCodeType::C_CURRENT_PRICE_DESCENDING;
$pageNum = 1;
do {
    $request->ActiveList->Pagination->PageNumber = $pageNum;
    /**
     * Send the request.
     */
    $response = $service->getMyeBaySelling($request);
    /**
     * Output the result of calling the service operation.
     */
    echo "==================\nResults for page $pageNum\n==================\n <br>";
    if (isset($response->Errors)) {
        foreach ($response->Errors as $error) {
            printf(
                "%s: %s\n%s\n\n",
                $error->SeverityCode === Enums\SeverityCodeType::C_ERROR ? 'Error' : 'Warning',
                $error->ShortMessage,
                $error->LongMessage
            );
        }
    }
    if ($response->Ack !== 'Failure' && isset($response->ActiveList)) {
        foreach ($response->ActiveList->ItemArray->Item as $item) {
            printf(
                "(%s) %s: %s %.2f\n <br>",
                $item->ItemID,
                $item->Title,
                $item->SellingStatus->CurrentPrice->currencyID,
                $item->SellingStatus->CurrentPrice->value
            );
        }
    }
    $pageNum += 1;
} while (isset($response->ActiveList) && $pageNum <= $response->ActiveList->PaginationResult->TotalNumberOfPages);
    }
}