import Typed from "typed.js";
import './password-toggle';

window.toggleMobileNav = function () {
    const overlay = document.getElementById("mobile-nav-overlay");
    const panel = document.getElementById("mobile-nav-panel");
    const icon = document.getElementById("hamburger-icon");
    if (!overlay || !panel) return;
    const isOpen = !overlay.classList.contains("hidden");
    if (isOpen) {
        panel.classList.remove("translate-x-0");
        panel.classList.add("translate-x-full");
        setTimeout(() => {
            overlay.classList.add("hidden");
            document.body.style.overflow = "";
        }, 300);
        if (icon) {
            icon.classList.remove("fa-xmark");
            icon.classList.add("fa-bars");
        }
    } else {
        overlay.classList.remove("hidden");
        panel.classList.remove("translate-x-full");
        void panel.offsetHeight;
        panel.classList.add("translate-x-0");
        document.body.style.overflow = "hidden";
        if (icon) {
            icon.classList.remove("fa-bars");
            icon.classList.add("fa-xmark");
        }
    }
};

window.toggleDashboardSidebar = function () {
    const sidebar = document.getElementById("mobile-sidebar");
    const panel = document.getElementById("mobile-sidebar-panel");
    if (!sidebar || !panel) return;
    const isOpen = !sidebar.classList.contains("hidden");
    if (isOpen) {
        panel.classList.remove("translate-x-0");
        panel.classList.add("-translate-x-full");
        setTimeout(() => {
            sidebar.classList.add("hidden");
            document.body.style.overflow = "";
        }, 300);
    } else {
        sidebar.classList.remove("hidden");
        panel.classList.remove("-translate-x-full");
        void panel.offsetHeight;
        panel.classList.add("translate-x-0");
        document.body.style.overflow = "hidden";
    }
};

window.toggleTheme = function () {
    const html = document.documentElement;
    const themeIcon = document.getElementById("theme-icon");

    html.classList.toggle("dark");

    if (html.classList.contains("dark")) {
        localStorage.setItem("theme", "dark");
        if (themeIcon) {
            themeIcon.classList.remove("fa-sun");
            themeIcon.classList.add("fa-moon");
            themeIcon.style.color = "#a78bfa";
        }
    } else {
        localStorage.setItem("theme", "light");
        if (themeIcon) {
            themeIcon.classList.remove("fa-moon");
            themeIcon.classList.add("fa-sun");
            themeIcon.style.color = "#7c3aed";
        }
    }
};

document.addEventListener("DOMContentLoaded", () => {
    // — Theme init —
    const savedTheme = localStorage.getItem("theme");
    const html = document.documentElement;
    const themeIcon = document.getElementById("theme-icon");

    if (savedTheme === "dark") {
        html.classList.add("dark");
        if (themeIcon) {
            themeIcon.classList.remove("fa-sun");
            themeIcon.classList.add("fa-moon");
            themeIcon.style.color = "#a78bfa";
        }
    } else {
        html.classList.remove("dark");
        if (themeIcon) {
            themeIcon.classList.remove("fa-moon");
            themeIcon.classList.add("fa-sun");
            themeIcon.style.color = "#7c3aed";
        }
    }

    // — Typed.js init —
    const el = document.getElementById("typed-text");
    const categoriesDataEl = document.getElementById("hero-categories");
    if (el) {
        let typedStrings = [
            "Kursus Web Development",
            "Kursus UI/UX Design",
            "Kursus Data Science",
            "Kursus Digital Marketing",
            "Kursus Bahasa Asing",
        ];

        if (categoriesDataEl && categoriesDataEl.dataset.categories) {
            try {
                const categories = JSON.parse(categoriesDataEl.dataset.categories);
                if (categories && categories.length > 0) {
                    typedStrings = categories.map(cat => "Kursus " + cat);
                }
            } catch (e) {
                console.error("Failed to parse hero categories", e);
            }
        }

        new Typed("#typed-text", {
            strings: typedStrings,
            typeSpeed: 60,
            backSpeed: 30,
            backDelay: 1500,
            startDelay: 500,
            loop: true,
            showCursor: true,
            cursorChar: "|",
        });
    }

    // — Active nav link on scroll —
    const navLinks = document.querySelectorAll(".nav-link");
    const sections = document.querySelectorAll("section[id]");

    if (navLinks.length && sections.length) {
        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        const id = entry.target.getAttribute("id");
                        navLinks.forEach((link) => {
                            link.classList.toggle(
                                "active",
                                link.getAttribute("href") === "#" + id
                            );
                        });
                    }
                });
            },
            { rootMargin: "-80px 0px -50% 0px", threshold: 0 }
        );

        sections.forEach((section) => observer.observe(section));
    }
});
