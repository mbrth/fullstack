<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Models\Review;
use App\Services\SentimentAnalysisService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    protected SentimentAnalysisService $aiService;

    public function __construct(SentimentAnalysisService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Display a listing of reviews with pagination and filters
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = Review::with('user:id,name');

        // Filtre par sentiment (uniquement si une valeur est fournie)
        if ($request->filled('sentiment')) {
            $query->where('sentiment', $request->sentiment);
        }

        // Tri
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = min($request->get('per_page', 15), 100);
        $reviews = $query->paginate($perPage);

        return response()->json([
            'data' => $reviews->map(function ($review) {
                return [
                    'id' => $review->id,
                    'user_id' => $review->user_id,
                    'user_name' => $review->user->name,
                    'content' => $review->content,
                    'sentiment' => $review->sentiment,
                    'score' => $review->score,
                    'topics' => $review->topics,
                    'created_at' => $review->created_at,
                ];
            }),
            'meta' => [
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
                'per_page' => $reviews->perPage(),
                'total' => $reviews->total(),
            ],
        ], 200);
    }

    /**
     * Store a newly created review
     *
     * @param ReviewRequest $request
     * @return JsonResponse
     */
    public function store(ReviewRequest $request): JsonResponse
    {
        // Analyser le contenu avec l'IA
        $validated = $request->validated();
        $analysis = $this->aiService->analyze($validated['content']);

        // Créer l'avis
        $review = Review::create([
            'user_id' => $request->user()->id,
            'content' => $validated['content'],
            'sentiment' => $analysis['sentiment'],
            'score' => $analysis['score'],
            'topics' => $analysis['topics'],
        ]);

        return response()->json([
            'message' => 'Avis créé et analysé avec succès',
            'review' => [
                'id' => $review->id,
                'user_id' => $review->user_id,
                'content' => $review->content,
                'sentiment' => $review->sentiment,
                'score' => $review->score,
                'topics' => $review->topics,
                'created_at' => $review->created_at,
                'updated_at' => $review->updated_at,
            ],
        ], 201);
    }

    /**
     * Display the specified review
     *
     * @param Review $review
     * @return JsonResponse
     */
    public function show(Review $review): JsonResponse
    {
        $review->load('user:id,name');

        return response()->json([
            'id' => $review->id,
            'user_id' => $review->user_id,
            'user_name' => $review->user->name,
            'content' => $review->content,
            'sentiment' => $review->sentiment,
            'score' => $review->score,
            'topics' => $review->topics,
            'created_at' => $review->created_at,
            'updated_at' => $review->updated_at,
        ], 200);
    }

    /**
     * Update the specified review
     *
     * @param ReviewRequest $request
     * @param Review $review
     * @return JsonResponse
     */
    public function update(ReviewRequest $request, Review $review): JsonResponse
    {
        // Vérifier l'autorisation
        if ($request->user()->id !== $review->user_id && $request->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Non autorisé',
            ], 403);
        }

        // Analyser le nouveau contenu
        $validated = $request->validated();
        $analysis = $this->aiService->analyze($validated['content']);

        // Mettre à jour l'avis
        $review->update([
            'content' => $validated['content'],
            'sentiment' => $analysis['sentiment'],
            'score' => $analysis['score'],
            'topics' => $analysis['topics'],
        ]);

        return response()->json([
            'message' => 'Avis mis à jour avec succès',
            'review' => [
                'id' => $review->id,
                'user_id' => $review->user_id,
                'content' => $review->content,
                'sentiment' => $review->sentiment,
                'score' => $review->score,
                'topics' => $review->topics,
                'created_at' => $review->created_at,
                'updated_at' => $review->updated_at,
            ],
        ], 200);
    }

    /**
     * Remove the specified review
     *
     * @param Request $request
     * @param Review $review
     * @return JsonResponse
     */
    public function destroy(Request $request, Review $review): JsonResponse
    {
        // Vérifier l'autorisation
        if ($request->user()->id !== $review->user_id && $request->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Non autorisé',
            ], 403);
        }

        $review->delete();

        return response()->json([
            'message' => 'Review deleted',
        ], 200);
    }

    /**
     * Reanalyze an existing review
     *
     * @param Review $review
     * @return JsonResponse
     */
    public function reanalyze(Review $review): JsonResponse
    {
        // Analyser à nouveau le contenu
        $analysis = $this->aiService->analyze($review->content);

        // Mettre à jour l'analyse
        $review->update([
            'sentiment' => $analysis['sentiment'],
            'score' => $analysis['score'],
            'topics' => $analysis['topics'],
        ]);

        return response()->json([
            'message' => 'Analyse mise à jour',
            'review' => [
                'id' => $review->id,
                'sentiment' => $review->sentiment,
                'score' => $review->score,
                'topics' => $review->topics,
                'updated_at' => $review->updated_at,
            ],
        ], 200);
    }
}

