const menuBtn = document.getElementById("menuBtn");
const navLinks = document.getElementById("navLinks");

if (menuBtn) {
    menuBtn.addEventListener("click", () => navLinks.classList.toggle("open"));
}

document.querySelectorAll('a[href^="#"]').forEach(link => {
    link.addEventListener("click", () => navLinks?.classList.remove("open"));
});
