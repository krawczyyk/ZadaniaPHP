<!DOCTYPE html>
<html>
<head>
    <title>Rekrutacja </title>
</head>
<body>
    <h1>Zadnia rekrutacyjne php</h1>

    <?php
    // Implementacja klasy Pipeline
    class Pipeline {
        public static function make(...$functions) {
            return function($arg) use ($functions) {
                $result = $arg;
                
                foreach ($functions as $func) {
                    $result = $func($result);
                }
                
                return $result;
            };
        }
    }

    // Implementacja klasy TextInput
    class TextInput {
        protected $value = '';

        public function add($text) {
            $this->value .= $text;
        }

        public function getValue() {
            return $this->value;
        }
    }

    // Implementacja klasy NumericInput
    class NumericInput extends TextInput {
        public function add($text) {
            // Jeśli tekst składa się tylko z cyfr, to dodaj do wartości
            if (ctype_digit($text)) {
                parent::add($text);
            }
        }
    }

    // Implementacja klasy RankingTable
    class RankingTable {
        protected $players = [];

        public function __construct($playerNames) {
            foreach ($playerNames as $name) {
                $this->players[$name] = ['score' => 0, 'gamesPlayed' => 0];
            }
        }

        public function recordResult($playerName, $score) {
            $this->players[$playerName]['score'] += $score;
            $this->players[$playerName]['gamesPlayed']++;
        }

        public function playerRank($rank) {
            uasort($this->players, function ($a, $b) {
                if ($a['score'] !== $b['score']) {
                    return $b['score'] - $a['score'];
                } elseif ($a['gamesPlayed'] !== $b['gamesPlayed']) {
                    return $a['gamesPlayed'] - $b['gamesPlayed'];
                } else {
                    return 0;
                }
            });

            $sortedPlayers = array_keys($this->players);
            return $sortedPlayers[$rank - 1];
        }
    }

    // Implementacja klasy Thesaurus
    class Thesaurus {
        protected $thesaurusData;

        public function __construct($data) {
            $this->thesaurusData = $data;
        }

        public function getSynonyms($word) {
            $synonyms = isset($this->thesaurusData[$word]) ? $this->thesaurusData[$word] : [];
            $result = [
                'word' => $word,
                'synonyms' => $synonyms,
            ];
            return json_encode($result);
        }
    }

    // Przykład użycia klasy Pipeline
    $pipeline = Pipeline::make(
        function($var) { return $var * 3; },
        function($var) { return $var + 1; },
        function($var) { return $var / 2; }
    );

    $result = $pipeline(3);
    echo "Wynik z Pipeline: " . $result . "<br>";

    // Przykład użycia klasy TextInput
    $textInput = new TextInput();
    $textInput->add("Hello");
    $textInput->add(" World");
    echo "TextInput: " . $textInput->getValue() . "<br>";

    // Przykład użycia klasy NumericInput
    $numericInput = new NumericInput();
    $numericInput->add("123");
    $numericInput->add("abc");
    $numericInput->add("456");
    echo "NumericInput: " . $numericInput->getValue() . "<br>";

    // Przykład użycia klasy RankingTable
    $table = new RankingTable(array('Jan', 'Maks', 'Monika'));
    $table->recordResult('Jan', 2);
    $table->recordResult('Maks', 3);
    $table->recordResult('Monika', 5);
    echo "Ranking 1: " . $table->playerRank(1) . "<br>";

    // Przykład użycia klasy Thesaurus
    $thesaurusData = array("market" => array("trade"), "small" => array("little", "compact"));
    $thesaurus = new Thesaurus($thesaurusData);

    // Przykład użycia funkcji getSynonyms
    $word = "small";
    $synonymsJson = $thesaurus->getSynonyms($word);
    echo "Synonimy dla '{$word}': " . $synonymsJson;
    ?>

</body>
</html>