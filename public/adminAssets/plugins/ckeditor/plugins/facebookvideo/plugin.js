/**
 * Facebook-Video plugin for CKEditor 4
 * Provides a widget to embed Facebook videos as protected blocks.
 */
CKEDITOR.plugins.add('facebookvideo', {
    requires: 'widget,dialog',
    icons: 'facebookvideo',
    init: function(editor) {
        // Register the widget
        editor.widgets.add('facebookvideo', {
            dialog: 'facebookvideoDialog',
            template: '<div class="ck-facebook-video"></div>',
            mask: true,
            allowedContent: 'div(!ck-facebook-video); iframe[*]',
            requiredContent: 'div(ck-facebook-video)',

            upcast: function(element) {
                return element.name == 'div' && element.hasClass('ck-facebook-video');
            },

            init: function() {
                var iframe = this.element.findOne('iframe');
                if (iframe) {
                    this.setData('url', iframe.getAttribute('src'));
                }
            },

            data: function() {
                if (this.data.url) {
                    this.element.setHtml('<iframe src="' + this.data.url + '" width="500" height="281" frameborder="0" allowfullscreen="1"></iframe>');
                } else {
                    this.element.setHtml('<p style="color:#888; font-style:italic;">[Empty Facebook Video]</p>');
                }
            }
        });

        // Add the toolbar button
        editor.ui.addButton('facebookvideo', {
            label: 'Embed Facebook video',
            command: 'facebookvideo',
            toolbar: 'insert,11',
            icon: this.path + 'icons/facebookvideo.png'
        });

        // Register the dialog
        CKEDITOR.dialog.add('facebookvideoDialog', function(editor) {
            return {
                title: 'Facebook Video Settings',
                minWidth: 400,
                minHeight: 100,
                contents: [
                    {
                        id: 'tab-basic',
                        elements: [
                            {
                                type: 'text',
                                id: 'url',
                                label: 'Facebook video URL',
                                setup: function(widget) {
                                    this.setValue(widget.data.url || '');
                                },
                                commit: function(widget) {
                                    var val = this.getValue().trim();
                                    // Handle both direct video URL and iframe SRC
                                    if (val.indexOf('video.php') !== -1) {
                                        widget.setData('url', val);
                                    } else {
                                        var match = val.match(/videos\/([^&$?\/]+)/i);
                                        if (match) {
                                            var src = "https://www.facebook.com/plugins/video.php?href=https%3A%2F%2Fwww.facebook.com%2Ffacebook%2Fvideos%2F" + match[1] + "%2F&width=500&show_text=false&height=281";
                                            widget.setData('url', src);
                                        } else {
                                            alert('Invalid Facebook URL. Please use format: https://www.facebook.com/page/videos/ID/');
                                        }
                                    }
                                }
                            }
                        ]
                    }
                ]
            };
        });

        // Add editor styling
        editor.addContentsCss('.ck-facebook-video { border: 1px dashed #314e8a; padding: 5px; background: #f0f2f5; display: inline-block; min-height: 50px; }');
    }
});
