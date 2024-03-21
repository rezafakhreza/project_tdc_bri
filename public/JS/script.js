const body = document.querySelector("body"),
    sidebar = body.querySelector(".sidebar"),
    toggle = body.querySelector(".toggle"),
    menu = body.querySelector(".menu-bar"),
    modeText = body.querySelector(".mode-text");

toggle.addEventListener("click", (event) => {
    event.stopPropagation(); // Menghentikan event bubbling agar tidak langsung menutup sidebar
    sidebar.classList.toggle("close");
});

menu.addEventListener("click", () => {
    sidebar.classList.remove("close");
});

// Tambahan event listener untuk menutup sidebar saat mengklik area di luar sidebar
document.addEventListener("click", (event) => {
    const target = event.target;
    if (!target.closest(".sidebar") && !target.closest(".toggle")) {
        sidebar.classList.add("close");
    }
});

// Event listener untuk tautan di dalam sidebar
sidebar.querySelectorAll("a").forEach(link => {
    link.addEventListener("click", (event) => {
        // Memeriksa apakah link pindah ke halaman baru atau hanya menambah hash
        if (link.getAttribute("href").charAt(0) === '/') {
            // Jika link pindah halaman, sidebar ditutup
            sidebar.classList.add("close");
        }
    });
});