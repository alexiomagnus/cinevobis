<?php
function ordinamentoFilm($cursor, $ids) {
    // Da puntatore ad array, si estraggono i dati da MongoDB come array
    $raw_films = iterator_to_array($cursor);

    // --- Riordinamento manuale ---
    $films_map = [];

    foreach ($raw_films as $f) {
        $films_map[$f['id']] = $f;
    }

    // Ricostruiamo la lista $films seguendo l'ordine esatto di $ids
    foreach ($ids as $id) {
        if (isset($films_map[$id])) {
            $films[] = $films_map[$id];
        }
    }

    return $films;
}