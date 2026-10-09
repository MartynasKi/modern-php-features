<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('mb_trim and friends');

// A no-break space (U+00A0) and an ideographic space (U+3000) around the text.
$text = "\u{00A0}\u{3000}Labas\u{00A0}";

section('trim() only knows ASCII whitespace');
dump(trim($text));

section('mb_trim() knows Unicode whitespace');
dump(mb_trim($text));
dump(mb_ltrim($text));
dump(mb_rtrim($text));

section('Custom characters work too');
dump(mb_trim('ąąLabasąą', 'ą'));

section('mb_ucfirst() and mb_lcfirst() also came in PHP 8.4');
dump(ucfirst('ąžuolas'));
dump(mb_ucfirst('ąžuolas'));
