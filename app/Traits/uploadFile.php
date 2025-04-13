<?php

namespace App\Traits;

use App\Repositories\Contracts\Resources\Files\FilesRepositoryContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

trait uploadFile
{
    protected $fileRepository;

    public function __construct(FilesRepositoryContract $fileRepository){
        $this->fileRepository = $fileRepository;
    }

    public function handleFileUpload(Request $req, ?string $filesId, callable $pathCallback): ?string
    {
        // Check if the request has a file
        if (!$req->hasFile('file')) {
            return $filesId; // If no file is uploaded, return the existing file ID
        }
    
        // Determine if it's an update or upload based on whether $filesId is provided
        $fileMethod = $filesId ? 'update' : 'upload';
    
        // Call the appropriate method for file upload or update
        $response = $this->fileRepository->$fileMethod($req, $pathCallback);
    
        // Check if the response has a status method and is an object
        if (is_object($response) && method_exists($response, 'status')) {
            if ($response->status() !== 200) {
                throw new \Exception('File processing failed.');
            }
    
            // Get the file ID from the response data
            $fileId = $response->getData()->data ?? null;
    
            // Log the file ID to check if it's being returned correctly
            \Log::info('File ID from file upload process:', ['fileId' => $fileId]);
    
            // Return the file ID
            return $fileId;
        } else {
            // Log an error if the response is not in the expected format
            \Log::error('Unexpected response type returned from file upload process.', ['response' => $response]);
    
            throw new \Exception('Unexpected response type returned from file upload process.');
        }
    }
    
    
}
