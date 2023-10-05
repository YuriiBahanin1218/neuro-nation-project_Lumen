<?php
use App\Http\Controllers\Controller;
use App\Models\History;
use App\Models\User;

class SessionHistoryController extends Controller
{
    public function getHistory($user_id)
    {
        try {
            // Find the user by ID
            $user = User::findOrFail($user_id);

            // Retrieve the user's session history
            $sessionHistory = History::where('user_id', $user_id)
                ->orderByDesc('date')
                ->limit(12)
                ->get();

            // Calculate the total score for each session
            $sessionHistory->each(function ($session) {
                $session->score = $session->scores->sum('score');
            });

            // Optionally, get the categories trained in the last session
            $lastSession = $sessionHistory->first();
            $lastSessionCategories = [];
            if ($lastSession) {
                $lastSessionCategories = $lastSession->session->courses
                    ->flatMap(function ($course) {
                        return $course->exercises->pluck('cat_id')->unique();
                    });
            }

            return response()->json([
                'history' => $sessionHistory,
                'lastSessionCategories' => $lastSessionCategories,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'User not found'], 404);
        }
    }
}
