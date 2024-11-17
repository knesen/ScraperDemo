<?php
require_once 'simple_html_dom.php';


$dom = file_get_html('https://oljefyndet.se/produkt-kategori/bil/bil-motorolja/', false);

$answer = array();

if(!empty($dom)) {
    $divClass = $title = '';$i = 0;
    foreach($dom->find(".jet-woo-products__item") as $divClass) {

        //ID
        $id = $divClass->getAttribute("data-product-id");

        $answer[$i]['Id'] = trim($id);


        //Name
        foreach($divClass->find(".jet-woo-product-title") as $name ) {
            $answer[$i]['Name'] = $name->plaintext;
        }
        //Price
        foreach ($divClass->find(".jet-woo-product-price") as $priceElement) {

            $lowestPrice = $priceElement->find('bdi', 0);
            $highestPrice = ($priceElement->find('bdi', 1)) ? trim($priceElement->find('bdi', 1)->innertext) : 0;

            $rawLowestPrice = $lowestPrice->innertext;
            $cleanLowestPrice = number_format((float) str_replace(',', '.', preg_replace('/[^\d.,\-–\s]+/', '', $rawLowestPrice)), 2, '.', '');

            if ($highestPrice != 0){
                $rawHighestPrice = $highestPrice;
                $cleanHighestPrice = number_format((float) preg_replace('/[^\d.\-–\s]+/', '', trim($highestPrice)), 2, '.', '');

                $priceSpan = ("$cleanLowestPrice - $cleanHighestPrice");
            }
            else {
                $priceSpan = $cleanLowestPrice;
            }

            $answer[$i]['Price in SEK'] = trim($priceSpan);
        }

        //ImageURL
        foreach($divClass->find('.jet-woo-product-thumbnail') as $imageContainer) {
            $imageElement = $imageContainer->find('img', 0);

            if (preg_match('/src="([^"]+)"/', $imageElement, $matches)) {
                $imageURL = $matches[1];
                }

            $answer[$i]['ImageURL'] = $imageURL;
        }
        $i++;
    }
}
print_r($answer); exit;

