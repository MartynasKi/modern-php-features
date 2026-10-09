# 022: New DOM API

PHP 8.4 added a new DOM API with a real HTML5 parser and CSS selectors.

## How it works

The new API lives in the `Dom` namespace, next to the old `DOMDocument`
classes.

- `Dom\HTMLDocument::createFromString()` and `createFromFile()` parse HTML5
  the way browsers do, using the Lexbor library. No more warnings for tags
  such as `<main>` or `<article>`, and broken markup is fixed the way a
  browser would fix it.
- `querySelector()` returns the first element that matches a CSS selector.
  `querySelectorAll()` returns all of them.
- Elements have browser-style helpers such as `classList`, `closest()`,
  `innerHTML` and `firstElementChild`. The document has `body`, `head` and
  `title`.
- `Dom\XMLDocument` is the matching class for XML.

The old `DOMDocument::loadHTML()` uses libxml's HTML 4 parser. It warns about
every HTML5 tag and can build a different tree than a browser for broken
markup. To find elements you use `getElementsByTagName()` or XPath. A simple
class check in XPath needs a long `concat(' ', normalize-space(@class), ' ')`
trick to avoid matching `post-list` when you want `post`.

The old classes still work and are not deprecated. The new ones are separate
classes, so you cannot mix nodes from the two APIs.

## Run the examples

This project uses PHP 8.5, but the new DOM API works since PHP 8.4. Run these
commands from the repository root after `composer install`.

### [01-html-document.php](examples/01-html-document.php)

`DOMDocument` reports `main` and `article` as invalid tags.
`Dom\HTMLDocument` parses the same page quietly, closes the unclosed `<p>` and
offers shortcuts such as `body` and `title`.

```shell
php ./022-dom-api/examples/01-html-document.php
```

### [02-query-selector.php](examples/02-query-selector.php)

Finds products with ID, class, attribute and pseudo-class selectors such as
`li:not(.sale)`. The last part walks up from a link with `closest()` and
checks a class with `classList`.

```shell
php ./022-dom-api/examples/02-query-selector.php
```

### [03-scraping-compared.php](examples/03-scraping-compared.php)

Collects post links twice. The old way needs `DOMXPath` and the class trick.
The new way is `querySelectorAll('article.post a')`. Both skip the
`post-list` link.

```shell
php ./022-dom-api/examples/03-scraping-compared.php
```

The PHP manual has more on the [Dom\HTMLDocument class](https://www.php.net/manual/en/class.dom-htmldocument.php).
