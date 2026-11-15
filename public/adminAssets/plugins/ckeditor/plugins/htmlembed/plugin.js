/**
 * HTML Embed Widget plugin for CKEditor 4
 * Provides a widget to embed arbitrary HTML (scripts, iframes, etc.)
 */
CKEDITOR.plugins.add('htmlembed', {
    requires: 'widget,dialog',
    icons: 'htmlembed',
    init: function(editor) {
        // Register the widget
        editor.widgets.add('htmlembed', {
            dialog: 'htmlembedDialog',
            template: '<div class="ck-html-embed"></div>',
            mask: true,
            allowedContent: 'div(!ck-html-embed); script[*]; iframe[*]; blockquote[*]; div[*]; p[*]; a[*]; img[*]',
            requiredContent: 'div(ck-html-embed)',
            
            upcast: function(element) {
                return element.name == 'div' && element.hasClass('ck-html-embed');
            },
            
            init: function() {
                this.setData('html', this.element.getHtml());
            },
            
            data: function() {
                // Set the HTML content directly into the widget element
                if (this.data.html) {
                    this.element.setHtml(this.data.html);
                } else {
                    this.element.setHtml('<p style="color:#888; font-style:italic;">[Empty HTML Embed]</p>');
                }
            }
        });

        // Add the toolbar button
        editor.ui.addButton('htmlembed', {
            label: 'HTML Embed',
            command: 'htmlembed',
            toolbar: 'blocks,10',
            icon: this.path + 'icons/embed.png',
            showLabel: true
        });

        // Register the dialog
        CKEDITOR.dialog.add('htmlembedDialog', function(editor) {
            return {
                title: 'HTML Embed',
                minWidth: 500,
                minHeight: 300,
                contents: [
                    {
                        id: 'info',
                        elements: [
                            {
                                id: 'html',
                                type: 'textarea',
                                label: 'Paste your HTML code here (iframe, script, etc.)',
                                'default': '',
                                rows: 10,
                                validate: CKEDITOR.dialog.validate.notEmpty('HTML code cannot be empty.'),
                                setup: function(widget) {
                                    this.setValue(widget.data.html);
                                },
                                commit: function(widget) {
                                    widget.setData('html', this.getValue());
                                }
                            }
                        ]
                    }
                ]
            };
        });

        // Add editor-only styling for the widget
        editor.addContentsCss(
            '.ck-html-embed { ' +
                'border: 1px dashed #ccc; ' +
                'padding: 10px; ' +
                'margin: 10px 0; ' +
                'background: #fdfdfd; ' +
                'min-height: 50px; ' +
            '}' +
            '.ck-html-embed:hover { border-color: #999; background: #f5f5f5; }' +
            '.cke_widget_wrapper:hover .ck-html-embed { border-color: #f39c12; }'
        );
    }
});
