<?php

namespace App\Http\Requests\TrainingLibrary;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\TrainingLibrary;
use App\Models\TrainingLibraryUser;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpWord\IOFactory;

class TrainingLibraryRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Adjust this if you have authorization logic
    }

    protected function prepareForValidation()
    {
        if ($this->has('tags') && is_string($this->tags)) {
            $this->merge([
                'tags' => json_decode($this->tags, true),
            ]);
        }
    }

    public function rules()
    {
        if (request()->isMethod('delete') || request()->isMethod('patch')) {
            return []; // No validation for DELETE && PATCH requests
        }

        return [
            'title' => 'required|string|max:255',
            'topic_id' => 'required',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'description' => 'nullable|string',
            'script' => 'nullable|string',
            'thumbnail' => 'nullable|file|image|mimes:jpg,jpeg,png|max:10240', // 10MB max
            // 'tl_video_url' => 'required|file|mimes:mp4,mov,avi|max:51200',
        ];
    }


    public function messages()
    {
        return [
            'title.required' => 'The title field is required.',
            'tl_video_url.required' => 'The video file is required.',
            'tl_video_url.mimes' => 'The video must be a valid format (mp4, mov, avi).',
            'tl_video_url.max' => 'The video size must not exceed 50MB.',
            'thumbnail.image' => 'The thumbnail must be an image.',
            'thumbnail.mimes' => 'The thumbnail must be a valid format (jpg, jpeg, png).',
            'thumbnail.max' => 'The thumbnail size must not exceed 10MB.',
        ];
    }

    public function saveTrainingLibrary()
    {

        $data = $this->validated();

        $data['tags'] = isset($data['tags']) && is_string($data['tags']) 
            ? json_decode($data['tags'], true) 
            : (is_array($data['tags']) ? $data['tags'] : []);

        $videoUrl = null;
        $thumbnailUrl = null;

        // Handle script file
        if ($this->hasFile('script_file')) {
            // Retrieve the uploaded file
            $file = $this->file('script_file');
            
            // Load the Word file
            $phpWord = IOFactory::load($file);

            // Create a writer to convert to HTML
            $htmlWriter = IOFactory::createWriter($phpWord, 'HTML');

            // Output the HTML content to a string
            $htmlContent = null;
            $htmlWriter->save('php://output');  // This will output the HTML content directly

            // Alternatively, if you want to capture the HTML content into a variable
            ob_start(); // Start output buffering
            $htmlWriter->save('php://output');
            $htmlContent = ob_get_clean(); // Capture the output

        }

        // Handle video upload
        try {
            if ($this->hasFile('tl_video_url')) {
                $videoFile = $this->file('tl_video_url');
                $videoFileName = uniqid() . '.' . $videoFile->getClientOriginalExtension();
                $videoFilePath = 'training-videos/' . $videoFileName;
        
                if (Storage::disk('s3')->put($videoFilePath, file_get_contents($videoFile), 'public')) {
                    $videoUrl = Storage::disk('s3')->url($videoFilePath);
                } else {
                    Log::error('Failed to upload video to S3', [
                        'file_name' => $videoFileName,
                        'file_path' => $videoFilePath
                    ]);
        
                    return response()->json(['message' => 'Failed to upload video to S3.'], 500);
                }
            }
        } catch (\Exception $e) {
            Log::error('S3 Video Upload Error', [
                'error' => $e->getMessage(),
                'file_name' => $videoFileName ?? 'N/A',
                'file_path' => $videoFilePath ?? 'N/A'
            ]);
        
            return response()->json([
                'message' => 'An error occurred while uploading the video.',
                'error' => $e->getMessage()
            ], 500);
        }

        // Handle thumbnail upload
        if ($this->hasFile('thumbnail_url')) {
            $thumbnailFile = $this->file('thumbnail_url');
            $thumbnailFileName = uniqid() . '.' . $thumbnailFile->getClientOriginalExtension();
            $thumbnailFilePath = 'training-thumbnails/' . $thumbnailFileName;

            if (Storage::disk('s3')->put($thumbnailFilePath, file_get_contents($thumbnailFile), 'public')) {
                $thumbnailUrl = Storage::disk('s3')->url($thumbnailFilePath);
            } else {
                return response()->json(['message' => 'Failed to upload thumbnail to S3.'], 500);
            }
        }

        // Save to the database
        $trainingLibrary = TrainingLibrary::create([
            'title' => $data['title'],
            'tags' => isset($data['tags']) ? json_encode($data['tags']) : '[]',
            'description' => $data['description'] ?? null,
            'script' => !empty($data['script']) ? $data['script'] : (!empty($htmlContent) ? $htmlContent : ""),
            'tl_video_url' => $videoUrl,
            'topic_id' => $data['topic_id'],
            'thumbnail_url' => $thumbnailUrl,
            'user_id' => auth()->id(),
        ]);

        return response()->json([
            'message' => 'Training Library Successfully Created!',
            'data' => $trainingLibrary,
        ], 201);
    }

    public function updateTrainingLibrary($id)
    {

        $data = $this->validated();


        // Decode tags if necessary
        $data['tags'] = isset($data['tags']) && is_string($data['tags']) 
            ? json_decode($data['tags'], true) 
            : (is_array($data['tags']) ? $data['tags'] : []);

        $videoUrl = null;
        $thumbnailUrl = null;

        // Find the existing TrainingLibrary record
        $trainingLibrary = TrainingLibrary::find($id);

        if (!$trainingLibrary) {
            return response()->json(['message' => 'Training Library not found.'], 404);
        }

        // Handle script file
        if ($this->hasFile('script_file')) {
            // Retrieve the uploaded file
            $file = $this->file('script_file');
            
            // Load the Word file
            $phpWord = IOFactory::load($file);

            // Create a writer to convert to HTML
            $htmlWriter = IOFactory::createWriter($phpWord, 'HTML');

            // Output the HTML content to a string
            $htmlContent = null;
            $htmlWriter->save('php://output');  // This will output the HTML content directly

            // Alternatively, if you want to capture the HTML content into a variable
            ob_start(); // Start output buffering
            $htmlWriter->save('php://output');
            $htmlContent = ob_get_clean(); // Capture the output

            // // Return or process the HTML content
            // return response()->json(['html' => $htmlContent]);
        } 

        // Handle video upload
        if ($this->hasFile('tl_video_url')) {

            Log::info('File detected', ['file_name' => $this->file('tl_video_url')->getClientOriginalName()]);


            $videoFile = $this->file('tl_video_url');
            $videoFileName = uniqid() . '.' . $videoFile->getClientOriginalExtension();
            $videoFilePath = 'training-videos/' . $videoFileName;

            if (Storage::disk('s3')->put($videoFilePath, file_get_contents($videoFile), 'public')) {
                $videoUrl = Storage::disk('s3')->url($videoFilePath);

                // Delete the old video file from S3 if it exists
                if ($trainingLibrary->tl_video_url) {
                    $oldVideoPath = str_replace(Storage::disk('s3')->url(''), '', $trainingLibrary->tl_video_url);
                    Storage::disk('s3')->delete($oldVideoPath);
                }
            } else {
                return response()->json(['message' => 'Failed to upload video to S3.'], 500);
            }
        } else {
            $videoUrl = $trainingLibrary->tl_video_url; // Retain the old URL if no new file is uploaded
        }

        // Handle thumbnail upload
        if ($this->hasFile('thumbnail_url')) {
            $thumbnailFile = $this->file('thumbnail_url');
            $thumbnailFileName = uniqid() . '.' . $thumbnailFile->getClientOriginalExtension();
            $thumbnailFilePath = 'training-thumbnails/' . $thumbnailFileName;

            if (Storage::disk('s3')->put($thumbnailFilePath, file_get_contents($thumbnailFile), 'public')) {
                $thumbnailUrl = Storage::disk('s3')->url($thumbnailFilePath);

                // Delete the old thumbnail file from S3 if it exists
                if ($trainingLibrary->thumbnail_url) {
                    $oldThumbnailPath = str_replace(Storage::disk('s3')->url(''), '', $trainingLibrary->thumbnail_url);
                    Storage::disk('s3')->delete($oldThumbnailPath);
                }
            } else {
                return response()->json(['message' => 'Failed to upload thumbnail to S3.'], 500);
            }
        } else {
            $thumbnailUrl = $trainingLibrary->thumbnail_url;
        }

        // Update the database record
        $trainingLibrary->update([
            'title' => $data['title'],
            'tags' => isset($data['tags']) ? json_encode($data['tags']) : '[]',
            'description' => $data['description'] ?? $trainingLibrary->description,
            'script' => $this->hasFile('script_file') ? $htmlContent : $data['script'],
            'tl_video_url' => $videoUrl,
            'topic_id' => $data['topic_id'],
            'thumbnail_url' => $thumbnailUrl,
        ]);

        return response()->json([
            'message' => 'Training Library Successfully Updated!',
            'data' => $trainingLibrary,
        ], 201);
    }

    public function toggleFavorite($id)
    {
        $userId = auth()->id(); // Get authenticated user
    
        $entry = TrainingLibraryUser::firstOrCreate(
            ['user_id' => $userId, 'training_library_id' => $id],
            ['is_favorite' => false, 'is_completed' => false]
        );
    
        $entry->is_favorite = !$entry->is_favorite;
        $entry->save();
    
        $message = $entry->is_favorite 
            ? 'Added this video successfully to favorites!' 
            : 'Removed this video from favorites!';
    
        return response()->json([
            'message' => $message,
            'data' => $entry
        ], 200);
    }
    

    public function markAsCompleted($id)
    {
        $userId = auth()->id();

        $entry = TrainingLibraryUser::firstOrCreate(
            ['user_id' => $userId, 'training_library_id' => $id],
            ['is_favorite' => false, 'is_completed' => false]
        );

        $entry->is_completed = true;
        $entry->save();

        return response()->json([
            'message' => 'Training marked as completed',
            'data' => $entry
        ], 200);
    }


    public function deleteTrainingLibrary($id) {

        // Find the record
        $trainingLibrary = TrainingLibrary::find($id);
    
        // Check if the record exists
        if (!$trainingLibrary) {
            return response()->json(['message' => 'Record not found'], 404);
        }
    
        // Delete the record
        $trainingLibrary->delete();
    
        // Return success response
        return response()->json(['message' => 'Record deleted successfully'], 200);
    }
    


}
