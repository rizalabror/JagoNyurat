const postEditor = document.getElementById('postEditor');
if (postEditor && typeof EasyMDE !== 'undefined') {
    var easyMDE = new EasyMDE({
        element: postEditor,
        toolbar: ['bold', 'italic', 'heading', '|', 'quote', 'unordered-list', 'ordered-list', '|', 'link', 'image', '|', 'preview', 'guide']
    });
}
