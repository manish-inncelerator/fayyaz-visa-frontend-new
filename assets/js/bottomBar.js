// Handle share button click
function handleShare() {
  if (navigator.share) {
    navigator
      .share({
        title: document.title,
        text: document.title,
        url: window.location.href,
      })
      .then(() => {
        // Native share was successful, do nothing
      })
      .catch((err) => {
        // Only show fallback if sharing was actually attempted and failed
        if (err.name !== "AbortError") {
          console.warn("Sharing failed", err);
          showShareModal();
        }
      });
  } else {
    showShareModal();
  }
}

// Show share modal with fallback options
function showShareModal() {
  prepareShareLinks();
  const shareModal = new bootstrap.Offcanvas(
    document.getElementById("shareModal")
  );
  shareModal.show();
}

// Prepare share links for the modal
function prepareShareLinks() {
  const url = encodeURIComponent(window.location.href);
  const text = encodeURIComponent(document.title);

  // Adjust buttons to be smaller and not full width
  const shareButtons = document.querySelectorAll("#shareModal .btn");
  shareButtons.forEach((button) => {
    // Remove any existing size and width classes
    button.classList.remove("btn-lg", "w-100");
    // Add smaller button class and prevent full width
    button.classList.add("btn-sm");
    // Set display to inline-block to prevent full width
    button.style.display = "inline-block";
    button.style.marginRight = "10px";
    button.style.marginBottom = "10px";
  });

  if (document.getElementById("whatsapp-share")) {
    document.getElementById(
      "whatsapp-share"
    ).href = `https://wa.me/?text=${text}%20${url}`;
  }
  if (document.getElementById("telegram-share")) {
    document.getElementById(
      "telegram-share"
    ).href = `https://t.me/share/url?url=${url}&text=${text}`;
  }
  if (document.getElementById("facebook-share")) {
    document.getElementById(
      "facebook-share"
    ).href = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
  }
  if (document.getElementById("messenger-share")) {
    document.getElementById(
      "messenger-share"
    ).href = `fb-messenger://share?link=${url}`;
  }
  if (document.getElementById("line-share")) {
    document.getElementById(
      "line-share"
    ).href = `https://social-plugins.line.me/lineit/share?url=${url}`;
  }
  if (document.getElementById("twitter-share")) {
    document.getElementById(
      "twitter-share"
    ).href = `https://twitter.com/intent/tweet?text=${text}&url=${url}`;
  }
}

// Copy page URL to clipboard
function copyLink() {
  navigator.clipboard
    .writeText(window.location.href)
    .then(() => {
      alert("Link copied to clipboard!");
    })
    .catch(() => {
      alert("Failed to copy link. Please try again.");
    });
}

// Initialize when DOM is loaded
document.addEventListener("DOMContentLoaded", function () {
  // Initialize share links
  prepareShareLinks();

  // Bottom bar scroll behavior
  const bottomBar = document.querySelector(".mobile-bottom-bar");
  if (bottomBar) {
    window.addEventListener(
      "scroll",
      function () {
        // Get current scroll position
        let scrollTop =
          window.pageYOffset || document.documentElement.scrollTop;
        // Get total height of the page minus viewport height
        let scrollHeight =
          document.documentElement.scrollHeight - window.innerHeight;

        // Only hide when at the bottom of the page (with a small threshold)
        if (scrollTop >= scrollHeight - 20) {
          // At bottom of page
          bottomBar.classList.add("hidden");
        } else {
          // Not at bottom of page
          bottomBar.classList.remove("hidden");
        }
      },
      false
    );
  }
});
