<?php

namespace App\Listeners;

use App\Events\ImportDocumentEvent;
use App\Jobs\ImportDocumentJob;

class ImportDocumentHandler
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     */
    public function handle(ImportDocumentEvent $event): void
    {
        foreach ($event->documentsDTO as $documentDTO) {
            ImportDocumentJob::dispatch($documentDTO)->onQueue('process_import_documents');
        }
    }

}
