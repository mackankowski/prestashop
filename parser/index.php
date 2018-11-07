<?php

require('simple_html_dom.php');
$url = 'http://sklep.intercars.com.pl';
$html = file_get_html($url);

$products = [
        array('Product ID', 'Active (0/1)', 'Name', 'Category', 'Image URL', 'Price', 'Quantity')
];

$productNum = 1;
$pageLimit = 2; // 1 page = 520 products

$subCategory = $html->find('.relative', 0); // czesci zamienne

foreach($subCategory->find('li a') as $category) {

        $categoryName = $category->plaintext;
        $categoryURL = $url . $category->href;

        for ($pageNum = 1; $pageNum <= $pageLimit; $pageNum++) {

                $categoryURL = $categoryURL . '#page=' . $pageNum;
        
                $htmlProductList= file_get_html($categoryURL);

                foreach ($htmlProductList->find('.prod-thumb') as $productItem) {
                
                        foreach($productItem->find('img') as $productImg) {
                                
                                $products[$productNum][0] = $productNum; // Product ID
                                $products[$productNum][1] = 1; // Active (0/1)
                                $products[$productNum][2] = $productImg->alt; // Name
                                $products[$productNum][3] = $categoryName; // Category
                                $products[$productNum][4] = $productImg->src; // Image URL

                        }
                
                        foreach($productItem->find('.current-price') as $productPrice) {
        
                                $products[$productNum][5] = substr($productPrice->plaintext, 0, -5); // Price
                                $products[$productNum][6] = rand(1,5); // Quantity
                                
                        }
        
                        ++$productNum;
                }
        }
}

$fp = fopen('products.csv', 'w');

foreach ($products as $fields) {
        fputcsv($fp, $fields, ';');
}

fclose($fp);

echo "Complete! Data saved in products.cvs ";

?>