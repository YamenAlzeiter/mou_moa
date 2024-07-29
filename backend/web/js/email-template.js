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
                editor.addCommand('insertMCOMDate', {
                    exec: function(editor) {
                        editor.insertText(' {MCOM_date} ');
                    }
                });
                editor.addCommand('insertUMCDate', {
                    exec: function(editor) {
                        editor.insertText(' {UMC_date} ');
                    }
                });
                editor.addCommand('insertPrinciple', {
                    exec: function(editor) {
                        editor.insertText(' {principle} ');
                    }
                });
                editor.addCommand('insertAdvice', {
                    exec: function(editor) {
                        editor.insertText(' {advice} ');
                    }
                });
                editor.addCommand('insertExecutionDate', {
                    exec: function(editor) {
                        editor.insertText(' {execution_date} ');
                    }
                });
                editor.addCommand('insertExpiryDate', {
                    exec: function(editor) {
                        editor.insertText(' {expiry_date} ');
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
                editor.ui.addButton('InsertMCOMDate', {
                    label: 'Insert MCOM Date',
                    command: 'insertMCOMDate',
                    toolbar: 'insert',
                    icon: 'https://style.iium.edu.my/images/iconly/light/Calendar.svg'
                });
                editor.ui.addButton('InsertUMCDate', {
                    label: 'Insert UMC Date',
                    command: 'insertUMCDate',
                    toolbar: 'insert',
                    icon: 'https://style.iium.edu.my/images/iconly/light/Calendar.svg'
                });
                editor.ui.addButton('InsertPrinciple', {
                    label: 'Insert Principle',
                    command: 'insertPrinciple',
                    toolbar: 'insert',
                    icon: 'https://style.iium.edu.my/images/iconly/light/Book.svg'
                });
                editor.ui.addButton('InsertAdvice', {
                    label: 'Insert Advice',
                    command: 'insertAdvice',
                    toolbar: 'insert',
                    icon: 'https://style.iium.edu.my/images/iconly/light/Chat.svg'
                });
                editor.ui.addButton('InsertExecutionDate', {
                    label: 'Insert Execution Date',
                    command: 'insertExecutionDate',
                    toolbar: 'insert',
                    icon: 'https://style.iium.edu.my/images/iconly/light/Calendar.svg'
                });
                editor.ui.addButton('InsertExpiryDate', {
                    label: 'Insert Expiry Date',
                    command: 'insertExpiryDate',
                    toolbar: 'insert',
                    icon: 'https://style.iium.edu.my/images/iconly/light/Calendar.svg'
                });
            }
        });
    } else {
        // If CKEditor is not loaded, wait and try again
        setTimeout(arguments.callee, 50);
    }
})();
