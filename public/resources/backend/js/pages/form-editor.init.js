const editors = new Map();

document.querySelectorAll('.ckeditor-classic').forEach(textarea => {
    ClassicEditor
        .create(textarea, {
            toolbar: [
                'heading', '|', 'bold', 'italic', 'link', 
                'bulletedList', 'numberedList', '|', 
                'blockQuote', 'undo', 'redo'
            ]
        })
        .then(editor => {
            editors.set(textarea.id, editor);
            editor.ui.view.editable.element.style.height = '200px';
        })
        .catch(error => {
            console.error(error);
        });
});