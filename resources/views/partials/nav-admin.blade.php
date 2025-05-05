{{-- resources/views/partials/nav-admin.blade.php --}}
<aside class="w-64 bg-white border-r border-gray-200 h-screen fixed">
    <div class="p-4 text-xl font-bold text-primary">
      Back-Office
    </div>
    <nav class="mt-6">
      <ul>
        <li class="px-4 py-2 hover:bg-gray-100">
          <a href="{{ route('dashboard') }}" class="flex items-center">
            <i class="fas fa-tachometer-alt w-5"></i>
            <span class="ml-3">Tableau de bord</span>
          </a>
        </li>
        <li class="px-4 py-2 hover:bg-gray-100">
          <a href="{{ route('admins.index') }}" class="flex items-center">
            <i class="fas fa-user-shield w-5"></i>
            <span class="ml-3">Administrateurs</span>
          </a>
        </li>
        <li class="px-4 py-2 hover:bg-gray-100">
          <a href="{{ route('professeurs.index') }}" class="flex items-center">
            <i class="fas fa-chalkboard-teacher w-5"></i>
            <span class="ml-3">Professeurs</span>
          </a>
        </li>
        <!-- ajoutez d’autres liens selon vos besoins -->
      </ul>
    </nav>
  </aside>
  