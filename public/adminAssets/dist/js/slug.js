// Convert any string into a URL-friendly slug (Bangla & English friendly)
function toSlug(title) {
    if (!title) return '';
    return title
        .trim()                             // Remove leading/trailing spaces
        .replace(/\s+/g, ' ')               // Replace multiple spaces with single space
        .replace(/\s/g, '-')                // Replace spaces with hyphens
        .replace(/[^a-zA-Z0-9\u0980-\u09FF\-]/g, '') // Keep Bangla, English letters, numbers, hyphens
        .replace(/-+/g, '-')                // Remove consecutive hyphens
        .toLowerCase();                     // Convert to lowercase
}

// Bind title input to slug input
function bindSlug(titleSelector, slugSelector) {
    const titleInput = document.querySelector(titleSelector);
    const slugInput = document.querySelector(slugSelector);

    if (!titleInput || !slugInput) return;

    titleInput.addEventListener('input', () => {
        slugInput.value = toSlug(titleInput.value);
    });
}
