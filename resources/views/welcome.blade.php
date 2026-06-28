<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Zece Info - Pregătire Informatică</title>
    
    <!-- Google Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>
<body class="bg-black text-white antialiased selection:bg-purple-650 selection:text-white">

    <!-- FLOATING NAVBAR -->
    <nav class="fixed top-5 left-1/2 -translate-x-1/2 w-[calc(100%-2rem)] max-w-6xl bg-[#212121]/90 backdrop-blur-md border border-neutral-800 rounded-[2rem] px-6 py-3 z-50 flex items-center justify-between shadow-2xl">
        <div class="flex items-center gap-3">
            <a href="#section1" class="flex items-center gap-2">
                <img src="{{ asset('images/zeceinfoblock.png') }}" alt="Zece Info" class="h-10 w-auto object-contain">
            </a>
        </div>
        
        <!-- Desktop Links - Hidden -->
        <div class="hidden"></div>

        <div class="flex items-center gap-3">
            <a href="{{ route('login') }}" class="inline-flex px-4 py-2 text-xs font-bold uppercase tracking-widest rounded-full border border-neutral-700 bg-neutral-900/50 hover:bg-white hover:text-black transition duration-300">
                Admin Login
            </a>
        </div>
    </nav>

    <!-- MOBILE DRAWER MENU - Hidden -->
    <div id="mobileMenu" class="hidden"></div>

    <section id="section1" class="min-h-screen pt-32 pb-16 px-4 md:px-8 flex flex-col items-center justify-center bg-black">
        <div class="max-w-md w-full mx-auto">
            
            <!-- Code Entry Panel -->
            <div class="bg-[#121212] border border-neutral-800 rounded-3xl p-8 md:p-12 flex flex-col items-center justify-center text-center shadow-xl relative overflow-hidden group w-full">
                <div class="absolute -top-24 -left-24 w-48 h-48 bg-purple-600/10 rounded-full blur-3xl group-hover:bg-purple-600/20 transition-all duration-500"></div>
                <div class="absolute -bottom-24 -right-24 w-48 h-48 bg-blue-600/10 rounded-full blur-3xl group-hover:bg-blue-600/20 transition-all duration-500"></div>

                <h2 class="text-3xl font-black text-white mb-2">Ai deja un cod?</h2>
                <p class="text-neutral-400 text-sm max-w-sm mb-8 leading-relaxed">
                    Introdu codul primit de la instructorul tău pentru a începe sesiunea de evaluare.
                </p>
                
                <form action="{{ route('access.code') }}" method="POST" class="w-full max-w-sm space-y-6 relative z-10">
                    @csrf
                    <div>
                        <input 
                            type="text" 
                            name="access_code" 
                            maxlength="6"
                            placeholder="DEMO12"
                            class="w-full text-center text-4xl tracking-[0.3em] font-mono font-bold uppercase border border-neutral-800 focus:border-purple-500 rounded-2xl bg-neutral-900 text-white placeholder-neutral-700 outline-none p-5 transition duration-300 focus:ring-2 focus:ring-purple-500/20"
                            required
                            autocomplete="off"
                        >
                        @error('access_code')
                            <p class="text-red-500 text-sm mt-3 font-semibold flex items-center justify-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full bg-white hover:bg-neutral-200 text-black font-extrabold uppercase rounded-2xl py-4.5 px-8 transition duration-300 shadow-lg hover:shadow-white/5 active:scale-98 tracking-wider text-sm">
                        Continuare
                    </button>
                </form>
            </div>

            <!-- Mentors Panel - Hidden -->
            <div class="hidden"></div>

        </div>
    </section>

    <!-- SECTION 5: SERVICII (Dark Purple) - Hidden -->
    <section id="section5" class="hidden">
        <div class="absolute -top-40 right-10 w-96 h-96 bg-purple-900/10 rounded-full blur-3xl"></div>
        
        <div class="max-w-6xl mx-auto space-y-12 relative z-10">
            <div class="text-center space-y-2">
                <h1 class="text-4xl md:text-5xl font-black text-white tracking-tight">Servicii</h1>
                <p class="text-purple-300/60 max-w-md mx-auto text-sm">
                    Oferim o gamă variată de cursuri și suport pentru pregătirea optimă a elevilor.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                
                <!-- Card 1 -->
                <div class="bg-white/5 border border-white/5 hover:border-purple-500/30 hover:bg-white/10 rounded-2xl p-6 flex flex-col justify-between transition-all duration-300 hover:-translate-y-1.5 shadow-lg group">
                    <div class="space-y-4">
                        <div class="w-12 h-12 rounded-xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-400 group-hover:scale-110 transition duration-300">
                            <!-- School Icon -->
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-white">Curs Certiport Prezență Fizică</h3>
                        <p class="text-neutral-400 text-xs leading-relaxed">
                            Participă la cursurile noastre în persoană pentru o experiență de învățare directă și interactivă.
                        </p>
                    </div>
                    <a href="#section2" class="mt-6 inline-flex w-max items-center gap-1.5 px-4 py-2 rounded-xl bg-white text-black hover:bg-neutral-200 text-xs font-bold transition duration-200">
                        Detalii
                    </a>
                </div>

                <!-- Card 2 -->
                <div class="bg-white/5 border border-white/5 hover:border-purple-500/30 hover:bg-white/10 rounded-2xl p-6 flex flex-col justify-between transition-all duration-300 hover:-translate-y-1.5 shadow-lg group">
                    <div class="space-y-4">
                        <div class="w-12 h-12 rounded-xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-400 group-hover:scale-110 transition duration-300">
                            <!-- Laptop Icon -->
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-white">Curs Certiport Online</h3>
                        <p class="text-neutral-400 text-xs leading-relaxed">
                            Învață în ritmul tău, de oriunde, cu acces la aceleași resurse de calitate și suport constant.
                        </p>
                    </div>
                    <a href="#section2" class="mt-6 inline-flex w-max items-center gap-1.5 px-4 py-2 rounded-xl bg-white text-black hover:bg-neutral-200 text-xs font-bold transition duration-200">
                        Detalii
                    </a>
                </div>

                <!-- Card 3 -->
                <div class="bg-white/5 border border-white/5 hover:border-purple-500/30 hover:bg-white/10 rounded-2xl p-6 flex flex-col justify-between transition-all duration-300 hover:-translate-y-1.5 shadow-lg group">
                    <div class="space-y-4">
                        <div class="w-12 h-12 rounded-xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-400 group-hover:scale-110 transition duration-300">
                            <!-- Consulting Icon -->
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-white">Consultanță după eșec</h3>
                        <p class="text-neutral-400 text-xs leading-relaxed">
                            Analizăm împreună ce nu a funcționat și creăm un nou plan de atac pentru succesul garantat.
                        </p>
                    </div>
                    <a href="#section2" class="mt-6 inline-flex w-max items-center gap-1.5 px-4 py-2 rounded-xl bg-white text-black hover:bg-neutral-200 text-xs font-bold transition duration-200">
                        Detalii
                    </a>
                </div>

                <!-- Card 4 -->
                <div class="bg-white/5 border border-white/5 hover:border-purple-500/30 hover:bg-white/10 rounded-2xl p-6 flex flex-col justify-between transition-all duration-300 hover:-translate-y-1.5 shadow-lg group">
                    <div class="space-y-4">
                        <div class="w-12 h-12 rounded-xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-400 group-hover:scale-110 transition duration-300">
                            <!-- User Check Icon -->
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-white">Ghidare Personală</h3>
                        <p class="text-neutral-400 text-xs leading-relaxed">
                            Beneficiază de un plan de studiu personalizat și sesiuni 1-la-1 cu mentorii noștri.
                        </p>
                    </div>
                    <a href="#section2" class="mt-6 inline-flex w-max items-center gap-1.5 px-4 py-2 rounded-xl bg-white text-black hover:bg-neutral-200 text-xs font-bold transition duration-200">
                        Detalii
                    </a>
                </div>

                <!-- Card 5 -->
                <div class="bg-white/5 border border-white/5 hover:border-purple-500/30 hover:bg-white/10 rounded-2xl p-6 flex flex-col justify-between transition-all duration-300 hover:-translate-y-1.5 shadow-lg group">
                    <div class="space-y-4">
                        <div class="w-12 h-12 rounded-xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-400 group-hover:scale-110 transition duration-300">
                            <!-- File Icon -->
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-white">Teste de Autoevaluare</h3>
                        <p class="text-neutral-400 text-xs leading-relaxed">
                            Verifică-ți cunoștințele cu teste complete care simulează structura examenului oficial.
                        </p>
                    </div>
                    <a href="#section2" class="mt-6 inline-flex w-max items-center gap-1.5 px-4 py-2 rounded-xl bg-white text-black hover:bg-neutral-200 text-xs font-bold transition duration-200">
                        Detalii
                    </a>
                </div>

                <!-- Card 6 -->
                <div class="bg-white/5 border border-white/5 hover:border-purple-500/30 hover:bg-white/10 rounded-2xl p-6 flex flex-col justify-between transition-all duration-300 hover:-translate-y-1.5 shadow-lg group">
                    <div class="space-y-4">
                        <div class="w-12 h-12 rounded-xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-400 group-hover:scale-110 transition duration-300">
                            <!-- Dumbbell/Training Icon -->
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-white">Teste de antrenament</h3>
                        <p class="text-neutral-400 text-xs leading-relaxed">
                            Exersează pe capitole specifice cu seturi de probleme dedicate pentru a-ți consolida materia.
                        </p>
                    </div>
                    <a href="#section2" class="mt-6 inline-flex w-max items-center gap-1.5 px-4 py-2 rounded-xl bg-white text-black hover:bg-neutral-200 text-xs font-bold transition duration-200">
                        Detalii
                    </a>
                </div>

                <!-- Card 7 -->
                <div class="bg-white/5 border border-white/5 hover:border-purple-500/30 hover:bg-white/10 rounded-2xl p-6 flex flex-col justify-between transition-all duration-300 hover:-translate-y-1.5 shadow-lg group">
                    <div class="space-y-4">
                        <div class="w-12 h-12 rounded-xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-400 group-hover:scale-110 transition duration-300">
                            <!-- Video Icon -->
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-white">Pachet video individual</h3>
                        <p class="text-neutral-400 text-xs leading-relaxed">
                            Învață în ritmul tău cu lecții video explicative și materiale de suport pentru fiecare subiect.
                        </p>
                    </div>
                    <a href="#section2" class="mt-6 inline-flex w-max items-center gap-1.5 px-4 py-2 rounded-xl bg-white text-black hover:bg-neutral-200 text-xs font-bold transition duration-200">
                        Detalii
                    </a>
                </div>

            </div>
        </div>
    </section>

    <!-- SECTION 2: CONTACT (Dark Blue) - Hidden -->
    <section id="section2" class="hidden">
        <div class="absolute bottom-0 left-10 w-96 h-96 bg-blue-900/10 rounded-full blur-3xl"></div>
        
        <div class="max-w-4xl mx-auto space-y-12 relative z-10">
            <div class="text-center space-y-2">
                <h1 class="text-4xl md:text-5xl font-black text-white tracking-tight">Contact</h1>
                <p class="text-blue-300/60 max-w-md mx-auto text-sm">
                    Suntem mereu disponibili pentru a răspunde la întrebări. Alege metoda preferată.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-6 gap-6 items-stretch">
                
                <!-- Instagram Card (Wide) -->
                <div class="md:col-span-4 bg-gradient-to-br from-[#E1306C]/10 to-[#fd1d1d]/5 border border-[#E1306C]/30 hover:border-[#E1306C] rounded-3xl p-8 text-center flex flex-col items-center justify-between gap-6 transition duration-300 group shadow-lg">
                    <div class="space-y-3 flex flex-col items-center">
                        <div class="w-16 h-16 rounded-full bg-[#E1306C]/10 flex items-center justify-center text-[#E1306C] group-hover:scale-110 transition duration-300 border border-[#E1306C]/20">
                            <!-- Instagram Icon -->
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <rect width="20" height="20" x="2" y="2" rx="5" ry="5" stroke-width="2"/>
                                <path stroke-width="2" d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37zM17.5 6.5h.01"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-white">Scrie-ne pe Instagram</h3>
                        <p class="text-neutral-400 text-xs max-w-sm leading-relaxed">
                            Cel mai rapid mod de a lua legătura cu noi este printr-un mesaj direct pe Instagram. Răspundem zilnic la toate întrebările tale.
                        </p>
                    </div>
                    <a href="https://instagram.com/zece.info" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-8 py-3 rounded-full bg-gradient-to-r from-[#833ab4] via-[#E1306C] to-[#fcb045] text-white hover:scale-105 active:scale-95 transition font-extrabold uppercase text-xs tracking-wider shadow-lg shadow-[#E1306C]/25">
                        Deschide Instagram
                    </a>
                </div>

                <!-- Email Card -->
                <div class="md:col-span-2 bg-white/5 border border-white/5 hover:border-blue-500/30 hover:bg-white/10 rounded-3xl p-6 flex flex-col justify-between items-center text-center transition duration-300 group shadow-lg">
                    <div class="space-y-4 flex flex-col items-center">
                        <div class="w-12 h-12 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-450 group-hover:scale-110 transition duration-300">
                            <!-- Envelope Icon -->
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-white">Email</h3>
                        <p class="text-neutral-400 text-xs leading-relaxed">
                            Trimiți o solicitare mai complexă? Ne poți contacta pe email.
                        </p>
                    </div>
                    <a href="mailto:zece.info@gmail.com" class="mt-6 text-xs font-semibold text-blue-400 hover:text-white px-4 py-2 border border-blue-500/30 hover:bg-blue-500/20 rounded-xl transition duration-200">
                        zece.info@gmail.com
                    </a>
                </div>

                <!-- Phone Card -->
                <div class="md:col-span-2 md:col-start-3 bg-white/5 border border-white/5 hover:border-blue-500/30 hover:bg-white/10 rounded-3xl p-6 flex flex-col justify-between items-center text-center transition duration-300 group shadow-lg">
                    <div class="space-y-4 flex flex-col items-center">
                        <div class="w-12 h-12 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-450 group-hover:scale-110 transition duration-300">
                            <!-- Phone Icon -->
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-white">Telefon</h3>
                        <p class="text-neutral-400 text-xs leading-relaxed">
                            Disponibili pentru apeluri între orele 10:00 și 18:00.
                        </p>
                    </div>
                    <a href="tel:+37379896797" class="mt-6 text-xs font-semibold text-blue-400 hover:text-white px-4 py-2 border border-blue-500/30 hover:bg-blue-500/20 rounded-xl transition duration-200">
                        +373 79 896 797
                    </a>
                </div>

            </div>
        </div>
    </section>

    <!-- SECTION 4: DESPRE (Dark Red) - Hidden -->
    <section id="section4" class="hidden">
        <div class="absolute top-10 left-10 w-96 h-96 bg-red-900/5 rounded-full blur-3xl"></div>
        
        <div class="max-w-4xl mx-auto space-y-12 relative z-10">
            <div class="text-center space-y-2">
                <h1 class="text-4xl md:text-5xl font-black text-white tracking-tight">Despre Proiect</h1>
            </div>

            <div class="bg-white/5 border border-white/5 rounded-3xl p-8 md:p-12 text-center max-w-2xl mx-auto shadow-xl backdrop-blur-md relative overflow-hidden">
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-red-500/5 rounded-full blur-2xl"></div>
                <p class="text-lg md:text-xl font-medium text-neutral-300 leading-relaxed">
                    "Zece Info" este o platformă creată pentru a ajuta elevii să se pregătească eficient pentru proba de informatică a examenului de Bacalaureat. Misiunea noastră este să oferim resurse de calitate, exerciții relevante și o metodă de învățare interactivă pentru a asigura succesul fiecărui utilizator. 🚀
                </p>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-black py-8 border-t border-neutral-900 text-center text-xs text-neutral-500">
        <div class="max-w-6xl mx-auto px-4 space-y-2">
            <p>&copy; {{ date('Y') }} ZeceInfo. Toate drepturile rezervate.</p>
            <p class="text-[10px] text-neutral-700">Construit cu dedicare pentru performanță academică.</p>
        </div>
    </footer>

    <!-- NAVBAR LOGIC -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const navbarToggler = document.getElementById('navbarToggler');
            const mobileMenu = document.getElementById('mobileMenu');
            const mobileLinks = mobileMenu.querySelectorAll('a');

            // Toggle drawer menu
            navbarToggler.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });

            // Close menu when links are clicked
            mobileLinks.forEach(link => {
                link.addEventListener('click', () => {
                    mobileMenu.classList.add('hidden');
                });
            });

            // Close menu when resize goes desktop
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 768) {
                    mobileMenu.classList.add('hidden');
                }
            });
        });
    </script>

</body>
</html>