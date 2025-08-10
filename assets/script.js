const imageUpload = document.getElementById('imageUpload');
const imagePreview = document.getElementById('imagePreview');
const customFileUpload = document.querySelector('.custom-file-upload');
const styleSelect = document.getElementById('styleSelect');
const methodSelect = document.getElementById('methodSelect');
const suggestButton = document.getElementById('suggestButton');
const suggestionOutput = document.getElementById('suggestionOutput');
const preview = document.getElementById('preview');

// Mock color suggestions with Japanese-inspired palette
const colorSuggestions = {
    modern: { 'ai': '#4A90E2', 'color-theory': '#E8ECEF', 'trend-based': '#2E2E2E' },
    traditional: { 'ai': '#8B5A2B', 'color-theory': '#D3C8A6', 'trend-based': '#A52A2A' },
    coastal: { 'ai': '#4682B4', 'color-theory': '#B0E0E6', 'trend-based': '#F0F8FF' },
    rustic: { 'ai': '#6B4E31', 'color-theory': '#8B4513', 'trend-based': '#D2B48C' }
};

// Trigger file input on custom button click
customFileUpload.addEventListener('click', () => {
    imageUpload.click();
});

// Handle image upload and preview
imageUpload.addEventListener('change', (event) => {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.src = e.target.result;
            imagePreview.style.display = 'block';
            preview.src = e.target.result;
            preview.style.display = 'none';
            customFileUpload.textContent = '別の画像を選択';
        };
        reader.readAsDataURL(file);
    }
});

// Handle suggest button click
suggestButton.addEventListener('click', () => {
    const style = styleSelect.value;
    const method = methodSelect.value;

    if (!imageUpload.files[0]) {
        suggestionOutput.textContent = 'まず画像をアップロードしてください。';
        return;
    }

    // Get suggested color
    const suggestedColor = colorSuggestions[style][method] || '#FFFFFF';
    suggestionOutput.textContent = `提案された色: ${suggestedColor} (スタイル: ${styleSelect.options[styleSelect.selectedIndex].text}, 方法: ${methodSelect.options[methodSelect.selectedIndex].text})`;

    // Apply color overlay to preview
    if (imagePreview.src) {
        preview.style.display = 'block';
        preview.style.backgroundColor = suggestedColor;
        preview.style.opacity = '0.7';
    }
});