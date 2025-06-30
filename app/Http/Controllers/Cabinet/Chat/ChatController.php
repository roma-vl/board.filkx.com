<?php

namespace App\Http\Controllers\Cabinet\Chat;

use App\Http\Controllers\Controller;
use App\Http\Services\NotificationService;
use App\Models\Adverts\Advert;
use App\Models\Adverts\Dialog\Dialog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ChatController extends Controller
{
    public function index(): Response
    {
        $user = auth()->user();

        $dialogs = Dialog::with('advert.firstPhoto', 'client', 'messages.user')
            ->where('user_id', $user->id)
            ->orWhere('client_id', $user->id)
            ->latest('updated_at')
            ->get();

        return Inertia::render('Account/Chat/Index', [
            'dialogs' => $dialogs,
        ]);
    }

    public function show(Request $request, Dialog $dialog): Response
    {
//        $this->authorizeDialog($dialog);

        $dialog->load('advert', 'client');
        $user = auth()->user();

        $dialogs = Dialog::with('advert.firstPhoto', 'client', 'messages.user')
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->orWhere('client_id', $user->id);
            })
            ->latest('updated_at')
            ->get();

        return Inertia::render('Account/Chat/Index', [
            'dialogs' => $dialogs,
            'activeDialogId' => $dialog->id,
        ]);
    }

    public function getMessages(Dialog $dialog, Request $request): JsonResponse
    {
//        $this->authorizeDialog($dialog);

        $messages = $dialog->messages()->with('user')->latest()->paginate(10);

        return response()->json([
            'messages' => $messages,
        ]);
    }

    public function getDialogByAdvert(Request $request, Advert $advert): JsonResponse
    {
        $user = $request->user();

        $dialog = $advert
            ->dialogs()
            ->where('user_id', $user->id)
            ->orWhere('client_id', $user->id)
            ->with('messages.user', 'client')
            ->get()
            ->first();

        return response()->json($dialog);
    }

    public function store(Request $request, Advert $advert)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        if ($advert->user->id !== $request->user()->id) {
            $message = $advert->writeClientMessage($request->user()->id, $request->input('message'));
            NotificationService::notify($request->user(), 'chat.new', [
                'message_id' => $message->id,
                'text' => Str::limit($message->message, 50),
            ]);

        } else {
            $message = $advert->writeOwnerMessage($request->input('client_id'), $request->input('message'));
            NotificationService::notify($request->user(), 'chat.new', [
                'message_id' => $message->id,
                'text' => Str::limit($message->message, 50),
            ]);
        }
    }

    public function createDialog(Request $request, Advert $advert)
    {
        $request->validate([
            'message' => 'required|string',
        ]);
    }

    protected function authorizeDialog(Dialog $dialog): void
    {
        abort_unless(
            $dialog->user_id === auth()->id() || $dialog->client_id === auth()->id(),
            403,
            'Access denied'
        );
    }
}
