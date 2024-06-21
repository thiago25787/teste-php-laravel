<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentImportRequest;
use App\Http\Resources\V1\DocumentResource;
use App\Libraries\DocumentManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class DocumentController extends Controller
{
    public function __construct(protected DocumentManager $documentManager)
    {

    }

    public function index(): AnonymousResourceCollection
    {
        $documents = $this->documentManager->listDocuments();

        return DocumentResource::collection($documents);
    }

    public function store(DocumentImportRequest $request): JsonResponse
    {
        $processed = $this->documentManager->importDocuments($request->file('file'));

        if (!$processed) {
            abort(Response::HTTP_NOT_FOUND, __('O arquivo importado não possui documentos.'));
        }

        return response()->json(['message' => __('A importação foi iniciada.')]);
    }

    public function processQueue(): JsonResponse
    {
        $processed = $this->documentManager->processDocuments();

        if (!$processed) {
            abort(Response::HTTP_NOT_FOUND, __('Ocorreu um erro ao processar a fila.'));
        }

        return response()->json(['message' => __('A importação foi iniciada.')]);
    }
}
