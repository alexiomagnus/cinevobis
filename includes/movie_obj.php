<?php
/**
 * Rappresenta un film e si occupa di normalizzare i dati grezzi provenienti
 * dall'API TMDB o da MongoDB in un formato strutturato e uniforme.
 */
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

    
    /**
     * Popola le proprietà del film a partire da un array grezzo (TMDB o MongoDB).
     * Applica valori di fallback per i campi mancanti e limita il cast ai primi 12 attori.
     *
     * @param array $data Array associativo con i dati del film (struttura TMDB).
     */
    public function __construct(array $data)
    {
        $this->titolo = $data['title'] ?? 'Titolo non disponibile';
        $this->titolo_orig = $data['original_title'] ?? '';

        $this->trama = !empty($data['overview']) ? $data['overview'] : 'Nessuna trama disponibile';        
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


    /**
     * Filtra il crew del film per estrarre solo i membri con job === 'Director'.
     * Reindizza l'array risultante per rimuovere i gap numerici lasciati da array_filter.
     *
     * @param array $data Array grezzo del film contenente la chiave 'credits.crew'.
     * @return array Array dei registi con i loro dati TMDB.
     */
    private function searchDirectors(array $data): array
    {
        $crew = $data['credits']['crew'] ?? [];

        $directors = array_filter($crew, function ($persona) {
            return ($persona['job'] ?? '') === 'Director';
        });

        return array_values($directors);
    }


    /**
     * Converte un array di risultati di ricerca TMDB in un formato semplificato
     * adatto alla visualizzazione nelle liste (id, titolo, anno, URL poster thumbnail).
     *
     * @param array $movies Array di film nel formato restituito dall'endpoint /search/movie di TMDB.
     * @return array Array semplificato con id, titolo, anno e URL poster (w92).
     */
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


    /**
     * Serializza tutte le proprietà del film in un array associativo.
     * Utile per passare i dati alle view senza esporre l'oggetto direttamente.
     *
     * @return array Array associativo con tutti i campi del film (titolo, trama, cast, ecc.).
     */
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