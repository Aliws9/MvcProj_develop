<link rel="stylesheet" href="<?php echo asset('tailwind/output.css'); ?>">

<!DOCTYPE html>
<html lang="fa" dir="rtl" data-theme="light" class="bg-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animated Image Login</title>
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .bg-animated-gradient {
            background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
        }
        
        .floating {
            animation: float 6s ease-in-out infinite;
        }
        
        .pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        .image-upload-container {
            transition: all 0.3s ease;
        }
        
        .image-upload-container:hover {
            transform: scale(1.05);
        }
        
        .image-preview {
            transition: all 0.3s ease;
            filter: drop-shadow(0 10px 15px rgba(0, 0, 0, 0.3));
        }
        
        .image-preview:hover {
            filter: drop-shadow(0 20px 25px rgba(0, 0, 0, 0.4));
        }
    </style>
</head>
<body class="min-h-screen bg-animated-gradient flex items-center justify-center p-4">
    <div class="absolute inset-0 overflow-hidden">
        <!-- Animated background elements -->
        <div class="absolute top-10 left-20 w-16 h-16 rounded-full bg-white/10 backdrop-blur-sm floating" style="animation-delay: 0s;"></div>
        <div class="absolute top-1/3 right-1/4 w-24 h-24 rounded-full bg-white/15 backdrop-blur-sm floating" style="animation-delay: 1s;"></div>
        <div class="absolute bottom-20 left-1/4 w-20 h-20 rounded-full bg-white/20 backdrop-blur-sm floating" style="animation-delay: 2s;"></div>
        <div class="absolute top-1/4 right-20 w-12 h-12 rounded-full bg-white/10 backdrop-blur-sm floating" style="animation-delay: 3s;"></div>
    </div>

    <div class="relative w-full max-w-md">
        <!-- Login Card -->
        <div class="bg-white/20 backdrop-blur-lg rounded-2xl shadow-2xl overflow-hidden transition-all duration-300 hover:shadow-3xl">
            <div class="p-8">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-white mb-2">Welcome Back</h1>
                    <p class="text-white/80">Authenticate with your image</p>
                </div>

                <!-- Image Upload Section -->
                <div class="mb-6">
                    <div id="imageUpload" class="image-upload-container border-2 border-dashed border-white/30 rounded-xl p-6 text-center cursor-pointer hover:border-white/50 transition-colors">
                        <div class="flex flex-col items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white mb-3 pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-white font-medium">Click to upload your image</p>
                            <p class="text-white/60 text-sm mt-1">Or drag and drop</p>
                        </div>
                        <input type="file" id="fileInput" class="hidden" accept="image/*" />
                    </div>
                    
                    <div id="imagePreviewContainer" class="hidden mt-4 flex justify-center">
                        <div class="relative">
                            <img id="imagePreview" class="image-preview w-32 h-32 rounded-full object-cover border-4 border-white/30" src="" alt="Preview">
                            <div class="absolute -bottom-2 -right-2 bg-indigo-500 rounded-full p-2 shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.121-1.121A2 2 0 0011.172 3H8.828a2 2 0 00-1.414.586L6.293 4.707A1 1 0 015.586 5H4zm6 9a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Fields -->
                <div class="space-y-4">
                    <div class="relative">
                        <input type="text" class="w-full bg-white/20 text-white placeholder-white/50 rounded-lg py-3 px-4 pl-10 focus:outline-none focus:ring-2 focus:ring-white/50 focus:bg-white/30 transition" placeholder="Username">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white/70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                    </div>
                    
                    <div class="relative">
                        <input type="password" class="w-full bg-white/20 text-white placeholder-white/50 rounded-lg py-3 px-4 pl-10 focus:outline-none focus:ring-2 focus:ring-white/50 focus:bg-white/30 transition" placeholder="Password">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white/70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <button class="w-full mt-6 bg-white text-indigo-600 py-3 px-4 rounded-lg font-semibold hover:bg-white/90 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-indigo-500 transition-all transform hover:scale-105 active:scale-95">
                    Authenticate
                </button>

                <div class="mt-4 text-center">
                    <a href="#" class="text-white/70 hover:text-white text-sm transition">Forgot your password?</a>
                </div>
            </div>
        </div>

        <div class="absolute -bottom-10 left-0 right-0 text-center">
            <p class="text-white/50 text-sm">Don't have an account? <a href="#" class="text-white hover:underline">Sign up</a></p>
        </div>
    </div>

    <script>
        // Handle image upload and preview
        const imageUpload = document.getElementById('imageUpload');
        const fileInput = document.getElementById('fileInput');
        const imagePreview = document.getElementById('imagePreview');
        const imagePreviewContainer = document.getElementById('imagePreviewContainer');

        imageUpload.addEventListener('click', () => {
            fileInput.click();
        });

        fileInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (event) => {
                    imagePreview.src = event.target.result;
                    imagePreviewContainer.classList.remove('hidden');
                    imageUpload.classList.add('hidden');
                    
                    // Add animation to the preview
                    imagePreview.style.transform = 'scale(0.8)';
                    setTimeout(() => {
                        imagePreview.style.transform = 'scale(1)';
                        imagePreview.style.transition = 'transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
                    }, 50);
                };
                reader.readAsDataURL(file);
            }
        });

        // Allow drag and drop
        imageUpload.addEventListener('dragover', (e) => {
            e.preventDefault();
            imageUpload.classList.add('border-white/50', 'bg-white/10');
        });

        imageUpload.addEventListener('dragleave', () => {
            imageUpload.classList.remove('border-white/50', 'bg-white/10');
        });

        imageUpload.addEventListener('drop', (e) => {
            e.preventDefault();
            imageUpload.classList.remove('border-white/50', 'bg-white/10');
            
            if (e.dataTransfer.files.length) {
                fileInput.files = e.dataTransfer.files;
                const event = new Event('change');
                fileInput.dispatchEvent(event);
            }
        });
    </script>
</body>
</html>