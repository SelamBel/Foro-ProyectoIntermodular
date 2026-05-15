document.addEventListener('DOMContentLoaded', () => {
    const lightBtn = document.getElementById('lightBtn');
    const darkBtn = document.getElementById('darkBtn');

    lightBtn.addEventListener('click', () => {
        location.reload();
    });
    
    darkBtn.addEventListener('click', () => {
        location.reload();
    });
});
