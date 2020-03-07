<?php

namespace Tests\Unit;

use App\Book;
use App\Reservation;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookReservationTest extends TestCase
{
    use RefreshDatabase;

    public function testBookCanBeCheckedOut()
    {
        $book = factory(Book::class)->create();
        $user = factory(User::class)->create();

        $book->checkout($user);

        $reservation = Reservation::first();

        $this->assertCount(1, Reservation::all());
        $this->assertEquals($user->id, $reservation->user_id);
        $this->assertEquals($book->id, $reservation->book_id);
        $this->assertEquals(now(), $reservation->checked_out_at);
    }
}
