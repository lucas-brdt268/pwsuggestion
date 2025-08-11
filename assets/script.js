const imageUpload = document.getElementById('imageUpload');
const imagePreview = document.getElementById('imagePreview');
const customFileUpload = document.querySelector('.custom-file-upload');
const styleSelect = document.getElementById('styleSelect');
// const methodSelect = document.getElementById('methodSelect');
const suggestButton = document.getElementById('suggestButton');
const suggestionOutput = document.getElementById('suggestionOutput');
const preview = document.getElementById('preview');

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
suggestButton.addEventListener('click', async () => {
    suggestButton.disabled = true;
    preview.style.display = 'none';

    const style = styleSelect.value;
    // const method = methodSelect.value;

    if (!imageUpload.files[0]) {
        // suggestionOutput.textContent = 'まず画像をアップロードしてください。';
        alert('まず画像をアップロードしてください。');
        return;
    }

    const form = new FormData();
    form.append('image', imageUpload.files[0]);
    form.append('style', styleSelect.value);
    console.log(form.values());

    try {
        const response = await fetch('suggest.php', {
            method: 'post',
            body: form,
        });

        let json = null;
        try {
            json = await response.json();
        } catch (e) {
            throw new Error('システムに問題が発生しています。しばらく経ってから再度お試しください。');
        }

        if (!response.ok && json) {
            throw new Error(json.error || '画像の生成に失敗しました。');
        }

        console.log(json);

        const base64Image = json.base64_image;
        const suggestedColor = json.suggested_color;
        const base64ImageUrl = `data:image/jpg;base64,${base64Image}`;
        
        preview.src = base64ImageUrl;
        preview.style.display = 'block';
        suggestionOutput.textContent = `提案された色: ${suggestedColor} (スタイル: ${styleSelect.options[styleSelect.selectedIndex].text})`;
    } catch (e) {
        suggestionOutput.textContent = e.message;
    }

    suggestButton.disabled = false;
});