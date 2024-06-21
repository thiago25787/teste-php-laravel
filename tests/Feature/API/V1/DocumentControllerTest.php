<?php

namespace Tests\Feature\API\V1;

use App\DTOs\DocumentDTO;
use App\Enums\CategoryEnum;
use App\Events\ImportDocumentEvent;
use App\Jobs\ImportDocumentJob;
use App\Listeners\ImportDocumentHandler;
use App\Models\Document;
use Database\Seeders\CategorySeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class DocumentControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testCreateDocumentShouldWork(): void
    {
        Event::fake([
            ImportDocumentEvent::class,
        ]);
        $this->postJson('api/v1/document', [
            'file' => $this->getFakeJsonFile('document_should_work.json', 1),
        ])->assertOk()
            ->assertJsonFragment(['message' => __('A importação foi iniciada.')]);

        Event::assertDispatched(ImportDocumentEvent::class, 1);
    }

    public function testCreateMultipleDocumentsShouldWork(): void
    {
        Event::fake([
            ImportDocumentEvent::class,
        ]);
        $this->postJson('api/v1/document', [
            'file' => $this->getFakeJsonFile('document_should_work.json', 5),
        ])->assertOk()
            ->assertJsonFragment(['message' => __('A importação foi iniciada.')]);

        Event::assertDispatched(ImportDocumentEvent::class, 1);
    }

    public function testCreateDocumentNoFileShouldFail(): void
    {
        Event::fake([
            ImportDocumentEvent::class,
        ]);
        $this->postJson('api/v1/document')
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['file']);

        Event::assertNotDispatched(ImportDocumentEvent::class);
    }

    public function testCreateDocumentInvalidFileShouldFail(): void
    {
        Event::fake([
            ImportDocumentEvent::class,
        ]);
        $this->postJson('api/v1/document', [
            'file' => $this->getFakeJsonFile('document_should_fail.json', 0),
        ])->assertNotFound();

        Event::assertNotDispatched(ImportDocumentEvent::class);
    }

    public function testListDocuments(): void
    {
        $this->seed(CategorySeeder::class);
        $documents = Document::factory()->count(10)->create();
        $response = $this->getJson('api/v1/document')
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'contents',
                        'category_id',
                        'financial_year',
                        'date',
                        'category' => [
                            'id',
                            'name',
                        ],
                    ],
                ],
            ]);

        foreach ($documents as $document) {
            $response->assertJsonFragment([
                'id' => $document->id,
                'title' => $document->title,
                'contents' => $document->contents,
                'category_id' => $document->category_id,
                'financial_year' => $document->financial_year,
                'date' => $document->created_at->format('d/m/Y'),
                'category' => [
                    'id' => $document->category->id,
                    'name' => $document->category->name,
                ],
            ]);
        }
    }

    public function testProcessQueueDocuments(): void
    {
        Queue::fake();
        $document = Document::factory()->make([
            'category_id' => CategoryEnum::REMESSA->value,
        ]);
        $document->title .= ' - semestre';

        $categoryEnum = CategoryEnum::tryFrom($document->category_id);

        $documentDTO = new DocumentDTO(
            $document->title,
            $document->contents,
            $categoryEnum->name(),
            $document->financial_year,
        );

        $event = new ImportDocumentEvent([$documentDTO]);

        $listener = app(ImportDocumentHandler::class);
        $listener->handle($event);

        $this->getJson('/api/document/process-queue')
            ->assertOk();

        Queue::assertPushed(ImportDocumentJob::class, 1);
    }

    private function getFakeJsonFile(string $fileName, int $qntDocumentsFileData = 1): UploadedFile
    {
        Storage::fake($fileName);
        $documents = [];
        for ($i = 0; $i < $qntDocumentsFileData; $i++) {
            $document = Document::factory()->make();
            $documents[] = [
                'titulo' => $document->title,
                'conteúdo' => $document->contents,
                'categoria' => CategoryEnum::tryFrom($document->category_id)->name(),
            ];

        }

        $content = json_encode([
            'exercicio' => $document->financial_year ?? now()->format('Y'),
            'documentos' => $documents,
        ]);

        return UploadedFile::fake()
            ->createWithContent($fileName, $content);
    }
}
