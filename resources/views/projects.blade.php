@extends('layouts.app')

@section('title','Projects - My Portfolio')
@section('description','Lihat proyek-proyek saya yang menampilkan keahlian dalam Laravel, PHP, dan teknologi web modern.')
@section('keywords','portfolio, projects, web development, laravel, javascript')

@section('content')
  <div class="max-w-6xl mx-auto px-6 py-12">
    <h1 class="text-3xl font-bold mb-6">Portofolio Saya</h1>
    <p class="mb-8 text-gray-700">Berikut adalah beberapa proyek yang telah saya kerjakan, menampilkan keahlian dan pengalaman saya dalam pengembangan web.</p>
    
    @if(isset($projects) && $projects->count() > 0)
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($projects as $project)
          <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
            @if($project->image)
              <img src="{{ asset('storage/'.$project->image) }}" alt="{{ $project->title }}" class="w-full h-48 object-cover">
            @else
              <div class="w-full h-48 bg-gradient-to-r from-primary to-accent flex items-center justify-center">
                <span class="text-white text-center text-sm font-semibold">{{ $project->title }}</span>
              </div>
            @endif
            <div class="p-6">
              <h3 class="text-xl font-bold mb-2">{{ $project->title }}</h3>
              <p class="text-sm text-gray-600 mb-3">{{ Str::limit($project->description, 100) }}</p>
              @if($project->tech_stack)
                <div class="mb-3 flex flex-wrap gap-1">
                  @foreach(explode(',', $project->tech_stack) as $tech)
                    <span class="inline-block bg-primary bg-opacity-10 text-primary text-xs px-2 py-1 rounded">{{ trim($tech) }}</span>
                  @endforeach
                </div>
              @endif
              @if($project->link)
                <a href="{{ $project->link }}" target="_blank" class="text-primary hover:underline text-sm font-medium">Lihat Proyek →</a>
              @endif
            </div>
          </div>
        @endforeach
      </div>
    @else
      <div class="bg-gray-50 rounded-lg p-12 text-center">
        <p class="text-gray-600 mb-4">Belum ada proyek yang dipublikasikan.</p>
        <p class="text-sm text-gray-500">Proyek-proyek akan ditampilkan di sini setelah ditambahkan melalui panel admin.</p>
      </div>
    @endif
  </div>
@endsection
