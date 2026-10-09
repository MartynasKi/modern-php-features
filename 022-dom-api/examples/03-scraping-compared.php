<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Scraping: old vs new');

$html = <<<'HTML'
    <!DOCTYPE html>
    <main>
        <article class="post featured"><h2><a href="/one">First post</a></h2></article>
        <article class="post"><h2><a href="/two">Second post</a></h2></article>
        <aside class="post-list"><a href="/three">Not a post</a></aside>
    </main>
    HTML;

section('DOMDocument and XPath');
libxml_use_internal_errors(true);
$old = new DOMDocument();
$old->loadHTML($html);
libxml_clear_errors();

$xpath = new DOMXPath($old);
// A class check in XPath needs this to avoid matching "post-list".
$links = $xpath->query("//article[contains(concat(' ', normalize-space(@class), ' '), ' post ')]//a");

foreach ($links as $link) {
    text($link->textContent . ' => ' . $link->getAttribute('href'));
}

section('Dom\HTMLDocument and CSS');
$document = Dom\HTMLDocument::createFromString($html);

foreach ($document->querySelectorAll('article.post a') as $link) {
    text($link->textContent . ' => ' . $link->getAttribute('href'));
}
