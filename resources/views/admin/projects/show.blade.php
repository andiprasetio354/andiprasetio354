@extends('layouts.app')

@section('title', $project->title)

@section('content')
  <div class="max-w-4xl mx-auto px-6 py-8">
    <h1 class="text-3xl font-bold mb-2">{{ $project->title }}</h1>
    @if($project->image)
      <img src="{{ asset('storage/'.$project->image) }}" alt="" class="mb-4 w-full rounded" />
    @endif
    <div class="mb-4 text-gray-700">{{ $project->description }}</div>
    <div class="text-sm text-gray-600">Tech: {{ $project->tech_stack }}</div>
    <div class="mt-4">
      <a href="{{ $project->link }}" class="btn-primary" target="_blank">Visit</a>
    </div>
  </div>
@endsection
