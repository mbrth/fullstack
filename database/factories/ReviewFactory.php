<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $reviews = [
            'Excellent service, livraison rapide et produit de qualité !',
            'Très satisfait de mon achat, je recommande vivement.',
            'Produit conforme à la description, emballage soigné.',
            'Déçu par la qualité, le produit est arrivé abîmé.',
            'Service client très professionnel et à l\'écoute.',
            'Livraison plus lente que prévu mais produit correct.',
            'Prix un peu élevé mais la qualité est au rendez-vous.',
            'Horrible expérience, je ne recommande absolument pas !',
            'Facile d\'utilisation, exactement ce que je cherchais.',
            'Rapport qualité-prix intéressant, très content.',
        ];

        $sentiments = ['positive', 'neutral', 'negative'];
        $topics = [
            ['delivery', 'quality'],
            ['price', 'quality'],
            ['service', 'speed'],
            ['packaging', 'delivery'],
            ['ease_of_use', 'quality'],
        ];

        return [
            'user_id' => \App\Models\User::factory(),
            'content' => fake()->randomElement($reviews),
            'sentiment' => fake()->randomElement($sentiments),
            'score' => fake()->numberBetween(0, 100),
            'topics' => fake()->randomElement($topics),
        ];
    }
}

