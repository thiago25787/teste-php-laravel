<?php

namespace App\Libraries;

use App\DTOs\DocumentDTO;
use App\Events\ImportDocumentEvent;
use App\Models\Document;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Http\UploadedFile;

class DocumentManager extends AbstractManager
{
    public function listDocuments(): LengthAwarePaginator
    {
        $documents = Document::with('category')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return $documents;
    }

    public function importDocuments(UploadedFile $uploadedFile): bool
    {
        $fileData = File::get($uploadedFile->path());
        $data = json_decode($fileData, true);

        if (empty($data['documentos'] ?? null)) {
            return false;
        }
        $documentsDTO = [];
        foreach ($data['documentos'] as $document) {
            $documentDTO = new DocumentDTO(
                $document['titulo'] ?? null,
                $document['conteúdo'] ?? null,
                $document['categoria'] ?? null,
                $data['exercicio'] ?? now()->format('Y')
            );
            $documentsDTO[] = $documentDTO;
        }

        event(new ImportDocumentEvent($documentsDTO));

        return true;
    }

    public function processDocuments(): bool
    {
        try {
            Artisan::call('queue:work', [
                '--once' => true,
            ]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function createDocument(array $dataDocument, string $categoryName): Document
    {
        $category = resolve(CategoryManager::class)
            ->getCategoryByName($categoryName);

        return $category->documents()
            ->create($dataDocument);
    }
}
