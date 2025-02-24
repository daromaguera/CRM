<?php

namespace App\Http\Requests\TrainingLibrary;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\TrainingLibraryTopic;
use Illuminate\Http\JsonResponse;
use Exception;

class TrainingLibraryTopicRequest extends FormRequest
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
     */
    public function rules(): array
    {
        if (request()->isMethod('delete') || request()->isMethod('patch')) {
            return []; // No validation for DELETE && PATCH requests
        }

        return [
            'topic' => 'required|string|unique:training_library_topic,topic|max:255',
        ];
    }

    /**
     * Handle the topic creation logic with error handling.
     */
    public function saveTrainingLibraryTopic()
    {
        try {
            $data = $this->validated();

            // Create and save new topic
            $topic = TrainingLibraryTopic::create([
                'topic' => $data['topic']
            ]);

            return response()->json([
                'message' => 'Training Library Topic created successfully!',
                'data' => $topic
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to create Training Library Topic.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateTrainingLibraryTopic($id)
    {

        $data = $this->validated();


        // Find the existing TrainingLibrary record
        $trainingLibraryTopic = TrainingLibraryTopic::find($id);

        if (!$trainingLibraryTopic) {
            return response()->json(['message' => 'Training Library Topic not found.'], 404);
        }

        // Update the database record
        $trainingLibraryTopic->update([
            'topic' => $data['topic'],
        ]);

        return response()->json([
            'message' => 'Training Library Topic Successfully Updated!',
            'data' => $trainingLibraryTopic,
        ], 201);
    }

    public function toggleDisplay($id)
    {
        // Find the TrainingLibraryTopic entry or fail if it doesn't exist.
        $entry = TrainingLibraryTopic::findOrFail($id);
    
        // Toggle the is_display field.
        $entry->is_display = !$entry->is_display;
        $entry->save();
    
        // Set message based on the new state of is_display.
        $message = $entry->is_display 
            ? 'Display activated!' 
            : 'Display deactivated!';
    
        return response()->json([
            'message' => $message,
            'data' => $entry
        ], 200);
    }

    public function deleteTrainingLibraryTopic($id) {

        // Find the record
        $trainingLibraryTopic = TrainingLibraryTopic::find($id);
    
        // Check if the record exists
        if (!$trainingLibraryTopic) {
            return response()->json(['message' => 'Record not found'], 404);
        }
    
        // Delete the record
        $trainingLibraryTopic->delete();
    
        // Return success response
        return response()->json(['message' => 'Record deleted successfully'], 200);
    }
    
}
