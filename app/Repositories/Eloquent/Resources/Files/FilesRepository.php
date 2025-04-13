<?php

namespace App\Repositories\Eloquent\Resources\Files;

use App\{
    Repositories\BaseRepositories,
    Traits\DbBeginTransac,
};
use App\Models\Resources\Files\Files;
use App\Repositories\Contracts\Resources\Files\FilesRepositoryContract;
use Illuminate\{
    Support\Facades\Storage,
    Support\Str
};
use Illuminate\Http\Request;

class FilesRepository extends BaseRepositories implements FilesRepositoryContract
{
    use DbBeginTransac;

    public function __construct(Files $files)
    {
        parent::__construct($files);
    }
    public function findFilesId(string $filesId): ?object   
    {
        return $this->findBy('filesId', $filesId);
    }
    public function saveFiles(Request $req, callable $pathCallback): object
    {
        return $this->executeTransaction(function () use ($req, $pathCallback) {
            $file            = $req->file('file');
            $originalName    = $file->getClientOriginalName();
            $extension       = $file->getClientOriginalExtension();
            $filename        = uniqid() . '.' . $extension;
            $destinationPath = $pathCallback 
                ? $pathCallback($file, $filename) 
                : storage_path('app/public/default_path/' . $filename);
            if (!file_exists(dirname($destinationPath))) {
                mkdir(dirname($destinationPath), 0777, true);
            }
    
            $file->move(dirname($destinationPath), $filename);
            $pathForDatabase = str_replace(storage_path('app/public'), 'storage', $destinationPath);
            $fileId = Str::uuid();
            $fileData = [
                'filesId'   => $fileId,
                'name'      => $originalName,
                'path'      => $pathForDatabase,
                'mime_type' => $file->getClientMimeType(),
                'size'      => filesize($destinationPath),
            ];
    
            return $this->create($fileData);
        });
    }
    

    public function updateFiles(array $data, string $filesId, string $storagePath = 'uploads/files'): object
    {
        return $this->executeTransaction(function () use ($data, $filesId, $storagePath) {
            $existingFile = $this->findBy('filesId', $filesId);
    
            if (!$existingFile) {
                throw new \Exception("File with ID {$filesId} not found.");
            }
    
            if (isset($data['file'])) {
                $this->deleteFiles(['filesId' => $filesId], $filesId);
                $file              = $data['file'];
                $filePath          = $file->store($storagePath);
                $data['path']      = $filePath;
                $data['name']      = $file->getClientOriginalName();
                $data['mime_type'] = $file->getClientMimeType();
                $data['size']      = $file->getSize();
            }
    
            $this->update($existingFile->id, $data);
    
            return $this->findBy('filesId', $filesId);
        });
    }

    public function deleteFiles(array $data, string $filesId): object
    {
        return $this->executeTransaction(function () use ($filesId) {
            $existingFile = $this->findBy('filesId', $filesId);

            if (!$existingFile) {
                throw new \Exception("File with ID {$filesId} not found.");
            }
            Storage::delete($existingFile->path);
            $this->delete($existingFile->id);
            return $existingFile;
        });
    }
}
