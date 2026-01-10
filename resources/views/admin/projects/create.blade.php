@extends('layouts.app')

@section('title','Create Project')

@section('content')
  <div class="max-w-3xl mx-auto px-6 py-8">
    <h1 class="text-2xl font-bold mb-4">Tambah Project</h1>

    @if ($errors->any())
      <div class="mb-4 p-3 bg-red-50 text-red-700 rounded">
        <ul class="list-disc list-inside">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('admin.projects.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded shadow">
      @csrf
      <label class="block mb-2">Title
        <input type="text" name="title" value="{{ old('title') }}" class="w-full border rounded px-3 py-2 mt-1" required>
      </label>

      <label class="block mb-2">Tech Stack
        <input type="text" name="tech_stack" value="{{ old('tech_stack') }}" class="w-full border rounded px-3 py-2 mt-1">
      </label>

      <label class="block mb-2">Link
        <input type="url" name="link" value="{{ old('link') }}" class="w-full border rounded px-3 py-2 mt-1">
      </label>

      <label class="block mb-4">Image
        <input type="file" name="image" class="w-full mt-1">
      </label>

      <label class="inline-flex items-center mb-4">
        <input type="checkbox" name="featured" value="1" class="mr-2"> Featured
      </label>

      <label class="block mb-4">Description
        <textarea name="description" class="w-full border rounded px-3 py-2 mt-1" rows="6">{{ old('description') }}</textarea>
      </label>

      <div class="flex gap-3">
        <button class="btn-primary">Simpan</button>
        <a href="{{ route('admin.projects.index') }}" class="px-4 py-2 rounded bg-gray-100">Batal</a>
      </div>
    </form>
  </div>
@endsection
