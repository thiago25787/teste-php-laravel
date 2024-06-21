<?php

namespace Tests\Feature\Jobs;

use App\DTOs\DocumentDTO;
use App\Enums\CategoryEnum;
use App\Events\ImportDocumentEvent;
use App\Listeners\ImportDocumentHandler;
use App\Models\Document;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ImportDocumentJobTest extends TestCase
{

    const TABLE_NAME = 'documents';

    protected function setUp(): void
    {
        parent::setUp();
        Carbon::setlocale('pt_BR');
    }

    public function testTitleCategoryRemessaShouldWork(): void
    {
        $document = Document::factory()->make([
            'category_id' => CategoryEnum::REMESSA->value,
        ]);
        $document->title .= ' - semestre';

        $documentDTO = $this->mountDTO($document);
        $event = new ImportDocumentEvent([$documentDTO]);

        $listener = app(ImportDocumentHandler::class);
        $listener->handle($event);

        $this->assertDatabaseHas(self::TABLE_NAME, [
            'title' => $document->title,
            'contents' => $document->contents,
            'financial_year' => $document->financial_year,
            'category_id' => $document->category_id,
        ]);
    }

    public function testTitleCategoryRemessaShouldFail(): void
    {
        $document = Document::factory()->make([
            'category_id' => CategoryEnum::REMESSA->value,
        ]);

        $documentDTO = $this->mountDTO($document);
        $event = new ImportDocumentEvent([$documentDTO]);

        $this->expectException(ValidationException::class);
        $listener = app(ImportDocumentHandler::class);
        $listener->handle($event);

        $this->assertDatabaseMissing(self::TABLE_NAME, [
            'title' => $document->title,
            'contents' => $document->contents,
            'financial_year' => $document->financial_year,
            'category_id' => $document->category_id,
        ]);
    }

    public function testCreateDocumentShouldFailMaxContentAndTitle(): void
    {
        $document = Document::factory()->make();
        $documentDTO = $this->mountDTO($document);
        $event = new ImportDocumentEvent([$documentDTO]);

        $this->expectException(ValidationException::class);
        $listener = app(ImportDocumentHandler::class);
        $listener->handle($event);

        $this->assertDatabaseMissing(self::TABLE_NAME, [
            'title' => $document->title,
            'contents' => $document->contents,
            'financial_year' => $document->financial_year,
            'category_id' => $document->category_id,
        ]);
    }

    public function testTitleCategoryRemessaParcialShouldWork(): void
    {
        $document = Document::factory()->make([
            'category_id' => CategoryEnum::REMESSA_PARCIAL->value,
        ]);
        $document->title .= ' - ' . now()->translatedFormat('F');

        $documentDTO = $this->mountDTO($document);
        $event = new ImportDocumentEvent([$documentDTO]);

        $listener = app(ImportDocumentHandler::class);
        $listener->handle($event);

        $this->assertDatabaseHas(self::TABLE_NAME, [
            'title' => $document->title,
            'contents' => $document->contents,
            'financial_year' => $document->financial_year,
            'category_id' => $document->category_id,
        ]);
    }

    public function testTitleCategoryRemessaParcialShouldFail(): void
    {
        $document = Document::factory()->make([
            'category_id' => CategoryEnum::REMESSA_PARCIAL->value,
        ]);

        $documentDTO = $this->mountDTO($document);
        $event = new ImportDocumentEvent([$documentDTO]);

        $this->expectException(ValidationException::class);
        $listener = app(ImportDocumentHandler::class);
        $listener->handle($event);

        $this->assertDatabaseMissing(self::TABLE_NAME, [
            'title' => $document->title,
            'contents' => $document->contents,
            'financial_year' => $document->financial_year,
            'category_id' => $document->category_id,
        ]);
    }

    private function mountDTO(Document $document): DocumentDTO
    {
        $categoryEnum = CategoryEnum::tryFrom($document->category_id);

        return new DocumentDTO(
            $document->title,
            $document->contents,
            $categoryEnum->name(),
            $document->financial_year,
        );
    }
}
