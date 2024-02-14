<?php

declare(strict_types=1);

namespace HPT;


use DOMDocument;
use DOMElement;
use DOMNode;
use DOMXPath;
use HPT\entity\Product;

class CzcGrabber implements IGrabber
{
public function getProductDetails(string $productCode): ?Product
{
    $url = "https://www.czc.cz/{$productCode}/hledat";
    $html = $this->fetchHtml($url);

    if (!$html) {
        return null;
    }

    return $this->parseProductDetails($productCode, $html);
}

private function fetchHtml(string $url): ?string
{
    $ch = curl_init($url);
    if (!$ch) {
        return null;
    }

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $html = curl_exec($ch);
    curl_close($ch);

    if (!is_string($html)) {
        return null;
    }

    return $html;
}

private function parseProductDetails(string $productCode, string $html): ?Product
{
    libxml_use_internal_errors(true);

    $dom = new DOMDocument();

    $htmlWithUtf8Meta = $this->ensureUtf8Encoding($html);

    libxml_clear_errors();
    if (!$dom->loadHTML($htmlWithUtf8Meta)) {
        return null;
    }

    $xpath = new DOMXPath($dom);
    $productNodes = $xpath->query("//div[@id='product-list-container']//div[@id='tiles']/div[contains(@class, 'new-tile')]");
    if (!$productNodes) {
        return null;
    }
    foreach ($productNodes as $productNode) {
        if (!$productNode instanceof DOMElement) {
            return null;
        }
        $gaImpressionAttr = $productNode->getAttribute('data-ga-impression');
        if (!empty($gaImpressionAttr)) {
            $gaImpressionData = json_decode($gaImpressionAttr, true);
            if ($gaImpressionData) {
                $name = $gaImpressionData['name'] ?? null;
                $price = $gaImpressionData['price'] ?? null;

                $rating = $this->extractRating($xpath, $productNode);

                return new Product($productCode, $name, $price, $rating);
            }
        }
    }

    return null;
}

    private function extractRating(DOMXPath $xpath, DOMNode $contextNode): ?int
    {
        $ratingNodes = $xpath->query(".//*[contains(@class, 'rating')]", $contextNode);
        if (!$ratingNodes) {
            return null;
        }
        foreach ($ratingNodes as $ratingNode) {
            if (!$ratingNode instanceof DOMElement) {
                return null;
            }
            $styleAttribute = $ratingNode->getAttribute('style');
            if ($styleAttribute && preg_match('/--rating-value:\s*(\d+)/', $styleAttribute, $matches)) {
                return intval($matches[1]);
            }
        }

        return null;
    }

private function ensureUtf8Encoding(string $html): string
{
    if (stripos($html, '<meta charset="UTF-8">') === false) {
        $html = '<meta charset="UTF-8">' . $html;
    }
    return $html;
}
}
