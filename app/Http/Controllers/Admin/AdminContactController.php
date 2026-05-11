<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class AdminContactController extends Controller
{
    public function index(Request $request)
    {
        $messages = ContactMessage::orderByDesc('created_at')
            ->when($request->search, fn($q, $s) => $q->where('name', 'like', "%$s%")->orWhere('email', 'like', "%$s%"))
            ->paginate(15)
            ->withQueryString();

        return view('admin.contact.index', compact('messages'));
    }

    public function markRead(ContactMessage $contactMessage)
    {
        $contactMessage->update(['is_read' => true]);
        return back();
    }

    public function destroy(ContactMessage $contactMessage)
    {
        $contactMessage->delete();
        return back()->with('success', 'Pesan dihapus.');
    }
}
