<?php

namespace Tests\Unit;

use App\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    public function testCheckedOutAtIsRecorded()
    {
        Reservation::create([
            'book_id' => 1,
            'user_id' => 1,
            'checked_out_at' => now()
        ]);

        $this->assertCount(1, Reservation::all());
    }
}
