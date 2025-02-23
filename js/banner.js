document.addEventListener("DOMContentLoaded", () => {
    const banner = document.getElementById("banner");
    const overlay = document.getElementById("overlay");
    const closeBtn = document.getElementById("closeBanner");

    if (localStorage.getItem("bannerShown")) {
        banner.style.display = "none";
        overlay.style.display = "none";
    } else {
        banner.style.display = "flex";
        overlay.style.display = "block";
    }

    closeBtn.addEventListener("click", () => {
        banner.style.display = "none";
        overlay.style.display = "none";
        localStorage.setItem("bannerShown", "true");
    });
});