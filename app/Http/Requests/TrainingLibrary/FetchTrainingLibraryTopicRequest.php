<?php

namespace App\Http\Requests\TrainingLibrary;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use App\Models\TrainingLibraryTopic;

class FetchTrainingLibraryTopicRequest extends FormRequest
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
        return [];
    }

    public function getTrainingLibraryTopics()
    {
        try {
            $topics = TrainingLibraryTopic::orderBy('created_at', 'asc')
                      ->where('is_display', true)
                      ->get();
    
            return $topics;
    
        } catch (\Exception $e) {
            \Log::error('Error in getTrainingLibraryTopics', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
    
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function getTrainingLibraryTopicsAll()
    {
        try {
            $topics = TrainingLibraryTopic::orderBy('created_at', 'asc')->get(); // Ascending order
    
            return $topics;
    
        } catch (\Exception $e) {
            \Log::error('Error in getTrainingLibraryTopicsAll', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
    
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
    
    
    
}
