<?php
function movie_sorting($cursor, $ids) {
    // Trasforma il cursore MongoDB in un array associativo
    $raw_films = iterator_to_array($cursor);

    // Mappa i film usando il loro ID come chiave per un accesso rapido
    $films_map = [];
    foreach ($raw_films as $f) {
        $films_map[$f['id']] = $f;
    }

    // Inizializza l'array
    $films = [];
    
    // Ricostruisce la lista seguendo l'ordine esatto di $ids
    foreach ($ids as $id) {
        if (isset($films_map[$id])) {
            $films[] = $films_map[$id];
        }
    }

    return $films;
}

function order_of_popularity ($n, $results) {
    // Ordinare per popolarità
    for ($i = 0; $i < $n - 1; $i++) {
        for ($j = $i + 1; $j < $n; $j++) {
            if ($results[$i]['popularity'] < $results[$j]['popularity']) {
                // scambio
                $temp = $results[$i];
                $results[$i] = $results[$j];
                $results[$j] = $temp;
            }
        }
    }

    return $results;
}

function search_film_by_id($topFilms, $movie_id) {
    foreach($topFilms as $topFilm) {
        if((int) $topFilm['id'] === $movie_id) {
            $film = $topFilm;
            break;
        }   
    }

    return $film;
}