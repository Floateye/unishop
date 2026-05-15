<?php

namespace App\Http\Controllers;

use App\Enum\PermissionType;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;

class ReviewController extends Controller
{
    public function index(Product $product)
    {
        $reviews = $product->reviews()->with('user')->latest()->get();

        return response()->json($reviews->map(function ($review) {
            return [
                'id'          => $review->id,
                'body'        => $review->body,
                'rating'      => $review->rating,
                'sentiment'   => $review->sentiment,
                'is_verified' => $review->is_verified,
                'created_at'  => $review->created_at->diffForHumans(),
                'user'        => $review->user
                    ? $review->user->first_name . ' ' . $review->user->last_name
                    : 'Anonymous',
            ];
        }));
    }

    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'body'   => ['required', 'string', 'max:1000'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
        ]);

        $already = Review::where('product_id', $product->id)
            ->where('user_id', auth()->id())
            ->exists();

        if ($already) {
            return response()->json(['error' => 'You have already reviewed this product.'], 422);
        }

        $sentiment = null;
        $apiKey = env('GEMMA_API_KEY');
        if ($apiKey) {
            try {
                $prompt = "Classify the sentiment of the following product review. "
                    . "Reply with exactly one word — choose from: positive, negative, mixed, neutral.\n\n"
                    . "Review: {$validated['body']}\n\nSentiment:";

                $response = Http::timeout(30)->post(
                    "https://generativelanguage.googleapis.com/v1beta/models/gemma-3-12b-it:generateContent?key={$apiKey}",
                    ['contents' => [['parts' => [['text' => $prompt]]]]]
                );

                if ($response->successful()) {
                    $raw = strtolower(trim(
                        $response->json('candidates.0.content.parts.0.text') ?? ''
                    ));
                    foreach (['positive', 'negative', 'mixed', 'neutral'] as $label) {
                        if (str_contains($raw, $label)) {
                            $sentiment = $label;
                            break;
                        }
                    }
                }
            } catch (\Exception $e) {
                // API unavailable — leave sentiment null
            }
        }

        $review = Review::create([
            'product_id' => $product->id,
            'user_id'    => auth()->id(),
            'body'       => $validated['body'],
            'rating'     => $validated['rating'],
            'sentiment'  => $sentiment,
        ]);

        $review->load('user');

        return response()->json([
            'id'          => $review->id,
            'body'        => $review->body,
            'rating'      => $review->rating,
            'sentiment'   => $review->sentiment,
            'is_verified' => $review->is_verified,
            'created_at'  => $review->created_at->diffForHumans(),
            'user'        => $review->user->first_name . ' ' . $review->user->last_name,
        ], 201);
    }

    public function destroy(Review $review)
    {
        Gate::authorize(PermissionType::DashboardView);

        $review->delete();

        return response()->json(['message' => 'Review deleted.']);
    }

    public function summarize(Product $product)
    {
        Gate::authorize(PermissionType::DashboardView);

        $reviews = $product->reviews()->with('user')->latest()->get();

        if ($reviews->isEmpty()) {
            return response()->json(['summary' => 'No reviews to summarize.']);
        }

        $apiKey = env('GEMMA_API_KEY');

        if (!$apiKey) {
            return response()->json(['error' => 'Gemma API key is not configured. Add GEMMA_API_KEY to your .env file.'], 503);
        }

        $reviewTexts = $reviews->map(fn($r) =>
            "- Rating: {$r->rating}/5, Sentiment: " . ($r->sentiment ?? 'unknown') . ", Review: {$r->body}"
        )->join("\n");

        $prompt = "You are analyzing product reviews. Below are reviews for a product. Provide a concise summary grouped by sentiment. "
            . "For negative reviews mention the specific complaints (e.g. '5 negative reviews: color fades quickly, smells tend to stick'). "
            . "For positive reviews mention the specific praise (e.g. '3 positive reviews: very comfy, true to size'). "
            . "For mixed reviews, note what they liked and disliked. Be brief and factual. "
            . "Keep the entire response under 100 words.\n\nReviews:\n{$reviewTexts}\n\nSummary:";

        try {
            $response = Http::timeout(30)->post(
                "https://generativelanguage.googleapis.com/v1beta/models/gemma-3-12b-it:generateContent?key={$apiKey}",
                [
                    'contents' => [
                        ['parts' => [['text' => $prompt]]]
                    ]
                ]
            );

            if ($response->successful()) {
                $data    = $response->json();
                $summary = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Could not parse summary.';
                return response()->json(['summary' => trim($summary)]);
            }

            return response()->json(['error' => 'Gemma API returned an error: ' . $response->status()], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to reach Gemma API.'], 500);
        }
    }
}
