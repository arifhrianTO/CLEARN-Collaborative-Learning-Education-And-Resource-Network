import Typed from "typed.js";
import './password-toggle';

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
});
