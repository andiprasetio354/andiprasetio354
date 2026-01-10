@extends('layouts.app')

@section('title','Contact')

@section('content')
  <div class="max-w-2xl mx-auto px-6 py-12">
    <h1 class="text-3xl font-bold mb-4">Hubungi Saya</h1>
    <p class="mb-6 text-gray-700">Ada pertanyaan atau proposal? Silakan isi formulir berikut dan saya akan merespon secepatnya.</p>

    @if(session('success'))
      <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif

    @if($errors->any())
      <div class="mb-4 p-4 bg-red-50 text-red-700 rounded">
        <ul class="list-disc list-inside">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="/contact" method="POST" class="space-y-4 bg-white p-6 rounded-lg shadow">
      @csrf
      <label class="block">
        <span class="text-gray-700 font-medium">Nama</span>
        <input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded px-3 py-2 mt-1" required>
      </label>

      <label class="block">
        <span class="text-gray-700 font-medium">Email</span>
        <input type="email" name="email" value="{{ old('email') }}" class="w-full border rounded px-3 py-2 mt-1" required>
      </label>

      <label class="block">
        <span class="text-gray-700 font-medium">Subject</span>
        <input type="text" name="subject" value="{{ old('subject') }}" class="w-full border rounded px-3 py-2 mt-1" required>
      </label>

      <label class="block">
        <span class="text-gray-700 font-medium">Pesan</span>
        <textarea name="message" class="w-full border rounded px-3 py-2 mt-1" rows="6" required>{{ old('message') }}</textarea>
      </label>

      <button type="submit" class="btn-primary">Kirim Pesan</button>
    </form>
  </div>
@endsection
