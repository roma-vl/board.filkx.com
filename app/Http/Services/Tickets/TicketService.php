<?php

namespace App\Http\Services\Tickets;

use App\Http\Requests\Ticket\CreateRequest;
use App\Http\Requests\Ticket\EditRequest;
use App\Http\Requests\Ticket\MessageRequest;
use App\Models\Tickets\Ticket;
use DomainException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class TicketService
{
    public function create(int $userId, CreateRequest $request): Ticket
    {
        return Ticket::new($userId, $request['subject'], $request['content']);
    }

    public function edit(int $id, EditRequest $request): void
    {
        $ticket = $this->getTicket($id);
        $ticket->edit(
            $request['subject'],
            $request['content']
        );
    }

    public function message(int $userId, int $id, MessageRequest $request): void
    {
        $ticket = $this->getTicket($id);
        $ticket->addMessage($userId, $request['message']);
    }

    public function approve(int $userId, int $id): void
    {
        $ticket = $this->getTicket($id);
        $ticket->approve($userId);
    }

    public function close(int $userId, int $id): void
    {
        $ticket = $this->getTicket($id);
        $ticket->close($userId);
    }

    public function reopen(int $userId, int $id): void
    {
        $ticket = $this->getTicket($id);
        $ticket->reopen($userId);
    }

    public function removeByOwner(int $id): void
    {
        $ticket = $this->getTicket($id);

        if (! $ticket->canBeRemoved()) {
            throw new DomainException('Unable to remove active ticket');
        }

        $ticket->delete();
    }

    public function removeByAdmin(int $id): void
    {
        $ticket = $this->getTicket($id);
        $ticket->delete();
    }

    public function getTicket($id): Ticket
    {
        return Ticket::findOrFail($id);
    }

    public function getTickets(): LengthAwarePaginator
    {
        return Ticket::query()
            ->forUser(Auth::user())
            ->orderByDesc('updated_at')
            ->paginate(20);
    }
}
