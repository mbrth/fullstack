<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics
     *
     * @return JsonResponse
     */
    public function stats(): JsonResponse
    {
        // Total d'avis
        $totalReviews = Review::count();

        // Distribution des sentiments
        $sentimentDistribution = Review::select('sentiment', DB::raw('count(*) as count'))
            ->groupBy('sentiment')
            ->pluck('count', 'sentiment')
            ->toArray();

        // Score moyen
        $averageScore = Review::avg('score') ?? 0;

        // Top thèmes
        $allTopics = Review::whereNotNull('topics')->pluck('topics')->flatten()->toArray();
        $topicCounts = array_count_values($allTopics);
        arsort($topicCounts);
        $topTopics = array_slice($topicCounts, 0, 5, true);
        $topTopicsFormatted = [];
        foreach ($topTopics as $topic => $count) {
            $topTopicsFormatted[] = [
                'topic' => $topic,
                'count' => $count,
            ];
        }

        // Avis récents (5 derniers)
        $recentReviews = Review::orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($review) {
                return [
                    'id' => $review->id,
                    'content' => $review->content,
                    'sentiment' => $review->sentiment,
                    'score' => $review->score,
                    'created_at' => $review->created_at,
                ];
            });

        // Avis au fil du temps (derniers 12 mois)
        // Utiliser strftime pour SQLite avec la syntaxe correcte
        $reviewsOverTime = Review::select(
            DB::raw("strftime('%Y-%m', created_at) as month"),
            DB::raw('count(*) as count')
        )
            ->where('created_at', '>=', date('Y-m-d H:i:s', strtotime('-12 months')))
            ->groupBy(DB::raw("strftime('%Y-%m', created_at)"))
            ->orderBy(DB::raw("strftime('%Y-%m', created_at)"))
            ->get();

        $labels = [];
        $data = [];
        foreach ($reviewsOverTime as $item) {
            $labels[] = date('M', strtotime($item->month . '-01'));
            $data[] = $item->count;
        }

        return response()->json([
            'total_reviews' => $totalReviews,
            'sentiment_distribution' => [
                'positive' => $sentimentDistribution['positive'] ?? 0,
                'neutral' => $sentimentDistribution['neutral'] ?? 0,
                'negative' => $sentimentDistribution['negative'] ?? 0,
            ],
            'average_score' => round($averageScore, 1),
            'top_topics' => $topTopicsFormatted,
            'recent_reviews' => $recentReviews,
            'reviews_over_time' => [
                'labels' => $labels,
                'data' => $data,
            ],
        ], 200);
    }
}

