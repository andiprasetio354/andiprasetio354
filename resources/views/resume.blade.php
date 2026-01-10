@extends('layouts.app')

@section('title','Resume / CV')

@section('content')
  <div class="max-w-4xl mx-auto px-6 py-12">
    <!-- Header -->
    <div class="mb-8">
      <h1 class="text-4xl font-bold mb-2">Nama Saya</h1>
      <p class="text-gray-600 mb-4">Web Developer | Laravel & JavaScript Specialist</p>
      <div class="flex gap-4 text-sm">
        <a href="mailto:andi@example.com" class="text-primary hover:underline">Email</a>
        <a href="#" class="text-primary hover:underline">LinkedIn</a>
        <a href="#" class="text-primary hover:underline">GitHub</a>
      </div>
    </div>

    <!-- Download CV -->
    <div class="mb-6">
      <a href="/resume/download" class="btn-primary">Unduh PDF</a>
    </div>

    <!-- Professional Summary -->
    <section class="mb-8">
      <h2 class="text-2xl font-bold mb-3 border-b-2 border-primary pb-2">Ringkasan Profesional</h2>
      <p class="text-gray-700">Web developer dengan pengalaman 3+ tahun dalam membangun aplikasi web full-stack menggunakan Laravel, PHP, dan JavaScript modern. Berfokus pada code quality, performance, dan user experience.</p>
    </section>

    <!-- Skills -->
    <section class="mb-8">
      <h2 class="text-2xl font-bold mb-3 border-b-2 border-primary pb-2">Skill</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <h3 class="font-semibold text-accent mb-2">Backend</h3>
          <ul class="list-disc list-inside text-gray-700">
            <li>PHP & Laravel Framework</li>
            <li>MySQL & Database Design</li>
            <li>RESTful API Development</li>
            <li>Authentication & Security</li>
          </ul>
        </div>
        <div>
          <h3 class="font-semibold text-accent mb-2">Frontend</h3>
          <ul class="list-disc list-inside text-gray-700">
            <li>JavaScript & ES6+</li>
            <li>Vue.js / React</li>
            <li>Tailwind CSS & Bootstrap</li>
            <li>Responsive Design</li>
          </ul>
        </div>
      </div>
    </section>

    <!-- Experience -->
    <section class="mb-8">
      <h2 class="text-2xl font-bold mb-3 border-b-2 border-primary pb-2">Pengalaman</h2>
      <div class="space-y-4">
        <div class="border-l-4 border-accent pl-4">
          <h3 class="font-bold">Senior Developer</h3>
          <p class="text-sm text-gray-600">PT Teknologi Maju | 2023 - Sekarang</p>
          <ul class="list-disc list-inside text-gray-700 mt-2 text-sm">
            <li>Develop dan maintain aplikasi internal menggunakan Laravel</li>
            <li>Lead tim frontend dalam migrasi ke Vue.js</li>
          </ul>
        </div>
        <div class="border-l-4 border-accent pl-4">
          <h3 class="font-bold">Web Developer</h3>
          <p class="text-sm text-gray-600">Startup XYZ | 2021 - 2023</p>
          <ul class="list-disc list-inside text-gray-700 mt-2 text-sm">
            <li>Membangun platform e-commerce dengan Laravel & Vue.js</li>
            <li>Implementasi payment gateway dan order management</li>
          </ul>
        </div>
      </div>
    </section>

    <!-- Education -->
    <section class="mb-8">
      <h2 class="text-2xl font-bold mb-3 border-b-2 border-primary pb-2">Pendidikan</h2>
      <div>
        <h3 class="font-bold">Sarjana Teknik Informatika</h3>
        <p class="text-sm text-gray-600">Universitas Indonesia | 2019</p>
      </div>
    </section>

    <!-- Certifications -->
    <section class="mb-8">
      <h2 class="text-2xl font-bold mb-3 border-b-2 border-primary pb-2">Sertifikat</h2>
      <ul class="list-disc list-inside text-gray-700 space-y-1">
        <li>Laravel Certified Developer (2023)</li>
        <li>AWS Solutions Architect Associate (2022)</li>
      </ul>
    </section>

    <!-- Print Button -->
    <div class="mt-8 text-center">
      <button onclick="window.print()" class="px-4 py-2 border border-gray-400 rounded hover:bg-gray-50">Cetak CV</button>
    </div>
  </div>

  <style>
    @media print {
      body {
        padding: 0;
      }
      .btn-primary, button {
        display: none;
      }
    }
  </style>
@endsection
