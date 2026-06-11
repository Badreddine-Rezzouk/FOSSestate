<?php

declare(strict_types=1);

function index(): void
{
    jsonResponse([
        'documents' => [
            ['id' => 1, 'name' => 'Lease Agreement', 'status' => 'available'],
            ['id' => 2, 'name' => 'Property Inspection Report', 'status' => 'available'],
        ],
    ]);
}

function upload(): void
{
    jsonResponse(['message' => 'Document upload endpoint accepted request.']);
}
