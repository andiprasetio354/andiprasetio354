<header class="shadow-sm bg-white">
  <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
    <a href="/" class="font-bold text-lg text-primary">MyPortfolio</a>
    <nav class="space-x-4">
      <a href="/" class="text-gray-700 hover:text-primary transition">Home</a>
      <a href="/about" class="text-gray-700 hover:text-primary transition">About</a>
      <a href="/projects" class="text-gray-700 hover:text-primary transition">Projects</a>
      <a href="/resume" class="text-gray-700 hover:text-primary transition">Resume</a>
      <a href="/contact" class="text-gray-700 hover:text-primary transition">Contact</a>
      @auth
        <a href="/dashboard" class="bg-primary text-white px-3 py-2 rounded text-sm hover:bg-opacity-90 transition">Dashboard</a>
      @endauth
      @guest
        <a href="/login" class="text-primary hover:underline">Login</a>
      @endguest
    </nav>
  </div>
</header>
