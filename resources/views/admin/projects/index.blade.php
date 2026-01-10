@extends('layouts.app')

@section('title','Admin - Projects')

@section('content')
  <div class="max-w-6xl mx-auto px-6 py-8">
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold">Projects</h1>
      <a href="{{ route('admin.projects.create') }}" class="btn-primary">Tambah Project</a>
    </div>

    @if(session('success'))
      <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded shadow">
      <table class="w-full table-auto">
        <thead class="bg-gray-50">
          <tr>
            <th class="p-3 text-left">Title</th>
            <th class="p-3 text-left">Tech</th>
            <th class="p-3">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($projects as $proj)
            <tr class="border-t">
              <td class="p-3">{{ $proj->title }}</td>
              <td class="p-3">{{ $proj->tech_stack }}</td>
              <td class="p-3 text-center">
                <a href="{{ route('admin.projects.edit', $proj) }}" class="text-blue-600 mr-3">Edit</a>
                <form action="{{ route('admin.projects.destroy', $proj) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus project ini?')">
                  @csrf @method('DELETE')
                  <button class="text-red-600">Hapus</button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="mt-4">{{ $projects->links() }}</div>
  </div>
@endsection
