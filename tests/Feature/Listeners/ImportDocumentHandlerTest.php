<?php

namespace Tests\Feature\Listeners;

use App\DTOs\DocumentDTO;
use App\Enums\CategoryEnum;
use App\Events\ImportDocumentEvent;
use App\Jobs\ImportDocumentJob;
use App\Listeners\ImportDocumentHandler;
use App\Models\Document;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ImportDocumentHandlerTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        Carbon::setlocale('pt_BR');
    }

    public function testHandler(): void
    {
        Queue::fake([
            ImportDocumentJob::class,
        ]);

        $documents = Document::factory()->count(5)->make();
        $documentsDTO = [];
        foreach ($documents as $document) {
            $title = $document->title . ' - ';
            $title .= $document->category_id === CategoryEnum::REMESSA->value ?
                    'semestre' :
                    now()->translatedFormat('F');
            $documentsDTO[] = new DocumentDTO(
                $title,
                $document->contents,
                CategoryEnum::tryFrom($document->category_id)->name(),
                $document->financial_year,
            );
        }
        $event = new ImportDocumentEvent($documentsDTO);
        $listener = app(ImportDocumentHandler::class);
        $listener->handle($event);

        Queue::assertPushed(ImportDocumentJob::class, count($documentsDTO));
    }
}
