<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Download Shared File</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50 text-gray-900 min-h-screen flex items-center justify-center p-4">
        
        <div x-data="shareViewer()" class="w-full max-w-md bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
            <!-- Header Banner -->
            <div class="h-32 bg-gradient-to-br from-indigo-600 via-purple-600 to-blue-500 relative">
                <div class="absolute inset-0 bg-black/10"></div>
                <div class="absolute -bottom-10 left-1/2 transform -translate-x-1/2 bg-white p-3 rounded-2xl shadow-lg">
                    <div class="w-16 h-16 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="pt-16 pb-8 px-8 text-center" x-show="!hasKey">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Access Denied</h2>
                <p class="text-gray-500 mb-6">The decryption key is missing from the URL. Please ensure you copied the entire link.</p>
            </div>

            <div class="pt-16 pb-8 px-8 flex flex-col items-center" x-show="hasKey" x-cloak>
                <div x-show="!isDownloaded">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2" x-text="decryptedName || 'Decrypting File Details...'"></h2>
                    
                    <div class="inline-flex items-center space-x-2 bg-gray-100 px-3 py-1 rounded-full mt-2 mb-8">
                        <span class="w-2 h-2 rounded-full bg-green-500"></span>
                        <span class="text-xs font-semibold text-gray-600">End-to-End Encrypted</span>
                    </div>

                    <div class="text-sm text-gray-500 mb-8 border-t border-b border-gray-100 py-4 w-full">
                        <div class="flex justify-between mb-2">
                            <span>File Size</span>
                            <span class="font-medium text-gray-800">
                                {{ number_format($shareLink->file->size / 1024 / 1024, 2) }} MB
                            </span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span>Shared</span>
                            <span class="font-medium text-gray-800">{{ $shareLink->created_at->diffForHumans() }}</span>
                        </div>
                        @if($shareLink->expires_at)
                        <div class="flex justify-between">
                            <span>Expires</span>
                            <span class="font-medium text-red-600">{{ $shareLink->expires_at->diffForHumans() }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Download Action -->
                <div class="w-full">
                    <button 
                        @click="startDownload" 
                        x-show="!isDownloading && !isDownloaded"
                        class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl shadow-lg shadow-indigo-200 transition duration-300 flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        <span>Download Securely</span>
                    </button>

                    <!-- Loading State -->
                    <div x-show="isDownloading" class="flex flex-col items-center justify-center py-4 space-y-4">
                        <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <p class="text-sm font-medium text-indigo-600" x-text="statusText"></p>
                    </div>

                    <!-- Success State -->
                    <div x-show="isDownloaded" class="flex flex-col items-center justify-center py-4 space-y-4 text-center">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center text-green-500 mb-2">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Download Complete</h3>
                        <p class="text-gray-500 text-sm">Your file was decrypted successfully on your device.</p>
                        
                        <button @click="resetView" class="mt-4 text-indigo-600 font-medium text-sm hover:underline">
                            Download Again
                        </button>
                    </div>
                </div>

                <div x-show="errorMessage" class="mt-4 p-3 bg-red-50 text-red-600 rounded-lg text-sm w-full text-center" x-text="errorMessage"></div>
            </div>
        </div>

        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('shareViewer', () => ({
                    keyHex: '',
                    hasKey: false,
                    decryptedName: null,
                    decryptedMime: null,
                    isDownloading: false,
                    isDownloaded: false,
                    statusText: 'Initializing...',
                    errorMessage: null,
                    
                    fileInfo: {
                        encryptedName: '{{ $shareLink->file->name }}',
                        encryptedMime: '{{ $shareLink->file->mime_type }}',
                        iv: '{{ $shareLink->file->iv }}',
                        downloadUrl: '{{ route("shares.download", $shareLink->uuid) }}'
                    },

                    async init() {
                        const hash = window.location.hash.substring(1);
                        if (hash && hash.length > 10) {
                            this.keyHex = hash;
                            this.hasKey = true;
                            await this.decryptMetadata();
                        }
                    },

                    async decryptMetadata() {
                        try {
                            const nameParts = this.fileInfo.encryptedName.split(':::');
                            if (nameParts.length === 2 && window.CryptoUtil) {
                                this.decryptedName = await window.CryptoUtil.decryptText(nameParts[0], nameParts[1], this.keyHex);
                            }

                            const mimeParts = this.fileInfo.encryptedMime.split(':::');
                            if (mimeParts.length === 2 && window.CryptoUtil) {
                                this.decryptedMime = await window.CryptoUtil.decryptText(mimeParts[0], mimeParts[1], this.keyHex);
                            }
                        } catch(e) {
                            this.errorMessage = "Failed to decrypt. The key may be incorrect or the link is corrupted.";
                        }
                    },

                    async startDownload() {
                        this.isDownloading = true;
                        this.errorMessage = null;
                        this.statusText = 'Downloading encrypted block...';

                        try {
                            const response = await axios.get(this.fileInfo.downloadUrl, {
                                responseType: 'blob'
                            });

                            this.statusText = 'Decrypting locally...';
                            
                            // To allow UI update
                            await new Promise(r => setTimeout(r, 100));

                            const decryptedBlob = await window.CryptoUtil.decryptFile(
                                response.data, 
                                this.fileInfo.iv, 
                                this.keyHex, 
                                this.decryptedMime || 'application/octet-stream'
                            );
                            
                            const url = window.URL.createObjectURL(decryptedBlob);
                            const a = document.createElement('a');
                            a.style.display = 'none';
                            a.href = url;
                            a.download = this.decryptedName || 'encrypted_file.bin';
                            document.body.appendChild(a);
                            this.statusText = 'Triggering save...';
                            a.click();
                            window.URL.revokeObjectURL(url);
                            
                            this.isDownloaded = true;
                        } catch(e) {
                            if (e.response && e.response.status === 403) {
                                this.errorMessage = "Access Denied: The link may be expired, revoked, or limit reached.";
                            } else {
                                this.errorMessage = "Decryption failed. Ensure the URL hash is correct.";
                            }
                        } finally {
                            this.isDownloading = false;
                        }
                    },

                    resetView() {
                        this.isDownloaded = false;
                        this.errorMessage = null;
                    }
                }));
            });
        </script>
    </body>
</html>
