import './bootstrap';
 
// Livewire handles Alpine — no separate import needed
 
// Copy to clipboard utility
window.copyToClipboard = function(text) {
    navigator.clipboard.writeText(text).then(() => {
        window.dispatchEvent(new CustomEvent('toast', {
            detail: { message: 'Copied to clipboard!', type: 'success' }
        }));
    });
};
 
// Listen for Livewire toast events
document.addEventListener('livewire:initialized', () => {
    Livewire.on('toast', ({ message, type }) => {
        window.dispatchEvent(new CustomEvent('toast', { detail: { message, type } }));
    });
});
 