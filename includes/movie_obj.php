<?php
class movieObj
{
    private $titolo;
    private $titolo_orig;
    private $trama;
    private $poster_path;
    private $voto;
    private $durata;
    private $anno;
    private $generi;
    private $cast;
    private $trailer_key;
    private $paese;
    private $registi;


    // titolo, titolo_orig, trama, poster_path, voto, durata, generi, cast, trailer_key, realease_date, paese, registi
    public function __construct($data)
    {
        $this->titolo = $data['title'] ?? 'Titolo non disponibile';
        $this->titolo_orig = $data['original_title'] ?? '';

        $this->trama = $data['overview'] ?? 'Nessuna trama disponibile.';
        $this->poster_path = !empty($data['poster_path']) ? $data['poster_path'] : null;

        $this->voto = $data['vote_average'] ?? 0;
        $this->trailer_key = $data['videos']['results'][0]['key'] ?? null;

        $this->durata = $data['runtime'] ?? '?';
        $this->anno = !empty($data['release_date']) ? substr($data['release_date'], 0, 4) : 'N/D';

        $this->generi = $data['genres'] ?? [];
        $this->paese = $data['production_countries'][0]['name'] ?? 'Nessun paese';

        $this->cast = array_slice($data['credits']['cast'] ?? [], 0, 12);
        $this->registi = $this->searchDirectors($data);
    }

    private function searchDirectors($data)
    {
        return array_filter(
            $data['credits']['crew'],

            function ($persona) {
                return $persona['job'] === 'Director';
            }
        );
    }

    public static function search($movies)
    {
        foreach ($movies as $movie) {
            $moviesList[] = [
                'id' => $movie['id'],
                'titolo' => $movie['title'] ?? 'Titolo non disponibile.',
                'anno'   => !empty($movie['release_date']) ? substr($movie['release_date'], 0, 4) : null,
                'poster' => !empty($movie['poster_path']) ? 'https://image.tmdb.org/t/p/w92' . $movie['poster_path'] : null
            ];
        }

        return $moviesList;
    }

    public function toArray()
    {
        return [
            'titolo' => $this->titolo,
            'titolo_orig' => $this->titolo_orig,
            'trama' => $this->trama,
            'poster_path' => $this->poster_path,
            'voto' => $this->voto,
            'durata' => $this->durata,
            'anno' => $this->anno,
            'generi' => $this->generi,
            'paese' => $this->paese,
            'cast' => $this->cast,
            'registi' => $this->registi,
            'trailer_key' => $this->trailer_key
        ];
    }
}