<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Enums and JSON');

enum Suit
{
    case Hearts;
    case Spades;
}

enum Status: string
{
    case Draft = 'draft';
    case Published = 'published';
}

$post = ['title' => 'Hello', 'status' => Status::Published];

section('A backed enum becomes its value');
$json = json_encode($post, JSON_THROW_ON_ERROR);
dump($json);

section('Decoding gives back a string, not the enum');
$decoded = json_decode($json, true, flags: JSON_THROW_ON_ERROR);
dump($decoded['status']);
dump(Status::from($decoded['status']));

section('A pure enum cannot be encoded');
try {
    json_encode(['suit' => Suit::Hearts], JSON_THROW_ON_ERROR);
} catch (JsonException $exception) {
    dump($exception->getMessage());
}

section('serialize() works for both kinds');
dump(serialize(Suit::Hearts));
dump(unserialize(serialize(Suit::Hearts)) === Suit::Hearts);
