<?php

namespace App\Jobs;

use App\DTOs\DocumentDTO;
use App\Http\Requests\DocumentRequest;
use App\Libraries\DocumentManager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ImportDocumentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * Create a new job instance.
     */
    public function __construct(private DocumentDTO $documentDTO)
    {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $categoryName = $this->documentDTO->categoryName;
            $documentData = [
                'financial_year' => $this->documentDTO->financialYear,
                'title' => $this->documentDTO->title,
                'contents' => $this->documentDTO->contents,
            ];

            $request = new DocumentRequest([...$documentData, ...['category' => $categoryName]]);
            $validator = Validator::make($request->all(), $request->rules());

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $manager = app(DocumentManager::class);
            $manager->createDocument($documentData, $categoryName);

        } catch (ValidationException $e) {
            $this->addLog($e);
            throw $e;
        } catch (\Exception $e) {
            $this->addLog($e);
            throw $e;
        }
    }

    private function addLog(\Exception $e): void
    {
        Log::channel('import_documents')
            ->error('[import_documents] - Exception: ' . $e->getMessage());
    }
}
