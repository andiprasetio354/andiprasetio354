@extends('layouts.app')

@section('title','Contact')

@section('content')
  <div class="max-w-2xl mx-auto px-6 py-12">
    <h1 class="text-3xl font-bold mb-4">Kontak</h1>
    <p class="mb-6">Silakan kirim pesan melalui formulir berikut (sementara ini hanya placeholder).</p>
    <form class="space-y-4 bg-white p-6 rounded-md shadow">
      <input class="w-full border rounded px-3 py-2" placeholder="Nama" />
      <input class="w-full border rounded px-3 py-2" placeholder="Email" />
      <textarea class="w-full border rounded px-3 py-2" placeholder="Pesan"></textarea>
      <button class="btn-primary" type="button">Kirim</button>
    </form>
  </div>
@endsection
