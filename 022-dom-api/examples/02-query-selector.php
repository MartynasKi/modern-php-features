<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('CSS selectors');

$html = <<<'HTML'
    <!DOCTYPE html>
    <ul id="products">
        <li class="product sale" data-price="9.99"><a href="/mug">Mug</a></li>
        <li class="product" data-price="24.50"><a href="/hoodie">Hoodie</a></li>
        <li class="product sale" data-price="4.00"><a href="/sticker">Sticker</a></li>
    </ul>
    HTML;

$document = Dom\HTMLDocument::createFromString($html);

section('querySelector() returns the first match');
dump($document->querySelector('#products a')->textContent);

section('querySelectorAll() returns every match');
foreach ($document->querySelectorAll('li.sale') as $item) {
    text($item->textContent . ' costs ' . $item->getAttribute('data-price'));
}

section('Attribute and pseudo-class selectors');
dump($document->querySelector('a[href="/hoodie"]')->textContent);
dump($document->querySelector('li:last-child a')->textContent);
dump($document->querySelector('li:not(.sale) a')->textContent);

section('classList and closest()');
$sticker = $document->querySelector('a[href="/sticker"]');
dump($sticker->closest('li')->classList->contains('sale'));
