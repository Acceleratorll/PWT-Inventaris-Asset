<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MovementServiceTest extends TestCase
{
    use RefreshDatabase;
    public function test_table_assets(): void
    {
        $response = $this->get('/admin/table/assets');

        $response->assertStatus(200);
    }

    public function test_table_rooms(): void
    {
        $response = $this->get('/admin/table/rooms');

        $response->assertStatus(200);
    }

    public function test_table_movements(): void
    {
        $response = $this->get('/admin/table/movements');

        $response->assertStatus(200);
    }

    public function test_pivot(): void
    {
        $response = $this->get('/admin/asset/pivot');

        $response->assertStatus(200);
    }
}
