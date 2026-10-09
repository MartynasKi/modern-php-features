<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Dom\HTMLDocument');

$html = <<<'HTML'
    <!DOCTYPE html>
    <html>
    <head><title>Demo page</title></head>
    <body>
        <main>
            <article><h1>Hello</h1><p>Unclosed paragraph</article>
        </main>
    </body>
    </html>
    HTML;

section('The old DOMDocument uses an HTML 4 parser');
libxml_use_internal_errors(true);
$old = new DOMDocument();
$old->loadHTML($html);

foreach (libxml_get_errors() as $error) {
    text(trim($error->message));
}

libxml_clear_errors();

section('Dom\HTMLDocument parses HTML5 like a browser');
$document = Dom\HTMLDocument::createFromString($html);
dump($document->querySelector('main')::class);

section('Broken markup is fixed the way a browser fixes it');
dump($document->querySelector('article')->innerHTML);

section('Handy shortcuts');
dump($document->body->firstElementChild->tagName);
dump($document->title);
