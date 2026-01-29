<?php

namespace App\Services;

class SentimentAnalysisService
{
    /**
     * Mots-clés positifs pour l'analyse de sentiment
     */
    private array $positiveWords = [
        'excellent', 'parfait', 'super', 'génial', 'magnifique', 'satisfait',
        'rapide', 'qualité', 'recommande', 'merci', 'bravo', 'top',
        'incroyable', 'fantastique', 'impressionnant', 'efficace', 'professionnel',
        'agréable', 'content', 'ravi', 'heureux', 'bien', 'bon', 'bonne',
        'great', 'good', 'amazing', 'wonderful', 'happy', 'love', 'best',
        'superbe', 'impeccable', 'formidable', 'extra', 'nickel', 'parfaite'
    ];

    /**
     * Mots-clés négatifs pour l'analyse de sentiment
     */
    private array $negativeWords = [
        'nul', 'horrible', 'mauvais', 'déçu', 'catastrophe', 'lent',
        'problème', 'erreur', 'retard', 'arnaque', 'pire', 'jamais',
        'inacceptable', 'honteux', 'scandaleux', 'médiocre', 'terrible',
        'décevant', 'frustrant', 'colère', 'fâché', 'mécontent',
        'bad', 'worst', 'terrible', 'awful', 'hate', 'disappointed', 'poor',
        'naze', 'pourri', 'incompétent', 'inadmissible', 'désastreux'
    ];

    /**
     * Mots intensificateurs
     */
    private array $intensifiers = [
        'très', 'vraiment', 'tellement', 'extrêmement', 'super',
        'hyper', 'ultra', 'trop', 'absolument', 'totalement',
        'very', 'really', 'extremely', 'absolutely', 'totally'
    ];

    /**
     * Thèmes avec leurs mots-clés associés
     */
    private array $topicKeywords = [
        'delivery' => ['livraison', 'livreur', 'colis', 'envoi', 'expédition', 'délai', 'shipping', 'delivery', 'réception', 'arrivée'],
        'price' => ['prix', 'coût', 'cher', 'économique', 'tarif', 'promotion', 'réduction', 'price', 'expensive', 'cheap', 'abordable', 'gratuit'],
        'quality' => ['qualité', 'matériau', 'finition', 'durable', 'solide', 'fragile', 'quality', 'material', 'résistant', 'robuste'],
        'service' => ['service', 'client', 'support', 'aide', 'assistance', 'conseiller', 'customer', 'help', 'accueil', 'équipe'],
        'speed' => ['rapide', 'vite', 'lent', 'attente', 'immédiat', 'fast', 'slow', 'quick', 'vitesse', 'rapidité'],
        'packaging' => ['emballage', 'carton', 'protection', 'packaging', 'box', 'colis', 'paquet'],
        'ease_of_use' => ['facile', 'simple', 'intuitif', 'compliqué', 'difficile', 'easy', 'simple', 'hard', 'pratique', 'accessible']
    ];

    /**
     * Analyse complète d'un texte
     *
     * @param string $text
     * @return array
     */
    public function analyze(string $text): array
    {
        $sentiment = $this->analyzeSentiment($text);
        $score = $this->calculateScore($text);
        $topics = $this->detectTopics($text);

        return [
            'sentiment' => $sentiment,
            'score' => $score,
            'topics' => $topics,
        ];
    }

    /**
     * Analyse le sentiment d'un texte
     *
     * @param string $text
     * @return string
     */
    public function analyzeSentiment(string $text): string
    {
        $text = mb_strtolower($text);

        $positiveCount = 0;
        $negativeCount = 0;

        // Compter les mots positifs
        foreach ($this->positiveWords as $word) {
            $positiveCount += substr_count($text, $word);
        }

        // Compter les mots négatifs
        foreach ($this->negativeWords as $word) {
            $negativeCount += substr_count($text, $word);
        }

        // Déterminer le sentiment
        if ($positiveCount > $negativeCount * 1.5) {
            return 'positive';
        } elseif ($negativeCount > $positiveCount * 1.5) {
            return 'negative';
        } else {
            return 'neutral';
        }
    }

    /**
     * Calcule un score de 0 à 100
     *
     * @param string $text
     * @return int
     */
    public function calculateScore(string $text): int
    {
        $text = mb_strtolower($text);

        $positiveCount = 0;
        $negativeCount = 0;
        $intensifierCount = 0;

        // Compter les mots positifs
        foreach ($this->positiveWords as $word) {
            $positiveCount += substr_count($text, $word);
        }

        // Compter les mots négatifs
        foreach ($this->negativeWords as $word) {
            $negativeCount += substr_count($text, $word);
        }

        // Compter les intensificateurs
        foreach ($this->intensifiers as $word) {
            $intensifierCount += substr_count($text, $word);
        }

        // Score de base (50%)
        $totalWords = $positiveCount + $negativeCount + 1; // +1 pour éviter division par zéro
        $baseScore = ($positiveCount / $totalWords) * 100;

        // Ajustement pour la longueur (15%)
        $textLength = mb_strlen($text);
        $lengthModifier = 1.0;
        if ($textLength < 20) {
            $lengthModifier = 0.85; // Pénalité pour texte très court
        } elseif ($textLength > 1000) {
            $lengthModifier = 0.90; // Légère pénalité pour texte très long
        }

        // Ajustement pour la ponctuation (15%)
        $exclamationCount = substr_count($text, '!');
        $punctuationModifier = 1.0;
        if ($exclamationCount > 0) {
            if ($positiveCount > $negativeCount) {
                $punctuationModifier = 1.10; // Boost pour sentiment positif avec exclamation
            } else {
                $punctuationModifier = 0.95; // Légère pénalité pour sentiment négatif avec exclamation
            }
        }

        // Ajustement pour les intensificateurs (20%)
        $intensifierModifier = 1.0 + ($intensifierCount * 0.05); // +5% par intensificateur
        $intensifierModifier = min($intensifierModifier, 1.3); // Plafonner à +30%

        // Calcul du score final
        $finalScore = $baseScore * $lengthModifier * $punctuationModifier * $intensifierModifier;

        // Assurer que le score est entre 0 et 100
        return (int) max(0, min(100, $finalScore));
    }

    /**
     * Détecte les thèmes abordés dans le texte
     *
     * @param string $text
     * @return array
     */
    public function detectTopics(string $text): array
    {
        $text = mb_strtolower($text);
        $detectedTopics = [];

        foreach ($this->topicKeywords as $topic => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($text, $keyword)) {
                    $detectedTopics[] = $topic;
                    break; // Passer au thème suivant dès qu'un mot-clé est trouvé
                }
            }
        }

        // Limiter à 5 thèmes maximum
        return array_slice(array_unique($detectedTopics), 0, 5);
    }
}

