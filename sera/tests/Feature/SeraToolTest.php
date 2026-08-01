<?php

namespace Tests\Feature;

use Tests\TestCase;

class SeraToolTest extends TestCase
{
    public function test_index_page_loads(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('CM Sera Tool');
        $response->assertSee('Generate Report');
    }

    public function test_report_requires_keywords(): void
    {
        $response = $this->post('/report', [
            'domain' => 'example.com',
            'keywords' => '',
        ]);

        $response->assertSessionHasErrors('keywords');
    }
}
