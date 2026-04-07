<x-app-layout>
    <!-- Header Removed for Clean Layout -->

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <div class="mb-4">
                <h2 class="text-3xl font-extrabold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">Profile Settings</h2>
                <p class="text-gray-500 mt-2">Manage your account credentials, security options, and vault access.</p>
            </div>

            <!-- Profile Info Container -->
            <div class="bg-white/80 backdrop-blur-md overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100 p-8 hover:shadow-2xl hover:border-indigo-200 transition-all duration-300">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Update Password Container -->
            <div class="bg-white/80 backdrop-blur-md overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100 p-8 hover:shadow-2xl hover:indigo-indigo-200 transition-all duration-300 relative overflow-hidden">
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-32 h-32 rounded-full bg-purple-100 opacity-50"></div>
                <div class="max-w-xl relative z-10">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete Account Container -->
            <div class="bg-red-50/50 backdrop-blur-md overflow-hidden shadow-lg sm:rounded-2xl border border-red-100 p-8 relative overflow-hidden group hover:bg-red-50 transition-colors duration-300">
                 <div class="absolute top-0 right-0 -mr-16 -mt-16 w-32 h-32 rounded-full bg-red-100 opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="max-w-xl relative z-10">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
