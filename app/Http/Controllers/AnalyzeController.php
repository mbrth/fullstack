<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnalyzeRequest;
use App\Services\SentimentAnalysisService;
use Illuminate\Http\JsonResponse;

class AnalyzeController extends Controller
{
    protected SentimentAnalysisService $aiService;

    public function __construct(SentimentAnalysisService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Analyze text without creating a review
     *
     * @param AnalyzeRequest $request
     * @return JsonResponse
     */
    public function analyze(AnalyzeRequest $request): JsonResponse
    {
        $analysis = $this->aiService->analyze($request->text);

        return response()->json([
            'sentiment' => $analysis['sentiment'],
            'score' => $analysis['score'],
            'topics' => $analysis['topics'],
        ], 200);
    }
}

