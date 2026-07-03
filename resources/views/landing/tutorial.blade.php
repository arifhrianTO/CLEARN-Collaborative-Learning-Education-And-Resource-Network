<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo-clearn.png') }}">
    <title>Clearn - Platform Pembelajaran Online</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#7C3AED',
                        'dark-bg': '#090613',
                        'dark-sidebar': '#120d22',
                        'dark-card': '#17122b',
                        'border-custom': '#2d2644',
                    }
                }
            }
        }
    </script>
    <button
        onclick="window.location='{{ route('home') }}'"
        class="absolute left-6 top-6 z-50 text-white hover:opacity-80 transition">
        <i class="fa-solid fa-arrow-left-long text-2xl -translate-x-1"></i>
    </button>
    <style type="text/tailwindcss">
    @layer base {
        body {
            @apply bg-slate-50 text-slate-900 transition-colors duration-300;

            font-family: 'Inter', 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;

            /* Tracking sedikit rapat agar terlihat modern & profesional */
            letter-spacing: -0.01em;

            margin: 0;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        h1, h2, h3, h4 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            @apply font-bold tracking-tight;
            letter-spacing: -0.025em;
            line-height: 1.2;
        }

        .dark body {
            @apply bg-[#121128] text-white;
        }
    }

    @layer components {
        nav {
            @apply fixed top-0 w-full z-50 transition-all duration-300 h-20 flex items-center justify-between px-10;
            @apply bg-white/80 backdrop-blur-md border-b border-slate-200;
        }
        .dark nav {
            @apply bg-[#121128]/95 border-white/5;
        }

        .card-landing {
            @apply rounded-[40px] transition-all duration-300 border;
            @apply bg-white border-slate-200 shadow-sm hover:shadow-md;
        }
        .dark .card-landing {
            @apply bg-[#1a1932] border-white/5 shadow-none hover:border-purple-500/30;
        }

        .btn-gradient-glow {
            @apply bg-gradient-to-r from-purple-600 to-indigo-600 text-white shadow-lg;
            box-shadow: 0 4px 15px rgba(139, 92, 246, 0.4);
            transition: all 0.3s ease;
        }
        .btn-gradient-glow:hover {
            @apply -translate-y-0.5;
            box-shadow: 0 8px 25px rgba(139, 92, 246, 0.5);
        }

        .text-muted-custom {
            @apply text-slate-500;
        }
        .dark .text-muted-custom {
            @apply text-slate-400;
        }

        footer {
            @apply pt-24 pb-12 px-12 border-t transition-colors duration-300;
            @apply bg-white border-slate-200;
        }
        .dark footer {
            @apply bg-[#121128] border-white/5;
        }
    }

    .glow-center {
        @apply absolute top-20 left-1/2 -translate-x-1/2 w-[800px] h-[400px] rounded-full -z-10 blur-[120px] transition-all duration-700;
        background: rgba(139, 92, 246, 0.15);
    }
    .dark .glow-center {
        background: rgba(139, 92, 246, 0.1);
    }
</style>
</head>
<body class="antialiased text-sm">

<main class="bg-[#0b0a14] text-white">
  <section class="relative overflow-hidden px-6 py-28 bg-[#A78BFA]">

      <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-10 items-center">
          <div>
              <h1 class="text-4xl md:text-6xl font-bold leading-tight mb-6 mt-15">
                  Bagikan <br> Pengetahuan Anda
              </h1>

              <p class="text-white/80 mb-8 max-w-md">
                  Bergabunglah dengan komunitas instruktur ahli kami dan ubah hasrat Anda menjadi keuntungan. Mulai mengajar hari ini.
              </p>

              <div class="flex gap-4">
                <button 
                onclick="window.location='{{ route('register.mentor') }}'"
                class="bg-white text-purple-600 px-6 py-3 rounded-xl font-semibold">
                 Mulai Mengajar
                </button>
              </div>
          </div>

          <div>
              <iframe class="rounded-2xl flex items-center justify-center" width="560" height="315" src="https://www.youtube.com/embed/OXr9HwyYqaI?si=iwxr1xZvnxuuBo7r" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
          </div>

      </div>
  </section>
  <section class="px-6 mt-20">
      <div class="max-w-6xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-4 mt-20">

          <div class="bg-[#151427] p-6 rounded-2xl text-center">
              <h3 class="text-3xl font-bold text-[#A78BFA]"><?php echo "50"?></h3>
              <p class="text-xs text-gray-400">Active Instructors</p>
          </div>

          <div class="bg-[#151427] p-6 rounded-2xl text-center">
              <h3 class="text-3xl font-bold text-[#A78BFA]"><?php echo "20"?></h3>
              <p class="text-xs text-gray-400">Paid to Instructors</p>
          </div>

          <div class="bg-[#151427] p-6 rounded-2xl text-center">
              <h3 class="text-3xl font-inter font-bold text-[#A78BFA]"><?php echo "2"?></h3>
              <p class="text-xs text-gray-400">Students Taught</p>
          </div>

          <div class="bg-[#151427] p-6 rounded-2xl text-center">
              <h3 class="text-3xl font-bold text-[#A78BFA]"><?php echo "50" ?></h3>
              <p class="text-xs text-gray-400">Countries Reached</p>
          </div>

      </div>
  </section>

  <!-- WHY -->
  <section class="px-6 py-24 text-center">
      <h2 class="text-3xl md:text-5xl font-bold mb-4">
          Mengapa Mengajar Bersama Kami?
      </h2>

      <p class="text-gray-400 mb-16">
          Semua yang Anda butuhkan untuk membuat, memasarkan, dan menjual kursus Anda secara online.
      </p>

      <div class="grid md:grid-cols-4 gap-6 max-w-6xl mx-auto">

          <div class="bg-[#151427] p-6 rounded-2xl text-left">
              <i class="fas fa-video mb-4 text-white bg-[#A78BFA] p-6 rounded-xl text-left px-3 py-3"></i>
              <h3 class="font-semibold mb-2">Buat Konten Berkualitas</h3>
              <p class="text-sm text-gray-400">
                Unggah kursus Anda dan jangkau jutaan pelajar yang antusias di seluruh dunia.
              </p>
          </div>

          <div class="bg-[#151427] p-6 rounded-2xl text-left">
              <i class="fas fa-dollar-sign mb-4 text-white bg-[#A78BFA] p-6 rounded-xl px-4 py-3"></i>
              <h3 class="font-semibold mb-2">Menghasilkan Uang</h3>
              <p class="text-sm text-gray-400">
                Tetapkan harga Anda sendiri dan dapatkan penghasilan dari setiap penjualan kursus.
              </p>
          </div>

          <div class="bg-[#151427] p-6 rounded-2xl text-left">
              <i class="fas fa-users mb-4 text-white bg-[#A78BFA] p-6 rounded-xl text-left px-3 py-3"></i>
              <h3 class="font-semibold mb-2">Bangun Audiens Anda</h3>
              <p class="text-sm text-gray-400">
                Kembangkan jumlah pengikut Anda dan bangun reputasi Anda sebagai ahli di bidang Anda.
              </p>
          </div>

          <div class="bg-[#151427] p-6 rounded-2xl text-left">
              <i class="fas fa-chart-line mb-4 text-white bg-[#A78BFA] p-6 rounded-xl text-left px-3 py-3"></i>
              <h3 class="font-semibold mb-2">Lacak Kesuksesan</h3>
              <p class="text-sm text-gray-400">
                  Akses analitik terperinci untuk memahami siswa Anda dan melakukan peningkatan.
              </p>
          </div>

      </div>
  </section>

  <section class="px-6 pb-24 text-center">
      <h2 class="text-5xl font-bold mb-4">Cara Kerjanya</h2>
      <p class="text-gray-400 mb-16">
          Empat langkah sederhana untuk memulai perjalanan mengajar Anda
      </p>

      <div class="grid md:grid-cols-4 gap-6 max-w-6xl mx-auto text-left">

          <div class="bg-[#151427] p-6 rounded-2xl">
              <h3 class="text-purple-400 text-4xl mb-2">01</h3>
              <h3>Ajukan Permohonan untuk Mengajar</h3>
              <p class="mt-2">Isi formulir aplikasi sederhana kami dan ceritakan tentang keahlian Anda.</p>
          </div>

          <div class="bg-[#151427] p-6 rounded-2xl">
              <h3 class="text-purple-400 text-4xl mb-2">02</h3>
              <h3>Rencanakan Kursus Anda</h3>
              <p class="mt-7">Kami akan menyediakan sumber daya dan praktik terbaik untuk menyusun konten Anda.</p>
          </div>

          <div class="bg-[#151427] p-6 rounded-2xl">
              <h3 class="text-purple-400 text-4xl mb-2">03</h3>
              <h3>Buat Konten</h3>
              <p class="mt-7">Isi formulir aplikasi sederhana kami dan ceritakan tentang keahlian Anda.</p>
          </div>

          <div class="bg-[#151427] p-6 rounded-2xl">
              <h3 class="text-purple-400 text-4xl mb-2">04</h3>
              <h3>Publikasikan & Dapatkan Penghasilan</h3>
              <p class="mt-2">Luncurkan kursus Anda dan mulai menghasilkan pendapatan sejak hari pertama.</p>
          </div>
      </div>
  </section>

  <section class="px-6 pb-24">
      <div class="max-w-6xl mx-auto bg-[#A78BFA] rounded-3xl text-center py-20 px-10">

        <h2 class="text-2xl md:text-3xl font-bold mb-4">
            Siap Memulai Perjalanan Mengajar Anda?
        </h2>

        <p class="text-white/80 mb-8">
            Bergabunglah dengan ribuan instruktur dan mulai menghasilkan.
        </p>

      </div>
  </section>
</main>
    <script>
    function toggleTheme() {
        const html = document.documentElement; 
        const themeIcon = document.getElementById('theme-icon');

        html.classList.toggle('dark');

        if (html.classList.contains('dark')) {
            themeIcon.classList.remove('fa-sun');
            themeIcon.classList.add('fa-moon');
            themeIcon.style.color = '#a78bfa'; 
            
            // Simpan pilihan user ke storage
            localStorage.setItem('theme', 'dark');
        } else {
            themeIcon.classList.remove('fa-moon');
            themeIcon.classList.add('fa-sun');
            themeIcon.style.color = '#7c3aed'; 
            
            localStorage.setItem('theme', 'light');
        }
    }

    (function() {
        const savedTheme = localStorage.getItem('theme');
        const html = document.documentElement;
        const themeIcon = document.getElementById('theme-icon');

        if (savedTheme === 'dark') {
            html.classList.add('dark');
            if(themeIcon) {
                themeIcon.classList.replace('fa-sun', 'fa-moon');
                themeIcon.style.color = '#a78bfa';
            }
        } else {
            html.classList.remove('dark');
            if(themeIcon) {
                themeIcon.classList.replace('fa-moon', 'fa-sun');
                themeIcon.style.color = '#7c3aed';
            }
        }
    })();
</script>

</body>
</html>