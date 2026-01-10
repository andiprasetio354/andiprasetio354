@extends('layouts.app')

@section('title','Admin - Contact Messages')

@section('content')
  <div class="max-w-6xl mx-auto px-6 py-8">
    <h1 class="text-2xl font-bold mb-6">Pesan Kontak</h1>

    @if(session('success'))
      <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded shadow overflow-x-auto">
      <table class="w-full table-auto">
        <thead class="bg-gray-50 border-b">
          <tr>
            <th class="p-3 text-left">Nama</th>
            <th class="p-3 text-left">Email</th>
            <th class="p-3 text-left">Subject</th>
            <th class="p-3 text-left">Status</th>
            <th class="p-3 text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($messages as $msg)
            <tr class="border-t hover:bg-gray-50">
              <td class="p-3">{{ $msg->name }}</td>
              <td class="p-3 text-sm"><a href="mailto:{{ $msg->email }}" class="text-blue-600">{{ $msg->email }}</a></td>
              <td class="p-3">{{ $msg->subject }}</td>
              <td class="p-3">
                <span class="px-2 py-1 rounded text-xs {{ $msg->status === 'read' ? 'bg-gray-200 text-gray-800' : 'bg-yellow-100 text-yellow-800' }}">
                  {{ ucfirst($msg->status) }}
                </span>
              </td>
              <td class="p-3 text-center text-sm space-x-2">
                <button onclick="showMessage({{ $msg->id }}, '{{ addslashes($msg->message) }}')" class="text-blue-600">Lihat</button>
                @if($msg->status === 'unread')
                  <form action="/admin/contact/{{ $msg->id }}/read" method="POST" class="inline-block">
                    @csrf
                    <button class="text-green-600">Baca</button>
                  </form>
                @endif
                <form action="/admin/contact/{{ $msg->id }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus pesan?')">
                  @csrf @method('DELETE')
                  <button class="text-red-600">Hapus</button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="p-4 text-center text-gray-500">Belum ada pesan.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="mt-4">{{ $messages->links() }}</div>
  </div>

  <div id="messageModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
    <div class="bg-white rounded-lg max-w-2xl w-full p-6">
      <h2 class="text-xl font-bold mb-3" id="modalSubject"></h2>
      <div class="mb-4" id="modalMessage"></div>
      <button onclick="document.getElementById('messageModal').classList.add('hidden')" class="px-4 py-2 bg-gray-300 rounded">Tutup</button>
    </div>
  </div>

  <script>
    function showMessage(id, msg) {
      document.getElementById('modalMessage').textContent = msg;
      document.getElementById('messageModal').classList.remove('hidden');
    }
  </script>
@endsection
