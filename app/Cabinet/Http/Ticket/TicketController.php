<?php

namespace App\Cabinet\Http\Ticket;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ticket\CreateRequest;
use App\Http\Requests\Ticket\MessageRequest;
use App\Http\Services\Tickets\TicketService;
use App\Models\Tickets\Ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class TicketController extends Controller
{
    public function __construct(private readonly TicketService $ticketService) {}

    public function index()
    {
        $tickets = $this->ticketService->getTickets();

        return Inertia::render('Cabinet/Ticket/Index', [
            'tickets' => $tickets,
        ]);
    }

    public function show(Ticket $ticket)
    {
        return Inertia::render('Cabinet/Ticket/Show', [
            'ticket' => $ticket,
            'statuses' => $ticket->statuses()->get(),
            'messages' => $ticket->messages()->with('user')->get(),
        ]);
    }

    public function store(CreateRequest $request)
    {
        try {
            $ticket = $this->ticketService->create(Auth::id(), $request);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('account.tickets.show', $ticket)->with('success', __('ticket.ticket_create'));
    }

    public function message(MessageRequest $request, Ticket $ticket)
    {
        try {
            $this->ticketService->message(Auth::id(), $ticket->id, $request);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('account.tickets.show', $ticket)->with('success', __('ticket.add_message'));
    }

    public function destroy(Ticket $ticket)
    {
        $this->checkAccess($ticket);
        try {
            $this->ticketService->removeByOwner($ticket->id);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('account.favorites.index')->with('success', __('ticket.ticket_delete'));
    }

    private function checkAccess(Ticket $ticket): void
    {
        if (! Gate::allows('manage-own-ticket', $ticket)) {
            abort(403);
        }
    }
}
