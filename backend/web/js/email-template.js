
(function() {
    if (typeof CKEDITOR !== 'undefined') {
        CKEDITOR.plugins.add('insertId', {
            icons: 'insertId',
            init: function(editor) {
                editor.addCommand('insertId', {
                    exec: function(editor) {
                        editor.insertText(' {id} ');
                    }
                });
                editor.addCommand('insertUser', {
                    exec: function(editor) {
                        editor.insertText(' {user} ');
                    }
                });
                editor.addCommand('insertReason', {
                    exec: function(editor) {
                        editor.insertText(' {reason} ');
                    }
                });
                editor.ui.addButton('InsertId', {
                    label: 'Insert ID',
                    command: 'insertId',
                    toolbar: 'insert',
                    icon: 'https://style.iium.edu.my/images/iconly/light/Bookmark.svg'
                });
                editor.ui.addButton('InsertUser', {
                    label: 'Insert User',
                    command: 'insertUser',
                    toolbar: 'insert',
                    icon: 'https://style.iium.edu.my/images/iconly/light/Add-User.svg'
                });
                editor.ui.addButton('InsertReason', {
                    label: 'Insert Reason',
                    command: 'insertReason',
                    toolbar: 'insert',
                    icon: 'https://style.iium.edu.my/images/iconly/light/Paper.svg'
                });
            }
        });
    } else {
        // If CKEditor is not loaded, wait and try again
        setTimeout(arguments.callee, 50);
    }
})();
