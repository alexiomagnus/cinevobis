<?php
// Gestisce la normalizzazione dei dati di un film provenienti da TMDB o MongoDB.
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

    // Costruttore della classe: estrae e normalizza i dettagli essenziali del film 
    // (titolo, trama, cast, ecc.) a partire dall'array di dati grezzi ricevuto in input.
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
        
        // Limita il cast ai primi 12 attori per evitare array troppo pesanti
        $this->cast = array_slice($data['credits']['cast'] ?? [], 0, 12);
        $this->registi = $this->searchDirectors($data);
    }

    // Funzione privata di supporto: analizza i dati della troupe (crew) 
    // e filtra l'array per restituire esclusivamente i membri col ruolo di regista.
    private function searchDirectors(array $data): array
    {
        $crew = $data['credits']['crew'] ?? [];

        $directors = array_filter($crew, function ($persona) {
            return ($persona['job'] ?? '') === 'Director';
        });

        return array_values($directors);
    }

    // Metodo statico: processa una lista di risultati grezzi (es. risultati di ricerca TMDB)
    // e restituisce un array semplificato contenente solo ID, titolo, anno e URL della locandina.
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

    // Restituisce tutte le proprietà dell'oggetto film formattate in un array associativo, 
    // ideale per il salvataggio su database documentali (come MongoDB) o per risposte JSON.
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
?>