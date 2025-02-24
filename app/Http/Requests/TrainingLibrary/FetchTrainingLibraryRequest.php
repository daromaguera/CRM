<?php

namespace App\Http\Requests\TrainingLibrary;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use App\Models\TrainingLibrary;
use App\Models\TrainingLibraryUser;

class FetchTrainingLibraryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'page' => 'integer|min:1',
            'per_page' => 'integer|min:1|max:100',
        ];
    }
    
    public function getFavoriteVideos($page, $perPage)
    {
        try {
            $userId = auth()->id();

            $videos = TrainingLibraryUser::where('user_id', $userId)
                ->where('is_favorite', true)
                ->paginate($perPage, ['*'], 'page', $page);

            return $videos;

        } catch (\Exception $e) {
            \Log::error('Error in getFavoriteVideo', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function getCompletedVideos($page, $perPage)
    {
        try {
            $userId = auth()->id();

            $videos = TrainingLibraryUser::where('user_id', $userId)
                ->where('is_completed', true)
                ->with('trainingLibrary')
                ->paginate($perPage, ['*'], 'page', $page);

            return $videos;

        } catch (\Exception $e) {
            \Log::error('Error in getCompletedVideo', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function getTrainingLibraries($page, $perPage, $tab)
    {
        try {
            $userId = auth()->id();
    
            $libraries = TrainingLibrary::with([
                'trainingLibraryUsers' => function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                }
            ])
            ->where('topic_id', $tab)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);
    
            return $libraries;
        } catch (\Exception $e) {
            \Log::error('Error in getTrainingLibraries', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
    
    public function getTrainingLibrariesWith($filter, $page, $perPage, $tab)
    {
        try {
            $userId = auth()->id();
    
            $libraries = TrainingLibrary::whereHas('trainingLibraryUsers', function ($query) use ($userId, $filter) {
                    $query->where('user_id', $userId)
                          ->where($filter, true);
                })
                ->with([
                    'trainingLibraryUsers' => function ($query) use ($userId) {
                        $query->where('user_id', $userId);
                    }
                ])
                ->where('topic_id', $tab)
                ->orderBy('created_at', 'desc')
                ->paginate($perPage, ['*'], 'page', $page);
    
            return $libraries;
        } catch (\Exception $e) {
            \Log::error('Error in getTrainingLibrariesWith', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
    
    

    
    
}
