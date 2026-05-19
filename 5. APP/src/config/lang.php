<?php

function loadLang(string $lang = 'es'): array {
    $path = __DIR__ . '/../lang/' . $lang . '.json';
    if (!file_exists($path)) {
        $path = __DIR__ . '/../lang/es.json';
    }
    return json_decode(file_get_contents($path), true) ?? [];
}

$GLOBALS['_lang'] = loadLang($_COOKIE['lang'] ?? 'es');

function t(string $key, array $replace = []): string {
    $str = $GLOBALS['_lang'][$key] ?? $key;
    foreach ($replace as $k => $v) {
        $str = str_replace(':' . $k, $v, $str);
    }
    return htmlspecialchars($str);
}