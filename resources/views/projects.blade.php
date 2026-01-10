@extends('layouts.app')

@section('title','Projects - My Portfolio')
@section('description','Lihat proyek-proyek saya yang menampilkan keahlian dalam Laravel, PHP, dan teknologi web modern.')
@section('keywords','portfolio, projects, web development, laravel, javascript')

@section('content')
  <div class="max-w-6xl mx-auto px-6 py-12">
    <h1 class="text-3xl font-bold mb-6">Portofolio</h1>
    <p class="mb-6">Proyek-proyek akan ditampilkan di sini. Nantikan pembaruan — saya akan menambahkan fitur CRUD untuk proyek di panel admin.</p>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="p-6 bg-white rounded-lg shadow">Project A</div>
      <div class="p-6 bg-white rounded-lg shadow">Project B</div>
      <div class="p-6 bg-white rounded-lg shadow">Project C</div>
    </div>
  </div>
@endsection
