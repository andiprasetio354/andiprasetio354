@extends('layouts.app')

@section('title','Home - Web Developer Portfolio')
@section('description','Welcome to my portfolio. Saya adalah web developer dengan spesialisasi Laravel, PHP, dan JavaScript modern.')
@section('keywords','web developer, portfolio, laravel, php, javascript')

@section('content')
  <section class="hero-gradient text-white py-20">
    <div class="max-w-5xl mx-auto px-6 text-center">
      <h1 class="text-4xl md:text-6xl font-extrabold mb-4">Halo, saya Developer Web</h1>
      <p class="text-lg md:text-xl mb-6">Saya membangun aplikasi yang menarik, cepat, dan dapat diskalakan. Lihat proyek saya di bawah.</p>
      <div class="flex justify-center gap-4">
        <a href="/projects" class="btn-primary">Lihat Portofolio</a>
        <a href="/about" class="bg-white text-gray-900 px-4 py-2 rounded-lg">Tentang Saya</a>
      </div>
    </div>
  </section>

  <section class="max-w-6xl mx-auto px-6 py-12">
    <h2 class="text-2xl font-bold mb-4">Skill Utama</h2>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
      <div class="p-6 bg-white rounded-lg shadow">PHP & Laravel</div>
      <div class="p-6 bg-white rounded-lg shadow">JavaScript & Vue/React</div>
      <div class="p-6 bg-white rounded-lg shadow">Design & UX</div>
    </div>
  </section>

@endsection
