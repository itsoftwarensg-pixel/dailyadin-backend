/**
 * @license Copyright (c) 2003-2016, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
    // Basic settings
    config.extraPlugins = 'htmlembed,image2';
    config.extraAllowedContent = 'iframe[*];script[*];div[*];blockquote[*];p[*];a[*];img[*]';
    config.contentsCss = ['body {font-size: 18px; line-height: 1.6; font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;}'];
    
    // UI Modernization
    config.uiColor = '#f3f3f3';
    config.removePlugins = 'elementspath';
    config.resize_enabled = false;
    config.toolbarCanCollapse = false;
    // Regrouped Toolbar: logical blocks for better usability
    config.toolbar = [
        { name: 'history', items: [ 'Undo', 'Redo' ] },
        { name: 'clipboard', items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord' ] },
        { name: 'find', items: [ 'Find', 'Replace' ] },
        { name: 'formatting', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
        { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
        { name: 'direction', items: [ 'BidiLtr', 'BidiRtl' ] },
        { name: 'tools', items: [ 'Source', 'ShowBlocks', 'Maximize' ] },
        { name: 'blocks', items: [ 'Blockquote', 'CreateDiv', 'htmlembed' ] },
        { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'RemoveFormat' ] },
        { name: 'paragraph', items: [ 'NumberedList', 'BulletedList' ] },
        { name: 'justify', items: [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
        { name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'SpecialChar', 'Iframe' ] },
        { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
    ];
};
