<?php
class movieObj
{
    private string $titolo;
    private string $titolo_orig;
    private string $trama;
    private ?string $poster_path;
    private float $voto;
    private $durata;
    private $anno;
    private array $generi;
    private array $cast;
    private ?string $trailer_key;
    private string $paese;
    private array $registi;

    
    public function __construct(array $data)
    {
        $this->titolo = $data['title'] ?? 'Titolo non disponibile';
        $this->titolo_orig = $data['original_title'] ?? '';

        $this->trama = $data['overview'] ?? 'Nessuna trama disponibile.';
        $this->poster_path = !empty($data['poster_path']) ? $data['poster_path'] : null;

        $this->voto = (float)($data['vote_average'] ?? 0);
        $this->trailer_key = $data['videos']['results'][0]['key'] ?? null;

        $this->durata = $data['runtime'] ?? 'N/A';
        $this->anno = !empty($data['release_date']) ? substr($data['release_date'], 0, 4) : 'N/A';

        $this->generi = $data['genres'] ?? [];
        $this->paese = $data['production_countries'][0]['name'] ?? 'Nessun paese';
        
        $this->cast = array_slice($data['credits']['cast'] ?? [], 0, 12);
        $this->registi = $this->searchDirectors($data);
    }


    private function searchDirectors(array $data): array
    {
        $crew = $data['credits']['crew'] ?? [];

        $directors = array_filter($crew, function ($persona) {
            return ($persona['job'] ?? '') === 'Director';
        });

        return array_values($directors);
    }


    public static function search(array $movies): array
    {
        $moviesList = [];
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


    public function toArray(): array
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