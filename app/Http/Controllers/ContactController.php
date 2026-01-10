<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    // Public: Show contact form
    public function showForm()
    {
        return view('contact');
    }

    // Public: Store contact message
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        ContactMessage::create($data);

        return redirect('/contact')->with('success', 'Pesan berhasil dikirim! Terima kasih.');
    }

    // Admin: List all messages
    public function adminIndex()
    {
        $messages = ContactMessage::latest()->paginate(15);
        return view('admin.contact.index', compact('messages'));
    }

    // Admin: Mark as read
    public function markRead(ContactMessage $message)
    {
        $message->update(['status' => 'read']);
        return back()->with('success', 'Pesan ditandai sebagai terbaca.');
    }

    // Admin: Delete message
    public function destroy(ContactMessage $message)
    {
        $message->delete();
        return back()->with('success', 'Pesan dihapus.');
    }
}
