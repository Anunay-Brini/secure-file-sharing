<x-app-layout>
    <!-- Header Removed per Request -->

    <div class="py-12" x-data="fileUploader({{ request()->user()->files->toJson() }})">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Upload Area -->
            <div class="bg-white/80 backdrop-blur-md overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                <div class="p-8 text-gray-900 flex flex-col items-center justify-center" x-bind:class="{'opacity-50 pointer-events-none': isUploading}">
                    <h3 class="text-2xl font-bold bg-gradient-to-r from-teal-600 to-emerald-600 bg-clip-text text-transparent mb-4">Secure File Upload</h3>
                    <p class="text-gray-500 mb-8 text-center max-w-lg">Your file will be encrypted directly in your browser before it ever reaches our servers. We never see your data.</p>
                    
                    <div class="w-full max-w-md relative">
                        <div x-show="isUploading" class="absolute inset-0 flex items-center justify-center z-10 bg-white/50 backdrop-blur-sm rounded-xl">
                            <svg class="animate-spin h-8 w-8 text-emerald-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                        <label class="flex justify-center w-full h-32 px-4 transition bg-white border-2 border-gray-300 border-dashed rounded-xl appearance-none cursor-pointer hover:border-emerald-500 focus:outline-none" :class="{ 'border-emerald-500 bg-emerald-50': isDragging }" @dragover.prevent="isDragging = true" @dragleave.prevent="isDragging = false" @drop.prevent="handleDrop($event)">
                            <span class="flex items-center space-x-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                <span class="font-medium text-gray-600">Drop files or click to Browse</span>
                            </span>
                            <input type="file" name="file_upload" class="hidden" @change="handleUpload($event)" :disabled="isUploading">
                        </label>
                    </div>
                </div>
            </div>

            <!-- Files List -->
            <div class="bg-white/80 backdrop-blur-md overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100 p-8">
                <h3 class="text-xl font-bold text-gray-800 mb-6">Your Encrypted Vault</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <template x-for="file in decryptedFiles" :key="file.id">
                        <div class="p-5 border border-gray-200 rounded-xl hover:shadow-lg transition-shadow duration-300 bg-white group hover:border-emerald-300 relative">
                            <!-- Loading indicator for download -->
                            <div x-show="file._isDownloading" class="absolute inset-0 z-10 flex items-center justify-center bg-white/70 backdrop-blur-sm rounded-xl">
                                <svg class="animate-spin h-6 w-6 text-emerald-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>

                            <div class="flex items-center justify-between mb-4">
                                <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <button @click="confirmDeletePrompt(file.id)" class="text-red-400 hover:text-red-600 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                            <h4 class="font-semibold text-gray-800 truncate" x-text="file.decryptedName || 'Locked File'"></h4>
                            <p class="text-xs text-gray-500 mt-1" x-text="formatBytes(file.size)"></p>
                            
                            <div class="mt-4 flex space-x-2">
                                <button @click="downloadFile(file)" class="flex-1 bg-emerald-50 text-emerald-600 rounded-lg py-2 text-sm font-medium hover:bg-emerald-100 transition disabled:opacity-50" :disabled="!file.decryptedName">
                                    Download
                                </button>
                                <button @click="createShareLink(file)" class="flex-1 border border-emerald-200 text-emerald-600 rounded-lg py-2 text-sm font-medium hover:bg-emerald-50 transition disabled:opacity-50" :disabled="!file.decryptedName">
                                    Share
                                </button>
                            </div>
                            
                            <!-- If key is missing -->
                            <div x-show="!file.decryptedName" class="mt-3 text-xs text-red-500 text-center">
                                ⚠ Decryption key not found on this device.
                            </div>
                        </div>
                    </template>
                    <div x-show="decryptedFiles.length === 0" class="col-span-full py-12 text-center text-gray-500">
                        Your vault is empty. Upload a file securely to get started.
                    </div>
                </div>
            </div>

    <!-- Share Modal -->
    <div x-show="shareModalOpen" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" x-cloak>
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div x-show="shareModalOpen" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel -->
            <div x-show="shareModalOpen" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
                
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 relative">
                    <!-- Close button -->
                    <button @click="shareModalOpen = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-teal-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Share Securely</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 mb-4">
                                    Your end-to-end encrypted sharing link is ready. The decryption key is embedded in the link and stays on your device.
                                </p>
                                
                                <div class="flex flex-col space-y-3">
                                    <div class="relative">
                                        <input type="text" readonly x-model="generatedShareUrl" class="w-full pr-24 pl-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-700 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                                        <button @click="copyToClipboard" class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-emerald-600 text-white rounded-lg px-3 py-1.5 text-xs font-semibold hover:bg-emerald-700 transition">
                                            <span x-show="!copySuccess">Copy</span>
                                            <span x-show="copySuccess">Copied!</span>
                                        </button>
                                    </div>
                                    <div class="text-xs text-yellow-600 bg-yellow-50 p-2 rounded-lg border border-yellow-100 flex items-start space-x-2">
                                        <svg class="h-4 w-4 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <span>Anyone with this link can download and decrypt your file. Share it only with trusted recipients.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-show="deleteModalOpen" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" x-cloak>
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div x-show="deleteModalOpen" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel -->
            <div x-show="deleteModalOpen" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
                
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 relative">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">Delete File</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to permanently delete this encrypted file? This action cannot be undone and any active share links will instantly break.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                    <button @click="executeDelete" type="button" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-gradient-to-r from-red-600 to-rose-600 text-base font-medium text-white hover:from-red-700 hover:to-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Yes, delete it
                    </button>
                    <button @click="deleteModalOpen = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('fileUploader', (initialFiles) => ({
                isDragging: false,
                isUploading: false,
                files: initialFiles,
                decryptedFiles: [],
                localKeys: JSON.parse(localStorage.getItem('zk_file_keys') || '{}'),
                shareModalOpen: false,
                generatedShareUrl: '',
                copySuccess: false,
                deleteModalOpen: false,
                fileToDelete: null,

                async init() {
                    this.decryptFilesList();
                },

                saveLocalKey(fileId, keyBase64) {
                    this.localKeys[fileId] = keyBase64;
                    localStorage.setItem('zk_file_keys', JSON.stringify(this.localKeys));
                },

                async decryptFilesList() {
                    const decrypted = [];
                    for(let file of this.files) {
                        const fileObj = { ...file, decryptedName: null, _isDownloading: false };
                        const key = this.localKeys[file.id];
                        if (key) {
                            try {
                                const nameParts = file.name.split(':::');
                                if (nameParts.length === 2 && window.CryptoUtil) { // Prevent JS err if not loaded
                                    fileObj.decryptedName = await window.CryptoUtil.decryptText(nameParts[0], nameParts[1], key);
                                }
                            } catch (e) {
                                console.error('Failed to decrypt filename for file', file.id);
                            }
                        }
                        decrypted.push(fileObj);
                    }
                    this.decryptedFiles = decrypted;
                },

                handleDrop(e) {
                    this.isDragging = false;
                    const dt = e.dataTransfer;
                    if (dt.files && dt.files.length) {
                        this.processUpload(dt.files[0]);
                    }
                },

                handleUpload(e) {
                    if (e.target.files && e.target.files.length) {
                        this.processUpload(e.target.files[0]);
                        // clear input
                        e.target.value = '';
                    }
                },

                async processUpload(rawFile) {
                    if (!rawFile) return;
                    this.isUploading = true;

                    try {
                        const keyPair = await window.CryptoUtil.generateKey();
                        const key = keyPair.key;
                        const base64Key = keyPair.base64;

                        const encName = await window.CryptoUtil.encryptText(rawFile.name, key);
                        const encMime = await window.CryptoUtil.encryptText(rawFile.type || 'application/octet-stream', key);
                        
                        const finalEncName = encName.encrypted + ':::' + encName.iv;
                        const finalEncMime = encMime.encrypted + ':::' + encMime.iv;

                        const encFileData = await window.CryptoUtil.encryptFile(rawFile, key);
                        
                        const formData = new FormData();
                        formData.append('encrypted_file', encFileData.blob);
                        formData.append('encrypted_name', finalEncName);
                        formData.append('encrypted_mime_type', finalEncMime);
                        formData.append('file_iv', encFileData.iv);

                        const response = await axios.post('/files', formData, {
                            headers: { 'Content-Type': 'multipart/form-data' }
                        });

                        const newFile = response.data.file;
                        this.saveLocalKey(newFile.id, base64Key);
                        this.files.unshift(newFile);
                        await this.decryptFilesList();
                        
                    } catch(e) {
                        console.error(e);
                        alert('Upload failed: ' + e.message);
                    } finally {
                        this.isUploading = false;
                    }
                },

                async downloadFile(file) {
                    const key = this.localKeys[file.id];
                    if (!key) return alert('Cannot download: Decryption key missing.');

                    file._isDownloading = true;

                    try {
                        const response = await axios.get('/files/' + file.id, {
                            responseType: 'blob'
                        });

                        const mimeParts = file.mime_type.split(':::');
                        let mimeType = 'application/octet-stream';
                        if (mimeParts.length === 2) {
                            mimeType = await window.CryptoUtil.decryptText(mimeParts[0], mimeParts[1], key);
                        }

                        const decryptedBlob = await window.CryptoUtil.decryptFile(response.data, file.iv, key, mimeType);
                        
                        const url = window.URL.createObjectURL(decryptedBlob);
                        const a = document.createElement('a');
                        a.style.display = 'none';
                        a.href = url;
                        a.download = file.decryptedName;
                        document.body.appendChild(a);
                        a.click();
                        window.URL.revokeObjectURL(url);
                    } catch(e) {
                        alert('Download/Decryption failed');
                    } finally {
                        file._isDownloading = false;
                    }
                },

                confirmDeletePrompt(fileId) {
                    this.fileToDelete = fileId;
                    this.deleteModalOpen = true;
                },

                async executeDelete() {
                    if(!this.fileToDelete) return;
                    try {
                        const fileId = this.fileToDelete;
                        await axios.delete('/files/' + fileId);
                        this.files = this.files.filter(f => f.id !== fileId);
                        await this.decryptFilesList();
                        this.deleteModalOpen = false;
                        this.fileToDelete = null;
                    } catch(e) {
                        alert('Failed to delete.');
                    }
                },

                formatBytes(bytes, decimals = 2) {
                    if (!+bytes) return '0 Bytes'
                    const k = 1024
                    const dm = decimals < 0 ? 0 : decimals
                    const sizes = ['Bytes', 'KiB', 'MiB', 'GiB']
                    const i = Math.floor(Math.log(bytes) / Math.log(k))
                    return `${parseFloat((bytes / Math.pow(k, i)).toFixed(dm))} ${sizes[i]}`
                },

                async createShareLink(file) {
                    const key = this.localKeys[file.id];
                    if (!key) return alert('Key missing!');
                    
                    try {
                        const response = await axios.post('/shares', {
                            file_id: file.id,
                            download_limit: null, // Customize later
                            expires_at: null // Customize later
                        });
                        
                        const uuid = response.data.share_link.uuid;
                        this.generatedShareUrl = window.location.origin + '/s/' + uuid + '#' + key;
                        this.shareModalOpen = true;
                    } catch(e) {
                        alert('Failed to generate share link.');
                    }
                },

                copyToClipboard() {
                    navigator.clipboard.writeText(this.generatedShareUrl).then(() => {
                        this.copySuccess = true;
                        setTimeout(() => this.copySuccess = false, 2000);
                    });
                }
            }));
        });
    </script>
</x-app-layout>
